<?php
class Category {

	private $conn;
	private $table_name = "categories";

	// Object properties
	public $id;
	public $name;
	public $description;
	public $created;

	public function __construct($db) {
		$this->conn = $db;
	}

	// Used by select drop-down list
	public function read() {
		$query = "SELECT
                id, name, description
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
	}

	// Used by select drop-down list
	public function readAll() {
		$query = "SELECT
                    id, name, description
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
	}
}
?>