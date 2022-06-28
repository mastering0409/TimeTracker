<?php
/**
 * Class Save_Form_Input
 *
 * Save form input into db
 * 
 * 8/14/20 update - CF7(ver5.2.1) now returning select fields in an array
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Save_Form_Input' ) ) {
    


    /**
     * Class
     * 
     */ 
    class Save_Form_Input
    {
        
        
        /**
         * Class Variables
         * 
         */ 
        private $data;
        private $form_post_id;
        private $result;
        private $client_id;
        private $project_id;
        private $task_id;
        //private $tt_db;
                
        
        /**
         * Constructor
         * 
         */ 
        public function __construct($raw_data, $id) {
            //removed $form added insertid
            $this->form_post_id = $id;
            $data = $this->clean_data($raw_data);
            $this->original_submission = $this->serialize_data($data);
            $this->client_id = $this->get_client_id($data);
            $this->project_id = $this->get_project_id($data);
            $this->task_id = $this->get_task_id($data);		
            
			
			/**
             * Add new task
             * 
             */
            if ( $this->form_post_id == tt_get_form_id('Add New Task') ) {
                $this->save_new_task($data,$this->client_id,$this->project_id,$this->task_id,$this->original_submission);
            }

            
            /**
             * Add new project
             * 
             */
            if ( $this->form_post_id == tt_get_form_id('Add New Project') ) {
                $this->save_new_project($data,$this->client_id,$this->original_submission);           
            }


            /**
             * Add new client
             * 
             */
            if ( $this->form_post_id == tt_get_form_id('Add New Client') ) {
                $this->save_new_client($data,$this->original_submission);
            }


            /**
             * Add new recurring task
             * 
             */
            if ( $this->form_post_id == tt_get_form_id('Add New Recurring Task') ) {
                $this->save_new_recurring_task($data,$this->client_id,$this->project_id,$this->original_submission);
            }


            /**
             * Add new time entry
             * 
             */
            if ( $this->form_post_id == tt_get_form_id('Add Time Entry') ) {
                $this->save_new_time_entry($data);
                
                //if the user entered a new task status update it in the task table
                if ( ($data['new-task-status'] <> null) and ($data['new-task-status'] <> '') and ($data['new-task-status'] <> '---')) {
                    $this->update_task_status($data);            
                }

                //if the user entered notes for follow up, create a new task
                if ( ($data['follow-up'] <> null) and ($data['follow-up'] <> '') ) {
                    $this->create_follow_up_task($data);            
                }

            } 
        }
     
        
        /**
         * Sanitize data
         * 
         * 
         * 
         */
        private function clean_data($raw_data) {
            $clean_data = array();
            foreach ($raw_data as $key => $data) {
                if (is_array($data)) {
                    //$clean_data[$key] = filter_var(htmlspecialchars_decode($data[0], ENT_NOQUOTES), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    //$raw = $data[0];
                    //$clean_data[$key] = htmlspecialchars_decode(filter_var($raw, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), ENT_NO_QUOTES);
                    $clean_data[$key] = filter_var($data[0], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    //var_dump(htmlspecialchars_decode('Weekly', ENT_NO_QUOTES, ENT_SUBSTITUTE));  //this is outputting null! do we need htmlspecialchars_decode??
                    //var_dump($clean_data[$key]);
                } else {
                    //$clean_data[$key] = filter_var(htmlspecialchars_decode($data, ENT_NOQUOTES), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    //$clean_data[$key] = htmlspecialchars_decode(filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), ENT_NOQUOTES);
                    $clean_data[$key] = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                }
            }
            return $clean_data;
        }
        
        
        /**
         * Serialize data to store in db with record so we have record of original entry
         * 
         */
        private function serialize_data($data) {
            //serialize for storing in database and lookup ID's for variables
            $this->original_submission = serialize($data);
            return $this->original_submission;
        }


        /**
         * Save new task into db
         * 
         */
        private function save_new_task($data) {
            global $wpdb;
            $table_name = 'tt_task';

            //Add New Record to Database
            //wpdb class prepares this so it doesn't need to be SQL escaped
            if ( ($data['time-estimate'] == null) or ($data['time-estimate'] == "") ) {
                $time_est = 0;
            } else {
                $time_est = tt_convert_fraction_to_time($data['time-estimate']);
            }
            $wpdb->insert( $table_name, array(
                'TDescription' => $data['task-description'],
                'ClientID'   => $this->client_id,
                'ProjectID'    => $this->project_id,
                'TCategory' => $data['task-category'],
                'TStatus' => "New",
                'TTimeEstimate' => $time_est,
                'TDueDate' => $data['due-date'],
                'TNotes' => $data['notes'],
                'TSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }


        /**
         * Save new recurring task into db
         * 
         */
        private function save_new_recurring_task($data) {
            global $wpdb;
            $table_name = 'tt_recurring_task';

            //Add New Record to Database
            //wpdb class prepares this so it doesn't need to be SQL escaped
            if ( ($data['time-estimate'] == null) or ($data['time-estimate'] == "") ) {
                $time_est = 0;
            } else {
                $time_est = tt_convert_fraction_to_time($data['time-estimate']);
            }
            $wpdb->insert( $table_name, array(
                'RTName' => $data['task-name'],
                'ClientID'   => $this->client_id,
                'ProjectID'    => $this->project_id,
                'RTTimeEstimate' => $time_est,
                'RTDescription' => $data['task-desc'],
                'Frequency' => $data['recur-freq'],
                'EndRepeat' => $data['end-repeat'],
                'RTSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }


        /**
         * Save new project into db
         * 
         */
        private function save_new_project($data) {
            global $wpdb;
            $table_name = 'tt_project';

            //Add New Record to Database
            if ( ($data['time-estimate'] == null) or ($data['time-estimate'] == "") ) {
                $time_est = 0;
            } else {
                $time_est = tt_convert_fraction_to_time($data['time-estimate']);
            }
            $wpdb->insert( $table_name, array(
                'PName' => $data['project-name'],
                'ClientID'   => $this->client_id,
                'PCategory'    => $data['project-category'],
                'PStatus' => "New",
                'PTimeEstimate' => $time_est,
                'PDueDate' => $data['due-date'],
                'PDetails' => $data['project-details'],
                'PSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }


        /**
         * Save new client into db
         * 
         */
        private function save_new_client($data) {
            global $wpdb;
            $table_name = 'tt_client';

            //Add New Record to Database
            $wpdb->insert( $table_name, array(
                'Company'   => $data['company'],
                'Contact'    => $data['contact-name'],
                'Email' => $data['contact-email'],
                'Phone' => $data['contact-telephone'],
                'BillTo' => $data['bill-to'],
                'Source' => $data['client-source'],
                'SourceDetails' => $data['client-source-details'],
                'CComments' => $data['comments'],
                'CSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }

        
        /**
         * Save new time entry into db
         * 
         */
        private function save_new_time_entry($data) {
            global $wpdb;
            $table_name = 'tt_time';

            //Convert Start and End Times to Date Formats (from text)
            $start = \DateTime::createFromFormat('n/j/y g:i A', $data['start-time'])->format('Y-m-d H:i:ss');
            $end = \DateTime::createFromFormat('n/j/y g:i A', $data['end-time'])->format('Y-m-d H:i:ss');

            //Add New Record to Database
            $wpdb->insert( $table_name, array(
                'StartTime' => $start,
                'EndTime'   => $end,
                'TNotes'    => $data['time-notes'],
                'ClientID' => $this->client_id,
                'TaskID' => $this->task_id,
                'Invoiced' => $data['invoiced'],
                'InvoiceNumber' => $data['invoice-number'],
                'InvoicedTime' => $data['invoiced-time'],
                'InvoiceComments' => $data['invoice-notes'],
                'FollowUp' => $data['follow-up'],
                'NewTaskStatus' => $data['new-task-status'],
                'TimeSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }


        /**
         * Update task status in db
         * 
         */
        private function update_task_status($data) {
            //flag task as complete if user checks complete box in time entry page
            global $wpdb;
            $new_task_status = $data['new-task-status'];
            $update_task_status_string = 'UPDATE tt_task SET TStatus ="' . $new_task_status . '" WHERE TaskID="' . $this->task_id . '"';
            $update_task_status_result = $wpdb->get_results($update_task_status_string);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }


        /**
         * Update task status in db
         * 
         */
        private function create_follow_up_task($data) {
            global $wpdb;
            $start = date('Y-m-d H:i', strtotime($data['start-time']));
            $end = date('Y-m-d H:i', strtotime($data['end-time']));
            $follow_up_task_notes = "Created as a follow up to task id " . $this->task_id . " work completed between " . $start . " and " . $end;
            $table_name = 'tt_task';

            //Add New Record to Database
            //wpdb class prepares this so it doesn't need to be SQL escaped
            $wpdb->insert( $table_name, array(
                'TDescription' => $data['follow-up'],
                'ClientID'   => $this->client_id,
                'TStatus' => "New",
                'TNotes' => $follow_up_task_notes,
                'TDueDate' => date('Y-m-d'),
                'TTimeEstimate' => 0,
                'TSubmission' => $this->original_submission
            ) );
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
        }
        
        
        /**
         * Get client id from name
         * 
         */
        private function get_client_id($data) {
            if ( !array_key_exists('client-name', $data) or $data['client-name'] == '' or $data['client-name'] == null) {
                $this->client_id = null;
            } else {
                $this->client_id = get_client_id_from_name($data['client-name']);
            }
            return $this->client_id;        
        }


        /**
         * Get project id from name
         * 
         */        
        private function get_project_id($data) {
            //Project field in table requires a valid Project ID or null value, won't except empty string
            if (!array_key_exists('project-name', $data) or $data['project-name'] == '' or $data['project-name'] == null) {
                $this->project_id = null;
            } else {
                $project = $data['project-name'];
                $this->project_id = get_project_id_from_name($project);
            }
            return $this->project_id;
        }


        /**
         * Get task id from name
         * 
         */
        private function get_task_id($data) {
            //Task field in table requires a valid Task ID or null value, won't except empty string
            if (!array_key_exists('task-name', $data) or $data['task-name'] == '' or $data['task-name'] == null) {
                $this->task_id = null;
            } else {
                $task = $data['task-name'];
                $task_number_from_string = substr($task,0,strpos($task,'-'));
                $this->task_id = $task_number_from_string;
            }
            return $this->task_id;
        }


        /**
         * Get result
         * 
         */
        public function get_result() {
            return $this->result;
        }


    } //close class

} //close if not exists