<?php
	session_start();
	
	unset(
		$_SESSION['usuarioLogin'],
		$_SESSION['usuarioNome'],
		$_SESSION['usuariocpf'],
		$_SESSION['permissao'],
		$_SESSION['sn_admin'],
		$_SESSION['sn_lancamento'],
		$_SESSION['sn_cadastro']
	);
	
	$_SESSION['msgneutra'] = "Logout realizado com sucesso!";
	
	//redirecionar o usuario para a página de login
	header("Location: index.php");

?>