<?php
session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);
$_SESSION['success'] = 'You are Logged out successfully';
header("Location: index.html");
?>