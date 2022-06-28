<?php
/**
 * Class Time_Tracker_Shortcode_Recurring_Task_List_Table
 *
 * SHORTCODE TO DISPLAY RECURRING TASK LIST
 * 
 * @since 1.1.1 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Recurring_Task_List_Table') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Recurring_Task_List_Table {


        /**
         * Class variables
         * 
         */
        public $shortcode = 'tt_recurring_task_list_table';


        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'recurring_task_list_table_shortcode' ) );
        }


        /**
         * Callback
         * 
         */
        public function recurring_task_list_table_shortcode() {
            $list = new Recurring_Task_List;
            $table = $list->create_table();
            return $table;
        }
    

        /**
         * 
         * Return
         * 
         */
        public function get_shortcode() {
            return $this->shortcode;
        }

    } //class
} //if class exists

$Time_Tracker_Shortcode_Recurring_Task_List_Table = new Time_Tracker_Shortcode_Recurring_Task_List_Table();