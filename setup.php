<?php
// Setup script to create default admin user
require_once 'includes/auth.php';

$adminExists = false;
$users = readJSON('users.json');

foreach ($users as $user) {
    if ($user['email'] === 'admin@example.com') {
        $adminExists = true;
        break;
    }
}

if (!$adminExists) {
    $result = registerUser('Administrator', 'admin@example.com', 'admin123', 'admin');
    if ($result['success']) {
        echo "Admin user created successfully!<br>";
        echo "Email: admin@example.com<br>";
        echo "Password: admin123<br>";
        echo "Role: admin<br>";
    } else {
        echo "Error creating admin: " . $result['message'];
    }
} else {
    echo "Admin user already exists.<br>";
}

$teacherExists = false;
foreach ($users as $user) {
    if ($user['email'] === 'teacher@example.com') {
        $teacherExists = true;
        break;
    }
}

if (!$teacherExists) {
    $result = registerUser('Teacher User', 'teacher@example.com', 'teacher123', 'teacher');
    if ($result['success']) {
        echo "Teacher user created successfully!<br>";
        echo "Email: teacher@example.com<br>";
        echo "Password: teacher123<br>";
        echo "Role: teacher<br>";
    }
}

$studentExists = false;
foreach ($users as $user) {
    if ($user['email'] === 'student@example.com') {
        $studentExists = true;
        break;
    }
}

if (!$studentExists) {
    $result = registerUser('Student User', 'student@example.com', 'student123', 'student');
    if ($result['success']) {
        echo "Student user created successfully!<br>";
        echo "Email: student@example.com<br>";
        echo "Password: student123<br>";
        echo "Role: student<br>";
    }
}

echo "<br><a href='login.php'>Go to Login</a>";
?>
