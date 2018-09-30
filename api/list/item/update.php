<?php
if($_SERVER['REQUEST_METHOD'] != 'PUT') {
    echo json_encode(
        ["message" => "Wrong request method, use PUT instead."]
    ); die;
}

// include database and object files
include_once '../../config/database.php';
include_once '../../objects/item.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare item object
$item = new item($db);

// get the data sent in the request
$data = json_decode(file_get_contents("php://input"));

if(!isset($data->id) || 
    !isset($data->name) ||
    !isset($data->price) ||
    !isset($data->is_checked) ||
    !isset($data->position)
    ){
        echo json_encode(
            ["message" => "Missing item properties."]
        ); die;
}

if ( is_numeric($data->id) ) {
    // set ID property of the item to be updated
    $item->id = $data->id;
} else {
    echo json_encode(
        array("message" => "Invalid id given.")
    );
    die;
}

// set item property values
$item->name = $data->name;
$item->price = $data->price;
$item->is_checked = $data->is_checked;
$item->position = $data->position;

// update the item
if($item->update()){
    echo json_encode(
        ["message" => "item was updated."]
    ); die;
}

// if unable to update the item, tell the user
else{
    echo json_encode(
        ["message" => "Unable to update item."]
    ); die;
}
?>