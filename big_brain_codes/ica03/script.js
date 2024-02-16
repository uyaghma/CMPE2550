$(document).ready(function () {
    $("#table-container").hide();
    // Function to get a random value within a specified range
    function getRandomValue(min, max) {
        return Math.random() * (max - min) + min;
    }

    // Function to generate a valid position for game elements
    function validPos() {
        let pos = getRandomValue(0, 90);
        while (pos > 25 && pos < 75) {
            pos = getRandomValue(0, 90);
        }
        return pos;
    }

    // Position and animate circle elements
    $('.circles li').each(function (index, circle) {
        $(circle).css({
            left: `${validPos()}%`,
            animationDelay: `${getRandomValue(1, 15)}s`
        });
    });

    $('.buttons').click(function() {
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
        $(this).hide();
    });

    function RetrieveSuccess(response) {
        $("#retrieve").html(response);
        setTimeout(function() {
            $("#retrieve").slideDown(500);
        }, 250);
    }

    function RetrieveError(xhr, textStatus, errorThrown) {
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