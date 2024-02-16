$(document).ready(function () {
    let active = false;
    
    $("#gameboard").hide();

    // Function to get a random value within a specified range
    function getRandomValue(min, max) {
        return Math.random() * (max - min) + min;
    }

    function validXPos() {
        let pos = getRandomValue(5, 95);
        return pos;
    }

    function validYPos() {
        let pos = getRandomValue(15, 95);
        return pos;
    }

    $('.gamename li').each(function (index, name) {
        $(name).css({
            left: `${validXPos()}%`,
            animationDelay: `${getRandomValue(3, 8)}s`,
            top: `${validYPos()}%`
        });
    });

    $('.gamename li').on('animationiteration', function () {
        $(this).css({
            left: `${validXPos()}%`,
            animationDelay: `${getRandomValue(3, 8)}s`,
            top: `${validYPos()}%`
        });
    });

    // Event handler for the 'Start' button
    $("#start").on("click", () => {
        StartGame();
    });

    // Event handler for the 'Quit' button
    $("#quit").on("click", () => {
        QuitGame();
        $("#p1").val(''); //clear textboxes for names
        $("#p2").val(''); 
        $('#gameboard').hide(250);
    });

    $('.col').click(function () {
        if (active) {
            var coords = $(this).attr('id').split('_');
            var row = parseInt(coords[0]);
            var col = parseInt(coords[1]);
            console.log(`${row}, ${col}`);
    
            MakeMoves(row, col);
        }
        else {
            UpdateStatus("You must press new game to start again.")
        }
    });

    // Function to update the game status message
    function UpdateStatus(message) {
        $('#status').text(message);
    }

    function UpdateBoard(gameboard) {
        for (var row = 1; row <= 8; row++) {
            for (var col = 1; col <= 8; col++) {
                var id = row + '_' + col;
                var val = gameboard[row - 1][col - 1];
                
                // Update the cell value on the game board
                $('#' + id).css("color", val);
                $('#' + id).html(val != '' ? '&#11044' : '');
                if (gameboard[row - 1][col - 1] == '#FFFFFF') {
                    $('#' + id).html(val != '' ? '&#9711' : '');
                    $('#' + id).attr('data-placeholder', 'true'); // Add data attribute
                } else {
                    $('#' + id).removeAttr('data-placeholder'); // Remove data attribute
                }
            }
        }
    }

    function StartGame() {
        var p1 = $('#p1').val();
        var p2 = $('#p2').val();

        if (p1.trim() == '' || p2.trim() == '') {
            UpdateStatus('Please input both player names before starting a new game.');
            return;
        }

        let data = {
            action: 'start',
            p1: p1,
            p2: p2
        }

        CallAjax("gameplay.php", data, "POST", "json", StartSuccess, StartError);
    }

    function StartSuccess(response) {
        console.log(response);
        UpdateStatus(response.response);
        UpdateBoard(response.gameboard);
        active = true;
        $("#gameboard").show(250);
    }

    function StartError(response) {
        console.log(response);
        UpdateStatus(response.error);
    }

    function MakeMoves(row, col) {
        let data = {
            action: 'move',
            row: row - 1,
            col: col - 1
        }

        // Check if the selected cell is empty before making a move
        if ($(`#${row}_${col}`).text() === '' || $(`#${row}_${col}`).attr('data-placeholder') === 'true') {
            CallAjax("gameplay.php", data, "POST", "json", MoveSuccess, MoveError);
        } else {
            // Display an error message for an invalid move
            UpdateStatus('Cell is already filled. Invalid move.');
        }
    }

    function MoveSuccess(response) {
        console.log(response);

        if (response.error) {
            UpdateStatus(response.error);
        }
        else {
            UpdateStatus(response.response);
            UpdateBoard(response.gameboard);
        }
    }

    function MoveError(response) {
        console.log(response);
        UpdateStatus(response.error);
    }

    function QuitGame() {
        let data = {
            action: 'quit'
        }

        CallAjax("gameplay.php", data, "POST", "json", QuitSuccess, QuitError);
    }

    function QuitSuccess(response) {
        console.log(response);
        UpdateStatus(response.response);
        UpdateBoard(response.gameboard);
        active = false;
    }

    function QuitError(response) {
        console.log(response);
        UpdateStatus(response.error);
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