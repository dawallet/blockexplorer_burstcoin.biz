<?php
// Pfade
$project = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace("\\", "/",__DIR__));
(!isset($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] == 'off') ? $protocol = 'http://' : $protocol = 'https://';
define('HTTP_ROOT', $protocol.$_SERVER['HTTP_HOST'].$project.'/');

// Initialisierung
require_once '../core/init.php';