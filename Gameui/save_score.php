<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Check if required parameters are set
if (!isset($_POST['game_id']) || !isset($_POST['score'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit();
}

// Get user ID, game ID, and score from session and POST data
$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'];
$score = $_POST['score'];

// Insert score into database (assuming you have a properly configured database connection)
// Adjust the query according to your database schema
$sql = "INSERT INTO users_scores (user_id, game_id, score) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $game_id, $score);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to save score"]);
}

$stmt->close();
$conn->close();
?>
