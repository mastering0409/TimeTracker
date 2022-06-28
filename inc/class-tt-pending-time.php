<?php
/**
 * Time Tracker Display Pending Time (ie: hasn't been billed yet)
 *
 * Sort pending time by bill-to and display in tables
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Pending_Time' ) ) {


    /**
     * Class
     * 
     */
    class Pending_Time
    {

        
        /**
         * Constructor
         * 
         */
        public function __construct() {
        }


        /**
         * Query db for time not yet billed
         * 
         */
        private function get_pending_time_from_db() {
            //Connect to Time Tracker Database
            //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
            global $wpdb;
            $sql_string = "SELECT tt_time.*, tt_client.Company, tt_client.BillTo, tt_task.TDescription, tt_task.TTimeEstimate,
                    Minute(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as LoggedMinutes,
                    Hour(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as LoggedHours
                FROM tt_time 
                LEFT JOIN tt_client
                    ON tt_time.ClientID = tt_client.ClientID
                LEFT JOIN tt_task
                    ON tt_time.TaskID = tt_task.TaskID
                WHERE (tt_time.Invoiced = \"\" OR tt_time.Invoiced IS NULL) AND tt_client.Billable = true
                ORDER BY tt_client.BillTo ASC, tt_time.ClientID ASC, tt_time.TaskID ASC, tt_time.StartTime ASC";
            $sql_result = $wpdb->get_results($query = $sql_string, $output = ARRAY_A);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        } //close function get to do list from db


        /**
         * Public function for child class
         * 
         */
        public function get_data_for_export() {
            $data = $this->get_time_grouped_by_billto();
            return $data;
        }
                        
            
            
        /**
         * Regroup data by Bill To
         * 
         */
        private function get_time_grouped_by_billto() {
            $pending_time = $this->get_pending_time_from_db();
            if ($pending_time) {
                $lastbillto = "not started";
                foreach ($pending_time as $item) {
                    $this_item_billto = sanitize_text_field($item['BillTo']);

                    if ($this_item_billto == "") {
                        $billto = "Unknown";
                    } else {
                        $billto = $this_item_billto;
                    }
                    
                    //create new key if first time seeing this bill to
                    if ($lastbillto != $billto) {
                        $grouped_time[$billto][0] = $item;
                    } else {
                        //or just add to the array under this key
                        array_push($grouped_time[$billto], $item);
                    }

                    //if ($this_item_billto == "") {
                    //    $lastbillto = "Unknown";
                    //} else {
                    $lastbillto = $billto;
                    //}
                }
            } else {
                $grouped_time = array();
            }
            return $grouped_time;
        }


        /**
         * Create front end display of time not yet billed
         * 
         */
        public function display_pending_time() {
            $grouped_time = $this->get_time_grouped_by_billto();
            if ($grouped_time) {

                //TABLE OF CONTENTS - WITH LINKS
                $html = "<strong>Click a Link to Jump to That Section</strong>";
                $html .= "<ul>";
                foreach ($grouped_time as $billtoname => $time_details) {
                    if ($billtoname != null) {
                        $html .= "<li><a href=\"#" . esc_attr($billtoname) . "\">Pending Time to Bill To: " . esc_textarea($billtoname) . "</a></li>";
                    } else {
                        $html .= "<li><a href=\"#None\">No Bill To Specified</a></li>";
                    }
                }
                $html.= "</ul>";
                $html .= "<br/>";

                //DETAILS
                foreach ($grouped_time as $billtoname => $time_details) {
                    if ($billtoname != null) {    
                        $html .= "<h2 id=\"" . esc_attr($billtoname) . "\">Pending Time, Bill To: " . esc_textarea($billtoname) . "</h2>";
                    } else {
                        $html .= "<h2 id=\"None\">Pending Time, No Bill To Specified</h2>";
                    }
                    $html .= $this->create_table($time_details);
                }
            } else {
                $html = "All caught up!";
            }
            return $html;
        }
        
        
        /**
         * Create individual tables for each bill to
         * 
         */ 
        private function create_table($time_entries) {    
            if (empty($time_entries)) {
                $table = "<strong>All caught up!</strong>";
                return $table;
            }

            //Begin creating table and headers
            $table = "<strong>Note: Gray shaded cells can't be changed.</strong><br/><br/>";
            $table .= "<table class=\"tt-table pending-time-table\">";
            $table .= "<thead><tr>";
            $table .= "<th>Client</th>";
            $table .= "<th>Task</th>";
            $table .= "<th>Task Description</th>";
            $table .= "<th>Start</th>";
            $table .= "<th>End</th>";
            $table .= "<th>Time Logged vs Estimate</th>";
            $table .= "<th>Invoiced</th>";
            $table .= "<th>Invoice #</th>";
            $table .= "<th>Invoiced Time</th>";
            $table .= "<th>Invoice Notes</th>";
            $table .= "<th>Status</th>";            
            $table .= "<th>Notes</th>";
            $table .= "</tr></thead>";

            $previous_client = sanitize_text_field($time_entries[0]['Company']);

            //Create body
            foreach ($time_entries as $item) {
                $client = sanitize_text_field($item['Company']);
                $logged_hours = sanitize_text_field($item['LoggedHours']);
                $logged_minutes = sanitize_text_field($item['LoggedMinutes']);
                $time_estimate = sanitize_text_field($item['TTimeEstimate']);
                $taskid = sanitize_text_field($item['TaskID']);
                $timeid = sanitize_text_field($item['TimeID']);
                $starttime = sanitize_text_field($item['StartTime']);
                $endtime = sanitize_text_field($item['EndTime']);

                if ($previous_client !== $client) {
                    $table .= "<tr><td class=\"divider-row\" colspan=\"12\"></td></tr>";
                }

                $ticket = $taskid . "-" . $item['TDescription'];
        
                $time_fraction_logged = (float)$logged_hours + round((float)$logged_minutes/60,2);
                $time_logged = tt_convert_to_string_time((float)$logged_hours, (float)$logged_minutes);
                if (($time_estimate != null) && ($time_estimate != 0)) {
                    $time_estimate_parts = explode(":", $time_estimate);
                    $time_estimate_as_number = tt_convert_to_decimal_time($time_estimate_parts[0], $time_estimate_parts[1]);
                    $percent_time_logged = " / " . $time_estimate_as_number . "<br/>" . round($time_fraction_logged / $time_estimate_as_number * 100) . "%";
                } else {
                    $percent_time_logged = "";
                }

                //create row
                $table .= "<tr>";           
                $table .= "<td id=\"client\" class=\"not-editable tt-col-width-ten-pct\">" . esc_textarea($client) . "</td>";
                $table .= "<td id=\"task-id\" class=\"not-editable tt-col-width-five-pct\">" . esc_textarea($taskid) . "</td>";
                $table .= "<td id=\"task-description\" class=\"not-editable tt-col-width-fifteen-pct\">"  . wp_kses_post(nl2br($item['TDescription'])) . "</td>";
                $table .= "<td id=\"start-time\" class=\"not-editable tt-col-width-five-pct\">" . esc_textarea(tt_format_date_for_display($starttime, "date_and_time")) . "</td>";
                $table .= "<td id=\"end-time\" class=\"not-editable tt-col-width-five-pct\">" . esc_textarea(tt_format_date_for_display($endtime, "date_and_time")) . "</td>";
                $table .= "<td id=\"time-logged\" class=\"not-editable tt-col-width-five-pct\">" . $time_logged . "<br/>" . $time_fraction_logged . " hrs" . $percent_time_logged . "</td>";
                $table .= "<td id=\"invoiced\" class=\"tt-editable tt-col-width-five-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_time', 'TimeID', 'Invoiced'," . esc_attr($timeid) . ")\">" . esc_textarea(sanitize_text_field($item['Invoiced'])) . "</td>";
                $table .= "<td id=\"invoice-number\" class=\"tt-editable tt-col-width-five-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_time', 'TimeID', 'InvoiceNumber'," . esc_attr($timeid) . ")\">" . esc_textarea(sanitize_text_field($item['InvoiceNumber'])) . "</td>";
                $table .= "<td id=\"invoiced-time\" class=\"tt-editable tt-col-width-five-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_time', 'TimeID', 'InvoicedTime'," . esc_attr($timeid) . ")\">" . esc_textarea(sanitize_text_field($item['InvoicedTime'])) . "</td>";
                $table .= "<td id=\"invoice-notes\" class=\"tt-editable tt-col-width-five-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_time', 'TimeID', 'InvoiceComments'," . esc_attr($timeid) . ")\">" . wp_kses_post(nl2br($item['InvoiceComments'])) . "</td>";
                $table .= "<td id=\"status\" class=\"tt-editable tt-col-width-five-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_task', 'TaskID', 'TStatus'," . esc_attr($taskid) . "), updateDatabase(this, 'tt_time', 'TimeID', 'NewTaskStatus'," . esc_attr($timeid) . ")\">" . esc_textarea(sanitize_text_field($item['NewTaskStatus'])) . "</td>";
                $table .= "<td id=\"task-notes\" class=\"tt-editable tt-col-width-thirty-pct\" contenteditable=\"true\" onBlur=\"updateDatabase(this, 'tt_time', 'TimeID', 'TNotes'," . esc_attr($timeid) . ")\">" . wp_kses_post(nl2br($item['TNotes'])) . "</td>";
                //close out row
                $table .="</tr>";

                $previous_client = $client;

            } // foreach loop

            //close out table
            $table .= "</table>";

            return $table;
        } //close function to create to do list table for display

    } //close class

} //close if class exists