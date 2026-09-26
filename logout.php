<?php
require 'config.php';

// Clear and destroy session
session_unset();
session_destroy();

// Back to login
header("Location: index.php");
exit;
?>
