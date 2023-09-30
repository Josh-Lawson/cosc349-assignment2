<?php
/**
 * @file
 * This file is used to sign out the user.
 * 
 */
session_start();
session_destroy();
header('Location: http://127.0.0.1:8080/common/sign_in.php');
exit();
?>