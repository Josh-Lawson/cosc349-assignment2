<?php
/**
 * @file
 * This file is used to display the navigation bar.
 * 
 */

session_start();

/**
 * Redirects to the sign in page if the user is not signed in
 */
if (!isset($_SESSION['username'])) {
    header('Location: ../common/sign_in.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css" />
</head>

<body>
    <div class="navbar">
        <div>
            <?php
            /**
             * Displays the user's username and a sign out button if the user is signed in.
             */
            if (isset($_SESSION['username'])) {
                echo 'Welcome, ' . $_SESSION['username'];
                ?>
                <form action="<?php echo $_SESSION['role'] == 'admin' ? 'http://ADMIN_IP_PLACEHOLDER/admin.php' : 'http://ADMIN_IP_PLACEHOLDER/'; ?>" method="GET" style="display:inline;">
                    <button type="submit">Home</button>
                </form>
                <form action="../common/sign_out.php" method="POST" style="display:inline;">
                    <button type="submit">Sign Out</button>
                </form>
                <?php
            } else {
                echo '<a href="http://USER_IP_PLACEHOLDER/common/sign_in.php">Sign In</a>';
            }
            ?>
        </div>
    </div>
</body>

</html>