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
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = validate_input($_POST["first_name"]);
    $last_name = validate_input($_POST["last_name"]);
    $date_of_birth = validate_input($_POST["date_of_birth"]);
    $address = validate_input($_POST["address"]);
    $email = validate_input($_POST["email"]);

    if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
        $errors[] = "Only letters and white space allowed in first name";
    }
    if (!is_valid_age($date_of_birth)) {
        $errors[] = "You must be at least 18 years old";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, date_of_birth = ?, address = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $date_of_birth, $address, $email, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('User details updated successfully'); window.location.href='welcome.php';</script>";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit Details</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
        <button type="submit" name="update_field" value="first_name">Update</button>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
        <button type="submit" name="update_field" value="last_name">Update</button>
        <br>
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>" required>
        <button type="submit" name="update_field" value="date_of_birth">Update</button>
        <br>
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $user['address']; ?>" required>
        <button type="submit" name="update_field" value="address">Update</button>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <button type="submit" name="update_field" value="email">Update</button>
        <br>
        <button type="submit" name="update_all">Update All</button>
    </form>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>
</body>
</html>
