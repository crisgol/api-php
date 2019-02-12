<?php
	// Required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	include_once '../config/core.php';
	include_once '../config/database.php';
	include_once '../objects/product.php';

	// Instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();

	// Initialize object
	$product = new Product($db);

	// GET keywords
	$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

	// Query products
	$stmt = $product->search($keywords);
	$num = $stmt->rowCount();

	// Check if more than 0 records found
	if($num>0) {

		// Products array
		$products_arr=array();
		$products_arr["records"]=array();

		// Retrieve table contents
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// Extract row.
			// This will amke $row['name'] to just $name only
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

		// Set response code - 200 OK
		http_response_code(200);
		echo json_encode($products_arr);
	} else {
		// Set response code - 404 Not found
		http_response_code(404);
		echo json_encode(array("message" => "No products found."));
	}
?>