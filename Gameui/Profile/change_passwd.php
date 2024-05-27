<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $newPassword = $input['newPassword'] ?? '';

    if (empty($newPassword)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "New password cannot be empty"]);
        exit();
    }

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Establish a connection to the database
    $servername = "localhost";
    $port = 3306; // MySQL default port
    $username = "rahul";
    $password = "..//rahul";
    $dbname = "Project";

    // Establishing Connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check the connection
    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
        exit();
    }

    // Prepare the SQL statement to update the password
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to prepare SQL statement: " . $conn->error]);
        exit();
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("si", $newPassword, $userId);
    $result = $stmt->execute();

    if ($result) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Password changed successfully"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to change password: " . $stmt->error]);
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
}
?>

