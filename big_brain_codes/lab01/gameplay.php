<?php
session_start();

if (isset($_POST['action'])) {
    $action = strip_tags($_POST['action']);

    switch ($action) {
        case 'start':
            StartGame();
            break;
        case 'quit':
            QuitGame();
            break;
        case 'move':
            MakeMoves();
            break;
        default:
            InvalidCall();
            break;
    }
} else {
    InvalidCall();
}

function StartGame()
{
    $_SESSION['gameboard'] = [
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '1', '2', '', '', ''],
        ['', '', '', '2', '1', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', '']
    ];

    if (isset($_POST['p1']) && isset($_POST['p2'])) {
        $_SESSION['p1'] = strip_tags($_POST['p1']);
        $_SESSION['p2'] = strip_tags($_POST['p2']);

        $_SESSION['turn'] = rand(1, 2) == 1 ? $_SESSION['p1'] : $_SESSION['p2'];
        $_SESSION['color'] = $_SESSION['turn'] == $_SESSION['p1'] ? 'blue' : 'gold';

        $response = [
            'response' => 'Game Started: ' . $_SESSION['turn'] . ' goes first with ' . $_SESSION['color'] . ' stones.',
            'gameboard' => $_SESSION['gameboard'],
            'turn' => $_SESSION['turn']
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Player names are required to start the game.']);
    }
}

function QuitGame()
{
    $_SESSION['gameboard'] = [
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', '']
    ];

    $response = [
        'response' => 'Game over! Enter in new names and start a new game.',
        'gameboard' => $_SESSION['gameboard']
    ];

    session_unset();
    session_destroy();

    echo json_encode($response);
}

function MakeMoves()
{
    if (!isset($_POST['row']) || !isset($_POST['col'])) {
        echo json_encode(['error' => 'Row and column values are required.']);
        return;
    }

    $row = (int) $_POST['row'];
    $col = (int) $_POST['col'];

    if ($_SESSION['gameboard'][$row][$col] != '') {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid move. The selected cell is not empty. Still ' .$_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones."
        ]);
        return;
    }

    // Check if there's at least one direction in which a valid move can be made
    $validMoveAvailable = false;
    $directions = array(
        array(-1, -1), array(-1, 0), array(-1, 1),
        array(0, -1),                array(0, 1),
        array(1, -1),  array(1, 0),  array(1, 1)
    );

    foreach ($directions as $dir) {
        if (ValidateMove($row, $col, $dir[0], $dir[1])) {
            $validMoveAvailable = true;
            break;
        }
    }

    if (!$validMoveAvailable) {
        echo json_encode([
            'success' => false,
            'error' => 'No valid moves available for the current player. Switching turn.'
        ]);

        // Switch turn
        $_SESSION['turn'] = ($_SESSION['turn'] == $_SESSION['p1']) ? $_SESSION['p2'] : $_SESSION['p1'];
        $_SESSION['color'] = ($_SESSION['turn'] == $_SESSION['p1']) ? 'blue' : 'gold';

        echo json_encode([
            'response' => $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones.",
            'gameboard' => $_SESSION['gameboard'],
            'turn' => $_SESSION['turn']
        ]);

        // Check for game end
        $winner = WinCheck();
        if ($winner) {
            echo json_encode([
                'success' => true,
                'response' => 'Game over! The winner is ' . $winner . '.',
                'gameboard' => $_SESSION['gameboard'],
                'turn' => null
            ]);
            //session_unset();
            //session_destroy();
            return;
        }

        return;
    }

    // If the cell is empty and there's at least one direction for a valid move, continue with the move
    $_SESSION['gameboard'][$row][$col] = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $flips = array();

    foreach ($directions as $dir) {
        if (ValidateMove($row, $col, $dir[0], $dir[1])) {
            array_push($flips, array($row, $col, $dir[0], $dir[1]));
        }
    }

    foreach ($flips as $flip) {
        Flip($flip[0], $flip[1], $flip[2], $flip[3]);
    }

    // Return success response
    echo json_encode([
        'response' => $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones.",
        'gameboard' => $_SESSION['gameboard'],
        'turn' => $_SESSION['turn']
    ]);

    // Check for game end
    $winner = WinCheck();
    if ($winner != false) {
        echo json_encode([
            'success' => true,
            'message' => 'Game over! The winner is ' . $winner . '.',
            'gameboard' => $_SESSION['gameboard'],
            'turn' => null
        ]);
        session_unset();
        session_destroy();
        return;
    }
}

function ValidateMove($row, $col, $rowDir, $colDir)
{
    $opponent = ($_SESSION['turn'] == $_SESSION['p1']) ? '2' : '1';
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $r = $row + $rowDir;
    $c = $col + $colDir;

    if ($r >= 0 && $r <= 7 && $c >= 0 && $c <= 7 && $_SESSION['gameboard'][$r][$c] == $opponent) {
        $r += $rowDir;
        $c += $colDir;

        // Keep track of whether a player's piece is encountered
        $playerEncountered = false;

        while ($r >= 0 && $r <= 7 && $c >= 0 && $c <= 7) {
            if ($_SESSION['gameboard'][$r][$c] == '') {
                // Empty cell encountered without encountering player's piece
                return false;
            } elseif ($_SESSION['gameboard'][$r][$c] == $player) {
                // Player's piece encountered
                $playerEncountered = true;
                break;
            } elseif ($_SESSION['gameboard'][$r][$c] == $opponent) {
                // Opponent's piece encountered, continue searching
                $r += $rowDir;
                $c += $colDir;
            }
        }

        if ($playerEncountered) {
            return true;
        }
    }
    return false; // Return false if the condition isn't met
}

function Flip($row, $col, $rowDir, $colDir)
{
    $opponent = ($_SESSION['turn'] == $_SESSION['p1']) ? '2' : '1';
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $row += $rowDir;
    $col += $colDir;

    if ($row >= 0 && $row <= 7 && $col >= 0 && $col <= 7 && $_SESSION['gameboard'][$row][$col] == $opponent) {
        $_SESSION['gameboard'][$row][$col] = $player;
        Flip($row, $col, $rowDir, $colDir);
    } else {
        return;
    }
    $_SESSION['turn'] = ($_SESSION['turn'] == $_SESSION['p1']) ? $_SESSION['p2'] : $_SESSION['p1'];
    $_SESSION['color'] = ($_SESSION['turn'] == $_SESSION['p1']) ? 'blue' : 'gold';
}

function WinCheck() {
    $p1_pieces = 0;
    $p2_pieces = 0;

    foreach ($_SESSION['gameboard'] as $row) {
        foreach ($row as $cell) {
            if ($cell == '1') {
                $p1_pieces++;
            }
            else if ($cell == '2') {
                $p2_pieces++;
            }
        }
    }

    if ($p1_pieces + $p2_pieces == 64 || !(HasValidMoves($_SESSION['p1']) || HasValidMoves($_SESSION['p2']))) {
        if ($p1_pieces > $p2_pieces) {
            return $_SESSION['p1'];
        }
        else if ($p2_pieces > $p1_pieces) {
            return $_SESSION['p2'];
        }
        else {
            return 'draw';
        }
    }
    return false;
}

function HasValidMoves($player)
{
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';
    $placeholder = '3';
    $validMovesFound = false;

    // Reset the board
    for ($row = 0; $row < 8; $row++) {
        for ($col = 0; $col < 8; $col++) {
            if ($_SESSION['gameboard'][$row][$col] == '3') {
                $_SESSION['gameboard'][$row][$col] = '';
            }
        }
    }

    // Iterate through the game board
    for ($row = 0; $row < 8; $row++) {
        for ($col = 0; $col < 8; $col++) {
            // Check if the cell is empty
            if ($_SESSION['gameboard'][$row][$col] == '') {
                // Check if the move is valid in any direction
                $valid = false;
                for ($i = -1; $i <= 1; $i++) {
                    for ($j = -1; $j <= 1; $j++) {
                        if ($i != 0 || $j != 0) { // Exclude the current cell
                            if (CheckValidMoves($row, $col, $i, $j)['valid']) {
                                $_SESSION['gameboard'][$row][$col] = $placeholder;
                                $valid = true;
                                break; // Break the inner loop if a valid move is found
                            }
                        }
                    }
                    if ($valid) {
                        $validMovesFound = true;
                        break 2; // Break both loops if a valid move is found
                    }
                }
            }
        }
    }

    return $validMovesFound; // Returns true if valid moves are found, false otherwise
}

function InvalidCall()
{
    $response = [
        'response' => 'Invalid call.'
    ];

    echo json_encode($response);
}
