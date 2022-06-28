<?php
/**
 * Class Task_Details
 *
 * CLASS TO DISPLAY DETAILS OF TASK INCLUDING TIME WORKED
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Task_Details' ) ) {

    /**
     * Class
     * 
     */  
    class Task_Details
    {
        
        
        /**
         * Class variables
         * 
         */ 
        private $taskid;


        /**
         * Constructor
         * 
         */ 
        public function __construct() {
            if (isset($_GET['task-id'])) {
                $this->taskid = sanitize_text_field($_GET['task-id']);
            }
        }


        /**
         * Get results
         * 
         */ 
        public function generate_output_for_display() {
            return $this->get_html();
        }
        
        
        /**
         * Query db for task details
         * 
         */ 
        private function get_task_details_from_db() {
            global $wpdb;

            $sql_string_format = "SELECT tt_task.TaskID, tt_task.TDescription, tt_task.ClientID, tt_task.ProjectID,
                    tt_task.TStatus, tt_task.TTimeEstimate, tt_task.TDateAdded, tt_task.TDueDate,
                    tt_task.TNotes TaskNotes, tt_client.Company, tt_project.ProjectID, tt_project.PName,
                    tt_time.TimeID, tt_time.StartTime, tt_time.EndTime, tt_time.TNotes TimeNotes, tt_time.FollowUp,
                    tt_time.Invoiced, tt_time.InvoiceNumber, tt_time.InvoicedTime, tt_time.InvoiceComments
                FROM tt_task
                LEFT JOIN tt_client
                    ON tt_task.ClientID = tt_client.ClientID
                LEFT JOIN tt_project
                    ON tt_task.ProjectID = tt_project.ProjectID
                LEFT JOIN tt_time
                    ON tt_task.TaskID = tt_time.TaskID
                WHERE tt_task.TaskID = %s";

            $sql_string = $wpdb->prepare($sql_string_format, $this->taskid);
            $sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        }


        /**
         * Generate HTML for front end display
         * 
         */ 
        private function get_html() {
            $task = $this->get_task_details_from_db();

            $hrs_worked = 0;
            $hrs_invoiced = 0;

            if ( $task[0]->TimeID === NULL ) {   
                $total_time_display = "";           
            } else {
                foreach ($task as $time_entry) {
                    $start_time = date_create_from_format('Y-m-d H:i:s', $time_entry->StartTime);
                    $end_time = date_create_from_format('Y-m-d H:i:s', $time_entry->EndTime);
                    $elapsed_time = date_diff($start_time, $end_time);
                    $hrs_this_entry = $elapsed_time->format('%h');
                    $mins_this_entry = $elapsed_time->format('%i');
                    $hrs_worked = $hrs_worked + $hrs_this_entry + round(($mins_this_entry / 60),2);
                    $inv_time = sanitize_text_field($time_entry->InvoicedTime) ? sanitize_text_field($time_entry->InvoicedTime) : 0;
                    $hrs_invoiced = $hrs_invoiced + $inv_time;
                } //loop through all time entries to total time worked and invoiced
                if ($hrs_worked >0) {
                    $total_time_display = $hrs_worked . " hrs worked  /  " . $hrs_invoiced . " hrs invoiced  /  " . round($hrs_invoiced / $hrs_worked*100,0) . " % invoiced";
                } else {
                    $total_time_display = "0 hrs worked";
                }
            }

            $date_added_formatted = tt_format_date_for_display(sanitize_text_field($task[0]->TDateAdded), "date_and_time"); 
            $due_date_formatted = tt_format_date_for_display(sanitize_text_field($task[0]->TDueDate), "date_only");

            $display = "<h2>Task # " . esc_textarea(sanitize_text_field($this->taskid)) . " Overview</h2>";
            $display .= "<strong>Description:</strong>  " . wp_kses_post(nl2br($task[0]->TDescription)) . "<br/>";
            $display .= "<strong>Client:</strong>  " . esc_textarea(sanitize_text_field($task[0]->Company)) . "<br/>";
            $display .= "<strong>Project:</strong> " . esc_textarea(sanitize_text_field($task[0]->ProjectID)) . " - " . esc_textarea(sanitize_text_field($task[0]->PName)) . "<br/>";
            $display .= "<strong>Status:</strong>  " . esc_textarea(sanitize_text_field($task[0]->TStatus)) . "<br/>";
            $display .= "<strong>Date Added:</strong>  " . $date_added_formatted . "<br/>";
            $display .= "<strong>Due Date:</strong>  " . $due_date_formatted . "<br/>";
            $display .= "<strong>Notes:</strong>  " . wp_kses_post(nl2br($task[0]->TaskNotes)) . "<br/>";
            $display .= "<strong>Total Time:</strong>  " . $total_time_display . "<br/>";
            $display .= "<br/><hr/>";

            $display .= "<h2>Time Entries for Task # " . esc_textarea(sanitize_text_field($this->taskid)) . "</h2>";

            if ($task[0]->TimeID === NULL) {
                $display .= "     There are no time entries for this task.";
            } else {
                $display .= "<div id='time-entries' style='padding-left:40px;'>";
                foreach ($task as $time_entry) {
                    $start_time = date_create_from_format('Y-m-d H:i:s', sanitize_text_field($time_entry->StartTime));
                    $end_time = date_create_from_format('Y-m-d H:i:s', sanitize_text_field($time_entry->EndTime));
                    $start_time_formatted = tt_format_date_for_display(sanitize_text_field($time_entry->StartTime), "date_and_time");
                    $end_time_formatted = tt_format_date_for_display(sanitize_text_field($time_entry->EndTime), "date_and_time");                    
                    $elapsed_time = date_diff($start_time, $end_time);
                    $hrs_this_entry = $elapsed_time->format('%h');
                    $mins_this_entry = $elapsed_time->format('%i');
                    $hrs_worked = $hrs_this_entry + round(($mins_this_entry / 60),2);
                    $invoiced_time = ($time_entry->InvoicedTime == null) ? 0 : sanitize_text_field($time_entry->InvoicedTime);  
                    if ( ($hrs_worked == 0) or ($hrs_worked == null) ) {
                        $inv_percent = "-";
                    } else {
                        $inv_percent = round($invoiced_time / $hrs_worked*100,0);
                    }

                    $display .= "<h3>Time Entry ID: " . esc_textarea(sanitize_text_field($time_entry->TimeID)) . "</h3>";
                    $display .= "<strong>Time Worked:</strong>  " . $start_time_formatted . " - " . $end_time_formatted . "<br/>";
                    $display .= "<strong>Time Invoiced:</strong>  " . $hrs_worked . " hrs worked / " . esc_textarea($invoiced_time) . " hrs invoiced / " . $inv_percent . " % invoiced<br/>";
                    $invnumber = sanitize_text_field($time_entry->InvoiceNumber);
                    if ($invnumber === NULL OR $invnumber == "") {
                        $display .= "<strong>Invoiced:</strong>  " . esc_textarea(sanitize_text_field($time_entry->Invoiced)) . "<br/>";
                    } else {
                        $display .= "<strong>Invoiced:</strong>  " . esc_textarea(sanitize_text_field($time_entry->Invoiced)) . ", Invoice # " . esc_textarea($invnumber) . "<br/>";
                    }
                    $display .= "<strong>Invoice Comments:</strong>  " . wp_kses_post(nl2br($time_entry->InvoiceComments)) . "<br/>";
                    $display .= "<strong>Time Notes:</strong>  " . wp_kses_post(nl2br($time_entry->TimeNotes)) . "<br/>";
                    $display .= "<hr/>";
                } //end looping through time entries
                $display .= "</div>";
            } //end if count of time entries 
            return $display;
        }


    } //close class

} //close if class exists