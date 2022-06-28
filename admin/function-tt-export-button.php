<?php 
/**
 * Funciton Time_Tracker_Export_Button
 *
 * Function that runs when user clicks export button in admin area
 * Called by button on admin screen
 * After confirmation that this request is valid and security check, will run export tables function
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;


function tt_export_button_function() {

	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') and (isset($_POST['type'])) ) {
		
		if (check_ajax_referer('tt_export_data_nonce', 'security')) {

			$e = error_get_last();	
			
			require_once 'function-tt-export-tables.php';
			tt_export_data_function();
			
			$e_now = error_get_last();
			
		  	//send response
			if ( $e_now !== $e) {
				$return = array(
					'success' => false,
					'msg' => 'There was a problem. Error: ' . $e_now['message'] . ', in File: ' . $e_now['file'] . ' on line ' . $e_now['line'] 
				);
				wp_send_json_error($return, 500);
			} elseif (PHP_OS_FAMILY === "Windows") {
				$return = array(
					'success' => false,
					'msg' => 'MySQL Backups aren\'t always successful in Windows environments. Check your files to confirm the backup file exists and has data in it.'
				);
				wp_send_json_error($return, 500);
			} else {
				//success
				$return = array(
					'success' => true,
					'msg' => 'Your data was exported.'
				);
				wp_send_json_success($return, 200);
			} //send error/success response to json
				
			die();	

		} //if passed security check

	} //if post request where type is set	
}