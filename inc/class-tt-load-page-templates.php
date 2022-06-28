<?php
/**
 * Class: Time_Tracker_Load_Page_Templates
 *
 * Load page templates included with plugin
 * Ref: https://wordpress.stackexchange.com/questions/255804/how-can-i-load-a-page-template-from-a-plugin/255820
 * Ref: https://developer.wordpress.org/reference/classes/wp_theme/get_page_templates/
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
if ( ! class_exists('Time_Tracker_Load_Page_Templates') ) {

    
    /**
     * Class
     * 
     */
    class Time_Tracker_Load_Page_Templates {

        
        /**
         * Constructor
         * 
         */
        public function __construct() {
            $this->tt_templates = $this->listTTPageTemplates();
        }


        /**
         * Page Templates Added by TT
         * 
         */        
        private function listTTPageTemplates() {
            $tt_templates = array(
                'tt-page-template.php' => 'Time Tracker Page'
            );
            return $tt_templates;
        }


        /**
         * Include page templates so they appear in back end, edit page dropdown selection
         * 
         */ 
        public function includePageTemplatesInDropdown($page_templates) {
            //include page templates when WP "gets page templates"
            $page_templates = array_merge($page_templates, $this->tt_templates);
            return $page_templates;
        }


        /**
         * Help WP find the page template since it'll look in the theme directory
         * 
         */ 
        public function redirectToPluginDirectory($template) {
            //only change the directory if a Time Tracker template is being used
            $post = get_post();
            if ($post !== null) {
                $chosen_template = get_post_meta($post->ID, '_wp_page_template', true);
                $newlocation = "";
                foreach ($this->tt_templates as $tt_filename => $tt_name) {        
                    if (basename($chosen_template) == $tt_filename) {
                        $newlocation = TT_PLUGIN_DIR . 'templates/' . $tt_filename;
                    }
                } //check each tt template
                if (file_exists($newlocation)) {
                    return $newlocation;
                }
            }
            return $template;
        }

    } //close class
}  //close if class exists

$addTemplates = new Time_Tracker_Load_Page_Templates;
add_filter('theme_page_templates', [$addTemplates, 'includePageTemplatesInDropdown']);
add_filter('template_include', [$addTemplates, 'redirectToPluginDirectory']);