<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to delete a user.
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /**
     * Checks if the user has confirmed the deletion
     */
    if (isset($_POST['confirm'])) {
        $id = $_POST["id"];

        /**
         * Prepares a SQL statement to delete the user from the database
         */
        $stmt = $conn->prepare("DELETE FROM User WHERE userId = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: admin.php");
    } else {
        $id = $_POST["id"];

        ?>

        <html>

        <head>
            <title>Edit User</title>
            <link rel="stylesheet" type="text/css" href="../common/style/style.css">
        </head>

        <body>
            <main>
                <h1>Confirm Deletion</h1>


                <form action="delete_user.php" method="POST">
                    <p> Are you sure you want to delete the user with ID
                        <?php echo $id; ?>?
                    </p><br>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="confirm" value="1">
                    <input type="submit" value="Yes, delete User" />
                </form>

                <p><a href="admin.php">No, return to Admin Interface</a></p>
            </main>
        </body>

        <?php
    }
} else {
    ?>

    <html>

    <head>
        <title>Delete User</title>
        <link rel="stylesheet" type="text/css" href="../common/style/style.css">
    </head>

    <body>
        <header>
            <?php include '../common/navbar.php'; ?>
        </header>
        <main>
            <h1>Delete User</h1><br>

            <form action="delete_user.php" method="POST">
                User ID: <input type="text" name="id" value="<?php echo $userId; ?>" />
                <input type="submit" value="Delete User" />
            </form>
        </main>
    </body>

    </html>

    <?php
}
?>