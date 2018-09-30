var listObj; // stores the state of the shopping list
const LIST_ID = 1; // Temporaliy (whilst in developemt) hard code the list id

/**
 *  On page load start the app
 */
$(function(){
    read();

    /*******************************************/
    /*  Event handlers
    /*******************************************/

    // Sets up JQuery-UI for drag and drop, re-ordrring of items
    // On Sortable update handler
    $('#table-items tbody').sortable({
        update: function(event, ui){
            $(this).children().each(function(index) {
                var rowEL = $(this);
                if (rowEL.attr('data-position') != (index + 1)) {
                    rowEL.attr('data-position', (index + 1));
                    updateItem(rowEL);
                }
            });
        }
    });

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

    // Item name changed handler
    $('#table-items').on('change', '.name', function() {
        var rowEL = $(this).closest('tr');
        updateItem(rowEL);
    });

    // Item price changed handler
    $('#table-items').on('change', '.price', function() {
        var rowEL = $(this).closest('tr');
        // only update price if greater than zero
        var newPrice = rowEL.find('.price').val();
        if (newPrice < 0) { 
            newPrice = 0;
            rowEL.find('.price').val(newPrice.toFixed(2)); 
        } else {
            updateItem(rowEL);
        }
    })


    // Delete item button click handler
    $('#table-items').on('click', '.button-delete', function() {
        var rowEL = $(this).closest('tr');
        deleteItem(rowEL);
    });

});


/*******************************************/
/*  Ajax calls to the database
/*******************************************/


/**
 * 1 - Gathers item data submitted in the add new item form
 * 2 - Creates a new item object with the data
 * 3 - Adds the item object to the database
 */
function createItem() {
    var name = $('#input-name').val();
    var price = $('#input-price').val();
    var is_checked = 0;
    var position = $('#table-items tbody tr').length + 1;

    $.ajax({
        url: 'api/list/item/create.php',
        method: 'POST',
        contentType: 'application/json',

        data: JSON.stringify({list_id: LIST_ID,
                                name: name,
                                price: price,
                                is_checked: is_checked,
                                position: position
                            }),

        success: function(response) {
            console.log(response);
            read();
        },
        error: function(response) {
            console.log("ERROR:");
            console.log(response);
        }
    });
}

/**
 * 1 - Updates the item in the database with the specified id
 * 2 - calls read on success
 * 
 * @param jquery rowEL The tr row element of the item
 */
function updateItem(rowEL) {
    
    var id = rowEL.attr('data-id');
    var newName = rowEL.find('.name').val();
    var newPrice = rowEL.find('.price').val();
    var newIsChecked = (rowEL.hasClass("checked") ? 1 : 0);
    var newPosition = rowEL.attr('data-position');

    $.ajax({
        url: 'api/list/item/update.php',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({id:id, 
                                name: newName, 
                                price: newPrice,
                                is_checked: newIsChecked,
                                position: newPosition
                            }),
        success: function(response) {
            console.log(response);
            
            read();
        },        
        error: function(response) { 
            console.log("ERROR:");
            console.log(response); 
         }
    });
}


/**
 * 1 - Deletse the item from the databse
 * 2 - calls read
 * 
 * @param jQuery rowEL The tr row element of the item
 */
function deleteItem(rowEL) {

    var id = rowEL.attr('data-id');

    $.ajax({
        url: 'api/list/item/delete.php',
        method: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({id: id}),
        success: function(response) {
            console.log(response);

            read();
        },
        error: function(response) {
            console.log("ERROR:");
            console.log(response);
         }
    });
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
            <tr ' + trClass + ' data-id="' + item.id +'" data-position="'+ item.position +'">\
                <td><i class="glyphicon glyphicon-resize-vertical"></i><input type="text" class="name" value="' + item.name + '"></td>\
                <td><input type="number" step="any" class="price" value="' + item.price + '"></td>\
                <td>\
                    <button class="button-check"><i class="glyphicon glyphicon-ok"></i>Check</button>\
                    <button class="button-delete"><i class="glyphicon glyphicon-trash"></i>Delete</button>\
                </td>\
            </tr>\
        '); 
    });
}