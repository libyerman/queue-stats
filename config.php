<?php

require_once("misc.php");

$DBServer = 'server';
$DBUser   = 'user';
$DBPass   = 'pass';
$DBName   = 'dbname';
$DBTable   = 'queue_log';

$connection = new mysqli($DBServer, $DBUser, $DBPass, $DBName);
 
// check connection
if ($connection->connect_error) {
  trigger_error('Database connection failed: '  . $connection->connect_error, E_USER_ERROR);
}
// Credentials for AMI (for the realtime tab to work)
// See /etc/asterisk/manager.conf

$manager_host   = "127.0.0.1";
$manager_user   = "admin";
$manager_secret = "amp111";


// Available languages "es", "en", "ru", "de", "fr"
$language = "es";

require_once("lang/$language.php");

$page_rows = '100';
//$midb = conecta_db($dbhost,$dbname,$dbuser,$dbpass);
$self = $_SERVER['PHP_SELF'];

$DB_DEBUG = false; 

session_start();
header('content-type: text/html; charset: utf-8'); 


?>
