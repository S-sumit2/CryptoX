<?php
// filepath: c:\xampp\htdocs\cryptox\auth\update_profile.php
session_start();
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = ''; // Default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the user is logged in and their ID is stored in the session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "<script>alert('User not logged in'); window.location.href='../profile.html';</script>";
    exit();
}

// Get form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = trim($_POST['password']);

// Validate inputs
if (empty($name) || empty($email) || empty($phone)) {
    echo "<script>alert('All fields except password are required.'); window.location.href='../profile.html';</script>";
    exit();
}

// Update user data
if (!empty($password)) {
    // Hash the password if provided
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $hashed_password, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
}

if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully.'); window.location.href='../profile.html';</script>";
} else {
    echo "<script>alert('Failed to update profile.'); window.location.href='../profile.html';</script>";
}

$stmt->close();
$conn->close();
?>