<?
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="TicTacToe.js"></script>
    <link rel="stylesheet" href="TicTacToe.css">
    <title>Tic Tac Toe</title>
</head>

<body>
    <div id="site">
        <div id="title">
            <h1 id="header">Tic Tac Toe</h1>
        </div>
        <div class="main">
            <div id="player-info">
                <div id="status">
                    Welcome to Tic Tac Toe! Enter your names in the box below and hit new game to get started.
                </div>
                <div id="form">
                    <div id="inputs">
                        <input type="text" name="pname" id="p1" placeholder="Player 1" num="1">
                        <input type="text" name="pname" id="p2" placeholder="Player 2" num="2">
                    </div>
                    <div id="buttons">
                        <input type="button" value="New Game" id="start">
                        <input type="button" value="Quit Game" id="quit">
                    </div>
                </div>
            </div>
            <div id="gamebox">
                <div class="game">
                    <div class="toprow">
                        <div class="top left">
                            <input type="text" name="square" id="1_1" readonly>
                        </div>
                        <div class="top middle">
                            <input type="text" name="square" id="1_2" readonly>
                        </div>
                        <div class="top right">
                            <input type="text" name="square" id="1_3" readonly>
                        </div>
                    </div>
                    <div class="centerrow">
                        <div class="center left">
                            <input type="text" name="square" id="2_1" readonly>
                        </div>
                        <div class="center middle">
                            <input type="text" name="square" id="2_2" readonly>
                        </div>
                        <div class="center right">
                            <input type="text" name="square" id="2_3" readonly>
                        </div>
                    </div>
                    <div class="bottomrow">
                        <div class="bottom left">
                            <input type="text" name="square" id="3_1" readonly>
                        </div>
                        <div class="bottom middle">
                            <input type="text" name="square" id="3_2" readonly>
                        </div>
                        <div class="bottom right">
                            <input type="text" name="square" id="3_3" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">

        </div>
        <div class="area">
            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <ul class="exes">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>
</body>

</html>