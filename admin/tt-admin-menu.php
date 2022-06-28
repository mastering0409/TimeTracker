<?php
/**
 * Class Time_Tracker_Menu
 *
 * Define menu structure/pages for Time Tracker Plugin
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;

/**
 * Check that class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Menu') ) {

    /**
     * Create class for Time Tracker Menu
     * 
     */
    class Time_Tracker_Menu {

        var $plugin_name = 'time-tracker.php';
        
        
        /**
         * Time_Tracker_Menu Constructor
         * 
         */
        public function __construct() {
        }


        /**
         * Initialize
         * 
         */
        public static function init() {
            self::add_actions();
        }


        /**
         * Add actions and filters
         * 
         */
        public static function add_actions() {
            add_action( 'admin_menu', array(__CLASS__, 'tt_menu') );
            add_filter('plugin_action_links_' . TIME_TRACKER_PLUGIN_BASENAME, array(__CLASS__, 'tt_settings_link') );
        }


        /**
         * Add settings link underneath plugin name on WP plugins page
         * 
         */        
        public static function tt_settings_link($links) { 
            $settings_link = '<a href="admin.php?page=time-tracker">Settings</a>'; 
            array_unshift($links, $settings_link); 
            return $links; 
        }

        
        /**
         * Call add menu for each menu page
         * 
         */
        public static function tt_menu() {
            //main menu page
            self::add_menu('Time Tracker Settings', 'Time Tracker', 'manage_options', 'time-tracker', 'Logically_Tech\Time_Tracker\Admin\tt_admin_menu_home','','51');
            //add sub-pages
            self::add_sub_menu('time-tracker', 'Time Tracker Options', 'Options', 'manage_options', 'time-tracker', 'Logically_Tech\Time_Tracker\Admin\tt_admin_menu_home', null);
            self::add_sub_menu('time-tracker', 'Time Tracker Tools', 'Tools', 'manage_options', 'time-tracker-tools', 'Logically_Tech\Time_Tracker\Admin\tt_admin_menu_tools', null);
        }       
          
                
        /**
         * Register a custom menu page
         * 
         */
        public static function add_menu($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position) {
            add_menu_page(
                $page_title,
                $menu_title,
                $capability,
                $menu_slug,
                $function,
                $icon_url,                   
                $position
            );
        } 
         
                
        /**
         * Register a sub-menu page
         * 
         */
        public static function add_sub_menu($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position) {
            add_submenu_page(
                $parent_slug,
                $page_title,
                $menu_title,
                $capability,
                $menu_slug,
                $function,                  
                $position
            );
        } 

    }  //close class
}  //close if class exists


/**
 * Start Time Tracker Menu
 * 
 */
Time_Tracker_Menu::init();