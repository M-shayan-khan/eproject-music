<?php
session_start(); // Session start zaroori hai

// Sab sessions clear kar do
session_unset();
session_destroy();

// Redirect to homepage or login
header("Location: index.php");
exit();
?>
