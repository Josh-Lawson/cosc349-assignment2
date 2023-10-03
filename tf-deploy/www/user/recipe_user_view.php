<?php

include '../common/db_config.php';


require '../vendor/autoload.php';

use Aws\Lambda\LambdaClient;

/**
 * This function retrieves the image from the AWS Lambda function.
 */
function getRecipeImage($imageName)
{
    $client = new LambdaClient([
        'version' => 'latest',
        'region' => 'us-east-1'
    ]);

    $result = $client->invoke([
        'FunctionName' => 's3ImageHandler',
        'Payload' => json_encode([
            'operation' => 'GET',
            'filename' => $imageName
        ])
    ]);

    $response = json_decode($result['Payload'], true);
    if (isset($response['errorMessage'])) {
        return null; // handle errors as appropriate for your application
    }
    return "data:image/jpeg;base64," . $response;
}

/*


/**
 * @file
 * This file is used to display a recipe to the user.
 * 
 */


/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipeId = $_GET['recipeId'];

/**
 * Prepares a SQL statement to get the recipe and ingredients
 */
$sql = "SELECT * FROM Recipe WHERE recipeId = $recipeId";
$result = $conn->query($sql);
$recipe = $result->fetch_assoc();

/**
 * Prepares a SQL statement to get the ingredients for the recipe selected
 */
$sql = "SELECT * FROM RecipeIngredient JOIN Ingredient ON RecipeIngredient.ingredientId = Ingredient.ingredientId WHERE recipeId = $recipeId";
$result = $conn->query($sql);
$ingredients = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();

$imageDataUrl = getRecipeImage($recipe['imageName']);

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>View Recipe</title>
    <link rel="stylesheet" type="text/css" href="../common/style/style.css">
</head>

<body>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <main>
        <div class="recipe-details">

            <a href="../common/view_recipes.php">Back to Recipes</a><br><br>

            <?php if ($imageDataUrl): ?>
                <div class="recipe-image-container">
                    <img class="recipe-image" src="<?php echo $imageDataUrl; ?>" alt="Recipe Image" />
                </div>
            <?php endif; ?>

            <h1>
                <?php echo $recipe['recipeName']; ?>
            </h1>
            <div class="description-container">
                <label>Description</label>
                <ul>
                    <li>
                        <?php echo $recipe['description']; ?>
                    </li>
                </ul>
            </div>
            <div class="instructions-container">
                <label>Instructions</label>
                <ul>
                    <li>
                        <?php echo $recipe['instructions']; ?>
                    </li>
                </ul>
            </div>
            <h2>Ingredients</h2>
            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <?php echo $ingredient['ingredientName'] . " - " . $ingredient['quantity']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <main>
</body>

</html>