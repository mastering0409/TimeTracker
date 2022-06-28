<?php 
/**
 * Funciton Time_Tracker_Export_Tables
 *
 * Export Time tracker tables for backing up or prior to deleting
 * Called by button on admin screen and run automatically upon plugin deletion
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Admin;


function tt_export_data_function() {

	require_once __DIR__ . '/../inc/class-time-tracker-activator-tables.php';
	$table_list = \Logically_Tech\Time_Tracker\Inc\Time_Tracker_Activator_Tables::get_table_list();

	$path = ABSPATH . "../tt_logs/";
	$filename = 'mysqldump';
	$export_file = $path . "time_tracker_table_export_" . date('Y_m_d') . ".sql";

	$mysqldump_cmd = "mysqldump --user=" . DB_USER . " --password=" . DB_PASSWORD . " --host=" . DB_HOST . " " . DB_NAME . " --tables ";
	foreach ($table_list as $table_name) {
		$mysqldump_cmd .= $table_name . " ";
	}
	$mysqldump_cmd = substr($mysqldump_cmd,0,-1);

	if (PHP_OS_FAMILY === "Windows") {
		//echo "Running on Windows";
		$opened_file = fopen($export_file, "w");
		chmod($export_file, 0744);
		exec($mysqldump_cmd . " > " . $export_file);
		fclose($opened_file);
		chmod($export_file, 0644);
	} elseif (PHP_OS_FAMILY === "Linux") {
		//echo "Running on Linux";
		$mysqldump_cmd_str = $mysqldump_cmd . " > " . $export_file;
		$file = tempnam($path, $filename);
		file_put_contents($file, $mysqldump_cmd_str);
		chmod($file, 0744);
		passthru($file);
		unlink($file);
	}
	
}