<?php
/** 
 * @file
 * This file is used to create a new user account.
 * 
 */

/**
 * Holds database connection details
 */
$servername = "192.168.56.12";
$dbusername = "admin";
$dbpassword = "admin_pw";
$dbname = "RecipeManagementSystem";

/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Checks if the request method is POST
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Prepares a SQL statement to check if the username already exists
     */
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->bind_param("s", $username);
    $username = $_POST['username'];
    $stmt->execute();

    $result = $stmt->get_result();

    /**
     * Check if the username already exists
     */
    if ($result->num_rows > 0) {
        header('Location: create_account.php?error=Username already exists');
    } else {

        /**
         * Prepares a SQL statement to insert the new user into the database
         */
        $stmt = $conn->prepare("INSERT INTO User (name, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $password);

        $name = $_POST['name'];
        $password = $_POST['password'];
        $stmt->execute();

        header('Location: sign_in.php');
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="style/style.css" />
    <title>Create Account</title>
</head>

<body>
    <main>
        <h1>Create Account</h1>

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . $_GET['error'] . '</p>';
        }
        ?>

        <form action="create_account.php" method="POST">

            <label for="name">Name:</label><input type="text" id="name" name="name" required>
            <label for="username">Username:</label><input type="text" id="username" name="username" required
                pattern="[a-zA-Z0-9]{5,}" title="Username must be alphanumeric and at least 5 characters.">
            <label for="password">Password:</label><input type="password" id="password" name="password" required
                pattern=".{8,}" title="Password must be at least 8 characters.">
            <button type="submit">Create Account</button>
        </form>

        <p>Already have an account? <a href="sign_in.php">Sign In</a></p>

    </main>
</body>

</html>