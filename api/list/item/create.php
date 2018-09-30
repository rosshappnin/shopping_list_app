<?php
// get posted data
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(
        ["message" => "Wrong request method, use POST."]
    ); die;
}

// get database connection
include_once '../../config/database.php';

// instantiate item object
include_once '../../objects/item.php';

$database = new Database();
$db = $database->getConnection();

$item = new Item($db);

// get the new item values from the request
$data = json_decode(file_get_contents("php://input"));

if(!isset($data->list_id) ||
    !isset($data->name) ||
    !isset($data->price) ||
    !isset($data->is_checked) ||
    !isset($data->position)
  ){
        echo json_encode(
            ["message" => "Missing item properties."]
        ); die;
    die;
}

// set item property values
$item->list_id = $data->list_id;
$item->name = $data->name;
$item->price = $data->price;
$item->is_checked = $data->is_checked;
$item->position = $data->position;

// create the item
if($item->create()){
    echo json_encode(
        ["message" => "item was created."]
    ); die;
}

// if unable to create the item, tell the user
else{
    echo json_encode(
        ["message" => "Unable to create item."]
    ); die;
}

?>
