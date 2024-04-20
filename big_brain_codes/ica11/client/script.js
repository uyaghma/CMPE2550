$(document).ready(function () {
    AJAX("https://localhost:7250/selections", "get", {}, "JSON", SelectionSuccess, Error);

    $('.get-orders').click(function (e) {
        var id = $('#custID').val();
        var location = $('#location-select').val();
        var idstatus = $('.custIDinfo');
        var locationstatus = $('.locinfo');
        var valid = true;

        if (isNaN(id) || parseInt(id) <= 0) {
            $('#custIDinfo').html('ID must be a number > 0');
            shakeStatus(idstatus);
            valid = false;
        }
        if (id == "") {
            $('#custIDinfo').html('ID cannot be empty');
            shakeStatus(idstatus);
            valid = false;
        }
        if (location == "") {
            shakeStatus(locationstatus);
            valid = false;
        }

        if (!valid) {
            return;
        }

        AJAX(`https://localhost:7250/orders?id=${id}&location=${location}`, "get", {}, "JSON", RetrieveSuccess, Error);
    });

    $(document).on('focus', '#custID', function () {
        $('.custIDinfo').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#location-select', function () {
        $('.locinfo').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#location2-select', function () {
        $('.loc2info').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#item-select', function () {
        $('.iteminfo').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#count', function () {
        $('.countinfo').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#payment-select', function () {
        $('.paymentinfo').hide();
        $('.error').hide();
    })

    $(document).on('focus', '#customerID', function () {
        $('.customerIDinfo').hide();
        $('.error').hide();
    })

    $(document).on('click', '#order', function () {
        $('.get-form').hide();
        $('.error').hide();
        $('.order-form').show();
        $('.custIDinfo').hide();
        $('.locinfo').hide();
        $('.loc2info').hide();
        $('.iteminfo').hide();
        $('.countinfo').hide();
        $('.paymentinfo').hide();
        $('.customerIDinfo').hide();
        $('.order-output').hide();
        $('.header').html('');
        $('.output').html('');
        $('.message').html('');
        $('#custID').val('');
        $('#location-select').val('');
    })

    $(document).on('click', '#order2', function () {
        $('.get-form').show();
        $('.order-form').hide();
        $('.custIDinfo').hide();
        $('.locinfo').hide();
        $('.loc2info').hide();
        $('.iteminfo').hide();
        $('.countinfo').hide();
        $('.paymentinfo').hide();
        $('.customerIDinfo').hide();
        $('.order-confirmation').hide();
        $('.message').html('');
        $('#customerID').val('');
        $('#item-select').val('');
        $('#count').val('');
        $('#payment-select').val('');
        $('#location2-select').val('');
        $('.order-id').html('');
        $('.order-id').hide();
        var orderbutton = ` <div>
                                <a type='button' class='btn btn-primary rounded-lg place-order form-control' id="send-order">Place Order</a>
                            </div>`
        $('.button').html(orderbutton);
        $('.order-status').html('');
        $('.receipt').html('');
        location.attr('disabled', false);
        id.attr('readonly', false);
    })

    $(document).on('click', '.delete', function () {
        var id = $(this).attr('id');
        var loc = $(this).data('loc');
        var cid = $(this).data('cid');
        console.log(loc);
        console.log(id);

        AJAX(`https://localhost:7250/delete/${id}/${cid}/${loc}`, 'delete', {}, 'JSON', DeleteSuccess, Error);
    })

    $(document).on('click', '#update-order', function () {
        var url = 'https://localhost:7250/update?';
        var oid = $('#orderID');
        var id = $('#customerID');
        var item = $('#item-select');
        var count = $('#count');
        var payment = $('#payment-select');
        var location = $('#location2-select');

        if (payment.val() == "") {
            shakeStatus(paymentStatus);
            valid = false;
        }
        if (count.val() == "") {
            shakeStatus(countStatus);
            valid = false;
        }
        if (item.val() == "") {
            shakeStatus(itemStatus);
            valid = false;
        }

        url += `oid=${oid.val()}&`;
        url += `id=${id.val()}&`;
        url += `item=${item.val()}&`;
        url += `count=${count.val()}&`;
        url += `payment=${payment.val()}&`;
        url += `location=${location.val()}&`;

        AJAX(url, "put", {}, "JSON", UpdateSuccess, Error);
    })

    $(document).on('click', '#send-order', function () {
        var url = 'https://localhost:7250/place?';
        var id = $('#customerID');
        var item = $('#item-select');
        var count = $('#count');
        var payment = $('#payment-select');
        var location = $('#location2-select');

        var idStatus = $('.customerIDinfo');
        var itemStatus = $('.iteminfo');
        var countStatus = $('.countinfo');
        var paymentStatus = $('.paymentinfo');
        var locationStatus = $('.loc2info');

        var valid = true;

        if (isNaN(id.val()) || parseInt(id.val()) <= 0) {
            $('#customerIDinfo').html('ID must be a number > 0');
            shakeStatus(idStatus);
            valid = false;
        }
        if (id.val() == "") {
            $('#customerIDinfo').html('ID cannot be empty');
            shakeStatus(idStatus);
            valid = false;
        }
        if (location.val() == "") {
            shakeStatus(locationStatus);
            valid = false;
        }
        if (payment.val() == "") {
            shakeStatus(paymentStatus);
            valid = false;
        }
        if (count.val() == "") {
            shakeStatus(countStatus);
            valid = false;
        }
        if (item.val() == "") {
            shakeStatus(itemStatus);
            valid = false;
        }

        if (!valid) {
            return;
        }

        url += `id=${id.val()}&`;
        url += `item=${item.val()}&`;
        url += `count=${count.val()}&`;
        url += `payment=${payment.val()}&`;
        url += `location=${location.val()}`;

        AJAX(url, "post", {}, "JSON", OrderSuccess, Error);
    })

    $(document).on('click', '#new-order', function () {
        var id = $('#customerID');
        var item = $('#item-select');
        var count = $('#count');
        var payment = $('#payment-select');
        var location = $('#location2-select');

        id.val('');
        item.val('');
        count.val('');
        payment.val('');
        location.val('');

        id.attr('readonly', false);
        item.attr('disabled', false);
        count.attr('readonly', false);
        payment.attr('disabled', false);
        location.attr('disabled', false);

        $('.order-id').html("");
        $('.order-id').hide();

        var orderbutton = ` <div>
                                <a type='button' class='btn btn-primary rounded-lg place-order form-control' id="send-order">Place Order</a>
                            </div>`
        $('.button').html(orderbutton);
        $('.order-status').html('');
        $('.receipt').html('');
    })

    function SelectionSuccess(response) {
        var items = `<option value="" selected hidden>Select an item</option>`;
        var locations = `<option value="" selected hidden>Select a location</option>`;
        var payments = `<option value="" selected hidden>Select a payment method</option>`;

        for (var i = 0; i < response['items'].length; i++) {
            items += `<option value="${response.items[i]['id']}">${response.items[i]['name']} : $${response.items[i]['price']}</option>`;
        }

        for (var i = 0; i < response['payments'].length; i++) {
            payments += `<option value="${response.payments[i]['payment']}">${response.payments[i]['payment']}</option>`;
        }

        for (var i = 0; i < response['locations'].length; i++) {
            locations += `<option value="${response.locations[i]['id']}">${response.locations[i]['name']}</option>`;
        }

        $('#location-select').html(locations);
        $('#location2-select').html(locations);
        $('#item-select').html(items);
        $('#payment-select').html(payments);
    }

    function RetrieveSuccess(response) {
        if (response.length > 0) {
            $('.order-output').show('slide', {direction: 'left'}, 1000);
            $('.header').html(`Orders for ${response[0]['name']} at ${response[0]['location']} location`);
            var rows = 0;
            var orders = `<table class="table table-sm table-light">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Payment Method</th>
                                    <th>Item Name</th>
                                    <th>Item Price</th>
                                    <th>Item Count</th>
                                    <th>Delete Order</th>
                                </tr>
                            </thead>
                            <tbody>`;

            for (var i = 0; i < response.length; i++) {
                rows++;
                orders += `<tr>`
                    + `<td>${response[i]['orderid']}</td>`
                    + `<td>${response[i]['date']}</td>`
                    + `<td>${response[i]['payment']}</td>`
                    + `<td>${response[i]['item']}</td>`
                    + `<td>${response[i]['price']}</td>`
                    + `<td>${response[i]['count']}</td>`
                    + `<td><div>
                                <a type='button' id='${response[i]['orderid']}' data-cid="${response[0]['cid']}" data-loc="${response[0]['locationid']}" class='btn btn-primary rounded-lg delete form-control'>Delete</a>
                            </div></td>`
                    + `</tr>`;
            }

            orders += `</tbody><caption>${rows} order(s) retrieved</caption></table>`
            $('.output').html(orders);
        }
        else {
            $('.header').html(``);
            $('.error').html(`There are no orders for this customer.`);
            shakeStatus($('.error'));
            $('#custID').val('');
            $('#location-select').val('');
        }
    }

    function OrderSuccess(response) {
        if (response['error'] === "") {
            $('.order-confirmation').show('slide', {direction:'left'}, 1000);
            var orderidDIV = `<label for="orderID">Order ID</label>
                          <input type="text" name="orderID" id="orderID" value="${response['oid']}" class="form-control" readonly>`

            var updatebutton = `<div>
                                <a type='button' class='btn btn-primary rounded-lg update-order form-control' id="update-order">Update Order</a>
                            </div>`

            MakeReceipt(response);
            $('#customerID').attr('readonly', true);
            $('#location2-select').attr('disabled', true);
            $('.order-status').html(response['message']);
            $('.button').html(updatebutton);
            $('.order-id').html(orderidDIV);
            $('.order-id').show();
        }
        else {
            $('.error').html(response['error']);
            shakeStatus($('.error'));
            $('#customerID').val('');
            $('#item-select').val('');
            $('#count').val('');
            $('#payment-select').val('');
            $('#location2-select').val('');
        }
    }

    function UpdateSuccess(response) {
        if (response['error'] === "") {
            MakeReceipt(response);
            $('.order-status').html(response['message']);
            $('#count').attr('readonly', true);
            $('#item-select').attr('disabled', true);
            $('#payment-select').attr('disabled', true);
            var newOrder = `<div>
                                <a type='button' class='btn btn-primary rounded-lg update-order form-control' id="new-order">New Order</a>
                            </div>`
            $('.button').html(newOrder);
        }
        else {
            $('.error').html(response['error']);
            shakeStatus($('.error'));
            $('#item-select').val('');
            $('#count').val('');
            $('#payment-select').val('');
        }
    }

    function DeleteSuccess(response) {
        if (response['error'] === "") {
            var id = response['cid'];
            var location = response['loc'];
            $('.message').html(response['message']);
            AJAX(`https://localhost:7250/orders?id=${id}&location=${location}`, "get", {}, "JSON", RetrieveSuccess, Error);
        }
        else {
            $('.order-status').html(response['error']);
        }
    }

    function shakeStatus(statusDiv) {
        statusDiv.show();
        statusDiv.effect("shake", { times: 2, distance: 10 }, 400);
    }

    function MakeReceipt(response) {
        var receipt = ` <h2 class="header">Order Details</h2>
        <table class="order-details">
            <tr>
                <td class="bold">${response['date']}</td>
            </tr>
            <tr>
                <td>Order Number:</td>
                <td>${response['oid']}</td>
            </tr>
            <tr>
                <td>Order Time:</td>
                <td>${response['time']}</td>
            </tr>
            <tr>
                <td>Location:</td>
                <td colspan="2">${response['locName']}</td>
            </tr>
        </table>
    
        <hr>
    
        <table class="items">
            <tr>
                <td class="bold">${response['itemName']}</td>
                <td>${response['count']}</td>
                <td class="right">${response['itemPrice']}</td>
            </tr>
        </table>
    
        <hr>
    
        <table class="totals">
            <tr>
                <td colspan="3" class="small-font">Subtotal</td>
                <td class="small-font right">${(response['itemPrice'] * response['count']).toFixed(2)}</td>
            </tr>
            <tr>
                <td colspan="3" class="small-font">Tax</td>
                <td class="small-font right">$${(response['itemPrice'] * response['count'] * 0.05).toFixed(2)}</td>
            </tr>
            <tr class="bold">
                <td colspan="3">Total</td>
                <td class="right"><strong>$${(response['itemPrice'] * response['count'] * 1.05).toFixed(2)}</strong></td>
            </tr>
        </table>`;
        $('.receipt').html(receipt);
    }

    function AJAX(url, method, data, dataType, successMethod, errorMethod) {
        let ajaxOptions = {};
        ajaxOptions['url'] = url;
        ajaxOptions['method'] = method;
        ajaxOptions['data'] = JSON.stringify(data);  // NEW for C#
        ajaxOptions['dataType'] = dataType;
        ajaxOptions['success'] = successMethod;
        ajaxOptions['error'] = errorMethod;
        ajaxOptions['contentType'] = "application/json";  // NEW for C#

        $.ajax(ajaxOptions);
    }
});