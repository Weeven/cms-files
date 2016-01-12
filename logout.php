<?php
require('settings.php');
session_start();
$_SESSION['is_open'] = FALSE;
session_destroy();

header("Location: $AdminUrl/");
exit;
?>