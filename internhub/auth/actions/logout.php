<?php
session_start();
session_destroy();
header('Location: /internhub/index.php');
exit;