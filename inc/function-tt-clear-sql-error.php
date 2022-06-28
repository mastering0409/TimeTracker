<?php 
/**
 * Funciton Time_Tracker_Clear_SQL_Error
 *
 * Update setting in options table indicating there's been a recent SQL error
 * Called by button next to error message on all TT screens
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


function tt_clear_sql_error_function() {
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') and (isset($_POST['update'])) ) {

       		if ( check_ajax_referer('tt_clear_sql_error_nonce', 'security')) {
				
				//update the settings
				if ( sanitize_text_field($_POST['update']) == "clear") {
      	    		$now = new \DateTime;
     	    		$now->setTimezone(new \DateTimeZone(get_option('timezone_string')));
    	    	    $update = update_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now->format('m-d-Y g:i A'),'error'=>'N/A', 'file'=>"", 'function'=>""));
    	    	} //if update says clear
				
				//send response
				if ($update) {
					$return = array(
						'success' => true,
						'msg' => 'The sql error was cleared'
					);
					wp_send_json_success($return, 200);
				} else {
					//failure
					$return = array(
						'success' => false,
						'msg' => 'There was a problem clearing the sql error' 
					);
					wp_send_json_error($return, 500);
				} //send error/success response to json
				
				die();
			
			} //security check passed

	}  //if post and update set
}