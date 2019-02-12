<?php
	// Required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	// Include database and object files
	include_once '../config/database.php';
	include_once '../objects/product.php';

	// Instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();

	// Initialize object
	$product = new Product($db);

	// Query products
	$stmt = $product->read();
	$num = $stmt->rowCount();

	// Checkif more than 0 records found
	if($num>0) {

		// Products array
		$products_arr = array();
		$products_arr["records"]=array();

		// Retrieve our table contents
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

		http_response_code(200);

		echo json_encode($products_arr);
	} else {
		// Set response if nothing found
		http_response_code(404);

		// Tell the user no products found
		echo json_encode(array("message" => "No products found."));
	}
