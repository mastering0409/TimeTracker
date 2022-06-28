<?php
/**
 * Time Tracker Class Display Message - Alert User if No Task Has Been Added
 * 
 * Alert users who are trying to enter time that a task needs to be added first
 * 
 * @since 1.5.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;
use function Logically_Tech\Time_Tracker\Inc\catch_sql_errors;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * Check if class exists
 * 
 */
if ( ! class_exists('Time_Tracker_Display_Message_Check_Task_Added') ) {


    /**
     * Create Class
     * 
     */
    class Time_Tracker_Display_Message_Check_Task_Added {


        /**
         * Class variables
         * 
         */
        private $task_count;
        

        /**
         * Constructor - get task count from db
         * 
         */
        public function __construct() {
            $this->task_count = $this->query_db_for_task_count();
        }


        /**
         * Public function to get message
         * 
         */
        public function display_message() {
            return $this->get_message();
        }


        private function get_message() {
            if ($this->task_count < 1) {
                $display = "<div class=\"error-message\" id=\"no-task-alert\">";
                $display .= "NOTE: You must add a task before entering time. Use the 'New Task' button in the menu or <a href='" . TT_HOME . "new-task/'>click here</a>.";
                $display .= "</div>";
            } else {
                $display = "";
            }
            return $display;
        }


        /**
         * Query db to get task count
         * 
         */
        private function query_db_for_task_count() {
            global $wpdb;
            $num_tasks = $wpdb->get_var($this->task_count_sql_string());
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $num_tasks;

        }


        /**
         * Create sql string to query db
         * 
         */
        private function task_count_sql_string() {
            $sql_str = "SELECT COUNT(TaskID) AS task_count FROM tt_task";
            return $sql_str;            
        }


    }  //class
} //class exists