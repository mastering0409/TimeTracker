<?php
/**
 * Functions to add custom field to Contact Form 7
 * Field Client Name Dropdown - List Sourced from SQL Table
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Create Custom CF7 Form Tag, Client Name Dropdown
 * 
 */
add_action( 'wpcf7_init', 'Logically_Tech\Time_Tracker\Inc\custom_add_form_tag_client_name');


/**
 * Initialize client_name as a custom CF7 form tag
 * 
 */
function custom_add_form_tag_client_name() {
  wpcf7_add_form_tag( 'client_name', 'Logically_Tech\Time_Tracker\Inc\custom_client_name_form_tag_handler', array('name-attr'=>true));
}


/**
 * Define callback for client_name form tag
 * 
 */
function custom_client_name_form_tag_handler( $tag ) {

  //Query time tracker database to get list of current clients and client id's
  //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
  global $wpdb;
  $client_list_search_string = "SELECT ClientID, Company FROM tt_client ORDER BY Company ASC";
  $client_list = $wpdb->get_results($client_list_search_string);
  catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);

  $atts = array(
        'type' => 'text',
        'name' => $tag->name,
        'id' => $tag->get_id_option(),
        'class' => $tag->get_class_option(),
        'default' => $tag->get_default_option(),
        'list' => $tag->name . '-options',
    );
    
    $input = sprintf(
        '<select %s />',
        wpcf7_format_atts( $atts)
    );

    $client_name = "<option value=\"\"></option>";

    foreach ($client_list as $val) {
      $company_name = $val->Company;
      if ((isset($_GET['client-name'])) AND ( stripslashes($_GET['client-name']) == $company_name )) {
        $client_name .= '<option value="' . esc_textarea($company_name) . '" selected="selected">' . esc_textarea($company_name) . '</option>';
      } else {
        $client_name .= '<option value="' . esc_textarea($company_name) . '">' . esc_textarea($company_name) . '</option>';
      }
    }

    //close out select tag
    $client_name .= '</select>';

    return $input . $client_name;
}