$(document).ready(function () {
    AJAX("https://localhost:7250/selections", "get", {}, "JSON", SelectionSuccess, Error);

    $('.get-orders').click(function (e) {
        var id = $('#custID').val();
        var location = $('#location-select').val();
        var idstatus = $('.custIDinfo');
        var locationstatus = $('.locinfo');
        var valid = true;
        
        if (isNaN(id) || parseInt(id) <= 0)
        {
            $('#custIDinfo').html('ID must be a number > 0');
            shakeStatus(idstatus);
            valid = false;
        }
        if (id == "")
        {
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

        var data = {};
        data.id = id;

        AJAX(`https://localhost:7250/orders?id=${id}&location=${location}`, "get", data, "JSON", RetrieveSuccess, Error);
    });

    $(document).on('focus', '#custID', function () {
        $('.custIDinfo').hide();
    })

    $(document).on('focus', '#location-select', function () {
        $('.locinfo').hide();
    })

    $(document).on('focus', '#location2-select', function () {
        $('.loc2info').hide();
    })

    $(document).on('focus', '#item-select', function () {
        $('.iteminfo').hide();
    })

    $(document).on('focus', '#count', function () {
        $('.countinfo').hide();
    })

    $(document).on('focus', '#payment-select', function () {
        $('.paymentinfo').hide();
    })

    $(document).on('focus', '#customerID', function () {
        $('.customerIDinfo').hide();
    })

    $(document).on('click', '#order', function () {
        $('.get-form').hide();
        $('.order-form').show();
    })

    $(document).on('click', '#order2', function () {
        $('.get-form').show();
        $('.order-form').hide();
    })

    $(document).on('click', 'delete', function () {
        var id = $(this).attr('id');
        var cid = $(this).data('loc');

        AJAX(`https://localhost:7250/delete/${id}/${cid}`, 'delete', data, 'JSON', RetrieveSuccess, Error);
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

        AJAX(url, "put", data, "JSON", OrderSuccess, Error);
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
        var paymentStatus = $('paymentinfo');
        var locationStatus = $('.loc2info');

        var valid = true;

        if (isNaN(id.val()) || parseInt(id.val()) <= 0)
        {
            $('#customerIDinfo').html('ID must be a number > 0');
            shakeStatus(idStatus);
            valid = false;
        }
        if (id.val() == "")
        {
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
        url += `location=${location.val()}&`;

        AJAX(url, "post", data, "JSON", OrderSuccess, Error);
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
        if (response.length > 0)
        {
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
                                <a type='button' id='${response[i]['orderid']}' data-loc="${response[0]['cid']}" class='btn btn-primary rounded-lg delete form-control'>Delete</a>
                            </div></td>`
                        + `</tr>`;
            }
    
            orders += `</tbody><caption>${rows} order(s) retrieved</caption></table>`
            $('.output').html(orders);
        }
        else {
            $('.header').html(``);
            $('.output').html(`There are no orders for this customer.`);
        }
    }

    function OrderSuccess(response) {
        var orderidDIV = `<div class="form-group">
                            <label for="orderID">Order ID</label>
                            <input type="text" name="orderID" id="orderID" value="${response['orderid']}" class="form-control col-sm-7" readonly>
                        </div>`

        var updatebutton = `<div class="form-group button" id="button" style="margin-top: 2rem;">
                                <div class="col-sm-7">
                                    <a type='button' class='btn btn-primary rounded-lg update-order form-control' id="update-order">Update Order</a>
                                </div>
                            </div>`
        
        $('#customerID').attr('readonly', true);
        $('#location2-select').attr('readonly', true);
        $('.output').html(`Thank you for your order. Order can be picked up in ${response['readytime']} minute(s).\n You can modify your item, number of items and payment method.`);
        $('.button').html(updatebutton);
        $('.order-id').html(orderidDIV);
    }

    function UpdateSuccess(response) {
        $('.output').html(`Order has been updated.\nYou can no longer make changes to your order.`);
    }

    function DeleteSuccess(response) {

    }

    function shakeStatus(statusDiv) {
        statusDiv.show();
        statusDiv.effect("shake", { times: 2, distance: 10 }, 400);
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