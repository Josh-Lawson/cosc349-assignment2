<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used for admins to view a recipe.
 * 
 */


/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipeId = $_GET['recipeId'];

/**
 * Prepares a SQL statement to select the recipe from the database
 */
$sql = "SELECT * FROM Recipe WHERE recipeId = $recipeId";
$result = $conn->query($sql);
$recipe = $result->fetch_assoc();

/**
 * Prepares a SQL statement to select the ingredients for the recipe selected
 */
$sql = "SELECT * FROM RecipeIngredient JOIN Ingredient ON RecipeIngredient.ingredientId = Ingredient.ingredientId WHERE recipeId = $recipeId";
$result = $conn->query($sql);
$ingredients = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
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


            <h1>Update Recipe</h1><br>

            <form action="edit_recipe.php" method="POST">



                <h3>Recipe Name</h3>
                <input type="text" name="recipeName" value="<?php echo $recipe['recipeName']; ?>">

                <h3>Description</h3>
                <textarea name="description"><?php echo $recipe['description']; ?></textarea>

                <h3>Instructions</h3>
                <textarea name="instructions"><?php echo $recipe['instructions']; ?></textarea>

                <h3>Ingredients</h3>
                <div>

                    <?php foreach ($ingredients as $index => $ingredient): ?>
                        <li>
                            <input type="text" name="ingredientName[]" value="<?php echo $ingredient['ingredientName']; ?>">
                            - &nbsp;
                            <input type="text" name="quantity[]" value="<?php echo $ingredient['quantity']; ?>">
                        </li>
                    <?php endforeach; ?>

                </div>

                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <button type="submit">Update Recipe</button>

            </form>
            &nbsp;

            <form method="POST" action="delete_recipe.php?recipeId=<?php echo $recipeId; ?>"
                onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <button type="submit">Delete Recipe</button>

            </form>


        </div>

    </main>
</body>

</html>