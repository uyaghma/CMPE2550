$(document).ready(function () {
    let active = false;
    
    $("#gameboard").hide();

    let gameboard;

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

    // Function to center the form div vertically and horizontally
    function centerForm() {
        let windowHeight = $(window).height();
        let formHeight = $("#player-info").outerHeight();
        let marginTop = (windowHeight - formHeight) / 4;
        $("#player-info").css("margin-top", marginTop);
    }

    // Initial centering of the form div
    centerForm();

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
        centerForm();
        $("#inputs").show(250);
        $("#start").show(250);
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
                $('#' + id).val(val != '' ? String.fromCharCode(9679) : '');
                if (val == '1') {
                    $('#' + id).css("color", "#28ABE2");
                } 
                else if (val == '2') {
                    $('#' + id).css("color", "#EBA25D");
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

        // Slide up the form div to make space for the gamebox div
        $("#player-info").css("margin-top", "0px");
        $("#inputs").hide(250);
        $("#start").hide(250);

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
        gameboard = response.gameboard;
        console.log(gameboard);
        active = true;
        $("#gameboard").show(250);
    }

    function StartError(response) {
        console.log(response);
        UpdateStatus(response.error);
        UpdateBoard(response.gameboard);
    }

    function MakeMoves(row, col) {
        let data = {
            action: 'move',
            row: row - 1,
            col: col - 1
        }

        CallAjax("gameplay.php", data, "POST", "json", MoveSuccess, MoveError);
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