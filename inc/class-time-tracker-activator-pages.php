<?php
/**
 * Class Time_Tracker_Activator_Pages
 *
 * Initial activation of Time Tracker Plugin - CREATE FRONT END PAGES
 * 
 * @since 1.0
 * 9-20-2021 - modified to allow for updating pages for version updates
 * 9-20-2021 - added timesheet detail page
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * If class doesn't already exist
 * 
 */       
if ( ! class_exists('Time_Tracker_Activator_Pages') ) {

    /**
     * Class
     * 
     */ 
    class Time_Tracker_Activator_Pages {

        private static $page_details = array();

        /**
         * Constructor
         * 
         */ 
        public function __construct() {
            //self::create_pages();
        }


        /**
         * Setup
         * 
         */ 
        public static function setup() {
            self::create_pages();
        }


        /**
         * Update Version
         * 
         */ 
        public static function check_pages_match_current_version() {
            self::create_pages();
        }
        
        
        /**
         * Add Pages to WP
         * 
         */
        private static function create_pages() {
            $i = 0;
            self::create_homepage_details_array();
            $homepage_id = self::check_page_exists_and_is_up_to_date($i);

            self::create_subpage_details_array($homepage_id);
            $num_of_pages = count(self::$page_details);
            for ($i = 1; $i < $num_of_pages; $i++) {
                self::check_page_exists_and_is_up_to_date($i);
            }
        }


        /**
         * Check page exists, has correct status and matches current version
         * 
         */
        private static function check_page_exists_and_is_up_to_date($i) {
            //query db for page if it exists
            $page_exists = tt_get_page_id(self::get_page_details($i)['post_title']);

            //if page doesn't exist
            if (empty($page_exists) or $page_exists == null) {
                $page_id = self::create_page(self::get_page_details($i));
            

            //page exists, make sure everything is correct
            } else {
                
                $page_id = $page_exists;
                    
                //pages are changed to draft on plugin deactivation
                if (get_post_status($page_exists) == 'draft') {
                    self::update_page_status($page_id);
                }

                //check content matches current version
                $installed_page = get_post($page_id);
                $installed_page_content = $installed_page->post_content;

                $updated_content = self::get_page_details($i)['post_content'];

                //does the content match the current version
                if ($installed_page_content != $updated_content) {
                    //update content of page in db
                    $updated_page = array(
                        'ID' => $page_id,
                        'post_content' => $updated_content
                    );
                    wp_update_post($updated_page);
                }                
            }
            return $page_id;
        }


        /**
         * Create Individual Page
         * 
         */
        private static function create_page($details) {
            $new_page_id = wp_insert_post($details);
            return $new_page_id;
        }


        /**
         * Update Page Status
         * 
         */
        private static function update_page_status($page_id) {
            wp_update_post(array(
                'ID' => $page_id,
                'post_status' => 'private'
            ));
        }

        
        /**
         * Get all arguments to add page to WP
         * 
         */
        private static function get_page_details($arr_index) {
            $arr = array(
                'post_author'           => '',
                'post_content'          => self::$page_details[$arr_index]['Content'],
                'post_content_filtered' => '',
                'post_title'            => self::$page_details[$arr_index]['Title'],
                'post_name'             => self::$page_details[$arr_index]['Slug'],
                'post_excerpt'          => '',
                'post_status'           => 'private',
                'post_type'             => 'page',
                'page_template'         => 'tt-page-template.php',
                'comment_status'        => 'closed',
                'ping_status'           => 'closed',
                'post_password'         => '',
                'to_ping'               => '',
                'pinged'                => '',
                'post_parent'           => self::$page_details[$arr_index]['Parent'],
                'menu_order'            => 0,
                'guid'                  => '',
                'import_id'             => 0,
                'context'               => '',
            );  
            return $arr;          
        }

        
        /**
         * Create homepage page-specific properties
         * 
         */
        private static function create_homepage_details_array() {
            $details_all = array();
            //tt-home
            $details = array(
                "Title" => "Time Tracker Home",
                "Parent" => 0,
                "Slug" => "time-tracker",
                "Content" => "[tt_month_summary][tt_year_summary]"
            );
            array_push($details_all, $details);
            self::$page_details = $details_all;
        }
            
            
        /**
         * Create array of pages and page-specific properties
         * 
         */
        public static function create_subpage_details_array($id) {
            $details_all = self::$page_details;
            $parent = $id;
            
            //clients
            $details = array(
                "Title" => "Clients",
                "Parent" => $parent,
                "Slug" => "clients",
                "Content" => "[tt_client_list_table]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //new client
            $details = array(
                "Title" => "New Client",
                "Parent" => $parent,
                "Slug" => "new-client",
                "Content" => "[contact-form-7 id=\"" . tt_get_form_id("Add New Client") . "\" title=\"Add New Client\" html_class=\"tt-form\"]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //new project
            $details = array(
                "Title" => "New Project",
                "Parent" => $parent,
                "Slug" => "new-project",
                "Content" => "[contact-form-7 id=\"" . tt_get_form_id("Add New Project") . "\" title=\"Add New Project\" html_class=\"tt-form\"]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //new recurring task
            $details = array(
                "Title" => "New Recurring Task",
                "Parent" => $parent,
                "Slug" => "new-recurring-task",
                "Content" => "[contact-form-7 id=\"" . tt_get_form_id("Add New Recurring Task") . "\" title=\"Add New Recurring Task\" html_class=\"tt-form\"]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //new task
            $details = array(
                "Title" => "New Task",
                "Parent" => $parent,
                "Slug" => "new-task",
                "Content" => "[contact-form-7 id=\"" . tt_get_form_id("Add New Task") . "\" title=\"Add New Task\" html_class=\"tt-form\"]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //new time entry
            $details = array(
                "Title" => "New Time Entry",
                "Parent" => $parent,
                "Slug" => "new-time-entry",
                "Content" => "[contact-form-7 id=\"" . tt_get_form_id("Add Time Entry") . "\" title=\"Add Time Entry\" html_class=\"tt-form\"]<button class=\"end-work-timer float-right no-border-radius\" onclick=\"update_end_timer()\">Set End Time</button>",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //open task list
            $details = array(
                "Title" => "Open Task List",
                "Parent" => $parent,
                "Slug" => "open-task-list",
                "Content" => "[tt_open_task_list_table]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //pending time
            $details = array(
                "Title" => "Pending Time",
                "Parent" => $parent,
                "Slug" => "pending-time",
                "Content" => "<input type=\"submit\" class=\"button tt-export-pending-time tt-button tt-midpage-button float-right no-border-radius\" name=\"tt-export-pending-time\" value=\"Download Data\" />[tt_pending_time_table]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //project list
            $details = array(
                "Title" => "Project List",
                "Parent" => $parent,
                "Slug" => "projects",
                "Content" => "[tt_project_list_table]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //task detail
            $details = array(
                "Title" => "Task Detail",
                "Parent" => $parent,
                "Slug" => "task-detail",
                "Content" => "[tt_show_task_details]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //task list
            $details = array(
                "Title" => "Task List",
                "Parent" => $parent,
                "Slug" => "task-list",
                "Content" => "[tt_task_list_table]",
				"Paginate" => array(
					"Flag" => true,
					"RecordsPerPage" => 100,
					"PreviousText" => '« Newer',
					"NextText" => 'Older »',
					"TotalRecordsQuery" => 'SELECT COUNT(*) FROM tt_task'
				)
            );
            array_push($details_all, $details);

            //recurring task list
            $details = array(
                "Title" => "Recurring Task List",
                "Parent" => $parent,
                "Slug" => "recurring-task-list",
                "Content" => "[tt_recurring_task_list_table]",
				"Paginate" => array(
					"Flag" => false
				)
            );
            array_push($details_all, $details);

            //time log
            $details = array(
                "Title" => "Time Log",
                "Parent" => $parent,
                "Slug" => "time-log",
                "Content" => "<button class=\"tt-accordion\">Search</button><div class=\"tt-accordion-panel\">[contact-form-7 id=\"" . tt_get_form_id("Filter Time") . "\" title=\"Filter Time\" html_class=\"filter-time-form\" html_class=\"tt-form\"]</div>[tt_time_log_table type=\'summary\'][tt_year_summary]<h3>Time Details</h3>[tt_time_log_table type=\'detail\']",
				"Paginate" => array(
					"Flag" => true,
					"RecordsPerPage" => 100,
					"PreviousText" => '« Newer',
					"NextText" => 'Older »',
					"TotalRecordsQuery" => 'SELECT COUNT(*) FROM tt_time'
				)
            );
            array_push($details_all, $details);

            //delete confirmation page
            $details = array(
                "Title" => "Delete Item",
                "Parent" => $parent,
                "Slug" => "delete-item",
                "Content" => "[tt_delete_confirmation_content]",
                "Paginate" => array(
                    "Flag" => false
                )
            );
            array_push($details_all, $details);
            
            self::$page_details = $details_all;
            return $details_all;
        }

    }  //close class

}  //close if class exists