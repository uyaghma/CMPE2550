<?
// Check if 'action' is set in the POST request
if (isset($_POST['action'])) {
    // Sanitize input data
    $action = strip_tags(trim($_POST['action']));

    // Switch on the action
    switch ($action) {
        // Handle quit action
        case 'quit':
            handleQuit();
            break;
        // Handle start action
        case 'start':
            handleStart();
            break;
        // Handle move action
        case 'move':
            handleMove();
            break;
        // Handle any other invalid action
        default:
            handleInvalidCall();
            break;
    }
} else {
    // Handle invalid call when 'action' is not set
    handleInvalidCall();
}

// Function to handle quit action
function handleQuit() {
    // Start the session
    session_start();
    
    //clear gameboard
    $_SESSION['gameBoard'] = [
        ['', '', ''],
        ['', '', ''],
        ['', '', '']
    ];

    $response = [
        'response' => 'quit',
        'gameBoard' => $_SESSION['gameBoard']
    ];
    // Unset and destroy the session
    session_unset();
    session_destroy();

    // Send a response indicating successful quit
    echo json_encode($response);
}

// Function to handle start action
function handleStart() {
    // Start a new game session
    session_start();

    // Initialize game board with empty cells
    $_SESSION['gameBoard'] = [
        ['', '', ''],
        ['', '', ''],
        ['', '', '']
    ];

    // Set current player randomly (1 or 2)
    $_SESSION['currPlayer'] = rand(1, 2);

    // Save player names from the POST data
    $_SESSION['player1'] = strip_tags(trim($_POST['player1']));
    $_SESSION['player2'] = strip_tags(trim($_POST['player2']));

    // Choose the current player based on currPlayer
    $_SESSION['pName'] = ($_SESSION['currPlayer'] == 1) ? $_SESSION['player1'] : $_SESSION['player2'];

    // Send a response with initial game state
    $response = [
        'gameBoard' => $_SESSION['gameBoard'],
        'currPlayer' => $_SESSION['currPlayer'],
        'pName' => $_SESSION['pName']
    ];

    echo json_encode($response);
}

// Function to handle move action
function handleMove() {
    // Start the session
    session_start();

    if (isset($_POST['row']) && isset($_POST['col'])) {
        $row = (int)$_POST['row'];
        $col = (int)$_POST['col'];

        // Check if the selected cell is empty
        if ($_SESSION['gameBoard'][$row][$col] == '') {
            // Fill the cell with 'X' or 'O' based on the current player
            $_SESSION['gameBoard'][$row][$col] = ($_SESSION['currPlayer'] == 1) ? 'X' : 'O';

            // Check for a win or draw
            if (checkWin($_SESSION['gameBoard'], ($_SESSION['currPlayer'] == 1) ? 'X' : 'O')) {
                $response = [
                    'gameBoard' => $_SESSION['gameBoard'],
                    'winner' => $_SESSION['pName'],
                    'status' => 'win'
                ];

                // End the game when there's a winner
                session_unset();
                session_destroy();

                echo json_encode($response);
                return;
            } elseif (checkDraw($_SESSION['gameBoard'])) {
                // Check for a draw
                $response = [
                    'gameBoard' => $_SESSION['gameBoard'],
                    'status' => 'draw'
                ];

                // End the game when it's a draw
                session_unset();
                session_destroy();

                echo json_encode($response);
                return;
            }

            // Switch to the other player's turn
            $_SESSION['currPlayer'] = ($_SESSION['currPlayer'] == 1) ? 2 : 1;
            $_SESSION['pName'] = ($_SESSION['currPlayer'] == 1) ? $_SESSION['player1'] : $_SESSION['player2'];

            // Send a response with updated game state
            $response = [
                'gameBoard' => $_SESSION['gameBoard'],
                'currPlayer' => $_SESSION['currPlayer'],
                'pName' => $_SESSION['pName']
            ];

            echo json_encode($response);
        } else {
            // Send an error response for an invalid move
            echo json_encode(['error' => 'Invalid move.']);
        }
    } else {
        // Send an error response for an invalid move
        echo json_encode(['error' => 'Invalid move.']);
    }
}

// Function to check for a win
function checkWin($board, $symbol) {
    for ($i = 0; $i < 3; $i++) {
        if (
            ($board[$i][0] == $symbol && $board[$i][1] == $symbol && $board[$i][2] == $symbol) ||
            ($board[0][$i] == $symbol && $board[1][$i] == $symbol && $board[2][$i] == $symbol)
        ) {
            return true;
        }
    }

    if (
        ($board[0][0] == $symbol && $board[1][1] == $symbol && $board[2][2] == $symbol) ||
        ($board[0][2] == $symbol && $board[1][1] == $symbol && $board[2][0] == $symbol)
    ) {
        return true;
    }

    return false;
}

// Function to check for a draw
function checkDraw($board) {
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($board[$i][$j] == '') {
                return false;
            }
        }
    }
    return true;
}

// Function to handle an invalid call
function handleInvalidCall() {
    $response = [
        'response' => 'Wrongcall',
        'errMessage' => 'Who are you? Not a valid call'
    ];

    echo json_encode($response);
}