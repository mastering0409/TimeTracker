<?php
/**
 * Time Tracker Plugin Settings Callback Functions
 *
 * Define callback functions for settings for Time Tracker Plugin
 * Ref: https://developer.wordpress.org/plugins/settings/using-settings-api/
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;

 
/**
 * Settings Section - Categories
 * Callback Function
 */
function tt_categories_section_callback() {
}


/**
 * Settings Field - Bill to Names
 * Callback Function
 */
function tt_categories_bill_to_names_callback() {
    //get the value if it's already been entered
    $setting = get_option('time-tracker');

    //display on menu page
    ?>
    <div class="tt-indent">Do you always bill directly to the client? 
    Or do white-label services where you bill to a third party? 
    Do you perform services under different business names?<br>
    In the section below, add your different bill to names, one per line.<br>
    Examples: Self, Client, Third Party Business Name<br><br>
    <textarea id="tt-bill-to" name="time-tracker[bill-to-names]" rows="5" cols="30" class="tt-options-form" form="tt-options"><?php
    $btn = trim(sanitize_textarea_field($setting['bill-to-names']));
    if (isset( $btn )) {
        echo esc_html($btn);
    } else {
        echo '';
    }
    ?></textarea><br></div>
    <hr>
    <?php
}


/**
 * Settings Field - Work Categories
 * Callback Function
 */
function tt_categories_work_categories_callback() {
    //get the value if it's already been entered
    $setting = get_option('time-tracker');

    //display on menu page
    ?>
    <div class="tt-indent">Time Tracker can help you keep track of different types of work.<br>
    In the section below, add the options you'd like to see for this field when entering a new project or task.<br>
    Examples: Website Design, Social Media Management, Website Updates.<br>
    Enter one category on each line.<br><br>
    <textarea id="tt-categories" name="time-tracker[work-categories]" rows="10" cols="30" class="tt-options-form" form="tt-options"><?php
    $wc = trim(sanitize_textarea_field($setting['work-categories']));
    if (isset( $wc )) {
        echo esc_html($wc);
    } else {
        echo '';
    }
    ?></textarea><br></div>
    <hr>
    <?php
}


/**
 * Settings Field - Client Categories
 * Callback Function
 */
function tt_categories_client_categories_callback() {
    //get the value if it's already been entered
    $setting = get_option('time-tracker');

    //display on menu page
    ?>
    <div class="tt-indent">Time Tracker will maintain information on each of your clients. When adding a new client you'll be asked to enter how they found you.<br>
    In the section below, add the options you'd like to see for this field, one per line. <br>
    Examples: Paid Ad, Organic Search, Referral.<br><br>
    <textarea id="tt-csource" name="time-tracker[client-categories]" rows="10" cols="30" class="tt-options-form" form="tt-options"><?php
    $cc = trim(sanitize_textarea_field($setting['client-categories']));
    if (isset( $cc )) {
        echo esc_html($cc);
    } else {
        echo '';
    }
    ?></textarea><br></div>
    <hr>
    <?php   
}


/**
 * Settings Field - Client Sub-Categories
 * Callback Function
 */
function tt_categories_client_sub_categories_callback() {
    //get the value if it's already been entered
    $setting = get_option('time-tracker');

    //display on menu page
    ?>
    <div class="tt-indent">You can also add a second level of information on how the client found you.<br>
    In the section below, add the options you'd like to see for this field, one per line.<br>
    Examples: Google PPC, Facebook Ad, LinkedIn Ad, Name of Individual That Referred Client<br><br>
    <textarea id="tt-client-sub-categories" name="time-tracker[client-sub-categories]" rows="10" cols="30" class="tt-options-form" form="tt-options"><?php
    $csc = sanitize_textarea_field($setting['client-sub-categories']);
    if (isset( $csc )) {
        echo esc_html($csc);
    } else {
        echo '';
    }
    ?></textarea><br></div>
    <br>
    <?php
}