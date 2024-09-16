<?php
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php?registered=true");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .container h2 {
            color: orange;
            margin-bottom: 1.5em;
        }
        form {
            margin: auto;
            width: 300px;
            padding: 1em;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 1em;
        }
        label {
            color: orangered;
            font-weight: bold;
            margin-bottom: 0.5em;
            display: block;;
        }
        input {
            width: 100%;
            padding: 0.5em;
            margin-bottom: 1em;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
        }
        button {
            padding: 0.7em;
            color: white;
            background-color: orangered;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="">
        <h2>Register</h2>
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
        <button class="back-button" onclick="history.back()">Back</button>
    </form>
    </div>
</body>
</html>
