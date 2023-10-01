<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to get the ingredient id from the ingredient name.
 * 
 */


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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ingredientName = $_POST['ingredientName'];

    /**
     * Prepares a SQL statement to check if the ingredient already exists
     */
    $stmt = $conn->prepare("SELECT ingredientId FROM Ingredient WHERE ingredientName = ?");
    $stmt->bind_param("s", $ingredientName);
    $stmt->execute();
    $stmt->bind_result($ingredientId);
    $stmt->fetch();
    $stmt->close();

    /**
     * If the ingredient does not exist, insert it into the database
     */
    if (!$ingredientId) {
        $stmt = $conn->prepare("INSERT INTO Ingredient (ingredientName) VALUES (?)");
        $stmt->bind_param("s", $ingredientName);
        $stmt->execute();
        $ingredientId = $stmt->insert_id;
        $stmt->close();
    }

    $conn->close();

    echo $ingredientId ? $ingredientId : "0";
}
?>