<?php
// Data handling functions for Classroom Management System
require_once __DIR__ . '/auth.php';

// Class functions
function getClasses() {
    return readJSON('classes.json');
}

function addClass($name, $description, $teacher_id) {
    $classes = readJSON('classes.json');
    $newClass = [
        'id' => uniqid(),
        'name' => $name,
        'description' => $description,
        'teacher_id' => $teacher_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $classes[] = $newClass;
    writeJSON('classes.json', $classes);
    return ['success' => true, 'class' => $newClass];
}

function getClassById($id) {
    $classes = readJSON('classes.json');
    foreach ($classes as $class) {
        if ($class['id'] === $id) {
            return $class;
        }
    }
    return null;
}

// Assignment functions
function getAssignments($class_id = null) {
    $assignments = readJSON('assignments.json');
    if ($class_id) {
        return array_filter($assignments, function($a) use ($class_id) {
            return $a['class_id'] === $class_id;
        });
    }
    return $assignments;
}

function addAssignment($class_id, $title, $description, $due_date, $teacher_id) {
    $assignments = readJSON('assignments.json');
    $newAssignment = [
        'id' => uniqid(),
        'class_id' => $class_id,
        'title' => $title,
        'description' => $description,
        'due_date' => $due_date,
        'teacher_id' => $teacher_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $assignments[] = $newAssignment;
    writeJSON('assignments.json', $assignments);
    return ['success' => true, 'assignment' => $newAssignment];
}

// Attendance functions
function getAttendance($class_id = null, $date = null) {
    $attendance = readJSON('attendance.json');
    if ($class_id) {
        $attendance = array_filter($attendance, function($a) use ($class_id) {
            return $a['class_id'] === $class_id;
        });
    }
    if ($date) {
        $attendance = array_filter($attendance, function($a) use ($date) {
            return $a['date'] === $date;
        });
    }
    return $attendance;
}

function markAttendance($class_id, $student_id, $status, $date) {
    $attendance = readJSON('attendance.json');
    
    // Remove existing attendance for this student/class/date
    $attendance = array_filter($attendance, function($a) use ($class_id, $student_id, $date) {
        return !($a['class_id'] === $class_id && $a['student_id'] === $student_id && $a['date'] === $date);
    });
    
    // Add new attendance
    $newAttendance = [
        'id' => uniqid(),
        'class_id' => $class_id,
        'student_id' => $student_id,
        'status' => $status,
        'date' => $date,
        'marked_at' => date('Y-m-d H:i:s')
    ];
    
    $attendance[] = $newAttendance;
    writeJSON('attendance.json', array_values($attendance));
    return ['success' => true];
}

// Announcement functions
function getAnnouncements($class_id = null) {
    $announcements = readJSON('announcements.json');
    if ($class_id) {
        return array_filter($announcements, function($a) use ($class_id) {
            return $a['class_id'] === $class_id;
        });
    }
    return $announcements;
}

function addAnnouncement($class_id, $title, $content, $teacher_id) {
    $announcements = readJSON('announcements.json');
    $newAnnouncement = [
        'id' => uniqid(),
        'class_id' => $class_id,
        'title' => $title,
        'content' => $content,
        'teacher_id' => $teacher_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $announcements[] = $newAnnouncement;
    writeJSON('announcements.json', $announcements);
    return ['success' => true, 'announcement' => $newAnnouncement];
}

// Get all students
function getStudents() {
    $users = readJSON('users.json');
    return array_filter($users, function($u) {
        return $u['role'] === 'student';
    });
}

// Get all teachers
function getTeachers() {
    $users = readJSON('users.json');
    return array_filter($users, function($u) {
        return $u['role'] === 'teacher';
    });
}
