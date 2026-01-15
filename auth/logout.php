<?php
require_once '../config/config.php';
require_once '../core/Auth.php';


Auth::logout();


session_destroy();

header("Location: ../index.php");
exit;
