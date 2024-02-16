<?
session_start();
global $flips;

if (isset($_POST['action'])) 
{
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
} 
else 
{
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

    $_SESSION['p1'] = strip_tags($_POST['p1']);
    $_SESSION['p2'] = strip_tags($_POST['p2']);

    $_SESSION['turn'] = rand(1, 2) == 1 ? $_SESSION['p1'] : $_SESSION['p2'];
    $_SESSION['color'] = $_SESSION['turn'] == $_SESSION['p1'] ? 'blue' : 'gold';

    if ($_SESSION['turn'] == $_SESSION['p1']) {
        HasValidMoves($_SESSION['p1']);
    }
    else {
        HasValidMoves($_SESSION['p2']);
    }

    $response = [
        'response' => 'Game Started: ' . $_SESSION['turn'] . ' goes first with ' . $_SESSION['color'] . ' stones.',
        'gameboard' => $_SESSION['gameboard'],
        'turn' => $_SESSION['turn']
    ];

    echo json_encode($response);
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
        echo json_encode(['error' => 'Oh no.']);
        return;
    }

    $row = (int) $_POST['row'];
    $col = (int) $_POST['col'];
    $opponent = ($_SESSION['turn'] == $_SESSION['p1']) ? '2' : '1';
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $flips = array();

    $directions = array(
        array(-1, -1), array(-1, 0), array(-1, 1),
        array(0, -1),                array(0, 1),
        array(1, -1),  array(1, 0),  array(1, 1)
    );

    foreach ($directions as $dir) {
        $flip = array();

        $r = $row + $dir[0];
        $c = $col + $dir[1];

        if ($row >= 0 && $row < 8 && $col >= 0 && $col < 8 && $_SESSION['gameboard'][$r][$c] == $opponent) {
            if (Flipper($r, $c, $dir[0], $dir[1])) {
                $flips = array_merge($flips, $flip);
            }
        }
    }

    if (empty($flips)) {
        echo json_encode(['error' => 'Invalid move. Your piece won\'t capture any pieces. Still ' . $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones."]);
        return;
    }

    foreach ($flips as $coords) {
        $_SESSION['gameboard'][$coords[0]][$coords[1]] == $player;
    }

    $_SESSION['gameboard'][$row][$col] = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $_SESSION['turn'] = ($_SESSION['turn'] == $_SESSION['p1']) ? $_SESSION['p2'] : $_SESSION['p1'];
    $_SESSION['color'] = ($_SESSION['turn'] == $_SESSION['p1']) ? 'blue' : 'gold';

    // Check for the winning condition
    $winner = WinCheck();
    if ($winner !== false) {
        $response = [
            'response' => 'Game Over! Winner: ' . ($winner == 'draw' ? 'It\'s a draw.' : $winner),
            'gameboard' => $_SESSION['gameboard'],
            'turn' => null
        ];

        echo json_encode($response);
        return;
    }

    echo json_encode([
        'response' => $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones.",
        'gameboard' => $_SESSION['gameboard'],
        'turn' => $_SESSION['turn']
    ]);
}

function CheckValidMoves($row, $col, $moveRow, $moveCol)
{
    $opponent = ($_SESSION['turn'] == $_SESSION['p1']) ? '2' : '1';
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    $row += $moveRow;
    $col += $moveCol;

    if ($row < 0 || $row >= 8 || $col < 0 || $col >= 8) {
        return ['valid' => false, 'message' => 'Invalid move. The cell is out of bounds.'];
    }
    if ($_SESSION['gameboard'][$row][$col] == $player) {
        return ['valid' => false, 'message' => 'Invalid move. Your piece must not be solely next to your own piece. Still ' . $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones."];
    }
    if ($_SESSION['gameboard'][$row][$col] == '') {
        return ['valid' => false, 'message' => 'Invalid move. Your piece must be next to at least 1 other piece. Still ' . $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones."];
    } 

    if ($_SESSION['gameboard'][$row][$col] == $opponent) {
        while ($row >= 0 && $row < 8 && $col >= 0 && $col < 8) {
            $row += $moveRow;
            $col += $moveCol;

            if ($row >= 0 && $row < 8 && $col >= 0 && $col < 8) {
                if ($_SESSION['gameboard'][$row][$col] == '') {
                    return ['valid' => false, 'message' => 'Invalid move. Your piece won\'t capture any pieces. Still ' . $_SESSION['turn'] . "'s turn with " . $_SESSION['color'] . " stones."];
                }
                if ($_SESSION['gameboard'][$row][$col] == $player) {
                    return ['valid' => true, 'message' => 'Valid move.'];
                }
            } else {
                break;
            }
        }
    }

    return false;
}

function Flipper($row, $col, $moveRow, $moveCol)
{
    $opponent = ($_SESSION['turn'] == $_SESSION['p1']) ? '2' : '1';
    $player = ($_SESSION['turn'] == $_SESSION['p1']) ? '1' : '2';

    // $row += $moveRow;
    // $col += $moveCol;

    // while ($row >= 0 && $row < 8 && $col >= 0 && $col < 8 && $_SESSION['gameboard'][$row][$col] == $opponent) {
    //     $_SESSION['gameboard'][$row][$col] = $player;
    //     $row += $moveRow;
    //     $col += $moveCol;
    // }

    if ($row >= 0 && $row < 8 && $col >= 0 && $col < 8 && $_SESSION['gameboard'][$row][$col] == '') {
        return false;
    }

    if ($_SESSION['gameboard'][$row][$col] == $player) {
        return true;
    }

    if (Flipper($row, $col, $moveRow, $moveCol)) {
        $flips[] = array($row, $col);
        return true;
    }

    return false;
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