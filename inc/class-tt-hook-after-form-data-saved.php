<?php
/**
 * Class Time_Tracker_After_Form_Data_Saved
 *
 * Action to happen after form data saved to database (if necessary)
 * Either go back to TT homepage or stay on same page and filter data if it's a filter form
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
if ( ! class_exists('Time_Tracker_After_Form_Data_Saved') ) {
    
    
    /**
     * Main Plugin Class
     * 
     */
    class Time_Tracker_After_Form_Data_Saved {

    
        /**
         * Constructor
         * 
        */
        public function _construct() {

        }


        /**
         * Decide what to do after data saved to db (if necessary)
         * 
        */
        public function after_save() {
            //wp_enqueue_script( 'start_timer_for_task', TT_PLUGIN_WEB_DIR_INC . 'js/start_timer_for_task.js', array(), null, true);
            //add_action('wp_enqueue_scripts', array($this,'time_tracker_scripts'));
            ?>
            <script type='text/javascript'>
    
                document.addEventListener('DOMContentLoaded', function () {  //make sure doc is done loading before looking for element
                
                    document.addEventListener( 'wpcf7mailsent', function (event) {
                    
                        //console.log(event);
                        var str = window.location.pathname;
                        //var tthome = document.location.origin + '/time-tracker/';
                        var formtype = "";
                        let startworking = false;
                        var client = "";
                        var taskdesc = "";

                        for (var i=0; i < event.detail.inputs.length; i++) {
                            if(event.detail.inputs[i].name == 'form-type') {
                                formtype = event.detail.inputs[i].value;
                            } else if( (event.detail.inputs[i].name == 'what-next') && (event.detail.inputs[i].value == 'StartWorking') ) {
                                startworking = true;
                            } else if(event.detail.inputs[i].name == 'client-name') {
                                client = event.detail.inputs[i].value;
                            } else if(event.detail.inputs[i].name == 'task-description') {
                                taskdesc = event.detail.inputs[i].value;
                            }
                        }

                        //if we're filtering data
                        if (formtype == 'filter') {
                            tt_filter_time_log(event);
                        
                        //we added a new task and want to start working
                        } else if (startworking == true) {
                            <?php
                            //if user clicked start task forward to time log page, filling data with last entered task
                            global $wpdb;
                            $task_row = $wpdb->get_results('SELECT max(TaskID) as TaskID FROM tt_task');
                            catch_sql_errors(__FILE__, __FUNCTION__, $wpdb->last_query, $wpdb->last_error);
                            //task hasn't saved yet so predict new task number
                            $taskid = $task_row[0]->TaskID + 1;
                            ?>
                            var recordtime = <?php $home = "'" . TT_HOME . "'"; echo $home; ?> + 'new-time-entry/?client-name=' + encodeURIComponent(client) + '&task-name=' + '<?php echo esc_attr($taskid); ?>' + '-' + encodeURIComponent(taskdesc);
                            location = recordtime;

                        //if it's a time tracker form submission, go back to tt homepage after submit
                        } else if (str.includes('time-tracker')) {
                            location = <?php $home = "'" . TT_HOME . "'"; echo $home; ?>;
                        }
                    
                    }, false );  //end wpcf7submit event listener
                
                }, false );  //end domcontentloaded event listener
            </script>
            <?php
        }   //after save function

    }  //close class
}   //if class does not exist

$aftersave = new Time_Tracker_After_Form_Data_Saved();

add_action( 'wp_footer', array($aftersave, 'after_save') );