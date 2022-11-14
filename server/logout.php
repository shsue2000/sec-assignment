<?php
//close the session
Session_start();
Session_destroy();
header('Location: ../client/index.php');
//logging out the user
$_SESSION["loggedin"] = false;
//unsetting the session key.
unset($_SESSION['sessionKey']);

?>