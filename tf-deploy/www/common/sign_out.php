<?php
/**
 * @file
 * This file is used to sign out the user.
 * 
 */
session_start();
session_destroy();
header('Location: http://USER_IP_PLACEHOLDER/common/sign_in.php');
exit();
?>