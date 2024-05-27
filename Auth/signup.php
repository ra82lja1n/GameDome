<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetching Data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connection parameters
    $servername = "localhost";
    $port = 3000;
    $db_username = "rahul"; 
    $db_password = "..//rahul"; 
    $DB = "Project";

    // Establishing Connection
    $establish_connection = mysqli_connect($servername, $db_username, $db_password, $DB, $port);
    if (!$establish_connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    // Inserting Data into the DB using prepared statements
    $query = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $statement = mysqli_prepare($establish_connection, $query);
    mysqli_stmt_bind_param($statement, "sss", $username, $email, $hashed_password);
    $success = mysqli_stmt_execute($statement);

    if ($success) {
        // Registration successful
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: "Registration Successful",
                    text: "Congrats Man",
                    icon: "success"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.html';
                    } else {
                        window.location.href = 'SignUp.html';
                    }
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        // Registration failed
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: "Registration Failed",
                    text: "Sorry Man",
                    icon: "error"
                });
            </script>
        </body>
        </html>
        <?php
    }

    // Terminating the Connection
    mysqli_close($establish_connection);
}
?>
