<?php
/**
 * Class Time_Tracker_Deativator
 *
 * Deactivation of Time Tracker Plugin
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * If class doesn't exist already
 * 
 */
if ( ! class_exists('Time_Tracker_Deactivator') ) {

    /**
     * Class
     * 
     */
    class Time_Tracker_Deactivator {
 
        /**
         * Deactivation main function
         * 
         */
        public static function deactivate() {
            //self::send_deletion_warning();  WON'T NEED TO DO THIS, ONLY DURING DELETION
            self::define_plugin_variables();
            //self::delete_tables();  DON'T REMOVE TABLES, ONLY DO THIS DURING PLUGIN DELETION ???
            self::deactivate_crons();
            self::deactivate_pages(); 
            //self::delete_forms();   DON'T REMOVE FORMS, ONLY DO THIS DURING PLUGIN DELETION ???
            
            //WHAT NEEDS TO BE DONE HERE?
        }


        /**
         * Warn user
         * 
         */
        public static function send_deletion_warning() {
            //WARNING: Deactivating 
        }
        
        
        
        /**
         * Definitions
         * 
         */
        public static function define_plugin_variables() {
            require_once 'class-time-tracker-activator-pages.php';
        }


        /**
         * Deactivate Crons
         * 
         */
        public static function deactivate_crons() {
            wp_clear_scheduled_hook( 'tt_recurring_task_check' );
        }
        
        
        /**
         * Delete tables
         * 
         */
        public static function deactivate_tables() {
            //don't do anything with tables on deactivation
        }


        /**
         * Deactivate pages
         * 
         */
        public static function deactivate_pages() {
            $tt_pages = Time_Tracker_Activator_Pages::create_subpage_details_array(1);
            $tt_pages_delete_order = array_reverse($tt_pages);
            /**$tt_pages = array(
                'time-tracker',
                'clients',
                'new-client',
                'new-project',
                'new-recurring-task',
                'new-task',
                'new-time-entry',
                'open-task-list',
                'pending-time',
                'project-list',
                'task-detail',
                'task-list',
                'time-log'
            );**/
            foreach ($tt_pages_delete_order as $tt_page) {
                self::change_page_to_draft($tt_page['Slug']);
            }
        }


        /**
         * Deactivate Page
         * 
         */
        private static function change_page_to_draft($pagename) {
            $post_id = get_page_by_path('time-tracker/' . $pagename, ARRAY_A, 'page');
            if ($post_id) {    
                $post_id['post_status'] = 'draft';
                return wp_update_post($post_id);
            }
        }


        /**
         * Delete forms
         * 
         */
        public static function deactivate_forms() {
            //don't do anything with forms on deactivation
        }

    }  //close class
 }  //close if class exists