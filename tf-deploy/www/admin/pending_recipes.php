<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to view pending recipes that have been submitted by users.
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
    /**
     * Checks if the approve button was clicked
     */
    if (isset($_POST['approve'])) {
        $recipeId = $_POST['recipeId'];

        /**
         * Prepares a SQL statement to add the recipe to the database
         */
        $stmt = $conn->prepare("UPDATE Recipe SET approved = TRUE WHERE recipeId = ?");
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();
        $stmt->close();

    } elseif (isset($_POST['deny'])) {

        $recipeId = $_POST['recipeId'];
        $deleteIngredientsStmt = $conn->prepare("DELETE FROM RecipeIngredient WHERE recipeId = ?");
        $deleteIngredientsStmt->bind_param("i", $recipeId);
        $deleteIngredientsStmt->execute();
        $deleteIngredientsStmt->close();

        $stmt = $conn->prepare("DELETE FROM Recipe WHERE recipeId = ?");
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Prepares a SQL statement to select all pending recipes from the database
 */
$result = $conn->query("SELECT * FROM Recipe WHERE approved = 0");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/style/style.css">
    <title>Pending Recipes</title>
</head>

<body>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <main>
        <h1>Pending Recipes</h1><br>
        <table border="1">
            <tr>
                <th>Recipe Name</th>
                <th>Description</th>
                <th>Instructions</th>
                <th>Approve Recipe</th>
                <th>Deny Recipe</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $recipeId = $row['recipeId'];
                    $recipeName = $row['recipeName'];
                    $description = $row['description'];
                    $instructions = $row['instructions'];
                    ?>
                    <tr>
                        <td>
                            <?php echo $recipeName; ?>
                        </td>
                        <td>
                            <?php echo $description; ?>
                        </td>
                        <td>
                            <?php echo $instructions; ?>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                                <input type="submit" name="approve" value="Approve">
                            </form>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                                <input type="submit" name="deny" value="Deny">
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='4'>0 results</td></tr>";
            }
            ?>
        </table>
    </main>
</body>

</html>
<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>