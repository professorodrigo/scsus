<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
date_default_timezone_set('America/Sao_Paulo');
require_once('functions.php');

if (!file_exists("db/scsus.db")){
	//SELECT name FROM sqlite_master WHERE type='table' AND name='{table_name}';
	$db = new SQLite3('db/scsus.db');
	$estrutura = "
		CREATE TABLE usuarios (
			login varchar (11) NOT NULL, 
			nome varchar (200) DEFAULT 'SN',
			telefone varchar (20) DEFAULT '(99)99999-9999',
			senha char (32) NOT NULL, 
			logado varchar (14) NOT NULL DEFAULT '00000000000000', 
			bloqueado int (1) NOT NULL DEFAULT 0, 
			tent int (1) NOT NULL DEFAULT 0, 
			cnes varchar (8) NOT NULL DEFAULT '0000000', 
			ine varchar (10) NOT NULL DEFAULT '0000000000', 
			tema varchar (20) NOT NULL DEFAULT 'padrao', 
			email varchar (150) NOT NULL DEFAULT 'admin@admin', 
			perfil varchar (20) NOT NULL DEFAULT 'usuario', 
			dtulogin BIGINT DEFAULT (30001231), 
			hrulogin BIGINT DEFAULT (0), 
			ctlogin BIGINT DEFAULT (0), 
			usenha char (32) NOT NULL,
			tptsenha int DEFAULT (180),
			tmpsenha int DEFAULT (0),
			iduss char (32) NOT NULL,
			PRIMARY KEY (login)
		);
	";
	$run_estrutura = $db->query($estrutura);
	$dados = "
		INSERT INTO usuarios (
			login, 
			nome,
			telefone,
			senha, 
			logado, 
			bloqueado, 
			tent, 
			cnes, 
			ine, 
			tema, 
			email, 
			perfil, 
			dtulogin, 
			hrulogin, 
			ctlogin,
			usenha,
			tptsenha,
			tmpsenha,
			iduss
		) VALUES (
			'admin', 
			'Administrador',
			'(99)99999-9999',
			'202cb962ac59075b964b07152d234b70', 
			'0', 
			0, 
			0, 
			'0000000', 
			'0000000000', 
			'padrao', 
			'admin@admin', 
			'admin', 
			30001231, 
			0, 
			0,
			'202cb962ac59075b964b07152d234b70',
			180,
			0,
			'202cb962ac59075b964b07152d234b70'
		);
	";
	$run_dados = $db->query($dados);

	$codinstall = date('dmYHis').$sobre['versimp'].rand(0,9).rand(0,9).rand(0,9);
	$texto = "<?php
	\$codinstall = '".$codinstall."';
	\$dtinstall = '".date('Ymd')."';
	?>\r\n";
	$file = "cfg/install.php";
	if (file_exists($file)){unlink($file);}
	$fconfig = fopen($file,'w');
	fwrite($fconfig, $texto);
	fclose($fconfig);
	$ctrli = 0;
	//include 'plugins/ctrli/index.php';
	
	$respas = 'rss'.rand(0,9).rand(0,9).rand(0,9).$codinstall.rand(0,9).rand(0,9).rand(0,9);
	$texto = "
	<?php
	\$db = new SQLite3('db/scsus.db');
	\$db->query(\"UPDATE usuarios SET tent = 0, bloqueado = 0, senha = '202cb962ac59075b964b07152d234b70' WHERE login = 'admin'\");
	header('location:login.php');
	?>\r\n";
	$file = $respas.".php";
	if (file_exists($file)){unlink($file);}
	$fconfig = fopen($file,'w');
	fwrite($fconfig, $texto);
	fclose($fconfig);
	
}
?>