<?php
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = '';  // Default is empty in XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


// SIGNUP
if (isset($_POST['signup'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($phone) || empty($username) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='../LoginPage.html';</script>";
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        exit();
        
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (email, phone, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $phone, $username, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to CryptoX home page after successful signup
        header("Location: /cryptox/CryptoX.html");
        exit();
    } else {
        echo "<script>alert('Signup failed: Email or Username may already exist.'); window.location.href='../LoginPage.html';</script>";
    }
    $stmt->close();
}

// LOGIN
if (isset($_POST['login'])) {
    $input = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($input) || empty($password)) {
        echo "<script>alert('Email/Username and Password are required.'); window.location.href='../LoginPage.html';</script>";
        exit();
    }

    // Check credentials
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $stmt->bind_result($hashed_password);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Redirect on successful login
        header("Location: /cryptox/CryptoX.html");
        exit();
    } else {
        echo "<script>alert('Invalid email/username or password.'); window.location.href='../LoginPage.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>