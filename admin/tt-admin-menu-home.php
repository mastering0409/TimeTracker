<?php
/**
 * Time Tracker Menu - Main Page
 *
 * Layout homepage for Time Tracker Admin Menus
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;


/**
 * Display a custom menu page
 * 
 */
function tt_admin_menu_home() { 
   ?>
   <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
   <button onclick="javascript:location.href='<?php echo TT_HOME ?>'" class="tt-admin-to-front button-primary ">Time Tracker Home</button>
   <?php 
         if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
            ?>
            <div class="tt-indent">
               <h2>IMPORTANT</h2>
               <p class="tt-important">The Contact Form 7 plugin must be installed and activated for Time Tracker to work properly. Please <a href="/wp-admin/plugin-install.php?s=contact+form+7&tab=search&type=term">install CF7</a> and activate it now.</p>
            </div>
            <?php
         }
         $tz = sanitize_text_field(get_option('timezone_string'));
	      if ( !$tz ) {
            ?>
            <div class="tt-indent">
               <h2>ALERT</h2>
				<p class="tt-important">You don't have a timezone set in your WordPress settings. All time logs will show UTC time. To use your local timezone, update your timezone in the WordPress menu <a href="/wp-admin/options-general.php">settings->general</a>.</p>
            </div>
            <?php
         } else if ( $tz == "UTC" ) {
            ?>
            <div class="tt-indent">
               <h2>Time Zone Check</h2>
				<p class="tt-important">You have a default time zone of <?php echo esc_textarea($tz); ?> set in WordPress.  All your time logs will show in this time. If this is not correct, please update your timezone in the WordPress menu <a href="/wp-admin/options-general.php">settings->general</a>.</p>
            </div>
            <?php
         } else {
            ?>
            <div class="tt-indent">
               <h2>Time Zone</h2>
				<p>Your time zone is set to <strong><?php echo esc_textarea($tz); ?></strong>. If this is not correct, please update it in the WordPress menu <a href="/wp-admin/options-general.php">settings->general</a>. All time entries will be saved in this time zone.</p>
            </div>
            <?php            
         }
         ?>
      <form action="options.php" method="post" id="tt-options">
         <?php
         do_settings_sections( 'time-tracker' );
         settings_fields( 'time-tracker' );
         submit_button( 'Save Settings' );
         ?>
      </form>
      <br/>
      <br/>
      Icons made by <a href="https://www.flaticon.com/free-icon/check-list_3203134" title="fjstudio">fjstudio</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a>
   <?php   
}