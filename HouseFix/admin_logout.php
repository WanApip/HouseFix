<?php
session_start();
session_unset();    // Clears all session variables
session_destroy();  // Destroys the session
header("Location: admin_login.php");
exit();
?>