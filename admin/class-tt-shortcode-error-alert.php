<?php
/**
 * Class Time_Tracker_Shortcode_Error_Alert
 *
 * SHORTCODE TO DISPLAY CLIENT LIST TABLE
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Error_Alert') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Error_Alert {

        
        /**
         * Class Variables
         * 
         */         
        public $shortcode = 'tt_error_alert';

        
        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'error_alert_shortcode' ) );
        }


        /**
         * Shortcode callback
         * 
         */
        public function error_alert_shortcode() {
            $sql_alert = new Time_Tracker_SQL_Result_Display_Message;
            $sql_message = $sql_alert->display_message();

            $client_alert = new Time_Tracker_Display_Message_Check_Client_Added;
            $client_message = $client_alert->display_message();

            if ($client_message == "") {
                $task_alert = new Time_Tracker_Display_Message_Check_Task_Added;
                $task_message = $task_alert->display_message();
            }

            return $sql_message . "<br>" . $client_message . "<br>" . $task_message;
        }
    

        /**
         * Return results
         * 
         */
        public function get_shortcode() {
            return $this->shortcode;
        }
    } //class
} //if class exists

$Time_Tracker_Shortcode_Error_Alert = new Time_Tracker_Shortcode_Error_Alert();