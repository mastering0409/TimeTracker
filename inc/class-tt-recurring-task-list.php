<?php
/**
 * Class Recurring_Task_List
 *
 * Get and display entire task list
 * 
 * @since 1.1.1
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );


/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Recurring_Task_List' ) ) {


    /**
     * Class
     * 
     */
    class Recurring_Task_List
    {

        private $rectaskid;        
        private $clientid;
        private $taskid;
        private $timeid;
        private $notes;
        private $projectid;
        private $assoc_field;
        private $assoc_id;

        /**
         * Constructor
         * 
         */
        public function __construct() {
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
            $this->get_recurring_tasks_from_db();
        }


        /**
         * Get html result
         * 
         */
        public function create_table($associated_field = "", $associated_id=0) {
            if ($associated_field <> "") {
                $this->assoc_field = sanitize_text_field($associated_field);
                $this->assoc_id = intval($associated_id);
            }
            return $this->get_html();
        }


        /**
         * Get table column order and table fields
         * 
         */
        private function get_table_fields() {
            $cols = [
                "ID" => [
                    "fieldname" => "RecurringTaskID",
                    "id" => "recurring-task-id",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Client" => [
                    "fieldname" => "Company",
                    "id" => "company-name",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Project ID" => [
                    "fieldname" => "ProjectID",
                    "id" => "project-id",
                    "editable" => false,
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
                    "fieldname" => "RTCategory",
                    "id" => "task-category",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Task" => [
                    "fieldname" =>"RTName",
                    "id" => "recurring-task-name",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ],
                "Frequency" => [
                    "fieldname" => "Frequency",
                    "id" => "frequency",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "text",
                    "class" => ""
                ],
                "Last Created" => [
                    "fieldname" => "LastCreated",
                    "id" => "last-created",
                    "editable" => false,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "End Repeat" => [
                    "fieldname" => "EndRepeat",
                    "id" => "end-repeat",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "date",
                    "class" => "tt-align-right"
                ],
                "Notes" => [
                    "fieldname" => "RTDescription",
                    "id" => "recurring-task-description",
                    "editable" => true,
                    "columnwidth" => "",
                    "type" => "long text",
                    "class" => ""
                ]
            ];
            return $cols;
        }
        
        
        /**
         * Query db for recurring tasks
         * 
         */
        private function get_recurring_tasks_from_db() {
            global $wpdb;

            $sql_string = "SELECT tt_recurring_task.*, tt_client.Company, tt_project.ProjectID, tt_project.PName
                FROM tt_recurring_task 
                LEFT JOIN tt_client
                    ON tt_recurring_task.ClientID = tt_client.ClientID
                LEFT JOIN tt_project
                    ON tt_recurring_task.ProjectID = tt_project.ProjectID";
            $sql_string .= $this->get_where_clauses();
            $sql_string .= " ORDER BY tt_recurring_task.RecurringTaskID ASC";
            $sql_result = $wpdb->get_results($sql_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);            
            $this->recurring_tasks = $sql_result;
        }


        /**
         * Get where clauses depending on input
         * 
         */
        private function get_where_clauses() {
            global $wpdb;
            $where_clauses = array();
            $where_clause = "";
            if (($this->assoc_id > 0) and ($this->assoc_field <>"")) {
                array_push($where_clauses, $this->assoc_field . "=" . $this->assoc_id);
            }
            if ($this->clientid <> null) {
                array_push($where_clauses, "tt_recurring_task.ClientID = " . $this->clientid);
            }
            if ($this->projectid <> null) {
                array_push($where_clauses, "tt_recurring_task.ProjectID = " . $this->projectid);
            }
            if ($this->rectaskid <> null) {
                array_push($where_clauses, "tt_recurring_task.RecurringTaskID = " . $this->rectaskid);
            }
            if ( ($this->notes <> "") and ($this->notes <> null) ) {
                //Ref: https://developer.wordpress.org/reference/classes/wpdb/esc_like/
                $wild = "%";
                $search_like = "'" . $wild . $wpdb->esc_like( $this->notes ) . $wild . "'";
                array_push($where_clauses, "tt_recurring_task.RTDescription LIKE " . $search_like);
            }
            if ( (count($where_clauses) > 1) or ((count($where_clauses) == 1) and ($where_clauses[0] <> "")) ) {
                $where_clause = " WHERE ";
                $where_clause .= implode(" AND ", $where_clauses);
            }
            return $where_clause;
        }


        /**
         * Iterate through data and add additional information for table
         * 
        **/
        private function get_all_data_for_display() {
            $rectasks = $this->recurring_tasks;
            foreach ($rectasks as $item) {
                //$rectask_details_button = "<button onclick='open_time_entries_for_recurring_task(\"" . esc_attr(sanitize_textarea_field($item->RecurringTaskID)) . "\")' id=\"recurring-task-" . esc_attr(sanitize_text_field($item->RecurringTaskID))  . "\" class=\"open-recurring-task-detail tt-table-button\">View</button>";
                $delete_rectask_button = "<button onclick='location.href = \"" . TT_HOME . "delete-item/?recurring-task-id=" . esc_attr($item->RecurringTaskID) . "\"' id=\"delete-recurring-task-" . esc_attr($item->RecurringTaskID)  . "'\" class=\"open-delete-page tt-button tt-table-button\">Delete</button>";
                $item->RecurringTaskID = [
                    "value" => $item->RecurringTaskID,
                    "button" => [
                        //$rectask_details_button,
                        $delete_rectask_button
                    ]
                ];
            }
            return $rectasks;
        }


        /**
         * Create HTML table for front end display
         * 
         */
        public function get_html() {
            $fields = $this->get_table_fields();
            $tasks = $this->get_all_data_for_display();
            //$tasks = $this->recurring_tasks;
            $args["class"] = ["tt-table", "task-list-table"];
            $tbl = new Time_Tracker_Display_Table();
            $table = $tbl->create_html_table($fields, $tasks, $args, "tt_recurring_task", "RecurringTaskID");
            return $table;
        }
            
        
    } //close class

} //close if class exists