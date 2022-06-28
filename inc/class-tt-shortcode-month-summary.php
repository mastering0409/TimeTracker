<?php
/**
 * Class Time_Tracker_Shortcode_Month_Summary
 *
 * SHORTCODE TO DISPLAY TOTAL HOURS BY COMPANY AND GRAND TOTAL
 * 
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( ! class_exists('Time_Tracker_Shortcode_Month_Summary') ) {

  /**
   * Class
   * 
   */  
  class Time_Tracker_Shortcode_Month_Summary {


    /**
     * Plugin Variables
     * 
     */   
    public $shortcode = 'tt_month_summary';


    /**
     * Constructor
     * 
     */    
    public function __construct() {
      add_shortcode( $this->shortcode, array( $this, 'month_summary_shortcode' ) );
    }


    /**
     * Shortcode callback
     * 
     */
    public function month_summary_shortcode() {    
      $month_summary= new Class_Hours_Worked_Month_Summary;
      $month_summary_display = $month_summary->createHTMLTable();
      return $month_summary_display;
    }
  

    /**
     * Return results
     * 
     */    
    public function get_shortcode() {
      return $this->shortcode;
    }

    
  } //class
} //if class exists

$Time_Tracker_Shortcode_Month_Summary = new Time_Tracker_Shortcode_Month_Summary();