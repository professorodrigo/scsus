<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');

$dbhost = isset($_POST["dbhost"]) ? trim($_POST["dbhost"]) : "localhost";
$dbport = isset($_POST["dbport"]) ? trim($_POST["dbport"]) : "5433";
$dbdb = isset($_POST["dbdb"]) ? trim($_POST["dbdb"]) : "esus";
$dbuser = isset($_POST["dbuser"]) ? trim($_POST["dbuser"]) : "postgres";
$dbpass = isset($_POST["dbpass"]) ? trim($_POST["dbpass"]) : "esus";

$update = "
	UPDATE banco
	   SET dbhost = '".$dbhost."',
		   dbport = '".$dbport."',
		   dbdb = '".$dbdb."',
		   dbuser = '".$dbuser."',
		   dbpass = '".$dbpass."'
	 WHERE id = '".$_SESSION['key']."';
";
$run_estrutura = $db->query($update);
?>