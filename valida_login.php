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
		
		$result_usuario = oci_parse($conn_ora, "SELECT checkbeiraleito.VALIDA_SENHA_FUNC_CHECK_BEIRA(:usuario,:senha) AS RESP_LOGIN,
												(SELECT INITCAP(usu.NM_USUARIO)
													FROM dbasgu.USUARIOS usu
													WHERE usu.CD_USUARIO = :usuario) AS NM_USUARIO												
												FROM DUAL");																																
												
		oci_bind_by_name($result_usuario, ':usuario', $usuario);
		oci_bind_by_name($result_usuario, ':senha', $senha);

		echo '</br> RESULTA USUARIO:' . $result_usuario . '</br>';
		
		oci_execute($result_usuario);
        $resultado = oci_fetch_row($result_usuario);
		print_r($resultado);
		echo '</br> COLUNA 0:' . $resultado['0']  . ' - ' . $resultado['1'] . '</br>';
		
		//Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		if(isset($resultado)){

			$cons_acesso_login="INSERT INTO portal_projetos.ACESSO
										SELECT portal_projetos.SEQ_CD_ACESSO.NEXTVAL AS CD_ACESSO,
										13 AS CD_PORTFOLIO,
										'CHECAGEM BEIRA LEITO' AS DS_PROJETO,
										'$usuario' AS CD_USUARIO_ACESSO,
										SYSDATE AS HR_ACESSO
										FROM DUAL";

			$result_acesso = oci_parse($conn_ora,$cons_acesso_login);

			$valida_acesso = oci_execute($result_acesso);

			if($valida_acesso){
			
				if($resultado[0] == 'Login efetuado com sucesso') {
					echo $_SESSION['usuarioLogin'] = $usuario;
					echo $_SESSION['usuarioNome'] = $resultado[1];
					echo $_SESSION['sn_administrador'] = $resultado[2];
					echo $_SESSION['sn_enfermagem'] = $resultado[3];
					echo $_SESSION['sn_passagem'] = $resultado[4];
					header("Location: $pag_apos");
				} else { 
					$_SESSION['msgerro'] = $resultado[0] . '!';
					header("Location: $pag_login");		
				}

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