<?php
session_start();
session_destroy();
header('Location: /internhub/admin/login.php');
exit;
?>