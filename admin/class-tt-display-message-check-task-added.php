<?php
/**
 * Time Tracker Class Display Message - Alert User if No Client's Added
 *
 * Alert users on installation that a client needs to be entered to start using
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
if ( ! class_exists('Time_Tracker_Display_Message_Check_Client_Added') ) {


    /**
     * Create Class
     * 
     */
    class Time_Tracker_Display_Message_Check_Client_Added {


        /**
         * Class variables
         * 
         */
        private $client_count;
        

        /**
         * Constructor - get client count from db
         * 
         */
        public function __construct() {
            $this->client_count = $this->query_db_for_client_count();
            //$this->result = $option['result'];
        }


        /**
         * Public function to get message
         * 
         */
        public function display_message() {
            return $this->get_message();
        }


        private function get_message() {
            if ($this->client_count < 1) {
                $display = "<div class=\"error-message\" id=\"no-client-alert\">";
                $display .= "Add a Client to Get Started. Use the 'Add Client' button in the menu or <a href='" . TT_HOME . "new-client/'>click here</a>.";
                $display .= "</div>";
            } else {
                $display = "";
            }
            return $display;
        }


        /**
         * Query db to get client count
         * 
         */
        private function query_db_for_client_count() {
            global $wpdb;
            $num_clients = $wpdb->get_var($this->client_count_sql_string());
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $num_clients;

        }


        /**
         * Create sql string to query db
         * 
         */
        private function client_count_sql_string() {
            $sql_str = "SELECT COUNT(ClientID) AS client_count FROM tt_client";
            return $sql_str;            
        }


    }  //class
} //class exists