<?php
include '../common/db_config.php';


/**
 * @file
 * This file is used to authenticate a user.
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

$username = $_POST['username'];
$password = $_POST['password'];

/**
 * Prepares a SQL statement to check if the username and password are correct
 */
$stmt = $conn->prepare("SELECT * FROM User WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);

$stmt->execute();

$result = $stmt->get_result();

$adminIp = getenv('ADMIN_IP');
$userIp = getenv('USER_IP');

/**
 * If the username and password are correct, set the session variables and redirect to the appropriate page
 */
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['username'] = $username;
        $_SESSION['userId'] = $row['userId'];
        $_SESSION['role'] = $row['role'];
        if ($row['role'] == 'admin') {
            header("Location: http://$adminIp/admin.php");
        } else {
            header("Location: http://$userIp/index.php");
        }
    }
} else {
    $_SESSION['errorMessage'] = 'Invalid username or password';
    header('Location: sign_in.php');

}

$stmt->close();
$conn->close();
?>