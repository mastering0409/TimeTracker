<?php
/**
 * Class Time_Tracker_ACtivator
 *
 * Initial activation of Time Tracker Plugin
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Check if class exists
 * 
 */
if ( ! class_exists('Time_Tracker_Activator') ) {

    //global $TT_DB_NAME;
    
    /**
     * Class
     * 
     */
    class Time_Tracker_Activator {

        private static $cf7_active = false;


        public static function activate() {
            self::define_plugin_variables();
            self::cf7_plugin_activated();
            if (self::$cf7_active) {
                include_once(TT_PLUGIN_DIR_INC . 'function-tt-cron-recurring-tasks.php');
                require_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-activator-tables.php');
                require_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-activator-forms.php');
                require_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-activator-pages.php');
                Time_Tracker_Activator_Tables::setup();
                Time_Tracker_Activator_Forms::setup();
                Time_Tracker_Activator_Pages::setup();
				self::set_initial_database_options();
            } else {
                ?>
                <script type="text/javascript">
                window.alert('Time Tracker requires Contact Form 7 plugin to work properly. Please install the Contact Form 7 plugin before activating Time Tracker.');
                </script>
                <?php
                die('Please install the Contact Form 7 plugin before activating Time Tracker.');
            }
        }


        /**
         * Definitions
         * 
         */
        private static function define_plugin_variables() {
            //Time Tracker Database Name
            //if (! defined('TT_DB_NAME')) {
                //define('TT_DB_NAME', DB_NAME . "_tt"); //time tracker database name
            //}
        }


        private static function cf7_plugin_activated() {
            //if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
            if (class_exists('WPCF7')) {
                //plugin is activated
                self::$cf7_active = true;
            }
        }
		
		
		private static function set_initial_database_options() {
			$now = new \DateTime;
            if ( ! (get_option('time-tracker-sql-result')) ) {
			    add_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now->format('m-d-Y g:i A'),'error'=>'none', 'file'=>'none', 'function'=>'none'));
            } else {
                update_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now->format('m-d-Y g:i A'),'error'=>'none', 'file'=>'none', 'function'=>'none'));
            }

            if ( ! (get_option('time-tracker')) ) {
                add_option('time-tracker', array('bill-to-names'=>'Client', 'work-categories'=>'Uncategorized', 'client-categories'=>'Uncategorized', 'client-sub-categories'=>'Uncategorized'));
            }
		}
		

    }  //close class
 }  //close if class exists
