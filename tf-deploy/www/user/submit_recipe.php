<?php
include '../common/db_config.php';

require '../vendor/autoload.php';
use Aws\Lambda\LambdaClient;


/**
 * @file
 * This file is used to submit a recipe.
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

/**
 * Checks if the request method is POST
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['userId'];
    $recipeName = $_POST['recipeName'];
    $instructions = $_POST['instructions'];
    $ingredients = $_POST['ingredients'];
    $quantities = $_POST['quantities'];
    $description = $_POST['description'];

    $imageName = $_FILES['recipeImage']['name'];
    $imageTempPath = $_FILES['recipeImage']['tmp_name'];
    $imageContent = base64_encode(file_get_contents($imageTempPath));

    $client = new LambdaClient([
        'version' => 'latest',
        'region' => 'us-east-1'
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
        die("Error uploading image: " . $response['errorMessage']);
    }


    $conn->begin_transaction();

    /**
     * Prepares a SQL statement to insert the new recipe into the database
     */
    try {
        $stmt = $conn->prepare("INSERT INTO Recipe (userId, recipeName, instructions, description, imageName) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception($conn->error);
        }
        $stmt->bind_param("issss", $userId, $recipeName, $instructions, $description, $imageName);
        $stmt->execute();
        $recipeId = $stmt->insert_id;

        /**
         * Prepares a SQL statement to insert each recipe ingredient into the database
         */
        for ($i = 0; $i < count($ingredients); $i++) {
            $ingredientId = $ingredients[$i];
            $quantity = $quantities[$i];
            $stmt = $conn->prepare("INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $recipeId, $ingredientId, $quantity);
            $stmt->execute();
        }

        $conn->commit();
        $stmt->close();
        $conn->close();

        /**
         * Redirects to the index page
         */
        header("Location: index.php");
        echo "Recipe submitted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

} else {

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submit Recipe</title>
        <link rel="stylesheet" type="text/css" href="../common/style/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#addIngredient').click(function () {
                    var ingredientName = $('#ingredientName').val();
                    var quantity = $('#quantity').val();

                    if (ingredientName == "" || quantity == "") {
                        alert("Please enter ingredient name and quantity");
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: '../common/get_ingredient_id.php',
                        data: { ingredientName: ingredientName },
                        success: function (ingredientId) {
                            if (ingredientId != "0") {
                                var newRow = '<tr><td><input type="hidden" name="ingredients[]" value="' + ingredientId + '">' + ingredientName + '</td><td><input type="hidden" name="quantities[]" value="' + quantity + '">' + quantity + '</td></tr>';
                                $('#ingredientsTable tbody').append(newRow);
                                $('#ingredientName').val('');
                                $('#quantity').val('');
                            } else {
                                alert("Ingredient does not exist");
                            }
                        }
                    });
                });
            });
        </script>
    </head>

    <body>
        <header>
            <?php include '../common/navbar.php'; ?>
        </header>
        <main>
            <h1>Submit Recipe</h1><br>
            <form method="post" enctype="multipart/form-data">

                <label for="recipeName">Recipe Name</label>
                <input type="text" id="recipeName" name="recipeName" required>

                <label for="recipeImage">Recipe Image</label>
                <input type="file" id="recipeImage" name="recipeImage" accept="image/*" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>

                <label for="instructions">Instructions</label>
                <textarea id="instructions" name="instructions" required></textarea><br>

                <label for="ingredientName">Ingredient</label>
                <input type="text" id="ingredientName">

                <label for="quantity">Quantity</label>
                <input type="text" id="quantity">

                <button type="button" id="addIngredient">Add Ingredient</button><br>

                <label for="ingredientsTable">Ingredients Added</label>
                <table id="ingredientsTable">
                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>


                <button type="submit">Submit Recipe</button>
            </form>
        </main>
    </body>

    </html>

    <?php
}
?>