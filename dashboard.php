<?php
// Dashboard - Main page after login
require_once 'includes/auth.php';
require_once 'includes/data_handler.php';

// Require login to access dashboard
requireLogin();

$userRole = getUserRole();
$userName = getUserName();
$userId = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_class':
                if ($userRole === 'admin' || $userRole === 'teacher') {
                    $result = addClass($_POST['name'], $_POST['description'], $userId);
                }
                break;
            case 'add_assignment':
                if ($userRole === 'admin' || $userRole === 'teacher') {
                    $result = addAssignment($_POST['class_id'], $_POST['title'], $_POST['description'], $_POST['due_date'], $userId);
                }
                break;
            case 'add_announcement':
                if ($userRole === 'admin' || $userRole === 'teacher') {
                    $result = addAnnouncement($_POST['class_id'], $_POST['title'], $_POST['content'], $userId);
                }
                break;
            case 'mark_attendance':
                if ($userRole === 'admin' || $userRole === 'teacher') {
                    $result = markAttendance($_POST['class_id'], $_POST['student_id'], $_POST['status'], $_POST['date']);
                }
                break;
        }
    }
}

// Get data for dashboard
$classes = getClasses();
$assignments = getAssignments();
$announcements = getAnnouncements();
$students = getStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Classroom Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>Classroom Management System</h1>
            </div>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <span class="user-info">Welcome, <?php echo htmlspecialchars($userName); ?> (<?php echo htmlspecialchars($userRole); ?>)</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>
    
    <main>
        <div class="dashboard-container">
            <h2>Dashboard</h2>
            
            <!-- Statistics Cards -->
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Classes</h3>
                    <p><?php echo count($classes); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Assignments</h3>
                    <p><?php echo count($assignments); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Announcements</h3>
                    <p><?php echo count($announcements); ?></p>
                </div>
                <?php if ($userRole === 'admin' || $userRole === 'teacher'): ?>
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <p><?php echo count($students); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($userRole === 'admin' || $userRole === 'teacher'): ?>
            
            <!-- Add Class Form -->
            <div class="section">
                <h3>Add New Class</h3>
                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="add_class">
                    <input type="text" name="name" placeholder="Class Name" required>
                    <input type="text" name="description" placeholder="Description" required>
                    <input type="submit" value="Add Class">
                </form>
            </div>
            
            <!-- Add Assignment Form -->
            <div class="section">
                <h3>Add New Assignment</h3>
                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="add_assignment">
                    <select name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="title" placeholder="Assignment Title" required>
                    <input type="text" name="description" placeholder="Description" required>
                    <input type="date" name="due_date" required>
                    <input type="submit" value="Add Assignment">
                </form>
            </div>
            
            <!-- Add Announcement Form -->
            <div class="section">
                <h3>Post Announcement</h3>
                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="add_announcement">
                    <select name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="content" placeholder="Content" required></textarea>
                    <input type="submit" value="Post">
                </form>
            </div>
            
            <?php endif; ?>
            
            <!-- Classes List -->
            <div class="section">
                <h3>Classes</h3>
                <?php if (empty($classes)): ?>
                <p>No classes available.</p>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($class['name']); ?></td>
                            <td><?php echo htmlspecialchars($class['description']); ?></td>
                            <td><?php echo htmlspecialchars($class['created_at']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            
            <!-- Assignments List -->
            <div class="section">
                <h3>Assignments</h3>
                <?php if (empty($assignments)): ?>
                <p>No assignments available.</p>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['description']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['due_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            
            <!-- Announcements List -->
            <div class="section">
                <h3>Announcements</h3>
                <?php if (empty($announcements)): ?>
                <p>No announcements.</p>
                <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                <div class="announcement">
                    <h4><?php echo htmlspecialchars($announcement['title']); ?></h4>
                    <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                    <small>Posted: <?php echo htmlspecialchars($announcement['created_at']); ?></small>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
