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
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $departure_date = $_POST['departure_date'];
    $room_number = $_POST['room_number'];

    $sql = "INSERT INTO chambre (fname, lastname, departure_date, room_number) VALUES ('$name', '$lastname', '$departure_date', '$room_number')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Chambre</title>
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

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chambre <?php echo $_GET['room']; ?></h2>
        <form method="post" action="form.php">
            <input type="hidden" name="room_number" value="<?php echo $_GET['room']; ?>">
            <label for="name">Prénom:</label>
            <input type="text" id="name" name="name" required>

            <label for="lastname">Nom:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="departure_date">Date de sortie:</label>
            <input type="date" id="departure_date" name="departure_date" required>

            <button type="submit">Soumettre</button>
        </form>
    </div>
</body>
</html>
