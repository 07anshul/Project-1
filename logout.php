<?php
session_start();


// Unset the variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header("location: login.php");
exit;
 ?>

<!-- HTML script -->
