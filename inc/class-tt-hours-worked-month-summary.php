<?php
/**
 * Time Tracker Class_Hours_Worked_Month_Summary 
 *
 * Takes the data from the hours worked class (query) and summarizes the current month's data for display
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
if ( !class_exists( 'Class_Hours_Worked_Month_Summary' ) ) {


    /**
     * If class doesn't already exist
     * 
     */
    class Class_Hours_Worked_Month_Summary extends Class_Hours_Worked_Detail
    {


        
        /**
         * Constructor
         * 
         */
        public function __construct() {
            parent::__construct();
            $hours_worked = $this->hours_worked;
        }


        /**
         * Reorganize data - Group by Month, then Week, then Bill To
         * 
         */        
        private function groupDataByMonthWeekAndBillTo() {
            $grouped_time = array();
            if (!empty($this->hours_worked)) {
                foreach ($this->hours_worked as $item) {
                    //only summarize current year and this month or week
                    $workyear = sanitize_text_field($item['WorkYear']);
                    $workmonth = sanitize_text_field($item['WorkMonth']);
                    $workweek = sanitize_text_field($item['WorkWeek']);
                    $thisweek = sanitize_text_field($item['ThisWeek']);
                    $billto = sanitize_text_field($item['BillTo']);

                    if ( ($workyear == date('Y')) && ( ($workmonth == date('n')) || ($workweek == $thisweek) ) ) {
                        //get month and week of current item
                        $workmonth = $workmonth;
                        $workweek = $workweek;
                        
                        //get bill to of current item
                        if ($billto == "") {
                            $billto = "Unknown";
                        } else {
                            $billto = $billto;
                        }

                        if ($workweek == $thisweek) {
                            $grouped_time['This Week'][$billto][] = $item;
                        }
                        if ($workmonth == date('n')) {
                            $grouped_time['This Month'][$billto][] = $item;
                        }
                        
                    } //if work is current year                
                } //for each piece of data from database
            }  //if no data
            return $grouped_time;
        }


        /**
         * Calculate running totals by Month, then Week, then Bill To
         * 
         */ 
        private function totalDataByMonthWeekAndBillTo() {
            $grouped_time = $this->groupDataByMonthWeekAndBillTo();
            $totaled_time = array();
            if (!empty($grouped_time)) {
                foreach ($grouped_time as $timeunit => $time_array) {
                    $timeunithoursworked = 0.0;
                    $timeunithoursinvoiced = 0.0;
                    $timeunitpending = 0.0;
                    foreach ($time_array as $billto => $billto_array) {                    
                        $totalhours = 0.0;
                        $totalminutes = 0.0;
                        $billedtime = 0.0;
                        $pendinghours = 0.0;
                        $pendingminutes = 0.0;
                        foreach ($billto_array as $item) {
                            $totalminutes = $totalminutes + $item['MinutesWorked'];
                            $totalhours = $totalhours + $item['HoursWorked'];
                            $billedtime = $billedtime + $item['BilledTime'];
                            if ( ($item['Invoiced']=="") || ($item['Invoiced']==null) )  {
                                $pendinghours = $pendinghours + $item['HoursWorked'];
                                $pendingminutes = $pendingminutes + $item['MinutesWorked'];
                            }
                        } //total hours from each detailed record inside billto name array
                        //save the total from the last bill to in a new array
                        $decimal_time_worked = tt_convert_to_decimal_time($totalhours, $totalminutes);
                        $totaled_time[$timeunit][$billto]['TimeWorked'] = round($decimal_time_worked,1);
                        $totaled_time[$timeunit][$billto]['TimeInvoiced'] = round($billedtime,1);
                        if ($decimal_time_worked == 0) {
                            $totaled_time[$timeunit][$billto]['PercentTimeInvoiced'] = 0;
                        } else {
                            $totaled_time[$timeunit][$billto]['PercentTimeInvoiced'] = round($billedtime/$decimal_time_worked*100,0);
                        }
                        $decimal_time_pending = tt_convert_to_decimal_time($pendinghours, $pendingminutes);
                        $totaled_time[$timeunit][$billto]['PendingTime'] = round($decimal_time_pending,1);
                        $totaled_time[$timeunit][$billto]['Billable'] = $item['Billable'];
                        //cumulative total for month (of all bill tos)
                        $timeunithoursworked = $timeunithoursworked + $decimal_time_worked;
                        $timeunithoursinvoiced = $timeunithoursinvoiced + $billedtime;
                        if ($timeunithoursworked == 0) {
                            $timeunitpercenthoursinvoiced = 0;
                        } else {
                            $timeunitpercenthoursinvoiced = round($timeunithoursinvoiced/$timeunithoursworked*100,0);
                        }
                        //only include billable clients in pending time
                        if ( $item['Billable'] == 1) {
                            $timeunitpending = $timeunitpending + $decimal_time_pending;    
                        }
                    } //loop bill to name inside this month
                    $totaled_time[$timeunit]['Total']['TimeWorked'] = $timeunithoursworked;
                    $totaled_time[$timeunit]['Total']['TimeInvoiced'] = $timeunithoursinvoiced;
                    $totaled_time[$timeunit]['Total']['PercentTimeInvoiced'] = $timeunitpercenthoursinvoiced;
                    $totaled_time[$timeunit]['Total']['PendingTime'] = $timeunitpending;
                    $totaled_time[$timeunit]['Total']['Billable'] = 1;
                } //loop through each month
            } //if not empty
            return $totaled_time;
        }


        /**
         * Summarize all Bill To Names included
         * 
         */ 
        private function listBillToNames($dataArray) {
            $bill_to_names = array();
            if (!empty($dataArray)) {
                foreach ($dataArray as $timeunit => $billToArray) {
                    foreach ($billToArray as $billToName => $detail) {
                        if ( ($billToName != 'Total') && (! (in_array($billToName, $bill_to_names))) ) {
                            $bill_to_names[] = $billToName;
                        }
                    } //for each billto group
                } //for each month array
            } //if array isn't empty
            //put in alphabetical order
            sort($bill_to_names);
            //make sure Total appears last in the array
            $bill_to_names[] = 'Total';
            return $bill_to_names;
        }


        /**
         * Create HTML display for front end display
         * 
         */ 
        public function createHTMLTable() {
            $time_summary = $this->totalDataByMonthWeekAndBillTo();
            $bill_to_names = $this->listBillToNames($time_summary);
            $columncount = count($bill_to_names) + 1;
            $table = "<h2>" . date('F') . " " . date('Y') . " Hours Worked</h2>";

            //open table
            $table .= "<table class=\"tt-table monthly-summary-table tt-even-columns-" . esc_attr($columncount) . "\">";

            //header row
            $table .= "<tr class=\"tt-header-row\">";
            $table .= "<th class=\"tt-bold-font tt-align-center\"></th>";
            foreach ($bill_to_names as $bill_to_name) {
                $table .= "<th class=\"tt-bold-font tt-align-center\">" . esc_textarea($bill_to_name) . "</th>";
            }            
            $table .= "</tr>";

            //add data to table
            $table .= "<tr>";                
            
            //current week hours worked
            $table .= "<td class=\"tt-align-center\">Current Week Hours Worked</td>";
            foreach ($bill_to_names as $bill_to_name) {        
                //no data for at all
                if (empty($time_summary)) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                //no data for this week
                } elseif ( ! ( array_key_exists("This Week", $time_summary)) ) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                //no data for this bill to for this week
                } elseif ( array_key_exists($bill_to_name, $time_summary['This Week']) ) {
                    $table .= "<td class=\"tt-align-right\">" . esc_textarea($time_summary['This Week'][$bill_to_name]['TimeWorked']) . "</td>";
                //cath other
                } else {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                }
            }
            $table .= "</tr>";

            //current month hours worked
            $table .= "<td class=\"tt-align-center\">" . date('F') . " " . date('Y') . " Hours Worked</td>";
            foreach ($bill_to_names as $bill_to_name) {      
                //no data at all
                if (empty($time_summary)) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                //no data for this month
                } elseif ( ! ( array_key_exists("This Month", $time_summary)) ) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                //no data for this bill to
                } elseif (array_key_exists($bill_to_name, $time_summary['This Month'])) {
                    $table .= "<td class=\"tt-align-right\">" . esc_textarea($time_summary['This Month'][$bill_to_name]['TimeWorked']) . "</td>";
                //catch other
                } else {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                }
            }
            $table .= "</tr>";

            //pending time
            $table .= "<td class=\"tt-align-center\">" . date('F') . " " . date('Y') . " Hours Pending</td>";
            foreach ($bill_to_names as $bill_to_name) {        
                if (empty($time_summary)) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                } elseif (array_key_exists($bill_to_name, $time_summary['This Month']) && ($time_summary['This Month'][$bill_to_name]['Billable'] == 1)) {
                    $table .= "<td class=\"tt-align-right\">" . esc_textarea($time_summary['This Month'][$bill_to_name]['PendingTime']) . "</td>";
                } else {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                }            
            }
            $table .= "</tr>";

            //invoiced time
            $table .= "<td class=\"tt-align-center\">" . date('F') . " " . date('Y') . " Hours Invoiced</td>";
            foreach ($bill_to_names as $bill_to_name) {        
                if (empty($time_summary)) {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                } elseif (array_key_exists($bill_to_name, $time_summary['This Month']) && ($time_summary['This Month'][$bill_to_name]['Billable'] == 1)) {
                    $table .= "<td class=\"tt-align-right\">" . esc_textarea($time_summary['This Month'][$bill_to_name]['TimeInvoiced']) . "</td>";
                } else {
                    $table .= "<td class=\"tt-align-right\">N/A</td>";
                }
            }
            $table .= "</tr>";      

            //close table
            $table .= "</table>";
            return $table;
        }

    } //close out class

}  //close out if class exists