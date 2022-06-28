<?php
/**
 * Functions to add custom field to Contact Form 7
 * Field Project Name Dropdown - List Sourced from SQL Table
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Add New CF7 Custom Form Field - Project Name Dropdown
 * 
 */
add_action( 'wpcf7_init', 'Logically_Tech\Time_Tracker\Inc\custom_add_form_tag_project_name');


/**
 * Initialize project_name as a custom CF7 form tag
 * 
 */
function custom_add_form_tag_project_name() {
  wpcf7_add_form_tag( 'project_name', 'Logically_Tech\Time_Tracker\Inc\custom_project_name_form_tag_handler', array('name-attr'=>true));
}


/**
 * Define callback for project_name form tag
 * 
 */
function custom_project_name_form_tag_handler( $tag ) {

//Query time tracker database to get list of current projects and project id's
  //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
  global $wpdb;
  $project_list_search_string = 'SELECT ProjectID, PName, ClientID FROM tt_project ORDER BY ProjectID ASC';
  $project_list = $wpdb->get_results($project_list_search_string);
  catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);

  //Build form tag
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

    $project_name = '<option value=null></option>';

    foreach ($project_list as $val) {
      if ((isset($_GET['project-name'])) AND ( stripslashes($_GET['project-name']) == $val->PName )) {
        $project_name .= '<option value="' . esc_textarea($val->PName) . '" selected="selected">' . esc_textarea($val->PName) . '</option>';
      } else {
        $project_name .= '<option value="' . esc_textarea($val->PName) . '">' . esc_textarea($val->PName) . '</option>';
      }
    }

    //close out select tag
    $project_name .= '</select>';
    return $input . $project_name;
}