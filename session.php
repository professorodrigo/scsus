<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

session_start();
$deslogar = false;
if((!isset ($_SESSION['login']) == true) and (!isset ($_SESSION['logado']) == true)){
	$deslogar = true;
}
$db = new SQLite3('db/scsus.db');
$logadodb = "";
$result = $db->query("SELECT logado FROM usuarios WHERE login = '".$_SESSION['login']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$logadodb = $array['logado'];
	if ($logadodb != $_SESSION['logado']){
		$deslogar = true;
	}
}
if ($deslogar){
	unset($_SESSION['login']);
	unset($_SESSION['logado']);
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	echo "
		<script>
		$(function () {
			alert('Alguem logou com seu seu usuário em outro local');
			setTimeout(
			  function() 
			  {
				top.location.href = 'index.php';
			  }, 5000);
		});
		</script>
	";
}
?>