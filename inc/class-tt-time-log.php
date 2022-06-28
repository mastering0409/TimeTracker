<?php
/**
 * Class Time_Log
 *
 * CLASS TO DISPLAY TIME LOG TABLE
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Time_Log' ) ) {


    /**
     * Class
     * 
     */  
    class Time_Log
    {

        private $clientid;
        private $projectid;
        private $rectaskid;
        private $taskid;
        private $timeid;        
        private $notes;
        private $startdate;
        private $enddate;
        private $record_limit = true;


        /**
         * Constructor
         * 
         */        
        public function __construct() {
            $this->timeid = (isset($_GET['time-id']) ? intval($_GET['time-id']) : null);
            if (isset($_GET['task-name'])) {
                if ($_GET['task-name'] <> null) {
                    $this->taskid = get_task_id_from_name(sanitize_text_field($_GET['task-name']));
                }
            } elseif (isset($_GET['task-id'])) {
                $this->taskid = intval($_GET['task-id']);
            } else {
                $this->taskid  = null;
            };
            $this->rectaskid = (isset($_GET['recurring-task-id']) ? intval($_GET['recurring-task-id']) : null);
            if (isset($_GET['project-name'])) {
                if ($_GET['project-name'] <> null) {
                    $this->projectid = get_project_id_from_name(sanitize_text_field($_GET['project-name']));
                }
            } elseif (isset($_GET['project-id'])) {
                $this->projectid = intval($_GET['project-id']);
            } else {
                $this->projectid = null;
            }
            if (isset($_GET['client-name'])) {
                if ($_GET['client-name'] <> null) {
                    $this->clientid = get_client_id_from_name(sanitize_text_field($_GET['client-name']));
                }
            } elseif (isset($_GET['client-id'])) {
                $this->clientid = intval($_GET['client-id']);
            } else {
                $this->clientid  = null;
            };
            $this->notes = (isset($_GET['notes']) ? sanitize_text_field($_GET['notes']) : null);
            $this->startdate = (isset($_GET['first-date']) ? sanitize_text_field($_GET['first-date']) : null);
            $this->enddate = (isset($_GET['last-date']) ? sanitize_text_field($_GET['last-date']) : null);
        }


        /**
         * Get results
         * 
         */
        public function create_table() {
            return $this->get_html();
        }


        /**
         * Get data from db - returns object
         * 
         */
        private function get_time_log_from_db() {
            global $wpdb;
            $sql_string = $this->create_sql_string();
            $sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        }


        /**
         * Get data from db - return array
         * 
         */
        protected function get_time_log_array_from_db() {
            global $wpdb;
            $sql_string = $this->create_sql_string();
            $sql_result = $wpdb->get_results($sql_string, 'ARRAY_A');
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        }


        /**
         * Prepare sql string
         * 
         */
        private function create_sql_string() {   
            global $wpdb;	
			$selectfrom = "SELECT tt_time.*, tt_client.Company, tt_client.BillTo, tt_task.ProjectID, tt_task.TCategory, tt_task.RecurringTaskID, tt_task.TDescription, tt_task.TStatus, tt_task.TTimeEstimate,
                    Minute(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as LoggedMinutes,
                    Hour(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as LoggedHours
                FROM tt_time 
                LEFT JOIN tt_client
                    ON tt_time.ClientID = tt_client.ClientID
                LEFT JOIN tt_task
                    ON tt_time.TaskID = tt_task.TaskID";
            $orderby = "ORDER BY tt_time.StartTime DESC";
            
            $sql_string = $selectfrom . $this->get_where_clauses() . " " . $orderby . $this->get_limit_parameter();
			//echo $sql_string;
            return $sql_string;
        }


        /**
         * Set pagination property
         * 
         */
        protected function remove_record_limit() {
            $this->record_limit = false;
        }
        
        
        /**
         * Get LIMIT parameter for query
         * 
         */
        private function get_limit_parameter() {
            if ($this->record_limit == false) {
                return "";
            } else {
                $record_numbers = get_record_numbers_for_pagination_sql_query();	
                $subset_for_pagination = " LIMIT " . $record_numbers['limit'] . " OFFSET " . $record_numbers['offset'];
                return $subset_for_pagination;                
            }
        }


        /**
         * Get where clauses depending on input
         * 
         */
        private function get_where_clauses() {
            global $wpdb;
            $where_clauses = array();
            $where_clause = "";
            if ($this->clientid <> null) {
                array_push($where_clauses, "tt_time.ClientID = " . $this->clientid);
            }
            if ($this->projectid <> null) {
                //no project id field in time table - so we have to get tasks and then time associated with those tasks
                array_push($where_clauses, "tt_task.ProjectID = " . $this->projectid);
            }
            if ($this->rectaskid <> null) {
                //no recurring task id field in time table - so we have to get tasks and then time associated with those tasks
                array_push($where_clauses, "tt_task.RecurringTaskID = " . $this->rectaskid);
            }
            if ($this->taskid <> null) {
                array_push($where_clauses, "tt_time.TaskID = " . $this->taskid);
            }
            if ( ($this->timeid <> "") and ($this->timeid <> null) and ($this->timeid <> "null") ) {
                array_push($where_clauses, "tt_time.TimeID = " . $this->timeid);
            }
            if ( ($this->startdate <> "") and ($this->startdate <> null) ) {
                array_push($where_clauses, "tt_time.StartTime >= '" . $this->startdate . " 00:00:01'");
            }
            if ( ($this->enddate <> "") and ($this->enddate <> null) ) {
                array_push($where_clauses, "tt_time.EndTime <= '" . $this->enddate . " 23:59:59'");
            }
            if ( ($this->notes <> "") and ($this->notes <> null) ) {
                //Ref: https://developer.wordpress.org/reference/classes/wpdb/esc_like/
                $wild = "%";
                $search_like = "'" . $wild . $wpdb->esc_like( $this->notes ) . $wild . "'";
                array_push($where_clauses, "tt_time.TNotes LIKE " . $search_like);
            }
            if ( (count($where_clauses) > 1) or ((count($where_clauses) == 1) and ($where_clauses[0] <> "")) ) {
                $where_clause = " WHERE ";
                $where_clause .= implode(" AND ", $where_clauses);
            }
            return $where_clause;
        }


        /**
         * Get table column order and table fields
         * 
         */
        private function get_table_fields() {
            $cols = [
                "ID" => [
                    "fieldname" => "TimeID",
                    "id" => "time-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Client ID" => [
                    "fieldname" => "ClientID",
                    "id" => "client-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Client" => [
                    "fieldname" => "Company",
                    "id" => "client-name",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Task ID" => [
                    "fieldname" => "TaskID",
                    "id" => "task-id",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Type" => [
                    "fieldname" => "TCategory",
                    "id" => "task-type",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Task" => [
                    "fieldname" =>"TDescription",
                    "id" => "task-description",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Start Time" => [
                    "fieldname" => "StartTime",
                    "id" => "start-time",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "date and time",
                    "class" => "tt-align-right"
                ],
                "End Time" => [
                    "fieldname" => "EndTime",
                    "id" => "end-time",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "date and time",
                    "class" => ""
                ],
                "Time Logged Vs Estimate" => [
                    "fieldname" => "TimeLoggedVsEstimate",
                    "id" => "time-logged-vs-estimate",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => "tt-align-right"
                ],
                "Status" => [
                    "fieldname" => "TStatus",
                    "id" => "task-status",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => "tt-align-right"
                ],
                "Invoiced?" => [
                    "fieldname" => "Invoiced",
                    "id" => "invoiced",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Invoice Number" => [
                    "fieldname" => "InvoiceNumber",
                    "id" => "invoice-number",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Invoiced Time" => [
                    "fieldname" => "InvoicedTime",
                    "id" => "invoice-time",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Invoice Comments" => [
                    "fieldname" => "InvoiceComments",
                    "id" => "invoice-comments",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Notes" => [
                    "fieldname" => "TNotes",
                    "id" => "task-notes",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Follow Up" => [
                    "fieldname" => "FollowUp",
                    "id" => "follow-up",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ]
            ];
            return $cols;
        }


        /**
         * Get Data from Table and Append with Any Extra Info
         * 
         */
        private function get_all_data_for_display() {
            $time_entries = $this->get_time_log_from_db();
            //$time_entries = $this->time_details;

            foreach ($time_entries as $item) {
                if ( (sanitize_text_field($item->RecurringTaskID) != null) and (sanitize_text_field($item->RecurringTaskID) != "") ) {
                    $icon = tt_add_recurring_task_icon();
                    $task_category = $item->TCategory;
                    $item->TCategory = [
                        "value" => $task_category,
                        "icon" => $icon
                    ];                    
                }

                $delete_time_button = "<button onclick='location.href = \"" . TT_HOME . "delete-item/?time-id=" . esc_attr($item->TimeID) . "\"' id=\"delete-time-" . esc_attr($item->TimeID)  . "'\" class=\"open-delete-page tt-button tt-table-button\">Delete</button>";
                $item->TimeID = [
                    "value" => $item->TimeID,
                    "button" => [
                        $delete_time_button
                    ]
                ];

                $time_estimate_formatted = get_time_estimate_formatted(sanitize_text_field($item->TTimeEstimate));
                $hours_logged = tt_convert_to_decimal_time(sanitize_text_field($item->LoggedHours), sanitize_text_field($item->LoggedMinutes));
                $percent_time_logged = get_percent_time_logged($time_estimate_formatted, $hours_logged);
                $time_worked_vs_estimate_class = get_time_estimate_class($percent_time_logged);
                $item->TimeLoggedVsEstimate = [
                    "value" => $hours_logged . $percent_time_logged,
                    "class" => $time_worked_vs_estimate_class
                ];
            }
            return $time_entries;
        }


        /**
         * Create Table
         * 
         */
        public function get_html() {            
            $fields = $this->get_table_fields();
            $time_entries = $this->get_all_data_for_display();
            $args["class"] = ["tt-table", "time-log-table"];
            $tbl = new Time_Tracker_Display_Table();
            $table = $tbl->create_html_table($fields, $time_entries, $args, "tt_time", "TimeID");
            return $table;
        }
        
    } //close class

} //close if class exists