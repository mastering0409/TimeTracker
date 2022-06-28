<?php
/**
 * Class Time_Tracker_Shortcode_Show_Task_Details
 *
 * SHORTCODE TO DISPLAY ENTIRE TASK LIST
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Show_Task_Details') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Show_Task_Details {

        
        
        /**
         * Class variables
         * 
         */
        public $shortcode = 'tt_show_task_details';


        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'show_task_details_shortcode' ) );
        }


        /**
         * Callback
         * 
         */
        public function show_task_details_shortcode() {
            $task = new Task_Details;
            $display = $task->generate_output_for_display();
            return $display;
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

$tt_Shortcode_Show_Task_Details = new Time_Tracker_Shortcode_Show_Task_Details();