<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php?switch=1"); // Optional flag to show switch message
exit();
?>