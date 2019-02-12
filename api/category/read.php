<?php
// Required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/category.php';

// Instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// Initialize object
$category = new Category($db);

// Query categories
$stmt = $category->read();
$num = $stmt->rowCount();

// Check if more than 0 records found
if ( $num > 0 ) {
	// Products array
	$categories_arr=array();
	$categories_arr["records"]=array();

	// Retrieve table contents
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$category_item=array(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description)
		);

		array_push($categories_arr["records"], $category_item);
	}

	// Set response code - 200 OK
	http_response_code(200);
	echo json_encode($categories_arr);
} else {
	// Set response code - 404 not found
	http_response_code(404);
	echo json_encode(array("message" => "No categories found."));
}
?>