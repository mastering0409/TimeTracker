<?php
/**
 * Class Time_Tracker_Shortcode_Project_List_Table
 *
 * SHORTCODE TO DISPLAY PROJECT LIST
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Project_List_Table') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Project_List_Table {

        
        /**
         * Class Variables
         * 
         */  
        public $shortcode = 'tt_project_list_table';


        /**
         * Constructor
         * 
         */ 
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'project_list_table_shortcode' ) );
        }


        /**
         * Callback
         * 
         */ 
        public function project_list_table_shortcode() {
            $list = new Project_List;
            $html = $list->get_page_html_with_each_status_in_different_table();
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

$Time_Tracker_Shortcode_Project_List_Table = new Time_Tracker_Shortcode_Project_List_Table();