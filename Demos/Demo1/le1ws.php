<? 
session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'erase':
            session_unset();
            session_destroy();
            echo json_encode(['data' => 'Session Cleared']);
            break;
        case 'store':
            StoreSessions();
            break;
        case 'display':
            break;
    }
}

function StoreSessions() {
    $data = array(
        'location' => $_SESSION['parking_location'],
        'plate' => $_SESSION['plate_number'],
        'entry' => $_SESSION['in_time'],
        'exit' => $_SESSION['out_time'],
        'charges' => $_SESSION['charges']
    );

    $_SESSION['data'] = array();

    echo json_encode(
        "<td>" . $data['location'] . "</td>" .
        "<td>" . $data['plate'] . "</td>". 
        "<td>" . $data['entry'] . "</td>".
        "<td>" . $data['exit'] . "</td>".
        "<td>" . $data['charges'] . "</td>"
    );
}
