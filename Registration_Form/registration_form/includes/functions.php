<?php
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function is_valid_age($dob) {
    $age = date_diff(date_create($dob), date_create('today'))->y;
    return $age >= 18;
}

function is_strong_password($password) {
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

function check_session_timeout() {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
        session_unset();
        session_destroy();
        echo "<script>alert('Session is over. Please log in again.'); window.location.href='register.php';</script>";
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>
