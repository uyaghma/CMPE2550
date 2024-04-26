$(document).ready(function () {
    
    AJAX("https://localhost:7099/", "get", {}, "HTML", ProcessHeaderSuccess, ProcessError);
    
    $('.place').click(function () {
        var url = "https://localhost:7099/form-process";
        
        var name = $("#name").val();
        var location = $("#location").val();
        var item = $("#menu").val();
        var quantity = $("#quantity").val();
        var payment = $("#payment").val();
        
        if (name == "" || location == "" || item == "" || quantity == 0 || payment == "")
        {
            $(".order-deets").html("Fields cannot be left empty!");
            return;
        }

        var data = {};
        data.name = name;
        data.location = location;
        data.item = item;
        data.quantity = quantity;
        data.payment = payment;

        if (data.name != "" && data.location != "" && data.item != "" && data.quantity != "" && data.payment != "") 
        {
            AJAX(url, "post", data, "JSON", ProcessForm, ProcessError);
        }
    });

    function AJAX(url, method, data, dataType, successMethod, errorMethod) {
        let ajaxOptions = {};
        ajaxOptions['url'] = url;
        ajaxOptions['method'] = method;
        ajaxOptions['data'] = JSON.stringify(data);  // NEW for C#
        ajaxOptions['dataType'] = dataType;
        ajaxOptions['success'] = successMethod;
        ajaxOptions['error'] = errorMethod;
        ajaxOptions['contentType'] = "application/json";  // NEW for C#

        console.log(ajaxOptions);
        $.ajax(ajaxOptions);
    }

    function ProcessHeaderSuccess(response, status)
    {
        $(".header").html(`<h1 class="welcome" style="text-align: center;">${response}</h1>`);
    }

    function ProcessForm(response, status)
    {
        var html = `Thanks for your order, ${response.name} <br><ol>`;
        html += `<li>Location: ${response.location}</li>`;
        html += `<li>Item: ${response.items}</li>`;
        html += `<li>Quantity: ${response.quantity}</li>`;
        html += `<li>Payment Method: ${response.payment}</li></ol>`;
        html += `Your order will be ready in ${response.responseTime} minutes.`;

        $(".order-deets").html(html);
    }

    function ProcessSuccess(returnedData, status) {
        console.log("Successful AJAX call");
        console.log(returnedData);
        console.log(status);
    }

    function ProcessError(request, error, messasge) {
        console.log("AJAX call Error");
        console.log(error);
        console.log(messasge);
    }
});