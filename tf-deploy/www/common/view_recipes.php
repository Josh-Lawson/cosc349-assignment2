<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to view recipes.
 * 
 */


/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Checks if the request method is POST and sets the filter variable
 */
$filter = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filter = $_POST["filter"];
}

/**
 * Prepares a SQL statement to get all recipes
 */
$sql = "SELECT DISTINCT Recipe.* FROM Recipe 
        JOIN RecipeIngredient ON Recipe.recipeId = RecipeIngredient.recipeId 
        JOIN Ingredient ON RecipeIngredient.ingredientId = Ingredient.ingredientId
        WHERE Recipe.approved = 1";
if ($filter != "") {
    $sql = $sql . " AND (recipeName LIKE '%$filter%' OR instructions LIKE '%$filter%')";
}

$result = $conn->query($sql);

?>

<html>

<head>
    <title>View Recipes</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <main>
        <h1>View Recipes</h1><br>

        <form action="view_recipes.php" method="POST">
            <div>
                Search: <input type="text" name="filter" value="<?php echo $filter; ?>" />
                <input type="submit" value="Search" />
            </div>
        </form>

        <table>
            <tr>
                <th>Recipe Name</th>
                <th>Description</th>
                <th>View Recipe</th>
            </tr>
            <?php
            /**
             * Loops through the results of the SQL query and displays them in a table
             */
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["recipeName"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td><a href='recipe_redirect.php?recipeId=".$row["recipeId"]."'>View</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </table>
    </main>
</body>

</html>