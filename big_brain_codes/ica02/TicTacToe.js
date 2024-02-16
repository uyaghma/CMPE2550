$(document).ready(function () {
    // Flag to track whether the game has started or not
    let gameStarted = false;

    // Function to get a random value within a specified range
    function getRandomValue(min, max) {
        return Math.random() * (max - min) + min;
    }

    // Function to generate a valid position for game elements
    function validPos() {
        let pos = getRandomValue(0, 90);
        return pos < 30 ? pos : pos + 25;
    }

    // Position and animate circle elements
    $('.circles li').each(function (index, circle) {
        $(circle).css({
            left: `${validPos()}%`,
            animationDelay: `${getRandomValue(1, 35)}s`
        });
    });

    // Position and animate 'X' elements
    $('.exes li').each(function (index, exe) {
        $(exe).css({
            left: `${validPos()}%`,
            animationDelay: `${getRandomValue(1, 35)}s`
        });
    });

    // Event handler for the 'Start' button
    $("#start").on("click", () => {
        startGame();
    });

    // Event handler for the 'Quit' button
    $("#quit").on("click", () => {
        quitGame();
        $("#p1").val(''); //clear textboxes for names
        $("#p2").val(''); 
    });

    // Event handler for clicking on the game board cells
    $('.game input[type="text"]').on("click", function () {
        if (gameStarted) {
            var coords = $(this).attr('id').split('_');
            var row = parseInt(coords[0]);
            var col = parseInt(coords[1]);

            // Make a move only if the game has started
            makeMove(row, col);
            console.log(`${row}, ${col}`);
        }
        else {
            // Display a message if the game has not started
            updateStatus("Start a new game before making a move.");
        }
    });

    // Function to update the game status message
    function updateStatus(message) {
        $('#status').text(message);
    }

    // Function to handle the 'Quit' action
    function quitGame() {
        let data = { action: 'quit' };
        CallAjax("gameFlow.php", data, "POST", "json", quitSuccess, quitError);
    }

    // Function to handle the 'Start' action
    function startGame() {
        var p1 = $("#p1").val();
        var p2 = $("#p2").val();

        // Check if player names are provided before starting the game
        if (p1.trim() === '' || p2.trim() === '') {
            updateStatus('Please fill out both player names before starting a new game.');
            return;
        }

        // Mark the game as started
        gameStarted = true;

        // Prepare data for starting the game
        let data = {
            action: "start",
            player1: p1,
            player2: p2
        };

        // Initiate the 'Start' action via AJAX
        CallAjax("gameFlow.php", data, "POST", "json", startSuccess, startError);
    }

    // Function to make a move on the game board
    function makeMove(row, col) {
        let data = {
            action: "move",
            row: row - 1,
            col: col - 1
        };

        // Check if the selected cell is empty before making a move
        if ($(`#${row}_${col}`).val() === '') {
            CallAjax("gameFlow.php", data, "POST", "json", moveSuccess, moveError);
        } else {
            // Display an error message for an invalid move
            console.log('Cell is already filled. Invalid move.');
        }
    }

    // Function to handle 'Quit' success response
    function quitSuccess(response) {
        console.log(response);
        updateBoard(response.gameBoard);
        gameStarted = false;
        updateStatus("Enter in new names and hit the 'New Game' to play again.");
    }

    // Function to handle 'Quit' error response
    function quitError(error) {
        console.error(error);
    }

    // Function to handle 'Start' success response
    function startSuccess(response) {
        console.log(response.gameBoard);
        // Update the game board and status message
        updateBoard(response.gameBoard);
        updateStatus('Game Started. ' + response.pName + ' goes first.');
    }

    // Function to handle 'Start' error response
    function startError(error) {
        console.error(error);
    }

    // Function to handle 'Move' success response
    function moveSuccess(response) {
        console.log(response);
        // Update the game board and status message
        updateBoard(response.gameBoard);
        
        // Check for win or draw conditions
        if (response.status === 'win') {
            updateStatus(response.winner + ` wins the game.`);
            gameStarted = false;
        }
        else if (response.status === 'draw') {
            updateStatus(`CATS! The game ends in a draw.`);
            gameStarted = false;
        }
        else {
            updateStatus(response.pName + `'s turn.`);
        }
    }

    // Function to handle 'Move' error response
    function moveError(error) {
        console.error(error);
    }

    // Function to update the game board based on the current state
    function updateBoard(currBoard) {
        for (var row = 1; row <= 3; row++) {
            for (var col = 1; col <= 3; col++) {
                var id = row + '_' + col;
                var val = currBoard[row - 1][col - 1];

                // Update the cell value on the game board
                $('#' + id).val(val === '' ? '' : val);
            }
        }
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