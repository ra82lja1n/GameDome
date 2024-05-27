<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["username"];
    $password = $_POST["password"];

    $servername = "localhost";
    $port = 3306; // MySQL default port
    $db_username = "rahul";
    $db_password = "..//rahul";
    $DB = "Project";

    // Establishing Connection
    $establish_connection = mysqli_connect($servername, $db_username, $db_password, $DB, $port);
    if (!$establish_connection) {
        echo json_encode(array("error" => "Connection failed: " . mysqli_connect_error()));
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = mysqli_prepare($establish_connection, $sql);
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password_hash'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $user_id = $row['id'];
            $username = $row['username'];
            $email = $row['email'];

            // Store user data in session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            // Return user ID as JSON response
            echo json_encode(array("user_id" => $user_id));
            exit();
        }
    }

    // If execution reaches here, login failed
    echo json_encode(array("error" => "Invalid email or password"));
    exit();
}
?>
