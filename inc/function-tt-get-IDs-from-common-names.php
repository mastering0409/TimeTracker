<?php
/**
 * Functions to query db and get table IDs from common names chosen by user in form
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * get client ID to load into table from the client name chosen by the user
 * 
 */
function get_client_id_from_name($client_name) {
  //Query time tracker database to get list of current clients and client id's
  //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
  global $wpdb;
  $client_id_search_string = $wpdb->prepare('SELECT ClientID FROM tt_client WHERE Company= "%s"', $client_name);
  $client_id_search_result = $wpdb->get_results($client_id_search_string);
  catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
  if ($client_id_search_result) {
    $client_id = $client_id_search_result[0]->ClientID;
    return $client_id;    
  } else {
    return "";
  }
}


/**
 * get project ID to load into table from the project name chosen by the user
 * 
 */
function get_project_id_from_name($project_name) {
  if ( ($project_name=="") or ($project_name == null)) {
    $project_id = null;
  } else {
    //$project_name_and_quotes = chr(34) . $project_name . chr(34); 
    //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
    global $wpdb;
    $project_id_search_string = $wpdb->prepare('SELECT ProjectID FROM tt_project WHERE PName= %s', $project_name);
    $project_id_search_result = $wpdb->get_results($project_id_search_string);
    if (!empty($project_id_search_result)) {    
      $project_id = $project_id_search_result[0]->ProjectID;
    } else {
      $project_id = null;
    }
    catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query . ' result is ' . $project_id, $wpdb->last_error);  
  }
  return $project_id;    
}


/**
 * get task ID to load into table from the task name chosen by the user
 * 
 */
function get_task_id_from_name($task_name) {
  //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
  global $wpdb;
  $task_id_search_string = $wpdb->prepare('SELECT TaskID FROM tt_task WHERE TDescription= "%s"', $task_name);
  $task_id_search_result = $wpdb->get_results($task_id_search_string);
  $task_id = $task_id_search_result[0]->TaskID;
  catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query . ' result is ' . $task_id, $wpdb->last_error);
  return $task_id;    
}