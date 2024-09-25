<?php
session_start();
include 'includes/functions.php';
check_session_timeout();

if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Welcome, <?php echo $user['first_name']; ?></h2>
    <p>First Name: <?php echo $user['first_name']; ?></p>
    <p>Last Name: <?php echo $user['last_name']; ?></p>
    <p>Date of Birth: <?php echo $user['date_of_birth']; ?></p>
    <p>Address: <?php echo $user['address']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <a href="edit.php">Edit</a>
    <a href="logout.php">Logout</a>
</body>
</html>
