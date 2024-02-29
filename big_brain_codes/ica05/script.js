$(document).ready(function () {
    $("#table-container").hide();

    $('.fetch').click(function() {
        $("#retrieve").hide();
        var id = $(this).attr('id');

        console.log(id);

        data = {
            id: id
        }

        CallAjax('ws.php', data, "POST", "html", RetrieveSuccess, RetrieveError);
    });

    $("#fetch").click(function () { 
        $("#table-container").slideDown(500);
        $(".circles").css("height", "190%");
        $(this).hide();
    });

    function RetrieveSuccess(response) {
        $("#retrieve").html(response);

        var html = "";
        html =  '<hr><label for="title-id">Title ID : </label><input type="text" name="title-id" id="title-id"><br>' + 
                '<label for="title">Title : </label><input type="text" name="title" id="title"><br>' +
                '<label for="type">Type : </label><select name="type" id="type">' +
                    '<option>psychology</option>' +
                '</select><br>' +
                '<label for="price">Price : </label><input type="text" name="price" id="price"><br>' +
                '<label for="author">Author : </label><select name="author" id="author">' +
                    '<option>Choose a Book Genre</option>' +
                '</select><br>' +
                '<button>Add Book</button>';

        $("#insert").html(html);

        setTimeout(function () {
            $("#retrieve").slideDown(500);
        }, 250);

        $(".delete").click(function () {
            // Delete functionality
        });

        $(".edit").click(function () {
            var id = $(this).attr("id");
            var deleteButton = $(`.delete#${id}`);
            var titleCell = $(`.title-cell#${id}`);
            var titleText = titleCell.text();
            var inputField = $(`<input type='text' name='inputs' class='edit-input' value='${titleText}'>`);
            
            if ($(this).hasClass("cancel")) {
                // Cancel functionality
                titleCell.html(titleText);
                $(this).html("Edit");
                deleteButton.html("Delete");
            } else {
                // Edit functionality
                titleCell.html(inputField);
                $(this).html("Cancel");
                deleteButton.html("Update");
            }
            $(this).toggleClass("cancel");

            deleteButton.click(function () {
                var id = $(this).attr("id");
                var editButton = $(`.edit#${id}`);
                var title = $(`.title-cell#${id}`);
                var input = title.val(); // Get input field value
                console.log(input);

                if (editButton.hasClass("cancel")) {
                    // Update functionality
                    title.html(input);
                    editButton.html("Edit");
                    $(this).html("Delete");
                } else {
                    // Delete functionality
                    // Code to delete entry
                }
                editButton.toggleClass("cancel");
            });
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
});