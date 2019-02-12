<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/product.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Prepare product object
$product = new Product($db);

// Get product ID
$data = json_decode(file_get_contents("php://input"));

// Set product ID to be deleted
$product->id = $data->id;

// Check if product exists
$product->readOne();

if ( $product->id!=null ) {
	// Delete the product
	if($product->delete()) {
		// Response code - 200 OK
		http_response_code(200);
		echo json_encode(array("message" => "Product was deleted."));
	} else {
		// Response code - 503 Service unavailable
		http_response_code(503);
		echo json_encode(array("message" => "Unable to delete the product."));
	}
} else {
	// Set response code - 404 Not found
	http_response_code(404);
	echo json_encode(array("message" => "Product not found."));		
}
?>