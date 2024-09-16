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
            background-image: url('background.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        form {
            margin: auto;
            width: 300px;
            padding: 1em;
            background: white;
            border-radius: 1em;
        }
        label {
            margin-bottom: .5em;
            color: #333333;
        }
        input {
            padding: .5em;
            color: #333333;
            background: #f9f9f9;
            border: none;
            border-radius: 4px;
            margin-bottom: 1em;
        }
        button {
            padding: 0.7em;
            color: white;
            background-color: #333333;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
