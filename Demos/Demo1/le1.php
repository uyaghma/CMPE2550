<?
    //Hint: Sessions are required on this page
    session_start();
    require_once('le1ws.php');
    //PART 1: Enter your Name 
    $name = "Uyghur Yaghma";

    //PART 2: provided data array
    $data =  array(
        'parking_location' => "Edmonton Airport",
        'plate_number' => "CZZ 6743",
        'in_time' => "12:00",
        'out_time' => "13:00",
        'charges' => 5);

    //if GET parameters are present, store those in SESSION.
    //if NOT present, use the element from $data to store in the SESSION.\
    if ($_GET['parking_location'] != '') {
        $_SESSION['parking_location'] = strip_tags(trim($_GET['parking_location']));
    }
    else {
        $_SESSION['parking_location'] = $data['parking_location'];
    }
    if ($_GET['plate_number'] != '') {
        $_SESSION['plate_number'] = strip_tags(trim($_GET['plate_number']));
    }
    else {
        if (!isset($_SESSION['plate_number'])) {
            $_SESSION['plate_number'] = $data['plate_number'];
        }
    }
    if ($_GET['in_time'] != '') {
        $_SESSION['in_time'] = strip_tags(trim($_GET['in_time']));
    }
    else {
        if (!isset($_SESSION['in_time'])) {
            $_SESSION['in_time'] = $data['in_time'];
        }
    }
    if ($_GET['out_time'] != '') {
        $_SESSION['out_time'] = strip_tags(trim($_GET['out_time']));
    }
    else {
        if (!isset($_SESSION['out_time'])) {
            $_SESSION['out_time'] = $data['out_time'];
        }
    }
    if ($_GET['charges'] != '') {
        $_SESSION['charges'] = strip_tags(trim($_GET['charges']));
    }
    else {
        if (!isset($_SESSION['charges'])) {
            $_SESSION['charges'] = $data['charges'];
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- PART 1: Display contents of $name in the title below in the format Name -->

    <title>CMPE 2550 LE1: <?echo $name?> </title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="code.js"></script>
    <style>
        body{
            background-color: rgb(175, 198, 166);
        }
        h1{
            color:blueviolet;
            border: 3px dotted blueviolet;
            border-radius: 20px;
            padding: 10px;
        }
        button
        {
            background-color: blueviolet;
            color:white;
            border: 3px solid white;
            padding: 10px;
            border-radius: 20px;
        }
        section{
            color: white;
            background-color: darkcyan;
            border: 2px solid white;
            padding: 10px;
            font-size: large;
        }
        table{
            margin-top: 10px;
        }
        table,td
        {
            border-collapse: collapse;
            color: blue;
            background-color: lightblue;
            border: 4px solid white;
            padding: 10px;
           
        }

    </style>
</head>
<body>

    <!-- PART 1: Display contents of $name variable here in the h1 below -->
    <header><h1>Parking Booking for : <?echo $name?> </h1></header>

    <hr>
    <!-- PART 2: Use the SESSION elements to present the data to the user-->
    <section>
        <!-- Thank you for your parking booking at Edmonton Airport -->

        <b><p>Thank you for your parking booking at <?echo $_SESSION['parking_location']?> </p></b>

        <!-- Parking details:
             Plate Number: CZH 8798
             In time: 13:00 
             Out Time: 14:00 
             Your Charges: X -->
        <p>Parking details: <br>Plate Number: <?echo $_SESSION['plate_number']?><br>In time: <?echo $_SESSION['in_time']?><br>Out time: <?echo $_SESSION['out_time']?><br>Charges: <?echo $_SESSION['charges']?></p>
    </section>
    <hr>

    <!-- PART 3: Form processing -->
    
    <form action="./le1.php" method="get">
        <label>Parking Location: <input type="text" name="parking_location" placeholder="<?echo $_SESSION['parking_location']?>"></label>
        <label>Plate Number: <input type="text" name="plate_number" placeholder="<?echo $_SESSION['plate_number']?>"></label>
        <br><br>
        <label>Entry Time: <input type="text" name="in_time" placeholder="<?echo $_SESSION['in_time']?>"></label>
        <label>Exit time: <input type="text" name="out_time" placeholder="<?echo $_SESSION['out_time']?>"></label>
        <br><br>
        <label>Charges: <input type="range" name="charges" min="0" max="100" value="<?echo $_SESSION['charges']?>"></label>
        <input type="submit" value="Submit">
    </form>
    <hr><br>
    <!-- PART 4: Web Services -->
    <!-- ERASE! -->
    <button id="erase">Erase Sessions</button>
    <button id="previouscharges">Display Previous bookings</button>

    <!--display the returned HTML here-->
    <aside></aside>

</body>
</html>
