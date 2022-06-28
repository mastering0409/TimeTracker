<?php
/**
 * Class Time_Tracker_Deletor
 *
 * Deactivation of Time Tracker Plugin
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

use Logically_Tech\Time_Tracker\Admin\tt_export_data_function as tt_export_data_function;


/**
 * If class doesn't exist already
 * 
 */
if ( ! class_exists('Time_Tracker_Deletor') ) {

    /**
     * Class
     * 
     */
    class Time_Tracker_Deletor {
 
        /**
         * Delete Main Function
         * 
         */
        public static function delete_all() {
            self::define_dependents();
            self::backup_everything();
            self::delete_tables();
            self::delete_pages();
            self::delete_forms();
        }


        /**
         * Warn user
         * 
         */
        public static function send_deletion_warning() {
            //WARNING: Deleting!
        }
        
        
        
        /**
         * Definitions and Dependencies
         * 
         */
        public static function define_dependents() {
            require_once 'class-time-tracker-activator-tables.php';
            require_once 'class-time-tracker-activator-pages.php';
            require_once 'class-time-tracker-activator-forms.php';
            require_once __DIR__ . '/../admin/function-tt-export-tables.php';
        }


        /**
         * Backup before deleting...just in case
         * 
         */
        public static function backup_everything() {
            \Logically_Tech\Time_Tracker\Admin\tt_export_data_function();
        }


        /**
         * Delete Tables - User Data - Only (From button on admin screen)
         * 
         */
        public static function delete_tables_only() {
            self::define_dependents();
            self::backup_everything();
            self::delete_tables(); //done with wpdb tables
        }
		
		
		/**
         * Delete Tables
         * 
         */
        public static function delete_tables() {
            self::remove_foreign_keys_from_tables();
            global $wpdb;
            $tt_tables = Time_Tracker_Activator_Tables::get_table_list();
            $tt_tables_delete_order = array_reverse($tt_tables);
            foreach ($tt_tables_delete_order as $tt_table) {
                $table_exists = $wpdb->query($wpdb->prepare('SHOW TABLES LIKE %s', $tt_table));
                catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                if ($table_exists) {
                    $sql = "DROP TABLE " . $tt_table;
                    $wpdb->query( $sql );
                    catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                }
            }
        }


        /**
         * Remove Foreign Keys in Preparation for Deleting Tables
         * 
         */
        public static function remove_foreign_keys_from_tables() {
            global $wpdb;
            //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $foreign_keys = array(
                "tt_time" => array("FK_TimeTableToTaskTable", "FK_TimeTableToClientTable"),
                "tt_recurring_task" => array("FK_RecurringTaskTableToProjectTable", "FK_RecurringTaskTableToClientTable"),
                "tt_task" => array("FK_TaskTableToRecurringTaskTable", "FK_TaskTableToProjectTable", "FK_TaskTableToClientTable"),
                "tt_project" => array("FK_ProjectTableToClientTable") 
            );
            foreach($foreign_keys as $table => $keys) {
                $table_exists = $wpdb->query($wpdb->prepare('SHOW TABLES LIKE %s', $table));
                catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                if ($table_exists) {
                    foreach($keys as $key) {
                        $altertable = "ALTER TABLE " . $table;
                        $altertable .= " DROP FOREIGN KEY IF EXISTS " . $key;
                        //dbDelta($removeFK);
                        $wpdb->query($altertable);
                        catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                    }
                }
            }
        }
        
        
        /**
         * Delete Pages
         * 
         */
        public static function delete_pages() {
            $tt_pages = Time_Tracker_Activator_Pages::create_subpage_details_array(0);
            foreach ($tt_pages as $tt_page) {
                self::delete_tt_subpage($tt_page['Slug']);
            }
            self::delete_tt_main_page();
        }


        /**
         * Delete Page
         * 
         */
        private static function delete_tt_subpage($pagename) {
            $post = get_page_by_path('time-tracker/' . $pagename, ARRAY_A, 'page');
            if ($post) {
                return wp_delete_post($post['ID']);
            }
        }


        /**
         * Delete Main Page
         * 
         */
        private static function delete_tt_main_page() {
            $post = get_page_by_path('time-tracker', ARRAY_A, 'page');
            if ($post) {
                return wp_delete_post($post['ID']);
            }
        }


        /**
         * Delete Forms
         * 
         */
        public static function delete_forms() {
            $tt_forms = Time_Tracker_Activator_Forms::create_form_details_array();
            foreach ($tt_forms as $tt_form) {
                //delete form
                $form = tt_get_form_id($tt_form['Title']);
                if ($form) {
                    self::delete_form($form);
                }
            }
        }


        /**
         * Delete Form
         * 
         */
        private static function delete_form($post_id) {
            return wp_delete_post($post_id);
        }

    }  //close class
 }  //close if class exists