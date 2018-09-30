<?php
require_once '../objects/item.php';

class ShoppingList {

    // database table name
    const TABLE_NAME = "list";
 
    // database connection
    private $conn;
 
    // object properties
    public $id;
    public $title;
    public $max_spend;
    public $is_alert;

    public $items = [];
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Fetches the ShoppingList properties from the database
     * For a single list where the list id = $this->id.
     * Sets the properties of the current object
     * 
     * @return boolean returns true if a list is found, false otherwise
     */
    function fetchListProperties()
    {
        // read ShoppingList properties
        $query = "SELECT
                    id, title, max_spend, is_alert
                FROM
                " . ShoppingList::TABLE_NAME . "
                WHERE
                    id = ?
                LIMIT
                    0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = (int) htmlspecialchars(strip_tags($this->id));
   
        // bind id of list to be read
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();

        $num = $stmt->rowCount();
 
        // check if more than 0 record found
        if($num > 0) {
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set values to object properties
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->max_spend = $row['max_spend'];
            $this->is_alert = $row['is_alert'];

            return true;

        } else {
            
            return false;
        }
    }

    /**
     * Fetches a list of items with list_id = $this->id from the database
     * Sets the items[] array with the results
     */
    function fetchListItems()
    {       
        $this->items = Item::fetchItemsByListId($this->conn, $this->id);
    }

    /**
     * Commits updates of the list properties to the database
     */
    function update()
    {
        // update query
        $query = "UPDATE
            " . ShoppingList::TABLE_NAME . "
        SET
            max_spend = :max_spend,
            is_alert = :is_alert
        WHERE
            id = :id";

        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id = (int) htmlspecialchars(strip_tags($this->id));
        $this->max_spend = (string) htmlspecialchars(strip_tags($this->max_spend));
        $this->is_alert = (int) htmlspecialchars(strip_tags($this->is_alert));
        
        // bind values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(":max_spend", $this->max_spend);
        $stmt->bindParam(":is_alert", $this->is_alert);
        
        // execute query
        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}