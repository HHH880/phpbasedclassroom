<?php
// Authentication functions for Classroom Management System

// Start session
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Check if user is logged in
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

// Get current user role
function getUserRole() {
    startSession();
    return $_SESSION['role'] ?? null;
}

// Get current user name
function getUserName() {
    startSession();
    return $_SESSION['name'] ?? null;
}

// Get data directory path
function getDataPath() {
    return __DIR__ . '/../data/';
}

// Read JSON file
function readJSON($filename) {
    $path = getDataPath() . $filename;
    if (!file_exists($path)) {
        return [];
    }
    $content = file_get_contents($path);
    return json_decode($content, true) ?? [];
}

// Write JSON file
function writeJSON($filename, $data) {
    $path = getDataPath() . $filename;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

// Register new user
function registerUser($name, $email, $password, $role = 'student') {
    $users = readJSON('users.json');
    
    // Check if email already exists
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
    }
    
    // Create new user
    $newUser = [
        'id' => uniqid(),
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $users[] = $newUser;
    writeJSON('users.json', $users);
    
    return ['success' => true, 'message' => 'Registration successful'];
}

// Login user
function loginUser($email, $password) {
    $users = readJSON('users.json');
    
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return ['success' => true, 'message' => 'Login successful'];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid email or password'];
}

// Logout user
function logoutUser() {
    startSession();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Require login (redirect if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Require specific role
function requireRole($role) {
    requireLogin();
    if (getUserRole() !== $role && getUserRole() !== 'admin') {
        header('Location: dashboard.php');
        exit();
    }
}
