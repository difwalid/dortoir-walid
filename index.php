<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dortoir";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current date
$today = date('Y-m-d');

// Get all rooms and their statuses
$sql = "SELECT room_number, departure_date FROM chambre";
$result = $conn->query($sql);

$occupied_rooms = array();
$free_rooms = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['departure_date'] == $today) {
            // Remove client from the room if departure date is today
            $room_number = $row['room_number'];
            $sql_delete = "DELETE FROM chambre WHERE room_number = $room_number";
            $conn->query($sql_delete);
        }
    }
}

// Re-fetch to update room statuses
$sql = "SELECT room_number FROM chambre";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $occupied_rooms[] = $row['room_number'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dortoir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .button-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .room-button {
            position: relative;
            width: 100px;
            height: 100px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .room-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .room-button.occupied {
            background-color: #ff0000;
        }

        .status-dot {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 10px;
            height: 10px;
            background-color: #28a745;
            border-radius: 50%;
        }

        .room-button.occupied .status-dot {
            background-color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue au Dortoir</h1>
        <div class="button-grid">
            <?php for ($i = 1; $i <= 9; $i++): ?>
                <button class="room-button <?php echo in_array($i, $occupied_rooms) ? 'occupied' : ''; ?>" onclick="redirectToPage(<?php echo $i; ?>, <?php echo in_array($i, $occupied_rooms) ? 'true' : 'false'; ?>)">
                    <span class="status-dot"></span>Chambre <?php echo $i; ?>
                </button>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        function redirectToPage(roomNumber, isOccupied) {
            if (isOccupied) {
                window.location.href = `client_info.php?room=${roomNumber}`;
            } else {
                window.location.href = `form.php?room=${roomNumber}`;
            }
        }
    </script>
</body>
</html>
