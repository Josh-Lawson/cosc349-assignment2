<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to add a new user.
 * 
 */


/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST["name"];
$username = $_POST["username"];
$password = $_POST["password"];

/**
 * Prepares a SQL statement to insert the new user into the database
 */
$stmt = $conn->prepare("INSERT INTO User (name, username, password) VALUES (?, ?, ?)");

if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}

$stmt->bind_param("sss", $name, $username, $password);
$stmt->execute();
$stmt->close();
$conn->close();

?>

<html>

<head>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <title>Add User</title>
    <link rel="stylesheet" type="text/css" href="../common/style/style.css">
</head>

<body>
    <main>
        <h1>Add User</h1><br>

        <form action="add_user.php" method="POST">
            name: <input type="text" name="name" />
            Username: <input type="text" name="username" />
            Password: <input type="text" name="password" />
            <input type="submit" value="Add User" />
        </form>
    </main>
</body>

</html>