<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$dbhost = isset($_POST["dbhost"]) ? trim($_POST["dbhost"]) : "localhost";
$dbport = isset($_POST["dbport"]) ? trim($_POST["dbport"]) : "5433";
$dbdb = isset($_POST["dbdb"]) ? trim($_POST["dbdb"]) : "esus";
$dbuser = isset($_POST["dbuser"]) ? trim($_POST["dbuser"]) : "postgres";
$dbpass = isset($_POST["dbpass"]) ? trim($_POST["dbpass"]) : "esus";
$texto = "<?php
\$dbhost = \"".$dbhost."\";
\$dbport = \"".$dbport."\";
\$dbdb = \"".$dbdb."\";
\$dbuser = \"".$dbuser."\";
\$dbpass = \"".$dbpass."\";
?>\r\n";
$file = "config/banco_".$_SESSION['key'].".php";
if (file_exists($file)){unlink($file);}
$fconfig = fopen($file,'w');
fwrite($fconfig, $texto);
fclose($fconfig);
?>