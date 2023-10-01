<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to delete a recipe.
 * 
 */

/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipeId = $_POST['recipeId'];

/**
 * Prepares a SQL statement to delete the recipe from the database
 */
$stmt = $conn->prepare("DELETE FROM RecipeIngredient WHERE recipeId = ?");
$stmt->bind_param("i", $recipeId);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM Recipe WHERE recipeId = ?");
$stmt->bind_param("i", $recipeId);
$stmt->execute();

/**
 * Redirects to the admin page
 */
header("Location: admin.php");
?>