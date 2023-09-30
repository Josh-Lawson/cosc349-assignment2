<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>

<head>
    <title>Admin Interface</title>
    <link rel="stylesheet" type="text/css" href="../common/style/style.css">
</head>

<body>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <main>

        <h1>Recipe Management</h1>
        <nav>
            <a href="../common/view_recipes.php">View All Recipes</a>
            <a href="pending_recipes.php">Review Pending Recipes</a>
            <a href="add_recipe.php">Add Recipe</a>
        </nav>

        <h1>User Management</h1>
        <nav>
            <a href="view_users.php">View all users</a>
            <a href="add_user.php">Add a new user</a>
            <a href="delete_user.php">Delete a user</a>
        </nav>

    </main>
</body>

</html>