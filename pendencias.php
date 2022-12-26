<?php

    include "cabecalho.php";

    $id_paciente = 1;
    $id_detalhado = 1000000;

    if(isset($_POST['frm_setor'])){

        @$var_frm_setor = $_POST['frm_setor'];

    }else{

        @$var_frm_setor = 0;

    }    

    //echo 'SETOR SELECIONADO = ' . $var_frm_setor . '</br></br>';

    /////////
    //SETOR//
    /////////

    $cons_setor ="SELECT DISTINCT
                  st.CD_SETOR, st.NM_SETOR
                  FROM dbamv.SETOR st
                  INNER JOIN dbamv.UNID_INT unid
                    ON unid.CD_SETOR = st.CD_SETOR
                  WHERE st.CD_SETOR = '$var_frm_setor'
                
                  UNION ALL
                
                  SELECT res.*
                  FROM(
                  SELECT DISTINCT st.CD_SETOR, st.NM_SETOR
                  FROM dbamv.SETOR st
                  INNER JOIN dbamv.UNID_INT unid
                    ON unid.CD_SETOR = st.CD_SETOR
                  WHERE st.CD_SETOR <> '$var_frm_setor'
                  ORDER BY st.NM_SETOR ASC) res";
    $result_setor = oci_parse($conn_ora, $cons_setor);
    @oci_execute($result_setor);

?>

<!--TITULO-->
<h11><i class="fas fa-tasks"></i> Pendências</h11>
<span class="espaco_pequeno" style="width: 6px;" ></span>
<h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 

<div class="div_br"> </div>

<!--------------------->
<!--DETALHE PENDECIAS-->
<!--------------------->   

</br>

<!--FILTROS-->
<form action="pendencias.php" method="post">

<div class="row">

    <!--SETOR-->  
    <div class="form-group col-md-3">
        <select name="frm_setor" class="form-control" required>

                <?php
                if($var_frm_setor == 0){
                    echo "<option value=''> SETOR</option>";
                }
                while(@$row_setor = oci_fetch_array($result_setor)){	

                    echo "<option value='" . $row_setor['CD_SETOR'] . "'>" . $row_setor['NM_SETOR'] . "</option>";

                }
            ?>

        </select>
    </div> 
                       
    <!--SUBMIT-->  
    <div class="form-group col-md-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> </button>
    </div>
    
</div>

</form>


<?php

    /////////
    //TOTAL//
    /////////

 $consulta_tot = "SELECT lt_set.CD_SETOR, lt_set.NM_SETOR,
    COUNT(hm.CD_ATENDIMENTO) AS QTD
    FROM dbamv.PRE_MED pm
INNER JOIN dbamv.ITPRE_MED itpm
ON itpm.CD_PRE_MED = pm.CD_PRE_MED
INNER JOIN dbamv.TIP_FRE tf
ON tf.CD_TIP_FRE = itpm.CD_TIP_FRE
INNER JOIN dbamv.TIP_ESQ esq
ON esq.CD_TIP_ESQ = itpm.CD_TIP_ESQ
INNER JOIN dbamv.PRESTADOR prest
ON prest.CD_PRESTADOR = pm.CD_PRESTADOR
INNER JOIN dbamv.HRITPRE_MED hm
ON hm.CD_ITPRE_MED = itpm.CD_ITPRE_MED
INNER JOIN dbamv.ATENDIME atd
ON atd.CD_ATENDIMENTO = pm.CD_ATENDIMENTO
AND TRUNC(atd.DT_ALTA) <> TRUNC(pm.DT_PRE_MED)
AND TRUNC(atd.DT_ALTA) <> TRUNC(pm.DT_PRE_MED+1)
AND TRUNC(atd.DT_ALTA) <> TRUNC(hm.DH_MEDICACAO)
AND TRUNC(atd.DT_ALTA) <> TRUNC(hm.DH_MEDICACAO+1)

INNER JOIN (SELECT mi.CD_ATENDIMENTO, mi.CD_LEITO,
mi.CD_LEITO_ANTERIOR, lt.DS_LEITO,
st.CD_SETOR, st.NM_SETOR,
mi.HR_MOV_INT AS DT_ENTRADA,
NVL((SELECT MIN(HR_MOV_INT) -1/(24*60*60)
 FROM MOV_INT
 WHERE CD_ATENDIMENTO = mi.CD_ATENDIMENTO
 AND CD_LEITO_ANTERIOR = mi.CD_LEITO
 AND HR_MOV_INT >= mi.HR_MOV_INT), SYSDATE) AS DT_SAIDA
FROM MOV_INT mi
INNER JOIN dbamv.LEITO lt
ON lt.CD_LEITO = mi.CD_LEITO
INNER JOIN dbamv.UNID_INT unid
ON unid.CD_UNID_INT = lt.CD_UNID_INT
INNER JOIN dbamv.SETOR st
ON st.CD_SETOR = unid.CD_SETOR
WHERE mi.CD_ATENDIMENTO IS NOT NULL
AND mi.TP_MOV <> 'R'
AND st.CD_SETOR = '$var_frm_setor') lt_set
ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
WHERE prest.CD_TIP_PRESTA IN (4,8,9)
AND itpm.CD_TIP_ESQ IN ('ANT','DPM','DEP','MAR','MCD','MED','MNP','MUC','QT','SOR','LM','HEM','LAB')
AND itpm.CD_TIP_PRESC <> 47493
AND pm.HR_PRE_MED >= SYSDATE -2
AND hm.DH_MEDICACAO >= SYSDATE-1
AND itpm.DH_CANCELADO IS NULL
AND hm.CD_ITPRE_MED  || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                  NOT IN (SELECT cons.CD_ITPRE_MED || TO_CHAR(cons.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                          FROM dbamv.HRITPRE_CONS cons
                          WHERE cons.SN_SUSPENSO = 'S'
                          AND TRUNC(cons.DH_CHECAGEM) >= TRUNC(SYSDATE-2))
AND hm.CD_ITPRE_MED NOT IN (SELECT bs.CD_ITPRE_MED 
            FROM dbamv.VW_BOLSAS_SANGUE_COM_RESERVA bs 
            WHERE bs.SN_BOLSA_COM_RESERVA = 'S')
GROUP BY
    lt_set.CD_SETOR, lt_set.NM_SETOR";

    $result_tot  = oci_parse($conn_ora, $consulta_tot);

    oci_execute($result_tot); 

    $row_tot = oci_fetch_array($result_tot);

    echo '<div class="row justify-content-md-center">';

        echo '<div 
            class="col-11" style="padding: 8px; border-radius: 3px; margin-top: 10px;
            color: #ffffff; background-color: #417ffa !important;">';
                                                        
            echo '<b>' . 'TOTAL PENDÊNCIAS: ';
            if(isset($row_tot['QTD'])) {
                echo $row_tot['QTD'];
            }else{
                echo '0';
            }            
            
            echo '</b>';

        echo '</div>';  
                    
    echo '</div>';  

    ////////////
    //PACIENTE//
    ////////////

 echo $consulta_pac = "SELECT atd.CD_ATENDIMENTO, lt_set.DS_LEITO, atd.CD_PACIENTE, pac.NM_PACIENTE, pac.DT_NASCIMENTO, pac.NM_MAE,
                            COUNT(hm.DH_MEDICACAO) AS QTD_PENDENCIA
                            FROM dbamv.PRE_MED pm
INNER JOIN dbamv.ITPRE_MED itpm
ON itpm.CD_PRE_MED = pm.CD_PRE_MED
INNER JOIN dbamv.TIP_FRE tf
ON tf.CD_TIP_FRE = itpm.CD_TIP_FRE
INNER JOIN dbamv.TIP_ESQ esq
ON esq.CD_TIP_ESQ = itpm.CD_TIP_ESQ
INNER JOIN dbamv.PRESTADOR prest
ON prest.CD_PRESTADOR = pm.CD_PRESTADOR
INNER JOIN dbamv.HRITPRE_MED hm
ON hm.CD_ITPRE_MED = itpm.CD_ITPRE_MED
INNER JOIN dbamv.ATENDIME atd
ON atd.CD_ATENDIMENTO = pm.CD_ATENDIMENTO
AND NVL(TRUNC(atd.DT_ALTA), SYSDATE - 5) <> TRUNC(pm.DT_PRE_MED)
AND NVL(TRUNC(atd.DT_ALTA), SYSDATE - 5) <> TRUNC(pm.DT_PRE_MED+1)
AND NVL(TRUNC(atd.DT_ALTA), SYSDATE - 5) <> TRUNC(hm.DH_MEDICACAO)
AND NVL(TRUNC(atd.DT_ALTA), SYSDATE - 5) <> TRUNC(hm.DH_MEDICACAO+1)
INNER JOIN (SELECT mi.CD_ATENDIMENTO, mi.CD_LEITO,
mi.CD_LEITO_ANTERIOR, lt.DS_LEITO,
st.CD_SETOR, st.NM_SETOR,
mi.HR_MOV_INT AS DT_ENTRADA,
NVL((SELECT MIN(HR_MOV_INT) -1/(24*60*60)
 FROM MOV_INT
 WHERE CD_ATENDIMENTO = mi.CD_ATENDIMENTO
 AND CD_LEITO_ANTERIOR = mi.CD_LEITO
 AND HR_MOV_INT >= mi.HR_MOV_INT), SYSDATE) AS DT_SAIDA
FROM MOV_INT mi
INNER JOIN dbamv.LEITO lt
ON lt.CD_LEITO = mi.CD_LEITO
INNER JOIN dbamv.UNID_INT unid
ON unid.CD_UNID_INT = lt.CD_UNID_INT
INNER JOIN dbamv.SETOR st
ON st.CD_SETOR = unid.CD_SETOR
WHERE mi.CD_ATENDIMENTO IS NOT NULL
AND mi.TP_MOV <> 'R'
AND st.CD_SETOR = '$var_frm_setor') lt_set
ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
INNER JOIN dbamv.PACIENTE pac
ON pac.CD_PACIENTE = atd.CD_PACIENTE
WHERE prest.CD_TIP_PRESTA IN (4,8,9)
AND itpm.CD_TIP_ESQ IN ('ANT','DPM','DEP','MAR','MCD','MED','MNP','MUC','QT','SOR','LM','HEM','LAB')
AND itpm.CD_TIP_PRESC <> 47493
AND pm.HR_PRE_MED >= SYSDATE -2
AND hm.DH_MEDICACAO >= SYSDATE-1
AND itpm.DH_CANCELADO IS NULL
AND hm.CD_ITPRE_MED  || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                  NOT IN (SELECT cons.CD_ITPRE_MED || TO_CHAR(cons.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                          FROM dbamv.HRITPRE_CONS cons
                          WHERE cons.SN_SUSPENSO = 'S'
                          AND TRUNC(cons.DH_CHECAGEM) >= TRUNC(SYSDATE-2))
AND hm.CD_ITPRE_MED NOT IN (SELECT bs.CD_ITPRE_MED 
            FROM dbamv.VW_BOLSAS_SANGUE_COM_RESERVA bs 
            WHERE bs.SN_BOLSA_COM_RESERVA = 'S')
                            GROUP BY  atd.CD_ATENDIMENTO, lt_set.DS_LEITO, atd.CD_PACIENTE, pac.NM_PACIENTE, pac.DT_NASCIMENTO, pac.NM_MAE";

    $result_pac  = oci_parse($conn_ora, $consulta_pac);

    oci_execute($result_pac); 

    while($row_pac = oci_fetch_array($result_pac)){

        echo '<div class="row justify-content-md-center">';

            echo '<div 
                class="col-11" style="padding: 3px; border-radius: 3px; margin-top: 10px;
                color: #ffffff; background-color: #6996EF !important;">';

                    echo '<div class="row justify-content-md-center" style="padding: 1px 1em 0 1em; border-radius: 3px !important;">';
                        
                        echo '<div class="col-11" style="background-color: #6996EF !important; padding-top: 3px; padding-bottom: 3px;">';
                            
                                echo '<b>'. $row_pac['CD_ATENDIMENTO'] . ' - ' . $row_pac['DS_LEITO'] . ' - ' . $row_pac['NM_PACIENTE'] . ' - PENDÊNCIAS: ' . $row_pac['QTD_PENDENCIA'] . ' ' .
                            
                                //BUTTON QUE VAI CHAMAR A MODAL COM AJAX
                                '<button type="button" class="btn btn-primary" style="padding: 0px 6px 0px 6px !important;" data-toggle="modal" 
                                data-target="#detalhepac" data-cdpac="'.$row_pac['CD_PACIENTE'].'">
                                <i class="fas fa-info-circle"></i></button>'. '</b>';
                                
                        echo '</div>';

                        echo '<div onclick="mostrar_pac_'. $id_paciente . '('.$row_pac['CD_PACIENTE'].','.$var_frm_setor .
                        ')" class="col-1" style="background-color: #6996EF !important; padding-top: 3px; padding-bottom: 3px;">';
                            echo '<b> <i id="proc_bot_'.$id_detalhado.'" class="fas fa-chevron-down"></i> </b>';           
                    
                        echo '</div>';

                    echo '</div>';

            echo '</div>';
         

        /////////////
        //DETALHADO//
        /////////////     

        echo '<div id="detalhe_' . $id_detalhado . '"                      
                class="col-11" style=" margin: 0 auto; border-radius: 3px; margin-top: 22px;
                border: solid 1px #6996EF;">';

                    echo "<div id='det_pend_" . $id_detalhado . "'></div>";

                        //CONSTRUTOR DA TABLE
                        include 'construtor_det_pend.php';                   

        echo '</div>';  

        echo '</div>';  

        $id_detalhado = $id_detalhado + 1;  

        $id_paciente = $id_paciente + 1;

    }

?>        
       
    </div>
</div>

<?php

    ////////////////////////////
    // MODAL DETALHE PACIENTE //
    ////////////////////////////    
    include 'modal_info_pac.php';

    include "rodape.php";

?>