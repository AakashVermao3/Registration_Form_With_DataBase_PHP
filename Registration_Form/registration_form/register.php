<?php
include 'includes/db.php';
include 'includes/functions.php';

session_start();

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = validate_input($_POST["first_name"]);
    $last_name = validate_input($_POST["last_name"]);
    $date_of_birth = validate_input($_POST["date_of_birth"]);
    $address = validate_input($_POST["address"]);
    $email = validate_input($_POST["email"]);
    $password = validate_input($_POST["password"]);

    if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
        $errors[] = "Only letters and white space allowed in first name";
    }
    if (!is_valid_age($date_of_birth)) {
        $errors[] = "You must be at least 18 years old";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (!is_strong_password($password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one letter and one number";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, date_of_birth, address, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $date_of_birth, $address, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['LAST_ACTIVITY'] = time();
            echo "<script>alert('User successfully registered'); window.location.href='welcome.php';</script>";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
        <br>
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" required>
        <br>
        <label for="address">Address:</label>
        <input type="text" name="address" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Submit</button>
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
