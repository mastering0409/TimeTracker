<?php
/**
 * Time Tracker Utility Functions
 *
 * Misc functions used throughout plugin
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Takes a fraction time and converts it for use in SQL time format (hh:mm:ss)
 * Example: 2.75 would return 24500, (ie: 02:45:00), 24500 can be inserted into time field in SQL
 * 
 */
function tt_convert_fraction_to_time($fraction_time) {
    if (($fraction_time == 0) or ($fraction_time == "") or ($fraction_time == null)) {
        return 0;
    } else {    
        $hours_part = intval($fraction_time) * 100 * 100;
        $minutes_part = ($fraction_time - intval($fraction_time)) * 60 * 100;
        $time_format = $hours_part + $minutes_part;
        return $time_format;
    }
}


/**
 * Takes hours and minutes and converts to hh:mm format
 * Correctly handles minutes over 60
 * Example: 1h + 90min would convert to: 2:30
 * 
 */
function tt_convert_to_string_time($hours, $minutes) {
    if ( (($hours == 0) or ($hours == null) or ($hours == "")) and (($minutes == 0) or ($minutes == null) or ($minutes == "")) ) {
        return 0;
    }
    
    //if minutes is over 60, convert to extra hours
    if ( $minutes >= 60 ) {
        $minutes_to_hours = intdiv($minutes, 60);
        $hours = $hours + $minutes_to_hours;
        $minutes = $minutes - ($minutes_to_hours * 60);
    } //if minutes over 60    
            
    //minutes are only an integer, add a preceeding 0 if necessary to make it look like a time format
    if (strlen($minutes) == 1) {
        $time_string = $hours . ":0" . $minutes;
    } else {
        $time_string = $hours . ":" . $minutes;
    } 
    return $time_string;
}


/**
 * Takes hours and minutes and converts to decimal hours
 * Correctly handles minutes over 60
 * Example: 1h + 90min would convert to: 2.5
 * 
 */
function tt_convert_to_decimal_time($hours, $minutes) {
    if ( (($hours == 0) or ($hours == null) or ($hours == "")) and (($minutes == 0) or ($minutes == null) or ($minutes == "")) ) {
        return 0;
    }
    
    //if minutes is over 60, convert to extra hours
    if ( $minutes >= 60 ) {
        $minutes_to_hours = intdiv($minutes, 60);
        $hours = $hours + $minutes_to_hours;
        $minutes = $minutes - ($minutes_to_hours * 60);
    } //if minutes over 60    
            
    $minutes_decimal = $minutes/60;
    $time_decimal_string = round($hours + $minutes_decimal,2);
    return $time_decimal_string;
}


/**
 * Takes a date (or date and time) and formats it for front end display, (ie: short date, with or without time)
 * Can be formatted to date and time or date, depending on user passed variable
 * type = output type
 * 
 */
function tt_format_date_for_display($date_entry, $type) {
    //if date is empty - return nothing
    if ( ($date_entry == "") or ($date_entry == "0000-00-00%") or ($date_entry == "0000-00-00") or ($date_entry == "0000-00-00 00:00:00")) {
        return "";
    } else {    
        //check if it's only a date coming in, create date object
        $dateinput = \DateTime::createFromFormat("Y-m-d", $date_entry);
        
        //if that didn't work, check if you can create a date object from a date and time format
        if (!$dateinput) {
            $dateinput = \DateTime::createFromFormat("Y-m-d H:i:s", $date_entry);

            //if that didn't work, not sure what it is, return nothing
            if (!$dateinput) {
                return "";
            }
        }
    }

    //format based on output choice
    if ($type == "date_and_time") {
        return date_format($dateinput, "n/j/y g:i a"); 
    } //date and time

    if ($type == "date_only") {
        return date_format($dateinput, "n/j/y"); 
    } //date only

    return "";
}


/**
 * Get Time Estimate Formatted
 * 
 */
function get_time_estimate_formatted($timeestimate) {
    if (($timeestimate == 0 ) or ($timeestimate == null)) {
        return null;
    } else {
        $time_estimate_parts = explode(":", $timeestimate);
        $time_estimate_formatted = tt_convert_to_decimal_time($time_estimate_parts[0], $time_estimate_parts[1]);  
        return $time_estimate_formatted;              
    }
}


/**
 * Get Time Worked vs Estimate Class
 * 
 */
function get_time_estimate_class($percent_time_logged) {
    if ( ($percent_time_logged <> "") and ($percent_time_logged > 100) ) {
        $time_worked_vs_estimate_class = "over-time-estimate";
    } else {
        $time_worked_vs_estimate_class = "";
    }
    return $time_worked_vs_estimate_class;
}


/**
 * Get Percentage of Time Logged vs Time Estimate
 * 
 */
function get_percent_time_logged($time_estimate_formatted, $hours_logged) {
    //evaluate time worked vs estimate, format data to display and apply css class based on result

    if (($time_estimate_formatted == 0 ) or ($time_estimate_formatted == null)) {
        $percent_time_logged = "";
        $details_for_display = "";
    } else {
        $percent_time_logged = round($hours_logged / $time_estimate_formatted * 100);
        //$percent_time_logged = "<br/>" . round($hours_logged / $time_estimate_formatted * 100) . "%";
        $details_for_display = " / " . $time_estimate_formatted . "<br/>" . $percent_time_logged . "%<br/>";

		if ($percent_time_logged > 100) {
			$percent_time_logged = 100;
		}
		$details_for_display .= "<div style='display:inline-block;width:100%;height:20px;border:1px solid black;'>";
		$details_for_display .= "<div style='background-color:green;height:20px;float:left;width:" . $percent_time_logged . "%;'></div>";
		$details_for_display .= "<div style='background-color:red;height:20px;'></div></div>"; 		
    }     
    return $details_for_display;
}


/**
 * Get Due Date Class - Are We On Time, Late, Or Getting Late
 * 
 */
function get_due_date_class($duedate, $status) {
    if ( ($duedate == "0000-00-00") || ($duedate == null) ) {
        $due_date_formatted = "";
        $due_date_class = "no-date";
    } else {
        $due_date_formatted = date_format(\DateTime::createFromFormat("Y-m-d", $duedate), "n/j/y");
        if (\DateTime::createFromFormat("Y-m-d", $duedate) <= new \DateTime() AND $status<>"Canceled" AND $status<>"Complete") {
            $due_date_class = "late-date";
        } elseif (\DateTime::createFromFormat("Y-m-d", $duedate) <= new \DateTime(date("Y-m-d", strtotime("+7 days"))) AND $status<>"Canceled" AND $status<>"Complete") {
            $due_date_class = "soon-date";
        } elseif (\DateTime::createFromFormat("Y-m-d", $duedate) > new \DateTime(date("Y-m-d", strtotime("+90 days"))) AND $status<>"Canceled" AND $status<>"Complete") {
            $due_date_class = "on-hold-date";
        } else {
            $due_date_class = "ok-date";
        }
    }
    return $due_date_class;
}


/**
 * Return month name from month number
 * Example: 1 returns January
 * 
 */
function get_month_name_from_number($monthnumber) {
    //create date object from month integer
    $dateObj   = \DateTime::createFromFormat('!m', $monthnumber);
    if ($dateObj) {
        $monthName = $dateObj->format('F'); // Full name
        return $monthName;
    }
    return "";
}


/**
 * Find the form ID for a form with the given name
 * 
 */
function tt_get_form_id($form_name) {
    //$forms = WPCF7_ContactForm::find(array("title" => $form_name));
	$forms = get_posts(array(
        'title'=> $form_name,
        'post_type' => 'wpcf7_contact_form'
    ), ARRAY_A);
    if ($forms) {
        return $forms[0]->ID;
    } else {
        return;
    }
}


/**
 * Check page status to verify it's private
 * 
 */
function check_page_status($page_id) {
    $status = get_post_status ( $page_id );
    if ( ($status == 'private') || ($status == 'protected') ) {
        return 'private';
    }
    return $status;
}        
 

/**
 * Find the page ID for a form with the given name (and verify it's not in trash)
 * 
 */
function tt_get_page_id($page_name) {
    //$forms = WPCF7_ContactForm::find(array("title" => $form_name));
    $pages = get_page_by_title($page_name, ARRAY_A);

    //no pages are returned
    if (empty($pages)) {
        return;

    //one page is returned
    } elseif (array_key_exists('post_status', $pages)) {
        if (($pages['post_status'] == 'publish') or ($pages['post_status'] == 'draft') or ($pages['post_status'] == 'private') or ($pages['post_status'] == 'inherit')) {
            return $pages['ID'];
        }

    //several pages must have been found - return first
    } else {
        foreach ($pages as $page) {
            if (($page['post_status'] == 'publish') or ($page['post_status'] == 'draft') or ($page['post_status'] == 'private') or ($page['post_status'] == 'inherit')) {
                return $page['ID'];
            }
        }
    }

    //nothing is published
    return;
}


/**
 * Get sitename
 * 
 */
function tt_get_site_name() {
    $site = esc_html(get_bloginfo( 'name' ));
    return $site;
}


/**
 * Get site.com
 * 
 */
function tt_get_site_url() {
    $url = esc_html(get_bloginfo( 'url' ));
    return $url;
}


/**
 * Return wordpress@site.com for use with form notification settings
 * 
 */
function tt_get_wordpress_email() {
    $wp_email = esc_html(get_bloginfo( 'url' ));
    $slash_loc = strpos($wp_email,"//");
    if ( $slash_loc > 0 ) {
        $wp_email = substr($wp_email,$slash_loc+2);
    }
    $www_loc = strpos($wp_email,"www");
    if ( $www_loc > 0 ) {
        $wp_email = substr($wp_email,$www_loc+4);
    }
    $wp_email = "wordpress@" . $wp_email;
    return $wp_email;
}


/**
 * Get admin email for website
 * 
 */
function tt_get_site_admin_email() {
    $email = esc_html(get_bloginfo( 'admin_email' ));
    return $email;
}


/**
 * Add Recurring Task Icon
 * 
 */
function tt_add_recurring_task_icon() {
    $html = "<img src=" . TT_PLUGIN_WEB_DIR_INC . "img/recurring-task-icon-150sq-blue.png class=\"tt-recurring-task-icon\" width=25 height=25>";
    return $html;
}


/**
 * Check for Pagination
 * 
 */
function check_for_pagination() {
	$pages = new Time_Tracker_Activator_Pages();
	$pages_detail = $pages->create_subpage_details_array(0);
	$slug = get_post_field('post_name');
	$current_page = null;
	$i = 0;
	do {
		if ($pages_detail[$i]['Slug'] == $slug) {
			$current_page = $i;
		}
		$i = $i + 1;
	} while ($current_page == null and $i < count($pages_detail) );
	if (is_null($current_page)) {
        $pagination['Flag'] = false;
    } elseif (key_exists('Paginate', $pages_detail[$current_page])) {
        $pagination = $pages_detail[$current_page]['Paginate'];
        if ($pagination['Flag'] == true) {
            global $wpdb;
            $sql_string = sanitize_text_field($pagination['TotalRecordsQuery']);
            $total_records = $wpdb->get_var($sql_string);
            $pagination['RecordCount'] = $total_records;
        }
    } else {
        $pagination['Flag'] = false;
    }
	return $pagination;
}


/**
 * Check for Pagination - Records Per Page
 * 
 */
function get_pagination_qty_per_page() {
    require_once(TT_PLUGIN_DIR_INC . 'class-time-tracker-activator-pages.php');
	$pages = new Time_Tracker_Activator_Pages();
	$pages_detail = $pages->create_subpage_details_array(0);
	$slug = get_post_field( 'post_name');
	$current_page = null;
	$i = 0;
	do {
		if ($pages_detail[$i]['Slug'] == $slug) {
			$current_page = $i;
		}
		$i = $i + 1;
	} while ($current_page == null and $i <= count($pages_detail) );
	$pagination = $pages_detail[$current_page]['Paginate'];
	if ($pagination['Flag'] == true) {
		return $pagination['RecordsPerPage'];
	} else {
		return 1000;
	}
}


/**
 * Add Pagination to Page
 * 
 */
function add_pagination($data_count, $max_per_page, $current_page_num, $prevtext, $nexttext) {
	//add_pagination($data_count, $records_in_table, $current_page_num, '« Newer', '« Older')
	global $wp_query;
	if (is_null($max_per_page) or $max_per_page == 0) {
		$max_per_page = 100;
	}
	$args = array(
		'total' => ceil($data_count/$max_per_page),
		'current' => $current_page_num,
		'prev_text' => $prevtext,
		'next_text' => $nexttext,
		'mid_size' => 10
	);
	echo paginate_links($args);
}


/**
 * Get Record Numbers Based on Page Number for Pagination
 * 
 */
function get_record_numbers_for_pagination_sql_query() {
	$records_per_page = get_pagination_qty_per_page();
	
	$page_num = basename($_SERVER['REQUEST_URI']);
	if (!(is_numeric($page_num))) {
		$records = array(
			'limit' => $records_per_page,
			'offset' => 0
		);
	} else {
		$records = array(
			'limit' => $records_per_page,
			'offset' => ($page_num*100)-101
		);
	}
	return $records;
}
	

/**
 * Record to sql_log on server, and save to options table to alert user
 * 
 */
function catch_sql_errors($filename, $functionname, $lastquery, $lasterror) {           
    $now = new \DateTime;
    //there was a sql error
    if( ($lasterror !== "") and ($lasterror !== null) ) {
		if (WP_DEBUG_LOG) {
        	log_sql('SQL ERROR: In file ' . $filename . ', function: ' . $functionname);
        	log_sql('SQL String: ' . $lastquery);
        	log_sql('SQL Error Details: ' . $lasterror);
		}
        
		//save to options table to alert user
        if (get_option('time-tracker-sql-result')) {
			update_option('time-tracker-sql-result', array('result'=>'failure','updated'=>$now->format('m-d-Y g:i A'),'error'=>$lasterror, 'file'=>$filename, 'function'=>$functionname));
		} else {
			add_option('time-tracker-sql-result', array('result'=>'failure','updated'=>$now->format('m-d-Y g:i A'),'error'=>$lasterror, 'file'=>$filename, 'function'=>$functionname));
		}
    
    //it was a success!
    } else {
        if (WP_DEBUG_LOG) {
			log_sql('SQL SUCCESS. SQL String: ' . $lastquery);
		}
        
        //if there are no results in the db, add them
        if (get_option('time-tracker-sql-result')) {
			
			$option = get_option('time-tracker-sql-result');			
			
			//if option already was a success, just update the date
			if ($option['result'] == 'success') {			
				update_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now->format('m-d-Y g:i A'),'error'=>'N/A', 'file'=>$filename, 'function'=>$functionname));
				
        	//if option was a failure, leave it there for 7 days and only update if it's at least 7 days old
        	} else {
            	$last_updated = date_create_from_format('m-d-Y g:i A', $option['updated']);
            	if ( date_diff($last_updated, $now)->format('%a') > 7 ) {
                	update_option('time-tracker-sql-result', array('result'=>'success','updated'=>$now->format('m-d-Y g:i A'),'error'=>'N/A', 'file'=>$filename, 'function'=>$functionname));
            	}
			}
		
		//option doesn't exist in db, create it
		} else {
			add_option('time-tracker-sql-result', array('result'=>'failure','updated'=>$now->format('m-d-Y g:i A'),'error'=>$lasterror, 'file'=>$filename, 'function'=>$functionname));
		}
    }
}


/**
 * Record to sql_log on server, for error handling
 * 
 */
function log_sql($msg) {
    if (WP_DEBUG_LOG) {
		$log_folder = ABSPATH . "../tt_logs/sql_log";
		if (!file_exists($log_folder)) {
			mkdir($log_folder, 0777, true);
		}
		$log_filename = $log_folder . '/sql_log_' . date('d-M-Y') . '.log';
		$now = new \DateTime();
		$now->setTimeZone(new \DateTimeZone(wp_timezone_string()));
		$log_str = date_format($now, 'M d, Y h:i:s A (T)') . ": " . $msg;
		file_put_contents($log_filename, $log_str . "\n", FILE_APPEND);
	}
}


/**
 * Record to cron_log on server, for error handling
 * 
 */
function log_cron($msg) {
    if (WP_DEBUG_LOG) {
		$log_folder = ABSPATH . "../tt_logs/cron_log";
		if (!file_exists($log_folder)) {
			mkdir($log_folder, 0777, true);
		}
		$log_filename = $log_folder . '/cron_log_' . date('d-M-Y') . '.log';
		$now = new \DateTime();
		$now->setTimeZone(new \DateTimeZone(wp_timezone_string()));
		$log_str = date_format($now, 'M d, Y h:i:s A (T)') . ": " . $msg;
		file_put_contents($log_filename, $log_str . "\n", FILE_APPEND);
	}
}


/**
 * Record to misc tt_log
 * 
 */
function log_tt_misc($msg) {
    if (WP_DEBUG_LOG) {
		$log_folder = ABSPATH . "../tt_logs/misc_log";
		if (!file_exists($log_folder)) {
			mkdir($log_folder, 0777, true);
		}
		$log_filename = $log_folder . '/misc_log_' . date('d-M-Y') . '.log';
		$now = new \DateTime();
		$now->setTimeZone(new \DateTimeZone(wp_timezone_string()));
		$log_str = date_format($now, 'M d, Y h:i:s A (T)') . ": " . $msg;
		file_put_contents($log_filename, $log_str . "\n", FILE_APPEND);
	}
}