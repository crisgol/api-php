<?php
	// Required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	include_once '../config/core.php';
	include_once '../shared/utilities.php';
	include_once '../config/database.php';
	include_once '../objects/product.php';

	// Utilities
	$utilities = new Utilities();

	// Instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();

	// Initialize object
	$product = new Product($db);

	// Query products
	$stmt = $product->readPaging($from_record_num, $records_per_page);
	$num = $stmt->rowCount();

	// Check if more than 0 records found
	if ( $num>0 ) {

		whLog('Call to read_paging.');

		// Products array
		$products_arr=array();
	    $products_arr["records"]=array();
	    $products_arr["paging"]=array();

		// Retrieve table contents
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// Extract row
			// This will make $row['name'] to just $name only
			extract($row);

			$product_item=array(
				"id" => $id,
				"name" => $name,
				"description" => html_entity_decode($description),
				"price" => $price,
				"category_id" => $category_id,
				"category_name" => $category_name
			);

			array_push($products_arr["records"], $product_item);
		}

		// Include paging
		$total_rows = $product->count();
		$page_url="{$home_url}product/read_paging.php?";
		$paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
		$prodcuts_arr["paging"]=$paging;

		// Set response code - 200 OK
		http_response_code(200);
		echo json_encode($products_arr);
	} else {
		// Set response code - 400 Not found
		http_response_code(404);
		echo json_encode(array("message" => "No products found."));
	}
?>