<?php
/**
 * Time Tracker Class_Hours_Worked_Detail 
 *
 * Get work time history from database
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
if ( !class_exists( 'Class_Hours_Worked_Detail' ) ) {


    /**
     * Class
     * 
     */ 
    class Class_Hours_Worked_Detail
    {

        /**
         * Constructor
         * 
         */     
        public function __construct() {
            $this->hours_worked = $this->query_database();
        }


        /**
         * Get data from db
         * 
         */ 
        private function query_database() {
            //Connect to Time Tracker Database
            //$tt_db = new wpdb(DB_USER, DB_PASSWORD, TT_DB_NAME, DB_HOST);
            global $wpdb;
            $result = $wpdb->get_results($query = $this->get_sql_string(), $output = ARRAY_A);
            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
            return $result;
        }


        /**
         * Define data query
         * 
         */ 
        private function get_sql_string() {
            $sql_string = "SELECT tt_time.StartTime as StartTime, tt_time.EndTime as EndTime,
                EXTRACT(week FROM tt_time.StartTime) as WorkWeek,
                EXTRACT(week FROM Now()) as ThisWeek,
                MONTH(tt_time.StartTime) as WorkMonth,
                YEAR(tt_time.StartTime) as WorkYear,
                Minute(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as MinutesWorked,
                Hour(TIMEDIFF(tt_time.EndTime, tt_time.StartTime)) as HoursWorked,
                tt_client.Company, tt_client.Billable, tt_client.BillTo, tt_time.Invoiced, tt_time.InvoicedTime as BilledTime
            FROM tt_time LEFT JOIN tt_client
            ON tt_time.ClientID = tt_client.ClientID
            ORDER BY WorkYear ASC, WorkMonth ASC, BillTo ASC, Company ASC";
            return $sql_string;
        }

    } //close class
} //if class exists