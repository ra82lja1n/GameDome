<?php
header('Content-Type: application/json');

$servername = "localhost";
$port = 3306; // Default port for MySQL
$username = "rahul";
$password = "..//rahul";
$dbname = "Project";

// Create connection
$conn = new mysqli($servername . ":" . $port, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch games with highest scores
$sql = "SELECT id, name AS title, link, imglink AS image, genre_id AS genreId FROM games"; // Include genre_id as genreId
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$games = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
} else {
    echo json_encode(["error" => "No games found"]);
    exit();
}

$conn->close();

echo json_encode($games);
?>
