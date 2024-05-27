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

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    header('Location: login.php'); // Redirect to login if the user is not authenticated
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Snake Game</title>
  <style>
    #board {
      display: block;
      margin: 20px auto;
      background-color: black;
    }
    #gameOverPopup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: white;
      border: 2px solid black;
      text-align: center;
    }
    #gameOverPopup button {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <canvas id="board"></canvas>
  <div id="gameOverPopup">
    <h2>GAME OVER</h2>
    <p id="finalScore"></p>
    <button onclick="restartGame()">Play Again</button>
  </div>

  <script>
    // Get user session ID from PHP session
    const userId = <?php echo json_encode($userId); ?>;

    // Ensure user ID is available
    if (!userId) {
      alert('User ID is not available. Please log in.');
      throw new Error('User ID is not available.');
    } else {
      console.log('User ID:', userId); // Debugging log to verify user ID
    }

    // JavaScript code for the snake game

    // Board
    var blockSize = 25;
    var rows = 20;
    var cols = 20;
    var board;
    var context; 
    var score = 0;

    // Snake head
    var snakeX = blockSize * 5;
    var snakeY = blockSize * 5;

    var velocityX = 0;
    var velocityY = 0;

    var snakeBody = [];

    // Food
    var foodX;
    var foodY;

    var gameOver = false;

    window.onload = function() {
        board = document.getElementById("board");
        board.height = rows * blockSize;
        board.width = cols * blockSize;
        context = board.getContext("2d"); // Used for drawing on the board

        placeFood();
        document.addEventListener("keyup", changeDirection);
        setInterval(update, 1000/10); // 100 milliseconds
    }

    function update() {
        if (gameOver) {
            context.fillStyle = "white";
            context.font = "30px Arial";
            context.fillText("GAME OVER", board.width / 4, board.height / 2);
            submitScore(userId);
            showGameOverPopup();
            return;
        }

        context.fillStyle = "black";
        context.fillRect(0, 0, board.width, board.height);

        context.fillStyle = "red";
        context.fillRect(foodX, foodY, blockSize, blockSize);

        context.fillStyle = "white";
        context.font = "16px Arial";
        context.fillText("Score: " + score, 10, 18); 

        if (snakeX == foodX && snakeY == foodY) {
            snakeBody.push([foodX, foodY]);
            placeFood();
            score += 10;
        }
        
        for (let i = snakeBody.length - 1; i > 0; i--) {
            snakeBody[i] = snakeBody[i - 1];
        }
        if (snakeBody.length) {
            snakeBody[0] = [snakeX, snakeY];
        }

        context.fillStyle = "lime";
        snakeX += velocityX * blockSize;
        snakeY += velocityY * blockSize;
        context.fillRect(snakeX, snakeY, blockSize, blockSize);
        for (let i = 0; i < snakeBody.length; i++) {
            context.fillRect(snakeBody[i][0], snakeBody[i][1], blockSize, blockSize);
        }

        if (snakeX < 0 || snakeX >= cols * blockSize || snakeY < 0 || snakeY >= rows * blockSize) {
            gameOver = true;
        }

        for (let i = 0; i < snakeBody.length; i++) {
            if (snakeX == snakeBody[i][0] && snakeY == snakeBody[i][1]) {
                gameOver = true;
            }
        }
    }

    function changeDirection(e) {
        if (e.code == "ArrowUp" && velocityY != 1) {
            velocityX = 0;
            velocityY = -1;
        } else if (e.code == "ArrowDown" && velocityY != -1) {
            velocityX = 0;
            velocityY = 1;
        } else if (e.code == "ArrowLeft" && velocityX != 1) {
            velocityX = -1;
            velocityY = 0;
        } else if (e.code == "ArrowRight" && velocityX != -1) {
            velocityX = 1;
            velocityY = 0;
        }
    }

    function placeFood() {
        let foodOnSnake;
        do {
            foodOnSnake = false;
            foodX = Math.floor(Math.random() * cols) * blockSize;
            foodY = Math.floor(Math.random() * rows) * blockSize;

            for (let i = 0; i < snakeBody.length; i++) {
                if (foodX == snakeBody[i][0] && foodY == snakeBody[i][1]) {
                    foodOnSnake = true;
                    break;
                }
            }
        } while (foodOnSnake);
    }

    function showGameOverPopup() {
        document.getElementById('finalScore').textContent = 'Your Score: ' + score;
        document.getElementById('gameOverPopup').style.display = 'block';
    }

    function restartGame() {
        // Reset game variables
        snakeX = blockSize * 5;
        snakeY = blockSize * 5;
        velocityX = 0;
        velocityY = 0;
        snakeBody = [];
        score = 0;
        gameOver = false;
        document.getElementById('gameOverPopup').style.display = 'none';
        placeFood();
    }

    function submitScore(userId) {
    if (userId) {
        const data = { user_id: userId, game_id: 1, score: score };
        
        console.log('Submitting score:', data);  // Debugging log

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'submit_score.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        console.log('Score submitted response:', response);  // Debugging log
                    } catch (e) {
                        console.error('Error parsing JSON response:', e, xhr.responseText);
                    }
                } else {
                    console.error('Error submitting score: HTTP status', xhr.status);
                }
            }
        };

        xhr.send(JSON.stringify(data));
    }
}


  </script>
</body>
</html>
