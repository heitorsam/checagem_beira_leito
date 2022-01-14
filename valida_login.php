<?php
	session_start();	
		
	//Incluindo a conexão com banco de dados
	include 'conexao.php';
	
	$pag_apos = 'home.php';	
	$pag_login = 'index.php';

	//O campo usuário e senha preenchido entra no if para validar
	if((isset($_POST['login'])) && (isset($_POST['senha']))){

		//Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário		
		//$result_usuario = "SELECT * FROM usuarios WHERE login = '$usuario' && senha = '$senha' LIMIT 1";
		
		$usuario = strtoupper($_POST['login']);
		$senha = $_POST['senha'];	
		
		echo $usuario;	echo '</br>'; echo $senha; echo '</br>';
		
		$result_usuario = oci_parse($conn_ora, "SELECT assinaturas.VALIDA_SENHA_FUNC_ASSINATURAS(:usuario,:senha) AS RESP_LOGIN,
												(SELECT INITCAP(usu.NM_USUARIO)
													FROM dbasgu.USUARIOS usu
													WHERE usu.CD_USUARIO = :usuario) AS NM_USUARIO,													CASE
														WHEN :usuario IN (SELECT DISTINCT puia.CD_USUARIO
																			FROM dbasgu.PAPEL_USUARIOS puia
																			WHERE puia.CD_PAPEL = 347) THEN 'S' --PORTAL ASSINATURAS
														ELSE 'N'
													END SN_USUARIO_COMUM
												FROM DUAL");																															
												
		oci_bind_by_name($result_usuario, ':usuario', $usuario);
		oci_bind_by_name($result_usuario, ':senha', $senha);

		echo '</br> RESULT USUARIO:' . $result_usuario . '</br>';
		
		oci_execute($result_usuario);
        $resultado = oci_fetch_row($result_usuario);

		echo '</br> COLUNA 0:' . $resultado['0']  . ' - ' . $resultado['1'] . '</br>';
		
		//Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		if(isset($resultado)){
			
			if($resultado[0] == 'Login efetuado com sucesso') {
				$_SESSION['usuarioLogin'] = $usuario;
				$_SESSION['usuarioNome'] = $resultado[1];
				$_SESSION['sn_admin'] = $resultado[2];
				$_SESSION['sn_lancamento'] = $resultado[3];
				$_SESSION['sn_cadastro'] = $resultado[4];
				$_SESSION['sn_usuario_comum'] = $resultado[5];
				header("Location: $pag_apos");
			} else { 
				$_SESSION['msgerro'] = $resultado[0] . '!';
				header("Location: $pag_login");		
			}
		//Não foi encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		//redireciona o usuario para a página de login
		}else{	
			//Váriavel global recebendo a mensagem de erro
			$_SESSION['msgerro'] = "Ocorreu um erro!";
			header("Location: $pag_login");
		}
		
	}
?>