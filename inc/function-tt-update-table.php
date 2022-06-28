<?php

/**
 * Function update table data based on user input
 *
 * Update data in SQL table based on user input in updateable html display table
 * Ref: https://phppot.com/php/php-mysql-inline-editing-using-jquery-ajax/
 *
 *
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * 
 * 
 */
function tt_update_table_function() {
	
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["id_field"]) ) {

		if ( check_ajax_referer( 'tt_update_table_nonce', 'security' )) {
					
			global $wpdb;

			$record = [
				sanitize_text_field($_POST['id_field']) => sanitize_text_field($_POST['id'])
			];

			//var_dump(strtolower(sanitize_text_field($_POST['field'])));
			//var_dump(strpos(strtolower(sanitize_text_field($_POST['field']), 'endrepeat')));
			//deal with date entries, must be inserted into database in yyyy-mm-dd format
			if ( ( strpos(strtolower(sanitize_text_field($_POST['field'])), 'date') OR strpos(strtolower(sanitize_text_field($_POST['field'])), 'time') OR strtolower(sanitize_text_field($_POST['field'])) == 'endrepeat' ) AND !(sanitize_text_field($_POST['field']) == 'InvoicedTime') ) {

				//convert the date entered from a string to a date/time object
				$date_entered = new \DateTime(sanitize_text_field($_POST['value']));

				//use date/time object to convert back to a string of standard SQL format yyyy-mm-dd
				$date_in_sql_format = $date_entered->format('Y') . "-" . $date_entered->format('m') . "-" . $date_entered->format('d');
				
				//deal with date and time entires, must be inserted into db in yyyy-mm-dd hh:mm:ss format
				if ( strpos(strtolower(sanitize_text_field($_POST['field'])), 'time') ) {
					$date_in_sql_format .= " " . $date_entered->format('H') . ":" . $date_entered->format('i') . ":" . $date_entered->format('s');
				}

				$data = [
					sanitize_text_field($_POST['field']) => $date_in_sql_format
				];
				//the last argument, %s, tells the function to keep the data in string format
				$result = $wpdb->update(sanitize_text_field($_POST['table']), $data, $record);
				catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
					
			//pass everything else along to the wp update function
			} else {

				//if updated value includes <br> that were automatically inserted remove them to avoid doulbe line breaks
				//we're using WPDB->update below so data should not be escaped
				if ( strpos(sanitize_textarea_field($_POST['value']), '<br><br>')) {
					$updated_value = str_replace('<br><br>','<br>',$_POST['value']);
				} else {
					$updated_value = $_POST['value'];
				}

				$data = [
					sanitize_text_field($_POST['field']) => $updated_value
				];
				$result = $wpdb->update(sanitize_text_field($_POST['table']), $data, $record);
				catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
			}

			//return result to ajax call
			if ($wpdb->last_error !== "") {
				$return = array(
					'success' => 'false',
					'details' => 'update table: ' . sanitize_text_field($_POST['table']). ', where  ' . sanitize_text_field($_POST['id_field']) . "=" . sanitize_text_field($_POST['id']) . ', update field: ' . sanitize_text_field($_POST['field']) . " to value: " . $updated_value,
					'message' => $wpdb->last_error
				);
				wp_send_json_error($return, 500);
			} else {
				$return = array(
					'success' => 'true',
					'details' => 'private',
					'message' => 'Success, database updated.'
				);
				wp_send_json_success($return, 200);			
			}
			
		} //was _POST request
	} //check nonce
	die();
}