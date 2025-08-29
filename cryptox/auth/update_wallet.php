<?php
// filepath: c:\xampp\htdocs\cryptox\auth\update_wallet.php
session_start();
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = ''; // Default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You are not logged in.'); window.location.href='../wallet.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$crypto = $_POST['crypto']; // Cryptocurrency (e.g., BTC, ETH)
$amount = $_POST['amount']; // Amount to deposit or withdraw
$action = $_POST['action']; // Action (deposit or withdraw)

// Validate inputs
if (empty($crypto) || empty($amount) || !in_array($action, ['deposit', 'withdraw'])) {
    echo "<script>alert('Invalid input.'); window.location.href='../wallet.html';</script>";
    exit();
}

// Insert or update wallet balance
if ($action === 'deposit') {
    // Deposit: Add the amount to the wallet balance
    $stmt = $conn->prepare("INSERT INTO wallets (user_id, crypto, balance) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE balance = balance + ?");
    $stmt->bind_param("isdd", $user_id, $crypto, $amount, $amount);
} elseif ($action === 'withdraw') {
    // Withdraw: Subtract the amount from the wallet balance if sufficient funds exist
    $stmt = $conn->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND crypto = ? AND balance >= ?");
    $stmt->bind_param("dids", $amount, $user_id, $crypto, $amount);
}

if ($stmt->execute()) {
    echo "<script>alert('Transaction successful.'); window.location.href='../wallet.html';</script>";
} else {
    echo "<script>alert('Transaction failed.'); window.location.href='../wallet.html';</script>";
}

$stmt->close();
$conn->close();
?>