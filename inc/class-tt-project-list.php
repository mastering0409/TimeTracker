<?php
/**
 * Class Project_List
 *
 * Get projects from db and create table to display on front end
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
if ( !class_exists( 'Project_List' ) ) {


    /**
     * Class
     * 
     */
    class Project_List
    {
        private $clientid;
        private $notes;
        private $projectid;
        private $startdate;
        private $enddate;


        /**
         * Class Variables
         * 
         */
        private $status_order = ["New", "Ongoing", "In Process", "Waiting Client", "Complete", "Canceled"];


        /**
         * Constructor
         * 
         */
        public function __construct() {
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
         * Create HTML table for front end display - with all statuses combined
         * 
         */
        public function get_table_of_all_projects() {
            return $this->get_complete_table_in_html();
        }
        
        
        /**
         * Create HTML table for front end display
         * 
         */
        public function create_table($pstatus) {
            $fields = $this->get_table_fields();
            $projects = $this->get_all_data_for_display($pstatus);
            $args["class"] = ["tt-table", "project-list-table"];
            $tbl = new Time_Tracker_Display_Table();
            $table = $tbl->create_html_table($fields, $projects, $args, "tt_project", "ProjectID");
            return $table;
        }


        /**
         * Combine All Project Status Tables for One Page
         * 
         */
        public function get_page_html_with_each_status_in_different_table() {
            $html = "";
            foreach ($this->status_order as $pstatus) {
                $html .= "<h3>" . $pstatus . " Projects</h3>";
                $html .= $this->create_table($pstatus);
            }
            return $html;   
        }


        /**
         * Create HTML table with ALL STATUSES COMBINED
         * 
         */
        public function get_complete_table_in_html() {
            $fields = $this->get_table_fields();
            $projects = $this->get_all_data_for_display();
            $args["class"] = ["tt-table", "project-list-table"];
            $tbl = new Time_Tracker_Display_Table();
            $table = $tbl->create_html_table($fields, $projects, $args, "tt_project", "ProjectID");
            return $table;
        }


        /**
         * Get details from db
         * 
         */
        private function get_projects_from_db($pstatus=null) {
            global $wpdb;
            $sql_string = "SELECT tt_project.*, tt_client.Company, 
                    NewTable.Minutes as LoggedMinutes,
                    NewTable.Hours as LoggedHours,
                    NewTable.LastWorked as LastEntry
                FROM tt_project 
                LEFT JOIN tt_client
                    ON tt_project.ClientID = tt_client.ClientID
                LEFT JOIN (SELECT tt_task.ProjectID, SUM(Minute(TIMEDIFF(EndTime, StartTime))) as Minutes,
                        SUM(Hour(TIMEDIFF(EndTime, StartTime))) as Hours,
                        MAX(StartTime) as LastWorked 
                    FROM tt_time LEFT JOIN tt_task ON tt_time.TaskID = tt_task.TaskID GROUP BY ProjectID) NewTable
                    ON tt_project.ProjectID = NewTable.ProjectID";
            $sql_string .= $this->get_where_clauses($pstatus);
            $sql_string .= " ORDER BY tt_project.ProjectID DESC";
            $sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $sql_result;
        }


        /**
         * Get where clauses depending on input
         * 
         */
        private function get_where_clauses($pstatus = null) {
            global $wpdb;
            $where_clauses = array();
            $where_clause = "";
            if ($this->clientid <> null) {
                array_push($where_clauses, "tt_project.ClientID = " . $this->clientid);
            }
            if ($this->projectid <> null) {
                array_push($where_clauses, "tt_project.ProjectID = " . $this->projectid);
            }
            if ($pstatus <> null) {
                array_push($where_clauses, "tt_project.PStatus = '" . $pstatus . "'");
            }            
            if ( ($this->startdate <> "") and ($this->startdate <> null) ) {
                array_push($where_clauses, "tt_project.PDateStarted >= '" . $this->startdate . "'");
            }
            if ( ($this->enddate <> "") and ($this->enddate <> null) ) {
                array_push($where_clauses, "tt_project.PDueDate <= '" . $this->enddate . "'");
            }
            if ( ($this->notes <> "") and ($this->notes <> null) ) {
                //Ref: https://developer.wordpress.org/reference/classes/wpdb/esc_like/
                $wild = "%";
                $search_like = "'" . $wild . $wpdb->esc_like( $this->notes ) . $wild . "'";
                array_push($where_clauses, "tt_project.PDetails LIKE " . $search_like);
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
                    "fieldname" => "ProjectID",
                    "id" => "recurring-task-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Project" => [
                    "fieldname" => "PName",
                    "id" => "company-name",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Client" => [
                    "fieldname" => "ClientID",
                    "id" => "project-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Category" => [
                    "fieldname" => "PCategory",
                    "id" => "project-name",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Status" => [
                    "fieldname" => "PStatus",
                    "id" => "task-category",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Date Added" => [
                    "fieldname" =>"PDateStarted",
                    "id" => "recurring-task-name",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Last Worked" => [
                    "fieldname" => "LastEntry",
                    "id" => "frequency",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Due Date" => [
                    "fieldname" => "PDueDate",
                    "id" => "last-created",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Notes" => [
                    "fieldname" => "PDetails",
                    "id" => "end-repeat",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Time Logged vs Estimate" => [
                    "fieldname" => "TimeLoggedVsEstimate",
                    "id" => "recurring-task-description",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ]
            ];
            return $cols;
        }


        /**
         * Get Due Date Class
         * 
         */
        private function get_due_date_class($duedate, $projstatus) {
            //evaluate due date and current status, apply class based on result
            $due_date_formatted = tt_format_date_for_display($duedate, "date_only");
            $due_date_object = \DateTime::createFromFormat("Y-m-d", $duedate);
            
            if ($due_date_formatted = "") {
                $due_date_class = "no-date";
            } elseif ($due_date_object <= new \DateTime() AND $projstatus<>"Canceled" AND $projstatus<>"Complete") {
                $due_date_class = "late-date";
            } elseif ($due_date_object <= new \DateTime(date("Y-m-d", strtotime("+7 days"))) AND $projstatus<>"Canceled" AND $projstatus<>"Complete") {
                $due_date_class = "soon-date";
            } elseif ($due_date_object > new \DateTime(date("Y-m-d", strtotime("+90 days"))) AND $projstatus<>"Canceled" AND $projstatus<>"Complete") {
                $due_date_class = "on-hold-date";
            } else {
                $due_date_class = "ok-date";
            }
            return $due_date_class;
        }
     

        /**
         * Iterate through data and add additional information for table
         * 
        **/
        private function get_all_data_for_display($pstatus=null) {
            $projects = $this->get_projects_from_db($pstatus);
            //add database data with time evaluations, classes, buttons, etc to forward on to table
            foreach ($projects as $item) {
                $duedate = sanitize_text_field($item->PDueDate);
                $projstatus = sanitize_text_field($item->PStatus);

                $project_details_button = "<button onclick='open_time_entries_for_project(\"" . esc_attr(sanitize_textarea_field($item->PName)) . "\")' id=\"project-" . esc_attr(sanitize_text_field($item->ProjectID))  . "\" class=\"open-project-detail tt-table-button\">View Time</button>";
                $delete_project_button = "<button onclick='location.href = \"" . TT_HOME . "delete-item/?project-id=" . esc_attr(sanitize_text_field($item->ProjectID)) . "\"' id=\"delete-project-" . esc_attr(sanitize_text_field($item->ProjectID))  . "'\" class=\"open-delete-page tt-button tt-table-button\">Delete</button>";
                $item->ProjectID = [
                    "value" => $item->ProjectID,
                    "button" => [
                        $project_details_button,
                        $delete_project_button
                    ]
                ];

                $due_date_class = $this->get_due_date_class($duedate, $projstatus);
                $item->PDueDate = [
                    "value" => $item->PDueDate,
                    "class" => $due_date_class
                ];

                $time_estimate_formatted = get_time_estimate_formatted(sanitize_text_field($item->PTimeEstimate));
                $hours_logged = tt_convert_to_decimal_time(sanitize_text_field($item->LoggedHours), sanitize_text_field($item->LoggedMinutes));
                $percent_time_logged = get_percent_time_logged($time_estimate_formatted, $hours_logged);
                $time_worked_vs_estimate_class = get_time_estimate_class($percent_time_logged);
                $item->TimeLoggedVsEstimate = [
                    "value" => $hours_logged . " / " . $time_estimate_formatted . "<br/>" . $percent_time_logged . "%",
                    "class" => $time_worked_vs_estimate_class
                ];
            }
            return $projects;
        }

    } //close class

} //close if class exists