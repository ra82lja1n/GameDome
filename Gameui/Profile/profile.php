<?php
session_start();

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

$username = "User not found";
$email = "User not found";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
 
    // Fetch user data
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($establish_connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $email = $row['email'];
    }
} else {
    $username = "User session ID not set";
    $email = "User session ID not set";
}
mysqli_close($establish_connection);
?>





<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <title>Profile</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Ubuntu', sans-serif;

      --test-bg-color: linear-gradient(to right, rgb(0, 255, 150), rgb(0, 150, 255));
      --test-border: 2px solid black;
    }

    html,
    body {
      height: 100vh;
      width: 100vw;
    }

    body {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #638ecb;

    }

    .profile-container {
      background: #006da4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 70%;
      height: 70%;
      border: var(--test-border);
      border-radius: 25px;

    }

    .profile {
      background-color: #8aaee0;
      width: 100%;
      height: 80%;
      border: var(--test-border);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .profile-pic-container {
      /* background: #ddd; */
      width: 30%;
      height: 85%;
      border-right: var(--test-border);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .profile-pic {
      background: #ddd;
      width: 200px;
      /* height: 150px; */
      border: var(--test-border);
      padding: 20px;
      border-radius: 100%;

    }

    .profile-info-container {
      width: 70%;
      height: 100%;
      /* border: var(--test-border); */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .profile-info {
      background: #dfdfdf94;
      width: 85%;
      height: 85%;
      border: var(--test-border);
      display: flex;
      align-items: center;
      justify-content: flex-start;
    }

    thead {
      padding: 10px;
      margin-right: 5px;
      border-right: var(--test-border);
    }

    tbody {
      width: 60%;
      padding: 10px;
      margin-left: 5px;
    }

    thead>tr {
      display: flex;
      flex-direction: column;
      align-items: flex-start;

    }

    tbody>tr {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      /* margin: 15px 5px; */

    }

    thead>tr>th {
      margin: 15px 5px;
      font-size: 1.3rem;
      border-bottom: var(--test-border);

    }

    tbody>tr>td {
      width: 100%;
      margin: 15px 5px;
      border-bottom: var(--test-border);
      font-size: 1.3rem;
    }

    #edit-passwd {
      cursor: pointer;
      position: absolute;
      right: 28%;
    }

    @media (max-width: 999px) {
      #edit-passwd {
        cursor: pointer;
        position: absolute;
        right: 20%;
      }

      .profile-pic {
        width: 120px;

      }

      .profile-info {
        overflow-x: scroll;
        overflow-y: hidden;
      }
    }

    @media (max-width: 600px) {
      .profile {
        flex-direction: column;
      }

      .profile-pic {
        width: 150px;

      }

      .profile-pic-container {
        width: 100%;
        height: 50%;
        border-bottom: var(--test-border);
        border-right: none;
      }

      .profile-info-container {
        width: 100%;
      }

      .profile-info {
        width: 100%;
      }

      #edit-passwd {
        cursor: pointer;
        position: absolute;
        right: 20%;
      }


    }
  </style>
</head>

<body>
<div class="profile-container">
    <h1 style="margin-bottom:20px ; padding: 8px; background: #022b42; color: #fff; border-radius: 15px; width: 50%; text-align: center;">
        Profile</h1>
    <div class="profile">
        <div class="profile-pic-container">
            <img class="profile-pic" src="360_F_660927626_HThgikmcaqtZfZVONwXBiPDeCCxNXBfx.webp" alt="profile-pic">
        </div>

        <div class="profile-info-container">
            <table class="profile-info">
                <thead class="thead">
                    <tr>
                        <th>Name :</th>
                        <th>Email :</th>
                        <th>Passwd :</th>
                        <th>AvgScore:</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <tr>
                        <td><?php echo $username ?></td>
                        <td><?php echo $email ?></td>
                        <td>**********
                            <span id="edit-passwd" class="material-symbols-outlined" onclick="changePassword()">
                                edit
                            </span>
                        </td>
                        <td>None</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



  <script>
    function matchHeight(element1, element2) {
      const max_height = Math.max(document.querySelector(element1).offsetHeight, document.querySelector(element2).offsetHeight)

      document.querySelector(element1).style.height = max_height + 'px';
      document.querySelector(element2).style.height = max_height + 'px';
    }

    // Function to handle changing password
    function changePassword() {
    Swal.fire({
        title: 'Enter New Password',
        input: 'password',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Change',
        showLoaderOnConfirm: true,
        preConfirm: (newPassword) => {
            return fetch('change_passwd.php', { // Update this path
                method: 'POST',
                body: JSON.stringify({ newPassword: newPassword }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Password Changed!', 'Your password has been updated.', 'success');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#edit-passwd').addEventListener('click', () => {
        changePassword();
    });
});


  </script>
</body>

</html>