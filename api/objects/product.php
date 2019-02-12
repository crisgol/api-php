<?php
include_once '../config/core.php';

class Product {

	// Database connection and table name
	private $conn;
	private $table_name = "products";

	// Object properties
	public $id;
	public $name;
	public $description;
	public $price;
	public $category_id;
	public $category_name;
	public $created;

	// Constructor with $db as database connection
	public function __construct($db) {
		$this->conn = $db;
	}

	// used for paging products
	public function count() {
		$query = "SELECT COUNT(*) AS total_rows FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	// Create products
	function create() {
		$query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, price=:price, description=:description, category_id=:category_id, created=:created";
 		
		// Prepare query
		$stmt = $this->conn->prepare($query);

		// Sanitize
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->price=htmlspecialchars(strip_tags($this->price));
	    $this->description=htmlspecialchars(strip_tags($this->description));
	    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
	    $this->created=htmlspecialchars(strip_tags($this->created));

		// Bind values
	    $stmt->bindParam(":name", $this->name);
	    $stmt->bindParam(":price", $this->price);
	    $stmt->bindParam(":description", $this->description);
	    $stmt->bindParam(":category_id", $this->category_id);
	    $stmt->bindParam(":created", $this->created);

		// Execute query
		if($stmt->execute()) {
			return true;
		}

		return false;
	}

	// Delete product
	function delete() {

		$query = "DELETE FROM " . $this->table_name ." WHERE id = ?";

		// Prepare the query
		$stmt = $this->conn->prepare($query);

		// Sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// Bind ID of record to delete
		$stmt->bindParam(1, $this->id);

		// Execute
		if($stmt->execute()) {
			return true;
		}

		return false;
	}

	// Read products
	function read() {
		// Select all query
		$query = "SELECT
					c.name AS category_name
					, p.id
					, p.name
					, p.description
					, p.price
					, p.category_id
					, p.created
				FROM
					" . $this->table_name . " p
				LEFT JOIN categories c ON
					p.category_id = c.id
				ORDER BY 
					p.created DESC";

		// Prepare query statement
		$stmt = $this->conn->prepare($query);

		// Execute query
		$stmt->execute();

		return $stmt;
	}

	// Used when filling up the update product form
	function readOne() {
		$query = "SELECT
					c.name AS category_name
					, p.id
					, p.name
					, p.description
					, p.price
					, p.category_id
					, p.created
				FROM
					" . $this->table_name . " p
				LEFT JOIN categories c ON
					p.category_id = c.id
				WHERE 
					p.id = ?
				LIMIT 
					0,1";

		// Prepare query statement
		$stmt = $this->conn->prepare( $query );

		// Bind ID of product to be updated
		$stmt->bindParam(1, $this->id);

		// Execute query
		$stmt->execute();

		// Get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// Set value to object properties
		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
		$this->id = $row['id'];
	}

	// Read products with pagination
	public function readPaging($from_record_num, $records_per_page) {
		$query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            ORDER BY p.created DESC
            LIMIT ?, ?";

      	// Prepare query statement  
        $stmt = $this->conn->prepare( $query );

        // Bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);;

        // Execute the query
        $stmt->execute();

        // Return values from database
        return $stmt;
	}

	// Search products
	function search($keywords) {

		$query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
            ORDER BY
                p.created DESC";

		// Prepare query statement
		$stmt = $this->conn->prepare($query);

		// Sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// Bind parameters
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);

		// Execute query
		$stmt->execute();

		return $stmt;
	}

	// Update the product	
	function update() {
	    $query = "UPDATE
	                " . $this->table_name . "
	            SET
	                name = :name,
	                price = :price,
	                description = :description,
	                category_id = :category_id
	            WHERE
	                id = :id";

		// Prepare query statement
		$stmt = $this->conn->prepare($query);

		// Sanitize
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->price=htmlspecialchars(strip_tags($this->price));
	    $this->description=htmlspecialchars(strip_tags($this->description));
	    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
	    $this->id=htmlspecialchars(strip_tags($this->id));

	    whLog($this->id . ' ' . $this->name . ' ' . $this->price . ' ' . $this->description . ' ' . $this->category_id);

		// Bind new values
		$stmt->bindParam(':name', $this->name);
	    $stmt->bindParam(':price', $this->price);
	    $stmt->bindParam(':description', $this->description);
	    $stmt->bindParam(':category_id', $this->category_id);
	    $stmt->bindParam(':id', $this->id);

    	// Execute query
    	if($stmt->execute()) {
    		return true;
    	}

    	return false;
	}
}