$(document).ready(function () {
    var au_id;
    $("#retrieve-container").hide(500);

    $(".retrieve").click(function () 
    {
        var id = $(this).attr("id"); 
        console.log(id);
        au_id = id;

        $("#retrieve-container").show(500);

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

            if (title === null || title === "")
            {
                $(`.title-check#${id}`).find('caption').remove();
                $(`.title-check#${id}`).append("<caption style='width: 100%; padding-top: 0;'>Please input a valid title.</caption>");
                $(`.title-check#${id}`).find('input').css("border", "red 2px solid");
                $(`.title-check#${id}`).find('input').effect("shake", { times:3 }, 350);
                $(`.type-check#${id}`).find('caption').remove();
                $(`.price-check#${id}`).find('caption').remove();
            }
            else if (type === null || type === "")
            {
                $(`.type-check#${id}`).find('caption').remove();
                $(`.type-check#${id}`).append("<caption style='width: 100%; padding-top: 0;'>Please select a type.</caption>");
                $(`.type-check#${id}`).find('input').css("border", "red 2px solid");
                $(`.type-check#${id}`).find('input').effect("shake", { times:3 }, 350);
                $(`.title-check#${id}`).find('caption').remove();
                $(`.price-check#${id}`).find('caption').remove();
            }
            else if (price === null || price === "")
            {
                $(`.price-check#${id}`).find('caption').remove();
                $(`.price-check#${id}`).append("<caption style='width: 100%; padding-top: 0;'>Please input a price.</caption>");
                $(`.price-check#${id}`).find('input').css("border", "red 2px solid");
                $(`.price-check#${id}`).find('input').effect("shake", { times:3 }, 350);
                $(`.title-check#${id}`).find('caption').remove();
                $(`.type-check#${id}`).find('caption').remove();
            }
            else 
            {
                $(`.title-check#${id}`).find('caption').remove();
                $(`.type-check#${id}`).find('caption').remove();
                $(`.price-check#${id}`).find('caption').remove();

                if (!isNaN(price) && price > 0) 
                {
                    data = {
                        action: 'update',
                        id: au_id,
                        t_id: id,
                        title: title,
                        type: type,
                        price: price
                    }
            
                    CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
                }
                else
                {
                    $(`.price-check#${id}`).find('caption').remove();
                    $(`.price-check#${id}`).append("<caption style='width: 100%; padding-top: 0;'>Please input a valid number.</caption>");
                    $(`.price-check#${id}`).find('input').css("border", "red 2px solid");
                    $(`.price-check#${id}`).find('input').effect("shake", { times:3 }, 350);
                }
            }
        });

        $('.delete').click(function () {
            var id = $(this).attr("id");  
            data = {
                action: 'delete', 
                id: au_id,
                t_id: id
            }
    
            CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
        });
    }

    $('.add-book').click(function () {
        var id = $('#title-id').val();
        var title = $('#title').val();
        var type = $('#type').val();
        var price = $('#price').val();
        var author = $('#author').val();
        
        console.log(id, title, type, price, author);

        data = {
            action: 'insert',
            id: id,
            title: title,
            type: type,
            price: price,
            author: author
        }

        CallAjax('ws.php', data, "GET", "html", RetrieveSuccess, RetrieveError);
    });
    
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