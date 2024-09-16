<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            background-image: url('image/Tourist.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
            padding-top: 100px;
        }
        h1 {
            font-size: 3em;
            color: orangered;
        }
        a {
            color: orangered;
            font-size: 20px;
        }
        p{
            color:orange;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 50px;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Mabuhay!</p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
