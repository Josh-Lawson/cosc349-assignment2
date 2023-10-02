<?php
/**
 * @file
 * This file is used to sign out the user.
 * 
 */
session_start();
session_destroy();

$userIp = getenv('USER_IP');

header("Location: http://$userIp/common/sign_in.php");
exit();
?>