<?php
/**
 * Class Time_Tracker_Save_Form_Data
 *
 * Hook into CF7 after data saved to save form data into Time Tracker tables in database
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );
 
/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Save_Form_Data') ) {

    
    /**
     * Class
     * 
     */
    class Time_Tracker_Save_Form_Data {


        /**
         * Constructor
         * 
         */        
        public function __construct() {

        }


        /**
         * Save data to the db
         * 
         */ 
        public function saveDataToTTDatabase() {
            $form = \WPCF7_Submission::get_instance();
            $data = $form->get_posted_data();
            $id = $form->get_contact_form()->id();
            new Save_Form_Input($data, $id); 
        }

    }  //close class
} //if class exists

$saveddata = new Time_Tracker_Save_Form_Data();

add_action( 'wpcf7_before_send_mail', array($saveddata, 'saveDataToTTDatabase') );