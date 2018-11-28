<?php

require_once("misc.php");

$DBServer = "server";
$DBUser   = "user";
$DBPass   = "pass");
$DBName   = "asteriskcdrdb";
$DBTable   = "queue_log";

$connection = new mysqli($DBServer, $DBUser, $DBPass, $DBName);
 
// check connection
if ($connection->connect_error) {
  trigger_error('Database connection failed: '  . $connection->connect_error, E_USER_ERROR);
}
// Credentials for AMI (for the realtime tab to work)
// See /etc/asterisk/manager.conf


$manager_host   = "asterisk_manager";
$manager_user   = "user";
$manager_secret = "pass";


// Available languages "es", "en", "ru", "de", "fr"
$language = "es";

require_once("lang/$language.php");

$page_rows = '100';
//$midb = conecta_db($dbhost,$dbname,$dbuser,$dbpass);
$self = $_SERVER['PHP_SELF'];

$DB_DEBUG = false; 

//error reporting
//error_reporting(E_ALL);

session_start();
header('content-type: text/html; charset: utf-8'); 


?>
