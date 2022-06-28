<?php 
/**
 * Function dynamic-task-dropdown
 *
* Dynamically update the task dropdown list depending on client chosen
* Called from update_task_list Javascript function triggered by client onchange event
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Fixes call to undefined function error when calling plugin_dir_url below
 * 
 */
//if ( !defined('ABSPATH') ) {
    //If wordpress isn't loaded load it up.
    //$path = $_SERVER['DOCUMENT_ROOT'];
    //include_once $path . '/wp-load.php';
//}


/**
 * Fixes call to undefined function error when calling get_client_id_from_name function
 * 
 */
/**if(!function_exists('get_client_id_from_name')) {
    include_once(__DIR__ . "/time-tracker.php"); 
}**/


function tt_update_task_list_function() {
	
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['client']) ) {

		if ( check_ajax_referer( 'tt_update_task_list_nonce', 'security' )) {

            //Which client was chosen by the user in the previous dropdown?
            //pull the variable from the url and remove the % encoding, and strip slashes before apostrophes, then clean
            $client_name = sanitize_text_field(stripslashes(urldecode($_POST['client'])));
            $client_id = get_client_id_from_name($client_name);

            //Query time tracker database to get list of current tasks and task id's
            //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
            global $wpdb;
            $task_list_search_string = $wpdb->prepare('SELECT TaskID, TDescription FROM tt_task WHERE ClientID="%s" AND TStatus <> \'Completed\' AND TStatus <> \'Canceled\' AND TStatus <> \'Closed\' ORDER BY TaskID DESC',$client_id);
            $task_list = $wpdb->get_results($task_list_search_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);

            $task_options = '<option value=null></options>';

            //Create new options for dropdown based on narrowed search results
            foreach ($task_list as $val) {
                $task_identifier_string = sanitize_text_field($val->TaskID) . "-" . sanitize_text_field($val->TDescription);
                $task_options .= '<option value="' . esc_html($task_identifier_string) . '">' . esc_html($task_identifier_string) . '</option>';
            }

            //close out select tag
            $task_options .= '</select>';

            //display response
            //echo $task_options;

            //return result to ajax call
			if ($task_options == "") {
				$return = array(
					'success' => 'false',
					'details' => 'No tasks returned',
					'message' => $wpdb->last_error
				);
				wp_send_json_error($return, 500);
			} else {
				$return = array(
					'success' => 'true',
					'details' => $task_options,
					'message' => 'Success'
				);
				wp_send_json_success($return, 200);			
			}
        
        } //security check
    } //post with client set
} //function