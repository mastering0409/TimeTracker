<?php
/**
 * Class Time_Tracker
 *
 * Include necessary files for the plugin if installed and activated
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
if ( ! class_exists('Time_Tracker') ) {

  /**
   * Main Plugin Class
   * 
   */  
  final class Time_Tracker {
  
    private static $instance;
  
    /**
     * Main Plugin Class
     * 
     */  
    public static function instance() {
      if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Time_Tracker ) ) {
        self::$instance = new Time_Tracker;
        self::$instance->setup_constants();
        self::$instance->load_dependencies();
        self::$instance->add_scripts();
        self::$instance->add_styles();
        self::$instance->check_plugin_version();
        //add_action( 'init', array( self::$instance, 'init' ) );
      }
      //ADMIN
      if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
        self::$instance->load_dependencies_admin();
        self::$instance->add_scripts_admin();
        self::$instance->add_styles_admin();
        self::$instance->init_settings();
      }
      return self::$instance;
    }  //end public function instance
  
  
    /**
     * Check Plugin Version
     * 
     */  
    private function check_plugin_version() {
      $installed_version = get_option('time_tracker_version');
      if ($installed_version != TIME_TRACKER_VERSION) {
        echo 'do not match';
        include_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-updater.php');
        $updater = New Time_Tracker_Updater;
        $new_version = $updater->tt_update_from($installed_version);
      }
    }
      
      
    /**
     * Definitions
     * 
     */  
    private function setup_constants() {
      //define('TT_DB_NAME', DB_NAME . "_tt"); //time tracker database name
      
      //TT Home - Allow for WP Install in Sub-Directories
      define('TT_HOME', home_url() . '/time-tracker/');
      
      //Plugin Server Directory Path - for php files
      define('TT_PLUGIN_DIR', plugin_dir_path(__DIR__));
      define('TT_PLUGIN_DIR_INC', plugin_dir_path(__FILE__));
      define('TT_PLUGIN_DIR_ADMIN', plugin_dir_path(__FILE__) . '../admin/');  
      
      //Plugin Visible Directory Path with Trailing Slash - for js, css files
      define('TT_PLUGIN_WEB_DIR_ADMIN', plugin_dir_url(__FILE__) . '../admin/');
      define('TT_PLUGIN_WEB_DIR_INC', plugin_dir_url(__FILE__));
    }
  
  
    /**
     * Load Classes, Functions
     * 
     */
    private function load_dependencies() {
      //FUNCTIONS
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-update-table.php');  //do we need this? called in js
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-utilities.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-get-IDs-from-common-names.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-custom-cf7-field-datetime.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-custom-cf7-field-task-dropdown.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-custom-cf7-field-project-dropdown.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-custom-cf7-field-client-dropdown.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-custom-cf7-field-categories-from-settings.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-clear-sql-error.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-cron-recurring-tasks.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-dynamic-task-dropdown.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-dynamic-project-dropdown.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-pending-time-export.php');
      include_once(TT_PLUGIN_DIR_INC . 'function-tt-delete-record.php');
     
      //CLASSES  
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-display-table.php');    
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-save-form-input.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-tool-tips.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-load-page-templates.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-save-to-file.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-hours-worked-detail.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-hours-worked-month-summary.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-hours-worked-year-summary.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-time-log.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-time-log-summary.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-pending-time.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-pending-time-export.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-task-list.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-task-details.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-client-list.php');
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-project-list.php'); 
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-recurring-task-list.php');

      include_once(TT_PLUGIN_DIR_ADMIN . 'class-tt-sql-result-display-message.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'class-tt-display-message-check-client-added.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'class-tt-display-message-check-task-added.php');

      include_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-activator-tables.php');

      //CONTACT FORM 7 HOOKS
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-hook-after-form-data-saved.php');  
      include_once(TT_PLUGIN_DIR_INC . 'class-tt-hook-save-form-data.php');

      //SHORTCODES
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-month-summary.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-year-summary.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-open-task-list-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-task-list-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-recurring-task-list-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-show-task-details.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-project-list-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-client-list-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-time-log-table.php');
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-pending-time.php'); 
      require_once(TT_PLUGIN_DIR_INC . 'class-tt-shortcode-delete-confirmation-content.php');
      require_once(TT_PLUGIN_DIR_ADMIN . 'class-tt-shortcode-error-alert.php');  

    } 


    /**
     * Load Scripts
     * 
     */
    public function time_tracker_scripts() {
      //SCRIPTS

      //wp_enqueue_script( 'update_project_list', TT_PLUGIN_WEB_DIR_INC . 'js/get_projects_for_client.js', array(), null, true);
      wp_enqueue_script( 'update_end_timer', TT_PLUGIN_WEB_DIR_INC . 'js/update_end_timer.js', array(), null, true);
      wp_enqueue_script( 'start_timer_for_task', TT_PLUGIN_WEB_DIR_INC . 'js/start_timer_for_task.js', array(), null, true);
      wp_enqueue_script( 'open_detail_for_task', TT_PLUGIN_WEB_DIR_INC . 'js/open_detail_for_task.js', array(), null, true);
      wp_enqueue_script( 'tt_filter_time_log', TT_PLUGIN_WEB_DIR_INC . 'js/filter_time_log.js', array(), null, true);
      wp_enqueue_script( 'tt_set_date_picker_default_value', TT_PLUGIN_WEB_DIR_INC . 'js/set_date_picker_default_value.js', array(), null, true);
      wp_enqueue_script( 'save_new_task_and_start_timer', TT_PLUGIN_WEB_DIR_INC . 'js/save_new_task_and_start_timer.js', array(), null, true);      
      wp_enqueue_script( 'open_time_entries_for_client', TT_PLUGIN_WEB_DIR_INC . 'js/open_time_entries_for_client.js', array(), null, true);
      wp_enqueue_script( 'open_time_entries_for_project', TT_PLUGIN_WEB_DIR_INC . 'js/open_time_entries_for_project.js', array(), null, true);
	    wp_enqueue_script( 'tt_open_mobile_menu', TT_PLUGIN_WEB_DIR_INC . 'js/open_mobile_menu.js', array(), null, true);
      wp_enqueue_script( 'tt_accordion', TT_PLUGIN_WEB_DIR_INC . 'js/tt_accordion.js', array(), null, true );

      wp_enqueue_script( 'tt_watch_for_client_change_project', TT_PLUGIN_WEB_DIR_INC . 'js/watch_for_client_change_to_update_project.js', array(), null, true);
      wp_enqueue_script( 'tt_update_project_dropdown', TT_PLUGIN_WEB_DIR_INC . 'js/get_projects_for_client.js', array('jquery'), null, true);
      
      wp_enqueue_script( 'tt_watch_for_client_change_task', TT_PLUGIN_WEB_DIR_INC . 'js/watch_for_client_change_to_update_task.js', array(), null, true);
      wp_enqueue_script( 'tt_update_task_dropdown', TT_PLUGIN_WEB_DIR_INC . 'js/get_tasks_for_client.js', array('jquery'), null, true);
      
      wp_enqueue_script( 'tt_clear_sql_error', TT_PLUGIN_WEB_DIR_INC . 'js/clear_sql_error.js', array('jquery'), null, true);
      wp_enqueue_script( 'updateDatabase', TT_PLUGIN_WEB_DIR_INC . 'js/update_table.js', array('jquery'), null, true);
      wp_enqueue_script( 'deleteRecord', TT_PLUGIN_WEB_DIR_INC . 'js/delete_record.js', array('jquery'), null, true);

      wp_enqueue_script( 'export_pending_time_to_csv', TT_PLUGIN_WEB_DIR_INC . 'js/export_pending_time_to_csv.js', array('jquery'), null, true);
      wp_enqueue_script( 'tt_download_file', TT_PLUGIN_WEB_DIR_INC . 'js/tt_download_file.js', array(), null, true);

      //SAVE PATH TO SCRIPTS FOR USE IN JS
      //wp_localize_script('update_task_list', 'getDirectory', array('pluginURL' => plugins_url('',__FILE__)));
      wp_localize_script('tt_update_project_dropdown', 'wp_ajax_object_tt_update_project_list', array('ajax_url' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('tt_update_project_list_nonce')));
      wp_localize_script('tt_update_task_dropdown', 'wp_ajax_object_tt_update_task_list', array('ajax_url' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('tt_update_task_list_nonce')));
      wp_localize_script('tt_clear_sql_error', 'wp_ajax_object_tt_clear_sql_error', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_clear_sql_error_nonce')));
	    wp_localize_script('updateDatabase', 'wp_ajax_object_tt_update_table', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_update_table_nonce')));
      wp_localize_script('export_pending_time_to_csv', 'wp_ajax_object_tt_export_pending_time', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_export_pending_time_nonce')));
      wp_localize_script('deleteRecord', 'wp_ajax_object_tt_delete_record', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_delete_record_nonce')));
    
      //pass time tracker homepage to functions - to work better with wordpress subfolder installs
      wp_localize_script( 'tt_filter_time_log', 'scriptDetails', array( 'tthomeurl' => TT_HOME));
      wp_localize_script( 'open_detail_for_task', 'scriptDetails', array( 'tthomeurl' => TT_HOME));
      wp_localize_script( 'open_time_entries_for_client', 'scriptDetails', array( 'tthomeurl' => TT_HOME));
      wp_localize_script( 'open_time_entries_for_project', 'scriptDetails', array( 'tthomeurl' => TT_HOME));
      wp_localize_script( 'start_timer_for_task', 'scriptDetails', array( 'tthomeurl' => TT_HOME));
    }


    /**
     * Load Styles
     * 
     */
    public function time_tracker_styles() {
      //STYLES
      wp_enqueue_style( 'time-tracker-style', TT_PLUGIN_WEB_DIR_INC . 'css/time-tracker.php');
    }


    /**
     * Enqueue Scipts
     * 
     */
    public function add_scripts() {
      //ADD CALLBACK FUNCTIONS FOR AJAX CALLS - ADD BEFORE SCRIPTS
      add_action('wp_ajax_tt_update_project_list', 'Logically_Tech\Time_Tracker\Inc\tt_update_project_list_function');
      add_action('wp_ajax_tt_update_task_list', 'Logically_Tech\Time_Tracker\Inc\tt_update_task_list_function');
	    add_action('wp_ajax_tt_update_table', 'Logically_Tech\Time_Tracker\Inc\tt_update_table_function');
	    add_action('wp_ajax_tt_clear_sql_error', 'Logically_Tech\Time_Tracker\Inc\tt_clear_sql_error_function');
      add_action('wp_ajax_tt_export_pending_time', 'Logically_Tech\Time_Tracker\Inc\tt_export_pending_time');
	    add_action('wp_ajax_tt_delete_record', 'Logically_Tech\Time_Tracker\Inc\tt_delete_record_function');

	    //SCRIPTS
      add_action('wp_enqueue_scripts', array($this,'time_tracker_scripts'));
    }


    /**
     * Enquque Styles
     * 
     */
    public function add_styles() {
      //STYLES
      add_action('wp_enqueue_scripts', array($this,'time_tracker_styles'));
    }
    
    
    /**
     * Load Classes, Functions - Admin
     * 
     */
    private function load_dependencies_admin() {
      //PLUGIN SETTINGS
      include_once(TT_PLUGIN_DIR_ADMIN . 'tt-admin-settings-callbacks.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'tt-admin-settings-init.php'); 
      
      //ADMIN MENUS
      include_once(TT_PLUGIN_DIR_ADMIN . 'tt-admin-menu.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'tt-admin-menu-home.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'tt-admin-menu-tools.php');

      //CLASSES and FUNCTIONS
      include_once(TT_PLUGIN_DIR_ADMIN . 'function-tt-export-button.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'function-tt-export-tables.php');
      include_once(TT_PLUGIN_DIR_ADMIN . 'function-tt-delete-tables.php');

      //SHORTCODES
     
      
    }  
      
    
    /**
     * Load Styles - Admin
     * 
     */    
    public function time_tracker_styles_admin() {
      wp_enqueue_style( 'time-tracker-style', TT_PLUGIN_WEB_DIR_INC . 'css/time-tracker.php');
    }


    /**
     * Load Scripts - Admin
     * 
     */
    public function time_tracker_scripts_admin() {
      wp_enqueue_script( 'tt_add_line_break', TT_PLUGIN_WEB_DIR_ADMIN . 'js/add_line_break.js', array(), null, true); 
		
      wp_enqueue_script( 'export_tt_data', TT_PLUGIN_WEB_DIR_ADMIN . 'js/export_tt_data.js', array('jquery'), null, true);
      wp_enqueue_script( 'delete_tt_data', TT_PLUGIN_WEB_DIR_ADMIN . 'js/delete_tt_data.js', array('jquery'), null, true);
      
      //SAVE PATH TO SCRIPTS FOR USE IN JS
      wp_localize_script('export_tt_data', 'wp_ajax_object_tt_export_data', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_export_data_nonce')));
      wp_localize_script('delete_tt_data', 'wp_ajax_object_tt_delete_data', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce('tt_delete_data_nonce')));
    }

    
    /**
     * Enqueue Scripts - Admin
     * 
     */    
    public function add_scripts_admin() {
      //ADD CALLBACK FUNCTIONS FOR AJAX CALLS - ADD BEFORE SCRIPTS
	    add_action('wp_ajax_tt_export_data', 'Logically_Tech\Time_Tracker\Admin\tt_export_button_function');
	    add_action('wp_ajax_tt_delete_data', 'Logically_Tech\Time_Tracker\Admin\tt_delete_data_function');
		
      //ADMIN SCRIPTS
      add_action('admin_enqueue_scripts', array($this,'time_tracker_scripts_admin'));

    }


    /**
     * Enqueue Styles - Admin
     * 
     */
    public function add_styles_admin() {
      //STYLES
      add_action('admin_enqueue_scripts', array($this,'time_tracker_styles_admin'));
    }


    /**
     * Add Plugin Settings
     * 
     */
    public function init_settings() {
      //SETTINGS
      add_action('admin_init', 'Logically_Tech\Time_Tracker\Admin\tt_admin_settings_init');
    }

  
  } //end time tracker class
  
} //end class exists check