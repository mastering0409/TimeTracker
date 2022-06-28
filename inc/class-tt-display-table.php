<?php
/**
 * Class Time_Tracker_Display_Table
 *
 * Takes data, fields, arguments, etc and creates html table output for front end
 * 
 * @param $fields       Array of fields with parameters
 * @param $data         Array of data, each item in array can be an OBJECT (result of sql) or an ARRAY - code should handle either
 * @param $table_args   Arguments for entire table (ie: classes)
 * @param $table_name   Name for entire table (id: id)
 * @param $table_key    If data inside table is editable, this is the primary key fieldname for the table
 * @return string       Html output including data in html table
 * 
 * @since 1.1.1
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Check if class exists
 * 
 */
if ( ! class_exists('Time_Tracker_Display_Table') ) {
    
    /**
     * Class
     * 
     */
    class Time_Tracker_Display_Table {

        public function __construct() {
        }


		/**
         * Create Entire HTML Table
         * 
         */
        public function create_html_table($fields, $data, $table_args, $table_name, $table_key) {
            if ($data) {
                $html_out = "<div style='text-align:center; padding-bottom: 0; margin-bottom: 0;'>Note: Gray shaded cells can't be changed.</div>";
				$html_out .= $this->start_table($table_args);
				$html_out .= $this->create_header_row($fields);
				$html_out .= $this->create_data_rows($fields, $data, $table_name, $table_key);
				$html_out .= $this->close_table();
			} else {
                $html_out = "<p style='font-weight:bold;padding-left:20px;'>Nothing to Display</p>";
            }
            return $html_out;
        }	 
        
        
        /**
         * Add Arguments to Table Tag
         * 
         */
        private function add_arguments($arguments) {
            $args = "";
            foreach ($arguments as $type=>$value) {
                $args .= $type . "='";
                
                if (is_array($value)) {
                    foreach ($value as $ind_value) {
                        $args .= esc_attr($ind_value) . " ";
                    }
                } else {
                    $args .= esc_attr($value) . " ";
                }
                
                $args = trim($args) . "' ";
            }
            return " " . trim($args);
        }


        /**
         * Start Table Tag
         * 
         */
        public function start_table($head_arguments = null) {
            $tbl = "<table";
            if ($head_arguments) {
                $tbl .= $this->add_arguments($head_arguments);
            }
            $tbl .=">";
            return $tbl;
        }
        
        
        /**
         * Close Table Tag
         * 
         */
        public function close_table() {
            return "</table>";
        }


        /**
         * Start Row
         * 
         */
        public function start_row($row_arguments = null) {
            $row = "<tr";
            if ($row_arguments) {
                $row .= $this->add_arguments($row_arguments);
            }
            $row .=">";
            return $row;
        }


        /**
         * Close Row
         * 
         */
        public function close_row() {
            return "</tr>";
        }


        /**
         * Start Header Data
         * 
         */
        public function start_header_data($header_data_arguments = null) {
            $th = "<th";
            if ($header_data_arguments) {
                $th .= $this->add_arguments($header_data_arguments);
            }
            $th .= ">";
            return $th;
        }


        /**
         * Close Header Data
         * 
         */
        public function close_header_data() {
            return "</th>";
        }


        /**
         * Start Data
         * 
         */
        public function start_data($data_arguments = null) {
            $td = "<td";
            if ($data_arguments) {
                $td .= $this->add_arguments($data_arguments);
            }
            $td .= ">";
            return $td;
        }


        /**
         * Close Data
         * 
         */
        public function close_data() {
            return "</td>";
        }


        /**
         * Create Header Row
         * 
         */
        private function create_header_row($fields) {
            $header_row = $this->start_row();
            $columns = $fields;
            foreach ($columns as $name=>$details) {
                $header_row .= $this->start_header_data() . $name . $this->close_header_data();                
            }
            $header_row .= $this->close_row();
            return $header_row;
        }


        /**
         * Sanitize, Escape, and Display Data
         * 
         */
        private function display_data_in_cell($data_type, $display_value) {
            if ($data_type == "text") {
                $data_display = esc_html(sanitize_text_field($display_value));
            } elseif ($data_type == "long text") {
                $data_display = stripslashes(wp_kses_post(nl2br($display_value)));
            } elseif ($data_type == "date") {
                $formatted_date = tt_format_date_for_display(sanitize_text_field($display_value), "date_only");
                $data_display = esc_html($formatted_date);
            } elseif ($data_type == "date and time") {
                $formatted_date = tt_format_date_for_display(sanitize_text_field($display_value), "date_and_time");
                $data_display = esc_html($formatted_date);
            }  elseif ($data_type == "email") {
                $data_display = esc_html(sanitize_email($display_value));
            } else {
                $data_display = esc_html(sanitize_text_field($display_value));
            }
            return $data_display;
        }


        /**
         * Get Arguments for Data Cell
         * 
         */
        private function get_cell_args($details, $item, $sql_fieldname, $table_name, $table_key) {
            $args = [];
            $args["id"] = $details["id"];
            $args["class"] = [];

            if (array_key_exists("class", $details)) {
                if (is_array($details["class"])) {
                    foreach ($details["class"] as $ind_class) {
                        array_push($args["class"], $ind_class);
                    }
                }
                if ($details["class"] <> "") {
                    array_push($args["class"], $details["class"]);                 
                }
            }
            
            if ($details["editable"]) {
                array_push($args["class"], "editable");
                $args["contenteditable"] = "true";
                $table_key_value = is_array($item->$table_key) ? $item->$table_key["value"] : $item->$table_key;
                $args["onBlur"] = "updateDatabase(this, '" . $table_name . "', '" . $table_key . "', '" . $sql_fieldname . "', '" . $table_key_value. "')";
            } else {
                array_push($args["class"], "not-editable");
            }

            if ( strlen($details["columnwidth"]) > 0 ) {
                array_push($args["class"], "tt-col-width-" . $details["columnwidth"] . "-pct");
            }

            $item_sql_fieldname_details = is_object($item) ? $item->$sql_fieldname : $item[$sql_fieldname];
            if ( is_array($item_sql_fieldname_details) ) {
                if ( array_key_exists("class", $item_sql_fieldname_details) ) {
                    array_push($args["class"], $item_sql_fieldname_details["class"]);
                }
                if ( array_key_exists("button", $item_sql_fieldname_details) ) {
                    if (is_array($item_sql_fieldname_details["button"])) {
                        $args["button"] = [];
                        foreach ($item_sql_fieldname_details["button"] as $ind_button) {
                            array_push($args["button"], $ind_button);
                        }
                    } else {
                        $args["button"] = $item_sql_fieldname_details["button"];
                    }
                }
                if ( array_key_exists("icon", $item_sql_fieldname_details) ) {
                    if (is_array($item_sql_fieldname_details["icon"])) {
                        $args["icon"] = [];
                        foreach ($item->$item_sql_fieldname_details["icon"] as $ind_icon) {
                            array_push($args["icon"], $ind_icon);
                        }
                    } else {
                        $args["icon"] = $item_sql_fieldname_details["icon"];
                    }
                }
            }
            return $args;
        }


        /**
         * Create All Data Rows
         * 
         */
        private function create_data_rows($fields, $data, $table_name, $table_key) {
            $data_rows = "";
            foreach ($data as $item) {
                $row = $this->create_data_row($fields, $item, $table_name, $table_key);
                $data_rows .= $row;
            }
            return $data_rows;
        }


        /**
         * Create Single Data Row
         * 
         */
        private function create_data_row($fields, $item, $table_name, $table_key) {
            $row = $this->start_row();
            foreach ($fields as $header=>$field_details) {
                $row .= $this->create_data_cell($field_details, $item, $table_name, $table_key);
            }
            $row .= $this->close_row();
            return $row;
        }


        /**
         * Create Single Data Cell
         * 
         */
        private function create_data_cell($field_details, $item, $table_name, $table_key) {
            $sql_fieldname = $field_details["fieldname"];
            $args = $this->get_cell_args($field_details, $item, $sql_fieldname, $table_name, $table_key);
            
            if ( is_object($item) ) {
                $display_value = is_array($item->$sql_fieldname) ? $item->$sql_fieldname["value"] : $item->$sql_fieldname;
            } elseif ( is_array($item) ) {
                $display_value = is_array($item[$sql_fieldname]) ? $item[$sql_fieldname]["value"] : $item[$sql_fieldname];
            }

            $cell = $this->start_data($args);
            $cell .= $this->display_data_in_cell($field_details["type"], $display_value);
            $cell .= $this->add_button_to_cell($args);
            $cell .= $this->add_icon_to_cell($args);
            $cell .= $this->close_data();
            return $cell;
        }


        /**
         * Add Button to Cell
         * 
         */
        private function add_button_to_cell($args) {
            $button = "<br/>";
            if (array_key_exists("button", $args)) {
                if (is_array($args["button"])) {
                    foreach ($args["button"] as $ind_button) {
                        $button .= $ind_button;
                    }
                } else {
                    $button .= $args["button"];
                }
            }
            return $button;            
        }


        /**
         * Add Icon to Cell
         * 
         */
        private function add_icon_to_cell($args) {
            $icon = "";
            if (array_key_exists("icon", $args)) {
                $icon .= "<br/>";
                if (is_array($args["icon"])) {
                    foreach ($args["icon"] as $ind_icon) {
                        $icon .= $ind_icon;
                    }
                } else {
                    $icon .= $args["icon"];
                }
            }
            return $icon;            
        }
	
		


    }  //close class
 }  //close if class exists