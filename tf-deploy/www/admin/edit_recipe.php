<?php
include '../common/db_config.php';

require '../vendor/autoload.php';
use Aws\Lambda\LambdaClient;


/**
 * @file
 * This file is used to edit a recipe.
 * 
 */


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

$sql = "SELECT * FROM Recipe WHERE recipeId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

function uploadRecipeImage($imageTempPath, $imageName) {
    $imageContent = base64_encode(file_get_contents($imageTempPath));

    $client = new LambdaClient([
        'version' => 'latest',
        'region'  => 'us-east-1'
    ]);

    $result = $client->invoke([
        'FunctionName' => 's3ImageHandler',
        'Payload' => json_encode([
            'operation' => 'PUT',
            'filename' => $imageName,
            'content' => $imageContent
        ])
    ]);

    $response = json_decode($result['Payload'], true);
    if (isset($response['errorMessage'])) {
        throw new Exception("Error uploading image: " . $response['errorMessage']);
    }
    return $imageName;
}

if (isset($_FILES['newRecipeImage']) && $_FILES['newRecipeImage']['tmp_name']) {
    $newImageName = $_FILES['newRecipeImage']['name'];
    $newImageTempPath = $_FILES['newRecipeImage']['tmp_name'];

    $uploadedImageName = uploadRecipeImage($newImageTempPath, $newImageName);

    $client = new LambdaClient([
        'version' => 'latest',
        'region'  => 'us-east-1'
    ]);

    if ($recipe['imageName'] && $recipe['imageName'] != $uploadedImageName) {
        $client->invoke([
            'FunctionName' => 's3ImageHandler',
            'Payload' => json_encode([
                'operation' => 'DELETE',
                'filename' => $recipe['imageName']
            ])
        ]);
    }

    $stmt = $conn->prepare("UPDATE Recipe SET imageName = ? WHERE recipeId = ?");
    $stmt->bind_param("si", $uploadedImageName, $recipeId);
    $stmt->execute();
}

header("Location: admin.php");
?>