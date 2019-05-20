jQuery(document).ready(function () {
	jQuery(document).on('click', '.menu-item', function () {
		jQuery('.menu-item').css('background-color', 'white');
		jQuery(this).css('background-color', 'rgb(119, 232, 255)');

		getFoodItem(jQuery(this).attr('id'));
		jQuery('#addItemButton').attr('disabled', false);
	});

	jQuery('#itemQuantity').change(function () {
		updateItemPrice();
	})

	jQuery('#addItemButton').click(function () {
		showItemAddedMessage();
		addRowInOrderSlip();
		addOrderEntry(); //adds the order as hidden input

		removeItemEntries();
	})

	jQuery('.makeorder').click(function () {
		removeItemEntries();

		var productCategory = jQuery(this).attr('id');
		var htmlString = "";

		jQuery.get('/view-menu/' + productCategory, function (data) {
			if (data.length > 0) {
				for (var index = 0; index < data.length; index++) {
					htmlString += "<a class='px-1 mx-1'>";
					htmlString += "<div class='menu-item card px-0 mx-1' style='width:9.3rem; height:5em; cursor:pointer;' id='" + data[index].id + "'>";
					htmlString += "<div class='card-body text-center my-auto'>";
					htmlString += "<h6 class='card-text'>" + data[index].productName + "</h6></div> </div> </a>";
					jQuery('#menu').html(htmlString);
				}
			} else {
				htmlString += "<div class='container'> <p style='font-style:italic;'> No product available </p></div>";
				jQuery('#menu').html(htmlString);
			}
		})
		jQuery('.makeorder').removeClass('active');
		jQuery(this).addClass('active');
	})
});

function toPeso(valueString) {
	console.log('₱'+valueString);
	return '₱'+valueString;
}

function addOrderEntry() {
	htmlString = "";

	orderIdentifier = jQuery('#numberOfOrders').val();

	htmlString += "<div id='itemOrderDiv" + orderIdentifier + "'>";
	htmlString += "<input type='number' value='" + jQuery('#itemID').val() + "' id='productID" + orderIdentifier + "' name='productID" + orderIdentifier + "'>";
	htmlString += "<input type='number' value='" + jQuery('#itemQuantity').val() + "' id='quantity" + orderIdentifier + "' name='quantity" + orderIdentifier + "'>";
	htmlString += "<input type='number' value='" + jQuery('#itemTotalPrice').val() + "' id='productID" + orderIdentifier + "' name='totalPrice" + orderIdentifier + "'>";
	htmlString += "</div>"

	jQuery('#ordersContainer').append(htmlString);
}

function showItemAddedMessage() {
	var translator = new T2W("EN_US");
	jQuery('#snackbar').html('Order of ' + translator.toWords(parseInt(jQuery('#itemQuantity').val())) + ' (' + jQuery('#itemQuantity').val() + ') ' + jQuery('#itemDescription').val() + ' added!');
	var x = document.getElementById("snackbar");
	x.className = "show";
	setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
}

function removeItemEntries() {
	jQuery('#itemID').val('');
	jQuery('#itemDescription').val('');
	jQuery('#itemQuantity').val(1);
	jQuery('#itemUnitPrice').val('');
	jQuery('#itemTotalPrice').val('');
	jQuery('.menu-item').css('background-color', 'white');
	jQuery('#addItemButton').attr('disabled', true);
}

function addRowInOrderSlip() {
	newOrderCount = parseInt(jQuery('#numberOfOrders').val()) + 1;
	jQuery('#numberOfOrders').val(newOrderCount);
	orderIdentifier = jQuery('#numberOfOrders').val();

	htmlString = "";

	htmlString += "<tr class='items' id='orderSlipItem" + orderIdentifier + "'>";
	//htmlString += "<a data-toggle='tooltip' title='Click to remove'>";
	htmlString += "<td class='py-2'>" + jQuery('#itemDescription').val() + "</td>";
	//htmlString += "</a>";
	htmlString += "<td style='text-align:right' class='orderItemQuantity py-2'>" + jQuery('#itemQuantity').val() + "</td>";
	htmlString += "<td style='text-align:right' class='orderItemUnitPrice py-2'>" + numeral(jQuery('#itemUnitPrice').val()).format('0,0.00') + "</td>";
	htmlString += "<td style='text-align:right' class='orderItemPrice py-2'>" + numeral(jQuery('#itemTotalPrice').val()).format('0,0.00') + "</td>";
	htmlString += "</tr>";

	jQuery('#emptyEntryHolder').remove();
	jQuery('#orderSlip').append(htmlString);

	updateOrderSubtotal();
}

function getFoodItem(productID) {
	jQuery.get('/get-product-item/' + productID, function (data) {
		console.log(data);
		jQuery('#itemID').val(data[0].id);
		jQuery('#itemDescription').val(data[0].productName);
		jQuery('#itemUnitPrice').val(data[0].price);
		updateItemPrice();
	})
}

function updateItemPrice() {
	var unitPrice = jQuery('#itemUnitPrice').val();
	var quantity = jQuery('#itemQuantity').val();
	jQuery('#itemTotalPrice').val(unitPrice * quantity);
}

function updateOrderSubtotal() {
	var totalPrice = 0;

	var prices = document.getElementsByClassName('orderItemPrice');

	for (var index = 0; index < prices.length; index++) {
		totalPrice += numeral(prices[index].innerHTML).value();
	}

	document.getElementById('ordersSubtotal').innerHTML = '';
	jQuery('#ordersSubtotal').html(toPeso(numeral(totalPrice).format('0,0.00')));

	updateOrderTotal();
}

function updateOrderTotal() {
	var subtotal = numeral(jQuery('#ordersSubtotal').html()).value();
	var discount = numeral(jQuery('#ordersDiscount').html()).value();

	var grandTotal = subtotal - discount;

	jQuery('#ordersGrandTotal').html('');
	jQuery('#ordersGrandTotal').html(toPeso(numeral(grandTotal).format('0,0.00')));
}

//remove item in the order slip
jQuery(document).ready(function () {
	jQuery(document).on('click', '.items', function () {
		//jQuery(this).remove(); GAC
		jQuery('#orderSlipItem' + jQuery(this).attr('id').slice(13)).remove(); //GAC
		jQuery('#itemOrderDiv' + jQuery(this).attr('id').slice(13)).remove();
		updateOrderSubtotal();

		//Gac
		if (jQuery('.items').length == 0) {
			htmlString = "";

			htmlString += "<tr id='emptyEntryHolder'>";
			htmlString += "<td class='py-2' style='text-align:center' colspan='4'>Add items from the menu</td>";
			htmlString += "</tr>";

			jQuery('#orderSlip').html(htmlString);
		}
		//end

		jQuery('#snackbar').html('Removed succesfully');
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
		jQuery(this).remove();
		updateOrderSubtotal();
	});

	//remove with clear button
	jQuery(document).on('click', '#clearItems', function () {
		jQuery('.items').remove();
		updateOrderSubtotal();
		jQuery('#snackbar').html('ALL ITEMS HAS BEEN REMOVED');
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
	})

});

jQuery(document).ready(function () {
	jQuery(document).on('click', '.restaurant-tables', function () {
		var tableNumber = jQuery(this).attr('id');
		/*if(jQuery.get('load-table-order-slip/'+jQuery(this).attr('id')) === 'undefined') {
			jQuery('#orderTableNumber').val(tableNumber);
			console.log('No orders');
		} else {*/
			jQuery.get('load-table-order-slip/'+jQuery(this).attr('id'), function(data){
				jQuery('.hidden-elements').show();
				jQuery('#billOut').prop('disabled', false);
				jQuery('#orderTableNumber').val(data[0][0].tableNumber);
				jQuery('#orderQueueNumber').val(data[0][0].queueNumber);

				var htmlString = "";

				for(var index = 0; index < data[1].length; index++) {
					//console.log(index);
					htmlString += "<tr><td class='py-2'>" + data[1][index].productName + "</td>";
					htmlString += "<td class='py-2'>" + data[1][index].quantity + "</td>";
					htmlString += "<td class='py-2'>" + (numeral(data[1][index].price).format('0,0.00')) + "</td>";
					htmlString += "<td class='py-2'>" + (numeral(data[1][index].totalPrice).format('0,0.00')) + "</td>";
					htmlString += "<td class='py-2'>" + data[1][index].paymentStatus + "</td></tr>";
					jQuery('#orderSlip').html(htmlString);
				}
			})
		//}
	})
});