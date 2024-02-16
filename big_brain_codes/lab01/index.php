<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="index.js"></script>
    <link rel="stylesheet" href="index.css">
    <title>Othello</title>
</head>

<body>
    <div id="site">
        <div id="title">
            <h1 id="header">Othello</h1>
        </div>
        <div class="main">
            <div id="player-info">
                <div id="status">
                    Welcome to Othello! Enter your names in the box below and hit new game to get started.
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
                <div id="gameboard">
                    <div class="row 1">
                        <input type="text" class="col" id="1_1">
                        <input type="text" class="col" id="1_2">
                        <input type="text" class="col" id="1_3">
                        <input type="text" class="col" id="1_4">
                        <input type="text" class="col" id="1_5">
                        <input type="text" class="col" id="1_6">
                        <input type="text" class="col" id="1_7">
                        <input type="text" class="col" id="1_8">
                    </div>
                    <div class="row 2">
                        <input type="text" class="col" id="2_1">
                        <input type="text" class="col" id="2_2">
                        <input type="text" class="col" id="2_3">
                        <input type="text" class="col" id="2_4">
                        <input type="text" class="col" id="2_5">
                        <input type="text" class="col" id="2_6">
                        <input type="text" class="col" id="2_7">
                        <input type="text" class="col" id="2_8">
                    </div>
                    <div class="row 3">
                        <input type="text" class="col" id="3_1">
                        <input type="text" class="col" id="3_2">
                        <input type="text" class="col" id="3_3">
                        <input type="text" class="col" id="3_4">
                        <input type="text" class="col" id="3_5">
                        <input type="text" class="col" id="3_6">
                        <input type="text" class="col" id="3_7">
                        <input type="text" class="col" id="3_8">
                    </div>
                    <div class="row 4">
                        <input type="text" class="col" id="4_1">
                        <input type="text" class="col" id="4_2">
                        <input type="text" class="col" id="4_3">
                        <input type="text" class="col" id="4_4">
                        <input type="text" class="col" id="4_5">
                        <input type="text" class="col" id="4_6">
                        <input type="text" class="col" id="4_7">
                        <input type="text" class="col" id="4_8">
                    </div>
                    <div class="row 5">
                        <input type="text" class="col" id="5_1">
                        <input type="text" class="col" id="5_2">
                        <input type="text" class="col" id="5_3">
                        <input type="text" class="col" id="5_4">
                        <input type="text" class="col" id="5_5">
                        <input type="text" class="col" id="5_6">
                        <input type="text" class="col" id="5_7">
                        <input type="text" class="col" id="5_8">
                    </div>
                    <div class="row 6">
                        <input type="text" class="col" id="6_1">
                        <input type="text" class="col" id="6_2">
                        <input type="text" class="col" id="6_3">
                        <input type="text" class="col" id="6_4">
                        <input type="text" class="col" id="6_5">
                        <input type="text" class="col" id="6_6">
                        <input type="text" class="col" id="6_7">
                        <input type="text" class="col" id="6_8">
                    </div>
                    <div class="row 7">
                        <input type="text" class="col" id="7_1">
                        <input type="text" class="col" id="7_2">
                        <input type="text" class="col" id="7_3">
                        <input type="text" class="col" id="7_4">
                        <input type="text" class="col" id="7_5">
                        <input type="text" class="col" id="7_6">
                        <input type="text" class="col" id="7_7">
                        <input type="text" class="col" id="7_8">
                    </div>
                    <div class="row 8">
                        <input type="text" class="col" id="8_1">
                        <input type="text" class="col" id="8_2">
                        <input type="text" class="col" id="8_3">
                        <input type="text" class="col" id="8_4">
                        <input type="text" class="col" id="8_5">
                        <input type="text" class="col" id="8_6">
                        <input type="text" class="col" id="8_7">
                        <input type="text" class="col" id="8_8">
                    </div>
                </div>
            </div>
        </div>
        <div class="area">
            <ul class="gamename">
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
                <li>Othello</li>
            </ul>
        </div>
    </div>
</body>

</html>