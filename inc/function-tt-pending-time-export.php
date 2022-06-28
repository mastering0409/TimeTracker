<?php 
/**
 * Funciton tt_export_pending_time
 *
 * Export and download pending time as csv file
 * 
 * @since 2.2
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


function tt_export_pending_time() {
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') and (isset($_POST['export_to_csv'])) ) {

       		if ( check_ajax_referer('tt_export_pending_time_nonce', 'security')) {
				//security check passed
				//update the settings
                $export = new Pending_Time_Export();
                $files_array = $export->export_each_billto();
				
                $return = array(
                    'success' => true,
                    'files' => $files_array,
                    'msg' => 'Data has been saved'
                );
                wp_send_json_success($return, 200);
				die();
			
			} else {
                $return = array(
                    'success' => false,
                    'error' => true,
                    'msg' => 'Security check failed'
                );
                wp_send_json_error($return, 500); 
                die();  
                
            }

	}  //if post and update set
}