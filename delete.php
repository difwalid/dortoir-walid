<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dortoir";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_room = intval($_POST['current_room']);
    $new_room = intval($_POST['new_room']);

    // Check if the new room is free
    $sql_check = "SELECT COUNT(*) AS count FROM chambre WHERE room_number = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $new_room);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if ($row_check['count'] == 0) {
        // Move the client to the new room
        $sql_move = "UPDATE chambre SET room_number = ? WHERE room_number = ?";
        $stmt_move = $conn->prepare($sql_move);
        $stmt_move->bind_param("ii", $new_room, $current_room);

        if ($stmt_move->execute()) {
            header("Location: client_info.php?room=$new_room");
        } else {
            echo "Error: " . $stmt_move->error;
        }

        $stmt_move->close();
    } else {
        echo "La chambre cible est déjà occupée.";
    }

    $stmt_check->close();
}
$conn->close();
?>
