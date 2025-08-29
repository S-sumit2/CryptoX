<?php
// filepath: c:\xampp\htdocs\cryptox\logout.php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the homepage
header("Location: index.html");
exit();
?>