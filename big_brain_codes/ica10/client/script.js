$(document).ready(function () {
    AJAX("https://localhost:7172/locations", "get", {}, "JSON", LocationSuccess, Error);

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

        AJAX(`https://localhost:7172/orders?id=${id}&location=${location}`, "get", data, "JSON", RetrieveSuccess, Error);
    });

    $(document).on('focus', '#custID', function () {
        $('.custIDinfo').hide();
    })

    $(document).on('focus', '#location-select', function () {
        $('.locinfo').hide();
    })

    function LocationSuccess(response) {
        var locations = `<option value="" selected hidden>Select a location</option>`;

        for (var i = 0; i < response.length; i++) {
            locations += `<option value="${response[i]['locationid']}">${response[i]['locationName']}</option>`;
        }

        $('#location-select').html(locations);
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