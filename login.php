<?php
include('database.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
            color: white;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 300px;
            text-align: center;
        }
        .container h2 {
            color: red; 
            margin-bottom: 1em;
        }
        .container label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
        }
        .container input {
            width: 100%;
            padding: 0.5em;
            margin-bottom: 1em;
            border: none;
            border-radius: 5px;
        }
        .container button {
            width: 100%;
            padding: 0.7em;
            background-color: red; 
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .container button:hover {
            background-color: darkred;
        }
        .container .error {
            color: red;
            margin-bottom: 1em;
        }
        .container p {
            margin-top: 1em;
            color: white;
        }
        .container p a {
            color: red;
            text-decoration: none;
        }
        .container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>TOURIST REST AREA MANOLO FORTICH</h1>
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
        <p>Not registered? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
