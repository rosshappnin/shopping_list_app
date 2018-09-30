<?php
// get posted data
if($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    echo json_encode(
        ["message" => "Wrong request method, use DELETE."]
    ); die;
}

// include database and object file
include_once '../../config/database.php';
include_once '../../objects/item.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare item object
$item = new item($db);

// get the item id
$data = json_decode(file_get_contents("php://input"));

if ( !empty($data->id) && is_numeric($data->id) ) {
// set item id to be deleted
$item->id = $data->id;
} else {
    echo json_encode(
        array("message" => "No, or invalid id given.")
    );
    die;
}

// delete the item
if($item->delete()) {
    echo json_encode(
        ["message" => "item was deleted."]
    ); die;
}

// if unable to delete the item, tell the user
else {
    echo json_encode(
        ["message" => "Unable to delete item."]
    ); die;
}
?>
