<?php
// Registration handler
require_once 'includes/auth.php';

$name = $_POST["name"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';

// Check for validation
if (empty($name) || empty($email) || empty($password)) {
    echo "All fields are required. <a href='index.html'>Go back</a>";
    exit();
}

if (strlen($password) < 8) {
    echo "Password should be 8 or more characters long. <a href='index.html'>Go back</a>";
    exit();
}

// Check if role is set (for admin/teacher registration)
$role = $_POST["role"] ?? 'student';

// Register the user
$result = registerUser($name, $email, $password, $role);

if ($result['success']) {
    echo "Registration Successful! <a href='login.php'>Click here to login</a>";
} else {
    echo $result['message'] . " <a href='index.html'>Go back</a>";
}
?>
