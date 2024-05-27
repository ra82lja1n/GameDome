<?php
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

$servername = "localhost";
$port = 3000;
$db_username = "rahul";
$db_password = "..//rahul";
$DB = "Project";

// Establishing Connection
$establish_connection = mysqli_connect($servername, $db_username, $db_password, $DB, $port);
if (!$establish_connection) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()]);
    exit();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Getting the POST data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Debugging: log the received data and raw data
file_put_contents('php_debug.log', "Raw Data: " . $rawData . "\nParsed Data: " . print_r($data, true), FILE_APPEND);

if (isset($data['user_id'], $data['game_id'], $data['score'])) {
    $user_id = $data['user_id'];
    $game_id = $data['game_id'];
    $score = $data['score'];

    // Insert the score into the database
    $query = "INSERT INTO scores (user_id, game_id, score) VALUES (?, ?, ?)";
    if ($stmt = $establish_connection->prepare($query)) {
        $stmt->bind_param('iii', $user_id, $game_id, $score);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Score submitted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit score']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$establish_connection->close();
?>
