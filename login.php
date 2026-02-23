<?php
// Login handler
require_once 'includes/auth.php';

$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($email) || empty($password)) {
        $error = "All fields are required";
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Classroom Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <br>
            <label for="pass">Password</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="LOGIN">
            <p>Don't have an account? <a href="index.html">Register here</a></p>
        </form>
    </div>
</body>
</html>
