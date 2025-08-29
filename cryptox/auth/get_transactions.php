<?php
// filepath: c:\xampp\htdocs\cryptox\auth\get_transactions.php
$host = 'localhost';
$db = 'user_auth';
$user = 'root';
$pass = ''; // Default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch transactions
$result = $conn->query("SELECT date, type, crypto, amount, fee, status FROM transactions ORDER BY date DESC");

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($transactions);

$conn->close();
?>