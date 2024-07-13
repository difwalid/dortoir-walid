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
    $room_number = intval($_POST['room_number']);
    $fname = $conn->real_escape_string($_POST['fname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $departure_date = $conn->real_escape_string($_POST['departure_date']);

    $sql = "UPDATE chambre SET fname = ?, lastname = ?, departure_date = ? WHERE room_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fname, $lastname, $departure_date, $room_number);

    if ($stmt->execute()) {
        header("Location: client_info.php?room=$room_number");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
