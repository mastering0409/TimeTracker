<?php
/**
 * Class Time_Tracker_Activator_Forms
 *
 * Initial activation of Time Tracker Plugin - CREATE FRONT END FORMS
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Check if class exists
 * 
 */
if ( ! class_exists('Time_Tracker_Activator_Forms') ) {

    class Time_Tracker_Activator_Forms {

        public static $form_details = array();
        public static $form_content = array();
        public static $mail_meta = array();
        public static $mail_2_meta = array();
        public static $msg_meta = array();
		public static $additional_settings = "";
        
        /**
         * Constructor
         * 
         */
        public function __construct() {
            //$form_details = self::create_form_details_array();
            //self::create_forms();
        }


        /**
         * Setup
         * 
         */
        public static function setup() {
            self::create_form_details_array();
            self::create_form_content_array();
            self::get_mail_meta();
            self::get_mail_2_meta();
            self::get_msg_meta();
			self::get_additional_settings();
            self::create_forms('false');
        }


        /**
         * Update Version
         * 
         */ 
        public static function check_forms_for_updates() {
            self::create_forms('true');
        }


        /**
         * Update Version
         * 
         */ 
        public static function force_form_updates() {
            self::create_forms('true');
        }
        
        
        /**
         * Check form exists and matches current version
         * 
         */
        private static function check_form_is_up_to_date($i, $form_post_id, $force_update) {
                $installed_form = get_post($form_post_id);
                $installed_form_content = $installed_form->post_content;
                $updated_content = self::$form_content[$i];
                //does the content match the current version}              
                if (($installed_form_content != $updated_content) || ($force_update == true)) {
                    $updated_form = array(
                        'ID' => $form_post_id,
                        'post_content' => $updated_content
                    );
                    $result = wp_update_post($updated_form);
                    $result_meta = update_post_meta($form_post_id, '_form', $updated_content);
                }                
            return $form_post_id;
        }


        /**
         * Create all forms in array
         * 
         */
        public static function create_forms($force_update) {
            $i = 0;
            $number_forms = count(self::$form_details);
            for ($i==0; $i<$number_forms; $i++) {
                $form_arr = self::get_form_details($i);
                
                //check if form exists already
                $form_exists = get_posts(array(
                    'title'=> $form_arr['post_title'],
                    'post_type' => 'wpcf7_contact_form'
                ), ARRAY_A);
                //if form does not exist, create it

                if (empty($form_exists)) {
                    $post_id = wp_insert_post($form_arr);
                    if ($post_id) {
                        add_post_meta($post_id, '_form', self::$form_content[$i]);
                        add_post_meta($post_id, '_mail', self::$mail_meta);
                        add_post_meta($post_id, '_mail_2', self::$mail_2_meta);
                        add_post_meta($post_id, '_messages', self::$msg_meta);
                        add_post_meta($post_id, '_additional_settings', self::$additional_settings);                    
                        add_post_meta($post_id, '_locale', self::get_user_location() );
                    }
                
                //if form does exist, confirm it is up to date with current version
                } else {
                    self::check_form_is_up_to_date($i, $form_exists[0]->ID, $force_update);
                }
            }
        }


        /**
         * post locale information for post meta
         * 
         */
        public static function get_user_location() {
            $users_location = "";
            $users_location = get_user_locale();
            if ($users_location) {
                return $users_location;
            } else {
                return "en_US";
            }
        }
        
        
        /**
         * Define arguments for creating form (CF7 post type)
         * 
         */
        public static function get_form_details($arr_index) {
            $arr = array(
                'post_author'           => '',
                'post_content'          => self::$form_details[$arr_index]['Content'],
                'post_content_filtered' => '',
                'post_title'            => self::$form_details[$arr_index]['Title'],
                'post_name'             => self::$form_details[$arr_index]['Slug'],
                'post_excerpt'          => '',
                'post_status'           => 'publish',
                'post_type'             => 'wpcf7_contact_form',
                'page_template'         => '',
                'comment_status'        => 'closed',
                'ping_status'           => 'closed',
                'post_password'         => '',
                'to_ping'               => '',
                'pinged'                => '',
                'post_parent'           => '',
                'menu_order'            => 0,
                'guid'                  => '',
                'import_id'             => 0,
                'context'               => '',
            );  
            return $arr;          
        }


        /**
         * Create array of properties that are form dependent
         * 
         */
        public static function create_form_details_array() {
            $details = array();
            $all_details = array();
          
            //add new client
            $details = array(
                "Title" => "Add New Client",
                "Slug" => "form-add-new-client",
                "Content" => self::get_form_content_new_client() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);

            //add new project
            $details = array(
                "Title" => "Add New Project",
                "Slug" => "form-add-new-project",
                "Content" => self::get_form_content_new_project() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);

            //add new recurring task
            $details = array(
                "Title" => "Add New Recurring Task",
                "Slug" => "form-add-new-recurring-task",
                "Content" => self::get_form_content_new_recurring_task() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);
  
            //add new task
            $details = array(
                "Title" => "Add New Task",
                "Slug" => "form-add-new-task",
                "Content" => self::get_form_content_new_task() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);

            //add time entry
            $details = array(
                "Title" => "Add Time Entry",
                "Slug" => "form-add-time-entry",
                "Content" => self::get_form_content_add_time_entry() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);

            //filter time
            $details = array(
                "Title" => "Filter Time",
                "Slug" => "form-filter-time",
                "Content" => self::get_form_content_filter_time() . "\r\n" . implode("\r\n", self::$mail_meta) . "\r\n" . implode("\r\n", self::$msg_meta)
            );
            array_push($all_details, $details);

            self::$form_details = $all_details;
            return $all_details;
        }


        /**
         * Create content details array
         * 
         */
        public static function create_form_content_array() {
            $content = array();
            array_push($content, self::get_form_content_new_client());
            array_push($content, self::get_form_content_new_project());
            array_push($content, self::get_form_content_new_recurring_task());
            array_push($content, self::get_form_content_new_task());
            array_push($content, self::get_form_content_add_time_entry());
            array_push($content, self::get_form_content_filter_time());
            self::$form_content = $content;
        }           



        /**
         * Create form content - New Client Form
         * 
         */
        public static function get_form_content_new_client() {
            $html = "<label> Company (required)</label>[text* company maxlength:100]";
            $html .= "<label> Contact Name</label>[text contact-name maxlength:100]";
            $html .= "<label> Contact Email </label>[email contact-email maxlength:100]";
            $html .= "<label> Telephone #</label>[text contact-telephone]";
            $html .= "<label> Bill To (required)</label>[bill_to_name bill-to ie:bill-to-name-dropdown]";
            $html .= "<label> Source (required)</label>[client_category client-source id:client-source-dropdown]";
            $html .= "<label> Source Details</label>[client_sub_category client-source-details id:client-source-details-dropdown]";
            $html .= "<label> Comments</label>[textarea comments maxlength:1000]";
            $html .= "[submit id:add-client-submit \"Submit\"]";
            return $html;
        }


        /**
         * Create form content - New Project
         * 
         */
        public static function get_form_content_new_project() {
            $html = "<label> Project Name (required)</label>[text* project-name maxlength:100]";
            $html .= "<label> Client (required)</label>[client_name client-name]";
            $html .= "<label> Category</label>[work_category project-category id:project-category-dropdown]";
            $html .= "<label>Time Estimate</label>[number time-estimate]";
            $html .= "<label>Due Date (required)</label>[date* due-date]";
            $html .= "<label> Details</label>[textarea project-details maxlength:500]";
            $html .= "[submit id:add-project-submit \"Submit\"]";
            return $html;
        }


        /**
         * Create form content - New Recurring Task
         * 
         */
        public static function get_form_content_new_recurring_task() {
            $html = "<label> Task Name (required)</label>[textarea* task-name 20x1 maxlength:1500]";
            $html .= "<label> Client (required)</label>[client_name client-name]";
            $html .= "<label> Project</label>[project_name project-name id:project-dropdown]";
            $html .= "<label> Category</label>[work_category task-category id:task-category-dropdown]";
            $html .= "<label> Time Estimate (required)</label>[text* time-estimate]";
            $html .= "Recurring Frequency (required)[select* recur-freq use_label_element \"Monthly\" \"Weekly\"]";
            $html .= "<label> Task Notes</label>[textarea task-desc]";
            $html .= "<label> End Repeat</label>[date end-repeat]";
            $html .= "[submit id:add-task-submit \"Send\"]";
            return $html;
        }


        /**
         * Create form content - New Task
         * 
         */
        public static function get_form_content_new_task() {
            $html = "<label> Task Description (required)</label>[textarea* task-description 20x1 maxlength:500]";
            $html .= "<label> Client (required)</label>[client_name client-name]";
            $html .= "<label> Project</label>[project_name project-name id:project-dropdown]";
            $html .= "<label> Category</label>[work_category task-category id:task-category-dropdown]";
            $html .= "<label> Time Estimate </label>[text time-estimate]";
            $html .= "<label> Due Date</label>[date due-date \"today\"]";
            $html .= "<label> Notes </label>[textarea notes]";
            $html .= "[hidden what-next default:\"SaveTask\"]<input type=\"submit\" name=\"submit-save\" class=\"tt-button tt-form-button tt-inline-button\" value=\"SaveTask\"><input type=\"submit\" name=\"submit-start\" class=\"tt-button tt-form-button tt-inline-button\" value=\"StartWorking\" onclick=\"save_new_task_and_start_timer()\">";
            return $html;
        }


        /**
         * Create form content - New Time Entry
         * 
         */
        public static function get_form_content_add_time_entry() {
            $html = "<label> Start Time (required)</label>[datetime start-time id:start-time]";
            $html .= "<label> Client (required)</label>[client_name client-name default:get]";
            $html .= "<label> Ticket (required)</label>[task_name task-name default:get id:task-dropdown]";
            $html .= "<label> Notes (required)</label>[textarea* time-notes maxlength:1999]";
            $html .= "<label> New Task Status</label>[select new-task-status id:new-task-status include_blank \"In Process\" \"Not Started\" \"Ongoing\" \"Waiting Client\" \"Complete\" \"Canceled\"]";
            $html .= "<label> End Time (required)</label>[datetime end-time id:end-time]";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-left\"><label> Invoiced?</label> [text invoiced id:invoiced]</div>";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-middle\"><label> Invoice #</label> [text invoice-number id:invoice-number]</div>";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-right\"><label> Invoiced Time</label> [text invoiced-time id:invoiced-time]</div>";
            $html .= "<label> Invoice Notes</label> [text invoice-notes id:invoice-notes]";
            $html .= "<label> Follow Up (Create New Task)</label>[text follow-up maxlength:500]";
            $html .= "[submit id:add-time-submit \"Send\"]";
            return $html;
        }


        /**
         * Create form content - Time Entry Filter
         * 
         */
        public static function get_form_content_filter_time() {
            $html = "<div class=\"tt-form-row\">";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-left\"><label> First Date</label>[date first-date id:first-date default:get]</div>";
            $html .= "<div class=\"tt-form-element tt-two-thirds tt-col-right\"><label> Client</label>[client_name client-name id:client-name default:get]</div>";
            $html .= "</div><div class=\"tt-form-row\">";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-left\"><label> Last Date</label>[date last-date id:last-date default:get]</div>";
            $html .= "<div class=\"tt-form-element tt-two-thirds tt-col-right\"><label> Project</label>[project_name project-name id:project-name default:get]</div>";
            $html .= "</div><div class=\"tt-form-row\">";
            $html .= "<div class=\"tt-form-element tt-one-third tt-col-left\"><label> Ticket</label>[task_name task-name id:task-name default:get]</div>";
            $html .= "<div class=\"tt-form-element tt-two-thirds tt-col-right\"><label> Notes </label>[text notes id:time-notes default:get]</div>";
            $html .= "</div><div class=\"tt-form-row\">";
            $html .= "[hidden form-type default:\"filter\"][submit id:filter-time-submit \"Filter Time Entries\"]";
            $html .= "</div></div>";
            return $html;
        }


        /**
         * Get Additional Settings
         * 
         */
        public static function get_additional_settings() {
            $settings = "skip_mail: on";  
            self::$additional_settings = $settings;
        }
		
		
		/**
         * Get Mail Meta
         * 
         */
        public static function get_mail_meta() {
            $body = "";
            $body = "From: [your-name] <[your-email]>\r\n";
            $body .= "Subject: [your-subject]\r\n";
            $body .= "\r\n";
            $body .= "Message Body:\r\n";
            $body .= "[your-message]\r\n";
            $body .= "\r\n";
            $body .= "-- \r\n";
            $body .= "This e-mail was sent from a contact form on " . tt_get_site_name() . " (" . tt_get_site_url() . ")";            
            
            $mail = array();
            $mail["active"] = true;
            $mail["subject"] = tt_get_site_name() . " \"[your-subject]\"";
            $mail["sender"] = tt_get_site_name() . " <" . tt_get_wordpress_email() . ">";
            $mail["recipient"] = tt_get_site_admin_email();
            $mail["body"] = $body;
            $mail["additional headers"] = "Reply-To: " . tt_get_site_admin_email() . "\r\n";
            $mail["attachments"] = "\r\n";
            $mail["use_html"] = false;
            $mail["exclude_blank"] = false;
            self::$mail_meta = $mail;
        }


        /**
         * Get Mail 2 Meta
         * 
         */
        public static function get_mail_2_meta() {
            $mail2 = array();
            $mail2 = self::$mail_meta;
            $mail2["active"] = false;
            self::$mail_2_meta = $mail2;
        }


        /**
         * Get Message Meta
         * 
         */
        public static function get_msg_meta() {
            $msg = array();
            $msg["mail_sent_ok"] = "Form submitted successfully.";
            $msg["mail_sent_ng"] = "There was an error submitting this form. Please try again later.";
            $msg["validation_error"] = "One or more fields have an error. Please check and try again.";
            $msg["spam"] = "There was an error trying to send your message. Please try again later.";
            $msg["accept_terms"] = "You must accept the terms and conditions before sending your message.";
            $msg["invalid_required"] = "Please verify all required fields have been filled in.";
            $msg["invalid_too_long"] = "The field is too long.";
            $msg["invalid_too_short"] = "The field is too short.";
            $msg["invalid_date"] = "The date format is incorrect.";
            $msg["date_too_early"] = "The date is before the earliest one allowed.";
            $msg["date_too_late"] = "The date is after the latest one allowed.";
            $msg["upload_failed"] = "There was an unknown error uploading the file.";
            $msg["upload_file_type_invalid"] = "You are not allowed to upload files of this type.";
            $msg["upload_file_too_large"] = "The file is too big.";
            $msg["upload_failed_php_error"] = "There was an error uploading the file.";
            $msg["invalid_number"] = "The number format is invalid.";
            $msg["number_too_small"] = "The number is smaller than the minimum allowed.";
            $msg["number_too_large"] = "The number is larger than the maximum allowed.";
            $msg["quiz_answer_not_correct"] = "The answer to the quiz is incorrect.";
            $msg["invalid_email"] = "The e-mail address entered is invalid.";
            $msg["invalid_url"] = "The URL is invalid.";
            $msg["invalid_tel"] = "The telephone number is invalid.";   
            self::$msg_meta = $msg;
        }

    }  //close class

}  //close if class exists