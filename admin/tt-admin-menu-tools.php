<?php
/**
 * Time Tracker Menu - Tools Page
 *
 * Layout tools page for Time Tracker Admin Menus
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;


/**
 * Display a custom sub-menu page
 * 
 */
function tt_admin_menu_tools() { 
   ?>
   <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <button onclick="javascript:location.href='<?php echo TT_HOME ?>'" class="tt-admin-to-front button-primary">Time Tracker Home</button>   
      <div class="tt-indent">
         <h2>Backup Data</h2>
         <p>
            Use this to manually backup your time tracker data.<br/>
            The tables will be exported and saved on a file in your website's root directory.
         </p>
         <button onclick="export_tt_data(&#34;backup&#34;)" class="button-primary">Backup Now</button>
         <hr/>
         <h2>Delete All Data</h2>
         <p>
            CAUTION! THIS CAN'T BE UNDONE!<br/>
            This button will delete all the clients, project, tasks, and time data associated with Time Tracker!<br/>
            Only use this if you're going to disable and delete the Time Tracker plugin and not use it again.<br/>
         </p>
         <button onclick="delete_tt_data(&quot;first&quot;)" class="button-primary">Delete Everything</button>
         <span id="delete-confirm" hidden>
            <p class="tt-warning">Are you really sure you want to delete everything? This can't be undone.</p>
            <button onclick="delete_tt_data(&quot;second&quot;)" class="button-primary delete-btn">YES, Delete Everything</button>
         </span>         
         <hr/>
      </div>
   <?php   
}