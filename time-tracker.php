<?php
 /**
 * Time Tracker
 *
 * @package           
 * @author            Amy McGarity
 * @copyright         2020-2022 Amy McGarity
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Time Tracker
 * Plugin URI:        https://www.logicallytech.com/services/wordpress-plugins/time-tracker/
 * Description:       A project, task and time tracking program for freelancers.
 * Version:           2.2.3
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Author:            Amy McGarity
 * Author URI:        https://www.logicallytech.com/
 * Text Domain:       time-tracker
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Logically_Tech\Time_Tracker;

use Logically_Tech\Time_Tracker\Inc\Time_Tracker;
use Logically_Tech\Time_Tracker\Inc\Time_Tracker_Activator;
use Logically_Tech\Time_Tracker\Inc\Time_Tracker_Deactivator;
use Logically_Tech\Time_Tracker\Inc\Time_Tracker_Deletor;


if ( !defined( 'ABSPATH' ) ) { 
  die( 'Nope, not accessing this' );
}


/**
 * Current plugin version.
 * Use SemVer - https://semver.org
 */
define('TIME_TRACKER_VERSION', '2.2.0');
define('TIME_TRACKER_PLUGIN_BASENAME', plugin_basename(__FILE__));


/**
 * ACTIVATE PLUGIN
 * Define the plugin activation class
 */
function activate_time_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-time-tracker-activator.php';
	Inc\Time_Tracker_Activator::activate();
}
register_activation_hook( __FILE__, 'Logically_Tech\Time_Tracker\activate_time_tracker' );


/**
 * DEACTIVATE PLUGIN
 * Define the plugin deactivation class
 */
function deactivate_time_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-time-tracker-deactivator.php';
	Inc\Time_Tracker_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'Logically_Tech\Time_Tracker\deactivate_time_tracker' );


/**
 * DELETE PLUGIN
 * Define the plugin uninstall/delete class
 */
function uninstall_time_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-time-tracker-delete.php';
	Inc\Time_Tracker_Deletor::delete_all();
}
register_uninstall_hook(__FILE__, 'Logically_Tech\Time_Tracker\uninstall_time_tracker');


/**
 * START PLUGIN
 * This is the function that creates the main plugin class
 */
function time_tracker_load() {
  require_once plugin_dir_path( __FILE__ ) . 'inc/class-time-tracker.php';
  return Inc\Time_Tracker::instance();
}


/**
 * START PLUGIN
 * Start it up!
 */
time_tracker_load();
