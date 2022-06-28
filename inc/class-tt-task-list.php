<?php
/**
 * Class Task_List
 *
 * Get and display entire task list
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );


/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Task_List' ) ) {


    /**
     * Class
     * 
     */
    class Task_List
    {

        private $clientid;
        private $rectaskid;
        private $taskid;
        private $timeid;
        private $notes;
        private $projectid;
        private $assoc_field;
        private $assoc_id;
        private $closed_status = ["COMPLETE", "CANCEL", "CLOSE"];
        private $status_search;


        /**
         * Constructor
         * 
         */
        public function __construct() {
            //$this->timeid = (isset($_GET['time-id']) ? intval($_GET['time-id']) : null);
            if (isset($_GET['task'])) {
                if ($_GET['task'] <> null) {
                    $this->taskid = get_task_id_from_name(sanitize_text_field($_GET['task']));
                }
            } elseif (isset($_GET['task-id'])) {
                $this->taskid = intval($_GET['task-id']);
            } else {
                $this->taskid  = null;
            };
            $this->rectaskid = (isset($_GET['recurring-task-id']) ? intval($_GET['recurring-task-id']) : null);
            if (isset($_GET['project'])) {
                if ($_GET['project'] <> null) {
                    $this->projectid = get_project_id_from_name(sanitize_text_field($_GET['project']));
                }
            } elseif (isset($_GET['project-id'])) {
                $this->projectid = intval($_GET['project-id']);
            } else {
                $this->projectid = null;
            }
            if (isset($_GET['client'])) {
                if ($_GET['client'] <> null) {
                    $this->clientid = get_client_id_from_name(sanitize_text_field($_GET['client']));
                }
            } elseif (isset($_GET['client-id'])) {
                $this->clientid = intval($_GET['client-id']);
            } else {
                $this->clientid  = null;
            };
            $this->notes = (isset($_GET['notes']) ? sanitize_text_field($_GET['notes']) : null);
            $this->startdate = (isset($_GET['start']) ? sanitize_text_field($_GET['start']) : null);
            $this->enddate = (isset($_GET['end']) ? sanitize_text_field($_GET['end']) : null);
        }


        /**
         * Get task list for a parent item
         * 
         */
        public function get_task_list_for_parent_item($tbl_name, $parent_record) {
            $this->assoc_field = $tbl_name . "." . sanitize_text_field(array_key_first($parent_record));
            $this->assoc_id = intval($parent_record[$this->assoc_field]);
            return $this->get_all_tasks_from_db();
        }


        /**
         * Get result
         * 
         */
        public function create_table($type = "", $associated_field = "", $associated_id=0) {
            if ($associated_field <> "") {
                $this->assoc_field = sanitize_text_field($associated_field);
                $this->assoc_id = intval($associated_id);
            }
            return $this->get_html($type);
        }


        /**
         * Get table column order and table fields
         * 
         */
        private function get_table_fields() {
            $cols = [
                "ID" => [
                    "fieldname" => "TaskID",
                    "id" => "task-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Client" => [
                    "fieldname" => "Company",
                    "id" => "client",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Project ID" => [
                    "fieldname" => "ProjectID",
                    "id" => "project-id",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Project" => [
                    "fieldname" => "PName",
                    "id" => "project-name",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Type" => [
                    "fieldname" => "TCategory",
                    "id" => "task-type",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Task" => [
                    "fieldname" =>"TDescription",
                    "id" => "task-description",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Due Date" => [
                    "fieldname" => "TDueDate",
                    "id" => "due-date",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Status" => [
                    "fieldname" => "TStatus",
                    "id" => "task-status",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Date Added" => [
                    "fieldname" => "TDateAdded",
                    "id" => "date-added",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Time Logged v Estimate" => [
                    "fieldname" => "TimeLoggedVsEstimate",
                    "id" => "time-worked",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => "tt-align-right"
                ],
                "Notes" => [
                    "fieldname" => "TNotes",
                    "id" => "task-notes",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ]
            ];
            return $cols;
        }
        
        
        /**
         * Query db for OPEN tasks
         * 
         */
        private function get_open_tasks_from_db() {
            $this->status_search = "OPEN";
            return $this->get_all_tasks_from_db();
            
            /**global $wpdb;

            $sql_string = "SELECT tt_task.*, tt_client.Company, tt_project.ProjectID, tt_project.PName,
                    NewTable.Minutes as LoggedMinutes, NewTable.Hours as LoggedHours
                FROM tt_task 
                LEFT JOIN tt_client
                    ON tt_task.ClientID = tt_client.ClientID
                LEFT JOIN tt_project
                    ON tt_task.ProjectID = tt_project.ProjectID
                LEFT JOIN (SELECT TaskID, SUM(Minute(TIMEDIFF(EndTime, StartTime))) as Minutes, SUM(Hour(TIMEDIFF(EndTime, StartTime))) as Hours FROM tt_time GROUP BY TaskID) NewTable
                    ON tt_task.TaskID = NewTable.TaskID
                WHERE tt_task.TStatus LIKE \"%Closed%\" AND tt_task.TStatus LIKE \"%Canceled%\" AND tt_task.TStatus LIKE \"%Complete%\" AND tt_task.TStatus NOT LIKE \"%Incomplete%\"";
            $sql_string .= str_replace("WHERE", "AND", $this->get_where_clauses());            
            $sql_string .= " ORDER BY tt_task.TDueDate ASC, tt_task.TDateAdded ASC";			
            
			$sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);            
            return $sql_result;**/
        }


        /**
         * Query db for ALL tasks
         * 
         */
        private function get_all_tasks_from_db() {
            global $wpdb;

            $sql_string = "SELECT tt_task.*, tt_client.Company, tt_project.ProjectID, tt_project.PName,
                    NewTable.Minutes as LoggedMinutes, NewTable.Hours as LoggedHours
                FROM tt_task 
                LEFT JOIN tt_client
                    ON tt_task.ClientID = tt_client.ClientID
                LEFT JOIN tt_project
                    ON tt_task.ProjectID = tt_project.ProjectID
                LEFT JOIN (SELECT TaskID, SUM(Minute(TIMEDIFF(EndTime, StartTime))) as Minutes, SUM(Hour(TIMEDIFF(EndTime, StartTime))) as Hours FROM tt_time GROUP BY TaskID) NewTable
                    ON tt_task.TaskID = NewTable.TaskID";
            $sql_string .= $this->get_where_clauses();
            $sql_string .= $this->get_order_by();
            //$sql_string .= " ORDER BY tt_task.TaskID DESC";    
			$record_numbers = get_record_numbers_for_pagination_sql_query();	
			$subset_for_pagination = "LIMIT " . $record_numbers['limit'] . " OFFSET " . $record_numbers['offset'];
			$sql_string .= " " . $subset_for_pagination;
			
            $sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        }


        /**
         * Get order clauses depending on type of search
         * 
         */
        private function get_order_by() {
            if ($this->status_search == "OPEN") {
                $order_by = " ORDER BY tt_task.TDueDate ASC, tt_task.TDateAdded ASC";
            } else {
                $order_by = " ORDER BY tt_task.TaskID DESC";
            }
            return $order_by;
        }


        /**
         * Get where clauses depending on input
         * 
         */
        private function get_where_clauses() {
            global $wpdb;
            $where_clauses = array();
            $where_clause = "";
            if ($this->status_search == "OPEN") {
                foreach ($this->closed_status as $status_name) {
                    array_push($where_clauses, "UCASE(tt_task.TStatus) NOT LIKE '%" . $status_name . "%'");
                }
            }
            if (($this->assoc_id > 0) and ($this->assoc_field <>"")) {
                array_push($where_clauses, $this->assoc_field . "=" . $this->assoc_id);
            }
            if ($this->clientid <> null) {
                array_push($where_clauses, "tt_task.ClientID=" . $this->clientid);
            }
            if ($this->projectid <> null) {
                array_push($where_clauses, "tt_task.ProjectID=" . $this->projectid);
            }
            if ($this->rectaskid <> null) {
                array_push($where_clauses, "tt_task.RecurringTaskID=" . $this->rectaskid);
            }
            if ($this->taskid <> null) {
                array_push($where_clauses, "tt_task.TaskID=" . $this->taskid);
            }
            if ( ($this->startdate <> "") and ($this->startdate <> null) ) {
                array_push($where_clauses, "tt_task.StartTime >= '" . $this->startdate . "'");
            }
            if ( ($this->enddate <> "") and ($this->enddate <> null) ) {
                array_push($where_clauses, "tt_task.StartTime <= '" . $this->enddate . "'");
            }
            if ( ($this->notes <> "") and ($this->notes <> null) ) {
                //Ref: https://developer.wordpress.org/reference/classes/wpdb/esc_like/
                $wild = "%";
                $search_like = "'" . $wild . $wpdb->esc_like( $this->notes ) . $wild . "'";
                array_push($where_clauses, "tt_task.TNotes LIKE " . $search_like);
            }
            if ( (count($where_clauses) > 1) or ((count($where_clauses) == 1) and ($where_clauses[0] <> "")) ) {
                $where_clause = " WHERE ";
                $where_clause .= implode(" AND ", $where_clauses);
            }
            return $where_clause;
        }


        /**
         * Get Data from Table and Append with Any Extra Info
         * 
         */
        private function get_all_data_for_display($type) {
            if ($type == "open_tasks") {
                $tasks = $this->get_open_tasks_from_db();
            } else {
                $tasks = $this->get_all_tasks_from_db();
            }

            foreach ($tasks as $item) {
                $duedate = sanitize_text_field($item->TDueDate);
                $taskstatus = sanitize_text_field($item->TStatus);
                $taskid = sanitize_text_field($item->TaskID);

                $start_work_button = "<button onclick='start_timer_for_task(\"" . esc_attr(sanitize_text_field($item->Company)) . "\", \"" . esc_attr($taskid . "-" . sanitize_text_field($item->TDescription)) . "\")' id=\"start-task-" . esc_attr($taskid)  . "\" class=\"start-work-timer tt-table-button\">Start</button>";
                $task_details_button = "<button onclick='open_detail_for_task(\"" . esc_attr($taskid) . "\")' id=\"view-task-" . esc_attr($taskid)  . "\" class=\"open-task-detail tt-table-button\">View</button>";
                $delete_task_button = "<button onclick='location.href = \"" . TT_HOME . "delete-item/?task-id=" . esc_attr($taskid) . "\"' id=\"delete-task-" . esc_attr($taskid)  . "'\" class=\"open-delete-page tt-button tt-table-button\">Delete</button>";
                $item->TaskID = [
                    "value" => $taskid,
                    "button" => [
                        $start_work_button,
                        $task_details_button,
                        $delete_task_button
                    ]
                ];

                if ( (sanitize_text_field($item->RecurringTaskID) != null) and (sanitize_text_field($item->RecurringTaskID) != "") ) {
                    $icon = tt_add_recurring_task_icon();
                    $task_category = $item->TCategory;
                    $item->TCategory = [
                        "value" => $task_category,
                        "icon" => $icon
                    ];                    
                }

                $due_date_class = get_due_date_class($duedate, $taskstatus);
                $item->TDueDate = [
                    "value" => $duedate,
                    "class" => $due_date_class
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
            return $tasks;
        }


        /**
         * Create Table
         * 
         */
        private function get_html($type) {            
            $fields = $this->get_table_fields();
            $tasks = $this->get_all_data_for_display($type);                
            $args["class"] = ["tt-table", "task-list-table"];
            $tbl = new Time_Tracker_Display_Table();
            $table = $tbl->create_html_table($fields, $tasks, $args, "tt_task", "TaskID");
            return $table;
        }
        
    } //close class

} //close if class exists