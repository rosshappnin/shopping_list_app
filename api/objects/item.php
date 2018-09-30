<?php
class Item {
 
    // database table name
    const TABLE_NAME = "items";

    // database connection
    private $conn;
 
    // object properties
    public $id;
    public $list_id;
    public $name;
    public $price;
    public $is_checked;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
 
    /**
     * Returns an array of Items with their list_id = $list_id
     * 
     * @param PDO $conn - PDO database connection
     * @param int $list_id - The list Id used to filter items by
     * 
     * @return itemsArr an array of 0 or more Item objects
     */
    public static function fetchItemsByListId($conn, $list_id)
    {
        // select by list id query
        $query = "SELECT
                    id, list_id, name, price, is_checked
                FROM
                    " . item::TABLE_NAME . " 
                WHERE
                    list_id = ?
                ORDER BY
                    id ASC";
    
        // prepare query statement
        $stmt = $conn->prepare($query);

        // sanitize
        $list_id = (int) htmlspecialchars(strip_tags($list_id));

        // bind id of list to be updated
        $stmt->bindParam(1, $list_id);
    
        // execute query
        $stmt->execute();
    
        $itemsArr = [];

        $num = $stmt->rowCount();

        // check if more than 0 record found
        if($num > 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                // create an Item object for each result
                $item = new Item($conn);

                $item->id = $row['id'];
                $item->list_id = $row['list_id'];
                $item->name = $row['name'];
                $item->price = $row['price'];
                $item->is_checked = $row['is_checked'];

                array_push($itemsArr, $item);
            }
        }

        // return an array of results
        return $itemsArr;
    }


    /**
     * Inserts a new Item into the database
     * Uses the Item object properties as values
     * 
     * @return boolean returns true if succesful, false otherwise
     * 
     */
    function create() {

        // query to insert record
        $query = "INSERT INTO
                    " . item::TABLE_NAME . "
                SET
                list_id = :list_id,
                name = :name,
                price = :price,
                is_checked = :is_checked";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->list_id = (int) htmlspecialchars(strip_tags($this->list_id));
        $this->name = (string) htmlspecialchars(strip_tags($this->name));
        $this->price = (float) htmlspecialchars(strip_tags($this->price));
        $this->is_checked = (int) htmlspecialchars(strip_tags($this->is_checked));

        // bind values
        $stmt->bindParam(":list_id", $this->list_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":is_checked", $this->is_checked);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

        
    /**
     * Deletes an item from th database where its id = $this->id
     * 
     * @return boolean returns true if succesful, false otherwise
     */
    function delete() {

        // delete query
        $query = "DELETE FROM " . item::TABLE_NAME . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = (int) htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
}