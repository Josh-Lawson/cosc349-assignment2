<?php
/**
 * @file
 * This file is used to edit a recipe.
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

$recipeId = $_POST['recipeId'];
$recipeName = $_POST['recipeName'];
$description = $_POST['description'];
$instructions = $_POST['instructions'];
$ingredientNames = $_POST['ingredientName'];
$quantities = $_POST['quantity'];

/**
 * Prepares a SQL statement to update the recipe in the database
 */
$stmt = $conn->prepare("UPDATE Recipe SET recipeName = ?, instructions = ?, description = ? WHERE recipeId = ?");
$stmt->bind_param("sssi", $recipeName, $instructions, $description, $recipeId);
$stmt->execute();

/**
 * Prepares a SQL statement to delete each recipe ingredient from the database
 */
$stmt = $conn->prepare("DELETE FROM RecipeIngredient WHERE recipeId = ?");
$stmt->bind_param("i", $recipeId);
$stmt->execute();

for ($i = 0; $i < count($ingredientNames); $i++) {
    $ingredientName = $ingredientNames[$i];
    $quantity = $quantities[$i];

    /**
     * Prepares a SQL statement to check if each ingredient already exists in the database
     */
    $stmt = $conn->prepare("SELECT ingredientId FROM Ingredient WHERE ingredientName = ?");
    $stmt->bind_param("s", $ingredientName);
    $stmt->execute();
    $result = $stmt->get_result();

    /**
     * Check if the ingredient already exists in the database
     */
    if ($result->num_rows == 0) {

        /**
         * Prepares a SQL statement to insert the ingredient into the database
         */
        $stmt = $conn->prepare("INSERT INTO Ingredient (ingredientName) VALUES (?)");
        $stmt->bind_param("s", $ingredientName);
        $stmt->execute();
        $ingredientId = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $ingredientId = $row['ingredientId'];
    }

    /**
     * Prepares a SQL statement to insert the recipe ingredient into the database
     */
    $stmt = $conn->prepare("INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $recipeId, $ingredientId, $quantity);
    $stmt->execute();
}

header("Location: admin.php");
?>