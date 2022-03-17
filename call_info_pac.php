<?php


    //CONEXAO
    include 'conexao.php';

    $var_pac = $_REQUEST['var_cd_paciente'];

    //Consulta no banco para trazer informações dos pacientes

    $consulta_paci = "SELECT pac.NM_PACIENTE, NVL(pac.NR_CPF,0) AS NR_CPF, 
                      TO_CHAR(pac.DT_NASCIMENTO,'DD/MM/YYYY') || ' ' AS DT_NASCIMENTO, pac.NM_MAE
                      FROM dbamv.PACIENTE pac
                      WHERE pac.CD_PACIENTE = '$var_pac'";

    $result_paci  = oci_parse($conn_ora, $consulta_paci);

    @oci_execute($result_paci);    

    while($row_paci = oci_fetch_array($result_paci)){
        
        $info_user[] = array(
            'paciente'	=> $row_paci['NM_PACIENTE'],
            'cpf' => $row_paci['NR_CPF'],
            'data_nasc' => $row_paci['DT_NASCIMENTO'],
            'nome_mae' => $row_paci['NM_MAE']
        );
    }

echo(json_encode($info_user));

?>