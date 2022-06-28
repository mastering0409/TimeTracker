<?php
/**
 * Time Tracker Plugin Settings
 *
 * Initialize settings for Time Tracker Plugin
 * Ref: https://developer.wordpress.org/plugins/settings/using-settings-api/
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;


function tt_admin_settings_init() {


    $now = date('m-d-Y g:i A');


    /*
     *register new settings on main menu page
     *
     */
    register_setting('time-tracker', 'time-tracker');
    add_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now, 'error'=>'N/A', 'file'=>'N/A', 'function'=>'N/A'));

    /*
     *register new section on main menu page
     *
     */
    add_settings_section(
        'time-tracker',  //id
        'Category Options',       //title
        'Logically_Tech\Time_Tracker\Admin\tt_categories_section_callback',  //callable callback
        'time-tracker'            //page
    );


    /*
     *register a new field in the tt_categories_section on the main menu page
     *
     */
    add_settings_field(
        'time-tracker[bill-to-names]',    //id
        'Bill To Names',            //title
        'Logically_Tech\Time_Tracker\Admin\tt_categories_bill_to_names_callback',   //callable callback
        'time-tracker',               //page
        'time-tracker'       //section
    );
    

    add_settings_field(
        'time-tracker[work-categories]',    //id
        'Work Categories',            //title
        'Logically_Tech\Time_Tracker\Admin\tt_categories_work_categories_callback',   //callable callback
        'time-tracker',               //page
        'time-tracker'       //section
    );


    add_settings_field(
        'time-tracker[client-categories]',
        'Client Categories',
        'Logically_Tech\Time_Tracker\Admin\tt_categories_client_categories_callback',
        'time-tracker',
        'time-tracker'
    );


    add_settings_field(
        'time-tracker[client-sub-categories]',
        'Client Sub-Categories',
        'Logically_Tech\Time_Tracker\Admin\tt_categories_client_sub_categories_callback',
        'time-tracker',
        'time-tracker'
    );
}