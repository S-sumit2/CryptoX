<?php
// filepath: c:\xampp\htdocs\cryptox\auth\get_wallet.php
session_start();
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = ''; // Default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Assuming the user is logged in and their ID is stored in the session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Fetch wallet balances
$stmt = $conn->prepare("SELECT crypto, balance FROM wallets WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wallets = [];
while ($row = $result->fetch_assoc()) {
    $wallets[] = $row;
}

header('Content-Type: application/json');
echo json_encode($wallets);

$stmt->close();
$conn->close();
?>