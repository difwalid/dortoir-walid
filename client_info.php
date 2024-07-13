<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dortoir";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$room_number = $_GET['room'];
$sql = "SELECT fname, lastname, departure_date FROM chambre WHERE room_number = $room_number";
$result = $conn->query($sql);

$client_info = null;
if ($result->num_rows > 0) {
    $client_info = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Handle delete
        $sql_delete = "DELETE FROM chambre WHERE room_number = $room_number";
        if ($conn->query($sql_delete) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        // Handle update
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $departure_date = $_POST['departure_date'];

        $sql_update = "UPDATE chambre SET fname='$name', lastname='$lastname', departure_date='$departure_date' WHERE room_number=$room_number";
        if ($conn->query($sql_update) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST['move'])) {
        // Handle move
        $new_room_number = $_POST['new_room_number'];

        // Check if new room is free
        $sql_check = "SELECT * FROM chambre WHERE room_number = $new_room_number";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            // Update the room number
            $sql_move = "UPDATE chambre SET room_number=$new_room_number WHERE room_number=$room_number";
            if ($conn->query($sql_move) === TRUE) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error moving record: " . $conn->error;
            }
        } else {
            echo "Error: New room is already occupied.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations du Client</title>
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
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
        }

        input, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Informations du Client - Chambre <?php echo $room_number; ?></h2>
        <?php if ($client_info): ?>
            <form method="post">
                <label>Prénom:</label>
                <input type="text" name="name" value="<?php echo $client_info['fname']; ?>" required>

                <label>Nom:</label>
                <input type="text" name="lastname" value="<?php echo $client_info['lastname']; ?>" required>

                <label>Date de sortie:</label>
                <input type="date" name="departure_date" value="<?php echo $client_info['departure_date']; ?>" required>

                <button type="submit" name="update">Modifier</button>
            </form>

            <form method="post" style="margin-top: 20px;">
                <label>Déplacer vers chambre:</label>
                <input type="number" name="new_room_number" min="1" max="9" required>
                <button type="submit" name="move">Déplacer</button>
            </form>

            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="delete" class="delete-button">Supprimer</button>
            </form>
        <?php else: ?>
            <p>Aucun client dans cette chambre.</p>
        <?php endif; ?>
    </div>
</body>
</html>
