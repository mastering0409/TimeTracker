<?php
/**
 * Class Time_Tracker_Tool_Tip
 *
 * Display tool tips to help new users
 * 
 * @since 2.0.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

/**
 * If class doesn't already exist
 * 
 */
if ( !class_exists( 'Time_Tracker_Tool_Tip' ) ) {


    /**
     * Class
     * 
     */
    class Time_Tracker_Tool_Tip
    {

        private $tips;


        /**
         * Constructor
         * 
         */
        public function __construct() {
            $this->set_tips();
        }


        /**
         * Get result
         * 
         */
        public function get_tip($id) {
            return $this->tip_html($id);
        }


        /**
         * Define tool tips
         * 
         */
        private function set_tips() {
            $this->tips = [
                "tip-end-work-timer" => "When done working click this button to set the end time in the form",
                "tip-2" => "" 
            ];
        }


        /**
         * Get tool tip
         * 
         */
        private function tip_text($id) {
            $tip_text = $this->tips[$id];
            return $tip_text;
        }

        /**
         * Get start of span
         * 
         */
        private function tip_start($id) {
            $tip_start = "<span class=\"tool-tip\" id=\"";
            $tip_start .= $id;
            $tip_start .= "\"><span class=\"tool-tip-text\">";
            return $tip_start;
        }

        /**
         * Get end of span
         * 
         */
        private function tip_end() {
            $tip_end = "</span></span>";
            return $tip_end;
        }

        /**
         * Get tool tip html
         * 
         */
        private function tip_html($id) {
            $tip_html = $this->tip_start($id) . $this->tip_text($id) . $this->tip_end();
            return $tip_html;
        }

    } //close class

} //close if class exists