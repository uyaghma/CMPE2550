$(document).ready(function () {
    $("#erase").click(function () { 
        let data = {
            action: 'erase'
        }

        $('input').val("");

        CallAjax("./le1ws.php", data, "POST", "json", Success, Error);
    });

    $("#previouscharges").click(function () {
        let data = {
            action: 'store'
        }

        CallAjax("./le1ws.php", data, "POST", "json", Success, Error);
    });

    function Success(response) {
        console.log(response);
        
        if(response.data) {
            $('aside').text(response);
        }
        $('aside').html(response)

    }

    function CallAjax(url, reqData, type, dataType, fxnSuccess, fxnError) {
        let ajaxOptions = {
            url: url,
            data: reqData,
            type: type,
            dataType: dataType
        };

        // Initiate the AJAX call
        let con = $.ajax(ajaxOptions);

        // Handle AJAX success and failure
        con.done(fxnSuccess);
        con.fail(fxnError);
    }
});

