<?php
	// Show error reporting
	ini_set('display errors', 1);
	error_reporting(E_ALL);

	// Home page URL
	$home_url="https://yoursite.com/api/";

	// Page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	// Set number of records per page
	$records_per_page = 5;

	// Calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page;

	// Log File
	// Source: http://www.onlinecode.org/create-log-file-php/
	function whLog($msg) {
		$log_time = date('Y-m-d h:i:sa');
		$log_filename = '../log';

		if (!file_exists($log_filename)) {
			mkdir($log_filename, 0777, true);
		}

		$log_file_data = $log_filename . '/log_' .date('Y-m-d') . '.log';
		file_put_contents($log_file_data, $log_time . ': ' . $msg . "\n", FILE_APPEND);
	}
?>