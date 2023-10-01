<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to redirect the user to the correct recipe view page.
 * 
 */

session_start();

/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['userId'];

/**
 * Prepares a SQL statement to get the user's role
 */
$stmt = $conn->prepare("SELECT role FROM User WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$recipeId = $_GET['recipeId'];

/**
 * Redirects the user to the appropriate recipe view page based on their role
 */
if($row['role'] == 'admin'){
    header('Location: http://ADMIN_IP_PLACEHOLDER/recipe_admin_view.php?recipeId=$recipeId');
} else {
    header('Location: http://USER_IP_PLACEHOLDER/recipe_user_view.php?recipeId=$recipeId');
}
$conn->close();
?>