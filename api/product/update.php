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

// Get ID of product to be edited
$data = json_decode(file_get_contents("php://input"));

// Set ID of property of product to be edited
$product->id = $data->id;

// Set product property values
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// Check if product exists
$product->readOne();

if ( $product->id!=null ) {
	// Update the product
	if($product->update()){

	    // set response code - 200 ok
	    http_response_code(200);
	    echo json_encode(array("message" => "Product was updated."));
	} else {
	 
	    // set response code - 503 service unavailable
	    http_response_code(503);
	    echo json_encode(array("message" => "Unable to update product."));
	}
} else {
	// Set response code - 404 Not found
	http_response_code(404);
	echo json_encode(array("message" => "Product not found."));		
}
?>