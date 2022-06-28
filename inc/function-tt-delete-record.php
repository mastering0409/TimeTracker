<?php

/**
 * Function delete record
 *
 *
 *
 * @since 2.2.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * 
 * 
 */
function tt_delete_record_function() {
	
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["id"]) ) {

		if ( check_ajax_referer( 'tt_delete_record_nonce', 'security' )) {
					
			global $wpdb;
			$record = [
				sanitize_text_field($_POST["field"]) => sanitize_text_field($_POST["id"])
			];

            //cascade delete functionality
            $tbls = New Time_Tracker_Activator_Tables();
            $tbl_list = $tbls->get_table_list();
            //time=4....client=0
            $current_table = array_search(sanitize_text_field($_POST["table"]), $tbl_list, true);
            if($current_table >= 0) {
                //cascade delete, start at 4 (time), delete from each table until get to current table
                for($i = (count($tbl_list)-1); $i >= $current_table; $i--) {                      
                        //we have to recursively delete time entries manually for projects and recurring tasks because time table does not include their IDs
                        //so have to get the tasks from these items first and delete all time entries for each task
                        //be careful - if there are no tasks when we go to get the time entries there will be no where clauses and it will return ALL time entries, deleting ALL time entries                     
                        if ( ($tbl_list[$i] == "tt_time") and (sanitize_text_field($_POST['table'])=="tt_recurring_task" or sanitize_text_field($_POST['table'])=="tt_project") ) {
                            if (($_POST['table'])=="tt_project") {
                                $wpdb->query($wpdb->prepare("DELETE tt_time.* FROM tt_time INNER JOIN tt_task ON tt_time.TaskID = tt_task.TaskID WHERE tt_task.ProjectID = %d", intval($_POST["id"])));
                                catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);  
                                if ($wpdb->last_error !== "") {
                                    $return = array(
                                        'success' => 'false',
                                        'details' => 'Record deletion FAILED for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']) . ' cascade delete failed for table ' . tbl_list[$i],
                                        'message' => $wpdb->last_error
                                    );
                                    wp_send_json_error($return, 500);
                                    die();
                                }
                            } elseif(($_POST['table'])=="tt_recurring_task") {
                                $wpdb->query($wpdb->prepare("DELETE tt_time.* FROM tt_time INNER JOIN tt_task ON tt_time.TaskID = tt_task.TaskID WHERE tt_task.RecurringTaskID = %d", intval($_POST["id"])));
                                catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                                    
                                if ($wpdb->last_error !== "") {
                                    $return = array(
                                        'success' => 'false',
                                        'details' => 'Record deletion FAILED for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']) . ' cascade delete failed for table ' . tbl_list[$i],
                                        'message' => $wpdb->last_error
                                    );
                                    wp_send_json_error($return, 500);
                                    die();
                                }
                            }

                        } else {

                            $result = $wpdb->delete($tbl_list[$i], $record);
                            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                            
                            if ($wpdb->last_error !== "") {
                                $return = array(
                                    'success' => 'false',
                                    'details' => 'Record deletion FAILED for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']) . ' cascade delete failed for table ' . tbl_list[$i],
                                    'message' => $wpdb->last_error
                                );
                                wp_send_json_error($return, 500);
                                die();
                            }
                        }
                }
            
            } else {
                $return = array(
                    'success' => 'false',
                    'details' => 'Record deletion FAILED for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']) . '. The table could not be found.',
                    'message' => $wpdb->last_error
                );
                wp_send_json_error($return, 500);
                die();                
            }		

			//return result to ajax call
			if ($wpdb->last_error !== "") {
                $return = array(
                    'success' => 'false',
                    'details' => 'Record deletion FAILED for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']),
                    'message' => $wpdb->last_error
                );
                wp_send_json_error($return, 500);
                die();
			} else {
                $return = array(
                    'success' => 'true',
                    'details' => 'private',
                    'message' => 'SUCCESS: Record deleted for table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['field']) . "=" . sanitize_text_field($_POST['id']),
                );
                wp_send_json_success($return, 200);		
                die();
			}
			
		} //was _POST request
	} //check nonce
die();
}