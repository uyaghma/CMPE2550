$(document).ready(function () {
    var pos = 45;
    var status = "";
    var winner = "";
    var maxmoves = 0;
    var start = false;
    var quit = false;

    $('#new-game').click(function (e) {
        var p1 = $('#p1');
        var p2 = $('#p2');

        var p1name = p1.val().replace(/(<([^>]+)>)/gi, "")
        var p2name = p2.val().replace(/(<([^>]+)>)/gi, "")

        if (p2name.length < 1)
        {
            p2.attr('aria-invalid', 'true');
            p2.effect('shake');
        }
        else
        {
            p1.attr('aria-invalid', 'false')
        }
        if (p1name.length < 1)
        {
            p1.attr('aria-invalid', 'true');
            p1.effect('shake');
        }
        else
        {
            p1.attr('aria-invalid', 'false')
        }

        if (p2.attr('aria-invalid') == 'false' && p1.attr('aria-invalid') == 'false')
        {
            var url = "https://localhost:7172/newgame";
            var data = {};
            data.p1name = p1name;
            data.p2name = p2name;

            AJAX(url, "post", data, "JSON", Success, Error);
        }
    });

    $('#pull').click(function (e) { 
        var url = "https://localhost:7172/movepiece";
        var data = {};

        data.pos = pos;
        data.status = status;
        data.maxmoves = maxmoves;
        data.winner = winner;
        data.start = start;

        if (!quit) {
            AJAX(url, "post", data, "JSON", MoveSuccess, Error);
        }
        else {
            AJAX("https://localhost:7172/quitgame", "post", "", "JSON", QuitSuccess, Error);
        }
    });

    $('#quit-game').click(function (e) { 
        var url = "https://localhost:7172/quitgame";
        data = {};
        
        AJAX(url, "post", data, "JSON", QuitSuccess, Error);
    });

    function Success(response)
    {
        $('.status').html(response.status);
        $('.game-piece').css('left', `45%`);
        status = response.status;
        winner = response.winner;
        maxmoves = response.maxmoves;
        start = response.start;
        pos = response.pos;
        quit = response.quit;
    }

    function MoveSuccess(response)
    {
        var piece = $('.game-piece');
        pos = response.pos;
        status = response.status;
        winner = response.winner;
        maxmoves = response.maxmoves;
        start = response.start;

        piece.css('left', `${response.pos}%`);
        $('.status').html(response.status);
    }

    function QuitSuccess(response)
    {
        $('.status').html(response.status);
        start = response.start;
        quit = response.quit;

        $('#p1').val("");
        $('#p2').val("");
    }

    function Error(response)
    {
        $('.status').html(response.error);
    }

    $('#p1').focus(function (e) { 
        e.preventDefault();
        $(this).attr('aria-invalid', 'false')
    });

    $('#p2').focus(function (e) { 
        e.preventDefault();
        $(this).attr('aria-invalid', 'false')
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
});