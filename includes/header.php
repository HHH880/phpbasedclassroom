<?php
// Common header for all pages
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>Classroom Management System</h1>
            </div>
            <?php if (isLoggedIn()): ?>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <span class="user-info">Welcome, <?php echo htmlspecialchars(getUserName()); ?> (<?php echo htmlspecialchars(getUserRole()); ?>)</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
            <?php endif; ?>
        </nav>
    </header>
    <main>
