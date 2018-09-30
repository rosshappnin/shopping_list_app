<?php
// get posted data
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    echo json_encode(
        ["message" => "Wrong request method, use GET."]
    ); die;
}

// include database and object files
include_once '../config/database.php';
include_once '../objects/shoppingList.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();


// initialize object
$Shoppinglist = new ShoppingList($db);


// check for id
if ( isset($_GET['id']) && ctype_digit($_GET['id']) )
{
    // set ID property of the list to be read
    $Shoppinglist->id = $_GET['id'];
} else {
    echo json_encode(
        array("message" => "No, or invalid id given.")
    ); die;
}

if ($Shoppinglist->fetchListProperties()) {
    $Shoppinglist->fetchListItems();
    // return shopping list data as JSON object
    echo json_encode($Shoppinglist);
} else {
    echo json_encode(
        array("message" => "No, list found with the id given.")
    ); die;
}
?>