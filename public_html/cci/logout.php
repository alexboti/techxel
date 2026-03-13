<?php
/**
 * CCI Portal — Logout
 */
session_name('techxel_cci');
session_start();
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
