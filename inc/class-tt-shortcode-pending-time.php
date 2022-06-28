<?php
/**
 * Class Time_Tracker_Shortcode_Pending_Time
 *
  * The [pending-time-table] shortcode.  Accepts a parameter and will display a table of time not yet invoiced (ie: pending).
 *
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Pending_Time') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Pending_Time {


        /**
         * Class Variables
         * 
         */  
        public $shortcode = 'tt_pending_time_table';
        private $atts = [];
        private $billto = '';


        /**
         * Constructor
         * 
         */  
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'pending_time_table_shortcode' ) );
        }


        /**
         * Get attributes of shortcode
         * 
         */  
        private function get_attributes($atts) {
            // normalize attribute keys, lowercase
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );
            // get bill to attribute, or set to 'all' for default
            if ( array_key_exists( 'billto', $atts ) ) {
                return $atts['billto'];
            } else {
                return '';  //default if none specified
            }
        }


        /**
         * Callback
         * 
         */  
        public function pending_time_table_shortcode($atts) {
            $this->billto = $this->get_attributes($atts);
            $list = new Pending_Time;
            $html = $list->display_pending_time();
            //$table = $list->create_table($this->billto);
            return $html;
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

$Time_Tracker_Shortcode_Pending_Time = new Time_Tracker_Shortcode_Pending_Time();