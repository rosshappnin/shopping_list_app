<?php
if($_SERVER['REQUEST_METHOD'] != 'PUT') {

    echo json_encode(
        ["message" => "Wrong request method, use PUT instead."]
    ); die;
}

// include database and object files
include_once '../config/database.php';
include_once '../objects/shoppinglist.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ShoppingList object
$Shoppinglist = new ShoppingList($db);

// get the data sent in the request
$data = json_decode(file_get_contents("php://input"));

if(!isset($data->id) || 
    !isset($data->max_spend) ||
    !isset($data->is_alert)) {
        echo json_encode(
            ["message" => "Missing list properties."]
        ); die;
}

// set ID property of the Shoppinglist to be updated
if (is_numeric($data->id) ) {
    $Shoppinglist->id = $data->id;
} else {
    echo json_encode(
        array("message" => "invalid id given")
    ); die;
}

// set ShoppingList property values
$Shoppinglist->max_spend = $data->max_spend;
$Shoppinglist->is_alert = $data->is_alert;

// update the ShoppingList
if($Shoppinglist->update()){
    echo json_encode(
        ["message" => "List was updated."]
    ); die;
}

// if unable to update the ShoppingList, tell the user
else {
    echo json_encode(
        ["message" => "Unable to update list."]
    ); die;
}
?>
