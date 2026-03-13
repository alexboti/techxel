<?php
/**
 * ICS Portal — Logout
 */
session_name('techxel_ics');
session_start();
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
