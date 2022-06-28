<?php
/**
 * Class Time_Tracker_Shortcode_Year_Summary
 *
 * SHORTCODE TO DISPLAY TOTAL HOURS FOR YEAR
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Year_Summary') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Year_Summary {


        /**
         * Class variables
         * 
         */  
        public $shortcode = 'tt_year_summary';


        /**
         * Constructor
         * 
         */  
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'year_summary_shortcode' ) );
        }


        /**
         * Callback
         * 
         */
        public function year_summary_shortcode() {
            $year_summary = new Class_Hours_Worked_Year_Summary;
            $year_summary_display = $year_summary->getHTML();
            return $year_summary_display;
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

$Time_Tracker_Shortcode_Year_Summary = new Time_Tracker_Shortcode_Year_Summary();