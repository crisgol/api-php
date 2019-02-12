<?php
	// Required headers
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header('Content-Type: application/json');

	include_once '../config/database.php';
	include_once '../objects/product.php';

	// Get database connection
	$database = new Database();
	$db = $database->getConnection();

	// Prepare product object
	$product = new Product($db);

	// Set ID property of record to read
	$product->id = isset($_GET['id']) ? $_GET['id'] : die();

	// Read the details of the product to be edited
	$product->readOne();

	if($product->name!=null) {
		// Create array
		$product_arr = array(
			"id" => $product->id
			, "name" => $product->name
			, "description" => $product->description
			, "price" => $product->price
			, "category_id" => $product->category_id
			, "category_name" => $product->category_name
		);

		// Set response code - 200 OK
		http_response_code(200);
		echo json_encode($product_arr);
	} else {
		// Set response code - 404 not found
		http_response_code(404);
		echo json_encode(array("message" => "Product does not exist."));
	}
?>
