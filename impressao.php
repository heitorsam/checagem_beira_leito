<?php

    include 'conexao.php';
    include 'cabecalho.php';
    include 'sql_impressao.php';


    include 'js/mensagens.php';
    include 'js/mensagens_usuario.php';
     
?>

<h11><i class="fas fa-list-ol"></i> Lista Impressão </h11>
<span class="espaco_pequeno" style="width: 6px;" >
</span>
<h27><a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a></h27> 

<div class="div_br">
</div>     

<form method="Post" autocomplete="off">
    <div class="row">
        <div class="col-md-3 ">
            Data da validação:
            <input class="form-control " type="date" id="dt_valida" name="dt_valida" >
        </div>
        <div class="col-md-3">
        </br>
            <button type="submit" class="btn btn-primary" id="btn_pesquisar" style=""> <i class="fa fa-search" aria-hidden="true"></i></button>	
        </div>
    </div>
</form>

<?php
$num = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){    
        if(@$_POST['cd_barra']== '' && @$_POST['cd_produto']== '' &&  @$_POST['dt_valida'] == ''){
            $_SESSION['msgerro'] = 'Digite um valor.';
            header('Location: impressao.php');	  
        }else{
            $temp_v_valor = @$_POST['cd_produto'];						
            $dt_inicio = @$_POST['dt_valida'];
            $var_cd_barra = @$_POST['cd_barra'];
            header('Location: impressao.php?pagina=1&filtro=' . $temp_v_valor.'&dt_inicio='. $dt_inicio.'&cd_barra='. $var_cd_barra);	  
        }
    }


    echo "</br>";
		
	echo "<div class='table-responsive col-md-18' style='background: ;'>
          <table class='table table-striped' cellspacing='0' cellpadding='0'>" . "<thead><tr>"; 
				
	echo "<th class='align-middle' style='text-align: center;'> Período </th>
          <th class='align-middle' style='text-align: center;'> Convênio </th>
          <th class='align-middle' style='text-align: center;'> Atendimento </th>
          <th class='align-middle' style='text-align: center;'> Paciente</th>
          <th class='align-middle' style='text-align: center;'> Nascimento</th>
          <th class='align-middle' style='text-align: center;'> Mãe</th>
          <th class='align-middle' style='text-align: center;'> Itens Prescritos</th>";         

          $contador_just = 0;
          
    while ($row_result = oci_fetch_array($result_registros)) {

        $num = $num + 1;

        echo "</tr></thead>";        
                     
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['DATA_PESQ']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['NM_CONVENIO']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['CD_ATENDIMENTO']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['NM_PACIENTE']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['DT_NASCIMENTO']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['NM_MAE']. "<br>" . "</td>";
        echo "<td class='align-middle' style='text-align: center;'>" . $row_result['QTD_PENDENCIA']. "<br>" . "</td>";
  
    }

?>

<?php

    echo "</table></div>"; 

    include 'rodape.php';
?>
