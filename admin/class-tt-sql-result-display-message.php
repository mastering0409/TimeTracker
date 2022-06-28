<?php
/**
 * Time Tracker Class SQL Result Display Message
 *
 * Alert for SQL errors on homepage
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * Check if class exists
 * 
 */
if ( ! class_exists('Time_Tracker_SQL_Result_Display_Message') ) {


    /**
     * Create Class
     * 
     */
    class Time_Tracker_SQL_Result_Display_Message {


        /**
         * Class variables
         * 
         */
        private $option;
        

        /**
         * Constructor - get option from db
         * 
         */
        public function __construct() {
            $this->option = get_option('time-tracker-sql-result');
            //$this->result = $option['result'];
        }


        /**
         * Public function to get message
         * 
         */
        public function display_message() {
            return $this->get_message();
        }


        /**
         * Create message for display
         * 
         */
        private function get_message() {
            $result = sanitize_text_field($this->option['result']);
            $msg = sanitize_text_field($this->option['updated']);
            if ($result == 'failure') {
                $display = "<div class=\"error-message\" id=\"sql-error-alert\">";
                $display .= "ALERT: There was a SQL error recently (";
                $display .= $msg;
                $display .= ") Please check the SQL logs or contact support for assistance.";
                $display .= "<button onclick=\"tt_clear_sql_error()\" class=\"clear-error\">Clear Error</button>";
                $display .= "</div>";
            } else {
                $display = "";
            }
            return $display;
        }

    }  //class
} //class exists