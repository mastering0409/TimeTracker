<?php
/**
 * Class Time_Tracker_Shortcode_Delete_Item_Confirmation
 *
 * SHORTCODE TO DISPLAY DETAILS OF ITEM USER SELECTED TO DELETE
 * 
 * @since 2.2.0
 *  
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Delete_Item_Confirmation') ) {

    /**
     * Class
     * 
     */  
    class Time_Tracker_Shortcode_Delete_Item_Confirmation {

        
        
        /**
         * Class variables
         * 
         */
        public $shortcode = 'tt_delete_confirmation_content';
        private $itemtype;
        private $itemid;
        private $confirmation_msg;
        private $details;
        public $display;
        public $related_records_warning;
        public $idfield;
        private $associated_tasks;


        /**
         * Constructor
         * 
         */
        public function __construct() {
            add_shortcode( $this->shortcode, array( $this, 'show_delete_confirmation_content_shortcode' ) );
        }


        /**
         * Callback
         * 
         */
        public function show_delete_confirmation_content_shortcode() {
            $this->get_data();
            $this->get_related_records_warning();
            $this->get_confirmation_message();
            $this->display = $this->confirmation_msg . $this->details;
            return $this->display;
        }


        /**
         * Get data
         * 
         */
        private function get_data() {
            if (isset($_GET['time-id'])) {
                $this->itemtype = "Time";
                $this->itemid = sanitize_text_field($_GET['time-id']); 
                $this->idfield = "TimeID";  
                $display = $this->get_time_details();
            }elseif (isset($_GET['task-id'])) {
                $this->itemtype = "Task";
                $this->itemid = sanitize_text_field($_GET['task-id']);
                $this->idfield = "TaskID";
                $display = $this->get_task_details();
                $display .= $this->get_time_details();
            } elseif (isset($_GET['recurring-task-id'])) {
                $this->itemtype = "Recurring Task";
                $this->itemid = sanitize_text_field($_GET['recurring-task-id']);  
                $this->idfield = "RecurringTaskID"; 
                $display = $this->get_rec_task_details();
                $display .= $this->get_task_details();
                $display .= $this->get_time_details();
            } elseif (isset($_GET['project-id'])) {
                $this->itemtype = "Project";
                $this->itemid = sanitize_text_field($_GET['project-id']);
                $this->idfield = "ProjectID";  
                $display = $this->get_project_details();
                $display .= $this->get_rec_task_details();
                $display .= $this->get_task_details();
                $display .= $this->get_time_details();
            } elseif (isset($_GET['client-id'])) {
                $this->itemtype = "Client";
                $this->itemid = sanitize_text_field($_GET['client-id']); 
                $this->idfield = "ClientID";  
                $display = $this->get_client_details();
                $display .= $this->get_project_details();
                $display .= $this->get_rec_task_details();
                $display .= $this->get_task_details();
                $display .= $this->get_time_details();
            } else {
                $this->itemtype = null;
                $this->itemid = null;
                $display = "Error - Nothing was Chosen To Delete";
            }
            $this->details = $display;
            return;
        }
    

        /**
         * Return results
         * 
         */
        public function get_shortcode() {
            return $this->shortcode;
        }


        /**
         * Get Confirmation Message
         * 
         */
        private function get_confirmation_message() {
            $msg = "<div class='tt-delete-confirm-msg'>
                <div>Are you sure you want to delete " . $this->itemtype . " #" . $this->itemid . "?</div>
                <div id=\"tt-delete-buttons\" class=\"tt-buttons-inline\">
                <button class=\"tt-delete-confirmation-button tt-delete-yes no-border-radius tt-button-inline\"
                onclick=\"deleteRecord('tt_" . str_replace(" ", "_", strtolower($this->itemtype)) . "', '" . $this->idfield . "', " . $this->itemid . ")\">YES</button>
                <button class=\"tt-delete-confirmation-button tt-delete-no no-border-radius tt-button-inline\" 
                onclick=\"delete_record()\">NO</button></div>
                <p>Details of the item are shown below.
                Please note, deleting an item can NOT be undone.</p>";
            $msg .=  $this->related_records_warning . "</div>";
            $msg .= "<div id='tt-delete-confirmation-result'></div>";
            $this->confirmation_msg = $msg;
            return;
        }


        /**
         * Get Related Records Warning
         * 
         */
        private function get_related_records_warning() {
            if ($this->itemtype == "Task") {
                $this->related_records_warning = "<div class='tt-related-records-warning'>
                    All <strong>time entries</strong> associated with this task will ALSO BE DELETED.
                    This can NOT be undone.
                    </div>";
            } elseif ($this->itemtype == "Recurring Task") {
                $this->related_records_warning = "<div class='tt-related-records-warning'>
                    All <strong>individual tasks and time entries</strong> associated with this recurring task will ALSO BE DELETED.
                    This can NOT be undone.
                    </div>";
            } elseif ($this->itemtype == "Project") {
                $this->related_records_warning = "<div class='tt-related-records-warning'>
                    All <strong>tasks, recurring tasks, and time entries</strong> associated with this project will ALSO BE DELETED.
                    This can NOT be undone.
                    </div>";
            } elseif ($this->itemtype == "Client") {
                $this->related_records_warning = "<div class='tt-related-records-warning'>
                    All <strong>tasks, recurring tasks, projects, and time entries</strong> associated with this client will ALSO BE DELETED.
                    This can NOT be undone.
                    </div>";
            }
            return;
        }


        /**
         * Get Display - Time
         * 
         */
        public function get_time_details() {
            $heading = "<h3>";
            if ($this->itemtype <> "Time") {
                $heading .= "Time Logged for ";
            } 
            $heading .= $this->itemtype . " #" .  $this->itemid . "</h3>";
            if (($this->itemtype == "Recurring Task") or ($this->itemtype == "Project")) {
                if ($this->associated_tasks > 0) {
                    $time_details = new Time_Log();
                    $details = $time_details->get_html(); 
                } else {
                    $details = "Nothing to display.";
                }              
            } else {
                $time_details = new Time_Log();
                $details = $time_details->get_html();                 
            }
            return $heading . $details;
        }
        
        
        /**
         * Get Display - Task
         * 
         */
        public function get_task_details() {
            $heading = "<h3>";
            if ($this->itemtype <> "Task") {
                $heading .= "Tasks Associated with ";
            } 
            $heading .= $this->itemtype . " #" .  $this->itemid . "</h3>";
            $task_details = new Task_List();
            $details = $task_details->create_table();
            $this->associated_tasks = substr_count($details, "<tr>") - 1;
            return $heading . $details;
        }


        /**
         * Get Display - Recurring Task
         * 
         */
        public function get_rec_task_details() {
            $heading = "<h3>";
            if ($this->itemtype <> "Recurring Task") {
                $heading .= "Recurring Tasks Associated with ";
            } 
            $heading .= $this->itemtype . " #" .  $this->itemid . "</h3>";
            $rec_task_details = new Recurring_Task_List();
            $details = $rec_task_details->create_table();
            return $heading . $details;
        }


        /**
         * Get Display - Project
         * 
         */
        public function get_project_details() {
            $heading = "<h3>";
            if ($this->itemtype <> "Project") {
                $heading .= "Projects Associated with ";
            } 
            $heading .= $this->itemtype . " #" .  $this->itemid . "</h3>";
            $project_details = new Project_List();
            $details = $project_details->get_complete_table_in_html();
            return $heading . $details;
        }


        /**
         * Get Display - Client
         * 
         */
        public function get_client_details() {
            $heading = "<h3>";
            if ($this->itemtype <> "Client") {
                $heading .= "Client Associated with ";
            } 
            $heading .= $this->itemtype . " #" .  $this->itemid . "</h3>";
            $client_details = new Client_List();
            $details = $client_details->get_html();
            return $heading . $details;
        }

    } //class
} //if class exists

$tt_Shortcode_Delete_Item = new Time_Tracker_Shortcode_Delete_Item_Confirmation();