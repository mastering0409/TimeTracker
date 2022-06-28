<?php
/**
 * Class Time_Tracker_Shortcode_Client_List_Table
 *
 * SHORTCODE TO DISPLAY CLIENT LIST TABLE
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Client_List_Table') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Client_List_Table {

        
        /**
         * Class Variables
         * 
         */         
        public $shortcode = 'tt_client_list_table';

        
        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'client_list_table_shortcode' ) );
        }


        /**
         * Shortcode callback
         * 
         */
        public function client_list_table_shortcode() {
            $list = new Client_List;
            $table = $list->create_table();
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

$Time_Tracker_Shortcode_Client_List_Table = new Time_Tracker_Shortcode_Client_List_Table();