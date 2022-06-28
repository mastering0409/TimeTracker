<?php 
/**
 * Funciton Time_Tracker_Delete_Tables
 *
 * Delete Time Tracker tables (only) which will delete all user data
 * Note: Forms and pages will be deleted with plugin deleted
 * Called by button on admin screen, after user confirmed
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;

use Logically_Tech\Time_Tracker\Inc\Time_Tracker_Deletor;


function tt_delete_data_function() {

	if ( ($_SERVER['REQUEST_METHOD'] = 'POST') && isset($_POST['type']) ){

		$type = sanitize_text_field($_POST['type']);

    	if ($type == 'confirmed') {
    	
			if (check_ajax_referer('tt_delete_data_nonce', 'security')) {

				$e_before = error_get_last();
				
				require_once __DIR__ . '/../inc/class-time-tracker-delete.php';
				Time_Tracker_Deletor::delete_tables_only();

				$e_after = error_get_last();
				
				if ($e_before !== $e_after) {
					$return = array(
						'success' => false,
						'msg' => 'There was an error deleting your data. Error Message: ' . $e_after['message']
					);
					wp_send_json_error($return, 500);
				} else {
					$return = array(
						'success' => true,
						'msg' => 'Your data was deleted.'
					);
					wp_send_json_success($return, 200);
				}
				
				wp_die();
				
			} //security check
        
    	} //post type confirmed

	} //if post with type set
	
} //function