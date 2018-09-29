/**
 *  On page load start the app
 */
$(function(){
    read();
});

// stores the state of the shopping list
var listObj;

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