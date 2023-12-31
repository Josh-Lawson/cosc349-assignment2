<?php

$adminIp = getenv('ADMIN_IP');
$userIp = getenv('USER_IP');

$internalAdminIp = getenv('INTERNAL_ADMIN_IP');
$internalUserIp = getenv('INTERNAL_USER_IP');

?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="style/style.css" />
    <title>Sign In Page</title>
</head>

<body>
    <main>

        <h1>
            <?php echo $_SERVER['SERVER_ADDR'] == $internalAdminIp ? "Admin Sign In" : "Sign In" ?>
        </h1>

        <?php if ($_SERVER['SERVER_ADDR'] == $internalAdminIp): ?>
            <h3>Please use your admin credentials to sign in to the admin interface</h3><br>
        <?php endif; ?>
        <fieldset>

            <?php if ($_SERVER['SERVER_ADDR'] == $internalUserIp): ?>
                <legend>&nbsp;Please Sign In Using Your Username And Password</legend><br>
            <?php endif; ?>

            <form action="authenticate.php" method="POST">

                <label for="username">&nbsp;Username:</label><input type="text" id="username" name="username" required
                    pattern="[a-zA-Z0-9]{5,}" title="Username must be alphanumeric and at least 5 characters.">
                <label for="password">&nbsp;Password:</label><input type="password" id="password" name="password"
                    required pattern=".{8,}" title="Password must be at least 8 characters.">
                <button type="submit">Sign In</button>
            </form><br>
            <?php if ($_SERVER['SERVER_ADDR'] == $internalUserIp): ?>
                <p>&nbsp;Don't have an account? <a href="http://<?php echo $userIp; ?>/common/create_account.php">Create one</a></p>
            <?php endif; ?>
        </fieldset>

    </main>
</body>

</html>