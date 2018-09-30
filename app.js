/**
 *  On page load start the app
 */
$(function(){
    read();

    
    /**
     * Add item button click handler
     * 
     * 1 - Invoked when the 'Add item buttom is clicked
     * 2 - Calls the create item function
     * 3 - Clears the form inputs.
     */ 
    $('#form-add-item').on('submit', function(event){
        event.preventDefault();
        
        createItem();
        
        // clear inputs
        $('#input-name').val('');
        $('#input-price').val('');
    });

    
    // Item check button click handler
    $('#table-items').on('click', '.button-check', function() {
        var rowEL = $(this).closest('tr');

        // toggle checked class
        rowEL.toggleClass("checked");

        updateItem(rowEL);
    });


    // Delete item button click handler
    $('#table-items').on('click', '.button-delete', function() {
        var rowEL = $(this).closest('tr');
        deleteItem(rowEL);
    });


});

// stores the state of the shopping list
var listObj;


/**
 * 1 - Gathers item data submitted in the add new item form
 * 2 - Creates a new item object with the data
 * 3 - Adds the item object to the ListObj items array
 */
function createItem() {
    var name = $('#input-name').val();
    var price = $('#input-price').val();

    // convert invalid price values to 0
    if(isNaN(price) || price.length == 0) {
        price = 0;
    }

    // format price to two decimal places
    price =  parseFloat(price).toFixed(2);

    // create item object
    var item = {'name' : name, 'price' : price};

    // adds the object to the listObj items array
    listObj['items'].push(item);
    
    // log the new listObj to console
    console.log(listObj);

    refresh();
}

var listObj; // stores the state of the shopping list
const LIST_ID = 1; // Temporaliy (whilst in developemt) hard code the list id

/**
 * 1 - Updates the specified rowEL item
 * 2 - calls refresh
 * 
 * @param jquery rowEL 
 */
function updateItem(rowEL){
    
    // get the index of the item
    var index = rowEL.attr('data-index');

    // set the items is_checked property to either 0 or 1, 
    // depending on whether or not it has the 'checked' class 
    var newIsChecked = (rowEL.hasClass("checked") ? 1 : 0);

    // update the item
    listObj['items'][index].is_checked = newIsChecked;

    // log the updated listObj to console
    console.log(listObj);
    
    refresh();
}


/**
 * 1 - Removes an item from listObj by index
 * 2 - calls refresh
 * 
 * @param jQuery rowEL The tr row element of the item
 */
function deleteItem(rowEL) {

    // get the index of the item
    var index = rowEL.attr('data-index');

    // remove the item at the specified index
    listObj['items'].splice(index, 1);

    // log the updated listObj to console
    console.log(listObj);

    refresh();
}

/**
 * 1 - Fetches the shopping list data from the server,
 * 2 - Assigns the new data to the listObj
 * 3 - then calls refresh.
 */
function read() {

    $.ajax({
        method: 'GET',
        url: 'api/list/read.php?id=' + LIST_ID,
        contentType: 'application/json; charset=utf-8',
        success: function(response) {
            console.log(response);

            // sets the shopping list data
            listObj = $.parseJSON(response);

            console.log(listObj);

           refresh();
        },
        error: function(response) {
            console.log("ERROR:");
            console.log(response);
        }
    });
}


/**
 * Rebuilds the page using data from the listObj
 */
function refresh() {

    // set list title
    $('#list-title').text(listObj.title); 

    // extract the items from the list object
    var items = [];

    // check that the listObj has an items property, if not exit.
    if (listObj.hasOwnProperty("items")) {
        items = listObj.items;
    } else {
       return false;
    }

    // select table body element
    var tbodyEL = $('#table-items tbody');
    
    // clear table body
    tbodyEL.html('');

    // foreach list item, add it to the table
    $.each(items, function(i, item) {

        // if item is checked add class checked.
        var trClass = (item.is_checked == 1 ? 'class="checked"' : '');
        
        tbodyEL.append('\
            <tr ' + trClass + ' data-index="' + i +'">\
                <td><input type="text" class="name" value="' + item.name + '"></td>\
                <td><input type="number" step="any" class="price" value="' + item.price + '"></td>\
                <td>\
                    <button class="button-check"><i class="glyphicon glyphicon-ok"></i>Check</button>\
                    <button class="button-delete"><i class="glyphicon glyphicon-trash"></i>Delete</button>\
                </td>\
            </tr>\
        '); 
    });
}