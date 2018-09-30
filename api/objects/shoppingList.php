<?php
require_once '../objects/item.php';

class ShoppingList {
 
    // database connection and table name
    private $conn;
    private $table_name = "list";
 
    // object properties
    public $id;
    public $title;

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
                    id, title
                FROM
                    " . $this->table_name . "
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
            $this->title = $row['title'];;

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

}