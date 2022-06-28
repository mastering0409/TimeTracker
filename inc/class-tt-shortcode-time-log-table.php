<?php
/**
 * Class Time_Tracker_Shortcode_Time_Log_Table
 *
 * SHORTCODE TO DISPLAY TIME LOG
 * 
 * Accepts type of log (detail vs summary) and displays resulting table
 * 9-20-2021 - added ability to display summary table
 * 
 * 
 * @param array $atts     Shortcode attributes, default empty.
 * @return string         Shortcode output.
 *  
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Time_Log_Table') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Time_Log_Table {


        /**
         * Class
         * 
         */ 
        public $shortcode = 'tt_time_log_table';


        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'time_log_table_shortcode' ) );
        }


        /**
         * Callback
         * 
         */
        public function time_log_table_shortcode($atts) {
            // normalize attribute keys, lowercase
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            //this sets defaults, and combines with user submitted atts
            $timelog_atts = shortcode_atts(
                array(
                    'type' => 'detail',
                ), $atts, 'timelog'
            );

            $time_detail = new Time_Log;
            if ($timelog_atts['type'] == 'detail') {
                $table = $time_detail->create_table();
                return $table;
            } elseif ($timelog_atts['type'] == 'summary') {
                //$time_detail_array = $time_detail->get_time_log_from_db();
                $time_summary = new Time_Log_Summary;
                $table = $time_summary->create_summary_table();
                return $table;                
            }
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

$Time_Tracker_Shortcode_Time_Log_Table = new Time_Tracker_Shortcode_Time_Log_Table();