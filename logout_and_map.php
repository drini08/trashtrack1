<?php
session_start();
session_unset();
session_destroy();
header("Location: map.php");
exit;
?>