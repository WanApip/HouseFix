<?php
session_start();
session_unset();    // Clears all session variables
session_destroy();  // Destroys the session
header("Location: tech_login.php");
exit();
?>