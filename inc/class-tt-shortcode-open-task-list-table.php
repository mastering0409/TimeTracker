<?php
/**
 * Class Time_Tracker_Open_Task_List_Table
 *
 * SHORTCODE TO DISPLAY OPEN TASK LIST
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Open_Task_List_Table') ) {

    /**
     * Class
     * 
     */ 
    class Time_Tracker_Open_Task_List_Table {


        /**
         * Plugin Variables
         * 
         */  
        public $shortcode = 'tt_open_task_list_table';


        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'open_task_list_table_shortcode' ) );
        }


        /**
         * Shortcode callback
         * 
         */
        public function open_task_list_table_shortcode() {
            $list = new Task_List;
            $table = $list->create_table("open_tasks");
            return $table;
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

$Time_Tracker_Open_Task_List_Table = new Time_Tracker_Open_Task_List_Table();