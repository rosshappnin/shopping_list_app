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

/**
 * 1 - Fetches the shopping list data from the server,
 * 2 - Assigns the new data to the listObj
 * 3 - then calls refresh.
 */
function read() {

    $.ajax({
        method: 'GET',
        url: 'api/list/data.json',
        contentType: 'application/json; charset=utf-8',
        success: function(response) {
            console.log(response);

            // sets up the shopping list obj with data retrieved from the server
            listObj = (response);
           
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
    
        tbodyEL.append('\
            <tr>\
                <td><input type="text" class="name" value="' + item.name + '"></td>\
                <td><input type="number" step="any" class="price" value="' + item.price + '"></td>\
            </tr>\
        '); 
    });
}