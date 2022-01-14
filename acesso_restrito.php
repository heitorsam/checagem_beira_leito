<?php
	// Se o usuário não está logado, manda para página de login.
	if (!isset($_SESSION['usuarioNome'])){
		
		unset(
			$_SESSION['usuarioLogin'],
			$_SESSION['usuarioNome'],
			$_SESSION['usuariocpf'],
			$_SESSION['permissao'],
			$_SESSION['sn_admin'],
			$_SESSION['sn_lancamento'],
			$_SESSION['sn_cadastro'],
			$_SESSION['sn_usuario_comum']			
		);
		
		$_SESSION['msgerro'] = "Sessão expirada!";
		header("Location: index.php");
		
	};

?>