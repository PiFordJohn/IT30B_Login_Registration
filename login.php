<?php
include('database.php');
session_start();

$error = "";
$email = trim(htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'));
$password = trim($_POST['password'] ?? '');
$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check failed login attempts in the last 15 minutes
    $check_attempts = $conn->prepare("SELECT COUNT(*) FROM failed_logins WHERE email = ? AND attempt_time > (NOW() - INTERVAL 15 MINUTE)");
    if (!$check_attempts) {
        die("Check failed attempts query failed: " . $conn->error);
    }
    
    $check_attempts->bind_param("s", $email);
    $check_attempts->execute();
    $check_attempts->bind_result($failed_attempts);
    $check_attempts->fetch();
    $check_attempts->close();

    // If 5 or more failed attempts, lock account temporarily
    if ($failed_attempts >= 5) {
        $error = "Your account is temporarily locked due to multiple failed login attempts.";
    } else {
        // Validate login credentials
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];

                // Clear failed login attempts after a successful login
                $clear_attempts = $conn->prepare("DELETE FROM failed_logins WHERE email = ?");
                $clear_attempts->bind_param("s", $email);
                $clear_attempts->execute();
                $clear_attempts->close();

                header("Location: dashboard.php");
                exit();
            } else {
                // Log failed login attempt
                $log_attempt = $conn->prepare("INSERT INTO failed_logins (email, ip_address, status, attempt_time) VALUES (?, ?, 'failed', NOW())");
                if (!$log_attempt) {
                    die("Prepare failed: " . $conn->error);
                }
                $log_attempt->bind_param("ss", $email, $ip_address);
                if (!$log_attempt->execute()) {
                    die("Failed to log attempt: " . $log_attempt->error);
                }
                $log_attempt->close();

                $error = "Invalid email or password.";

                // Re-check failed attempts after logging attempt
                $check_attempts = $conn->prepare("SELECT COUNT(*) FROM failed_logins WHERE email = ? AND attempt_time > (NOW() - INTERVAL 15 MINUTE)");
                $check_attempts->bind_param("s", $email);
                $check_attempts->execute();
                $check_attempts->bind_result($failed_attempts);
                $check_attempts->fetch();
                $check_attempts->close();

                // If 5 failed attempts, send alert email to administrator
                if ($failed_attempts >= 5) {
                    $admin_email = "cliffordjohnburdeoslutrago@gmail.com";  // Admin email
                    $subject = "🚨 Alert: Multiple Failed Login Attempts Detected";
                    $message = "Warning! There have been multiple failed login attempts for email: $email from IP: $ip_address.";
                    $headers = "From: noreply@example.com";
                    mail($admin_email, $subject, $message, $headers);
                }
            }
        } else {
            die("Login query failed: " . $conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
        .container h1{
            color: orange;
            font-style: inherit;
            font-weight: 300;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .container label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
            color: orangered;
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
            background-color: orangered; 
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
        <h1>Mabuhay!</h1>
        
        <?php if (!empty($error)) { echo "<div class='error'>$error</div>"; } ?>
        
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
