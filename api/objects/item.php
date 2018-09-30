<?php
class Item {
 
    // database table name
    const TABLE_NAME = "items";
 
    // object properties
    public $id;
    public $list_id;
    public $name;
    public $price;
    public $is_checked;
 
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
}