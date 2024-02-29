$(document).ready(function () {
    var au_id;

    $(".btn").click(function () 
    {
        var id = $(this).attr("id"); 
        console.log(id);
        au_id = id;

        data = {
            action: 'retrieve', 
            id: id
        }

        CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
    });

    function RetrieveSuccess(response) {
        $("#retrieve-container").html(response);

        $('.edit').click(function () {  
            var id = $(this).attr("id"); 
            data = {
                action: 'edit', 
                id: au_id,
                t_id: id
            }
    
            CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
        });

        $('.cancel').click(function () { 
            data = {
                action: 'retrieve', 
                id: au_id
            }
    
            CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
        });

        $('.update').click(function () { 
            var id = $(this).attr("id"); 
            var title = $(`.title-cell#${id}`).val();
            var type = $(`.type-cell#${id}`).val();
            var price = $(`.price-cell#${id}`).val();

            data = {
                action: 'update', 
                id: au_id,
                t_id: id,
                title: title,
                type: type,
                price: price
            }
    
            CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
        });

        $('.delete').click(function () {
            var id = $(this).attr("id");  
            data = {
                action: 'delete', 
                au_id: au_id,
                id: id
            }
    
            CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
        });
    }
    
    function RetrieveError(xhr, textStatus, errorThrown) 
    {
        console.error("AJAX error:", textStatus, errorThrown);
    }

    // Function to make an AJAX call
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
})