<?php
// filepath: c:\xampp\htdocs\cryptox\auth\add_transaction.php
session_start();
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = ''; // Default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $crypto = $_POST['crypto'];
    $amount = $_POST['amount'];
    $wallet_address = $_POST['wallet_address'];

    // Validate inputs
    if (empty($type) || empty($crypto) || empty($amount) || empty($wallet_address)) {
        echo "<script>alert('All fields are required.'); window.location.href='../transaction.html';</script>";
        exit();
    }

    // Calculate a fee (example: 2% of the amount)
    $fee = $amount * 0.02;

    // Insert transaction into the database
    $stmt = $conn->prepare("INSERT INTO transactions (type, crypto, amount, wallet_address, fee) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $type, $crypto, $amount, $wallet_address, $fee);

    if ($stmt->execute()) {
        echo "<script>alert('Transaction added successfully.'); window.location.href='../transaction.html';</script>";
    } else {
        echo "<script>alert('Failed to add transaction.'); window.location.href='../transaction.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>