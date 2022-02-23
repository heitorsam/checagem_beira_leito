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
                      AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY')
                      AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED+1,'DD/MM/YYYY')
                      AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY')
                      AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO+1,'DD/MM/YYYY')
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
                                AND st.CD_SETOR = $var_frm_setor) lt_set
                    ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
                    AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
                    WHERE prest.CD_TIP_PRESTA IN (4,8,9)
                    AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR','MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
                    AND pm.HR_PRE_MED >= SYSDATE -2
                    AND hm.DH_MEDICACAO >= SYSDATE-1
                    AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                    NOT IN (SELECT hcaux.CD_ITPRE_MED || '-' || TO_CHAR(hcaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                            FROM dbamv.HRITPRE_CONS hcaux
                            WHERE hcaux.DH_MEDICACAO >= SYSDATE-1)
                            --AND hcaux.SN_SUSPENSO <> 'S')
                    AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                    NOT IN (SELECT csmdaux.CD_ITPRE_MED || '-' || TO_CHAR(csmdaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                            FROM dbamv.HORA_COMPONT_IT_PRESCRIC_CSMD csmdaux
                            WHERE csmdaux.DH_MEDICACAO >= SYSDATE-1)
                    AND itpm.DH_CANCELADO IS NULL
                GROUP BY
                    lt_set.CD_SETOR, lt_set.NM_SETOR";

    $result_tot  = oci_parse($conn_ora, $consulta_tot);

    @oci_execute($result_tot); 

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

    $consulta_pac = "SELECT atd.CD_ATENDIMENTO, lt_set.DS_LEITO, atd.CD_PACIENTE, pac.NM_PACIENTE, pac.DT_NASCIMENTO, pac.NM_MAE,
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
                                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY')
                                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED+1,'DD/MM/YYYY')
                                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY')
                                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO+1,'DD/MM/YYYY')
                            INNER JOIN dbamv.PACIENTE pac
                                ON pac.CD_PACIENTE = atd.CD_PACIENTE       
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
                                        AND st.CD_SETOR = $var_frm_setor) lt_set
                                ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
                                AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
                            WHERE prest.CD_TIP_PRESTA IN (4,8,9)
                            AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR','MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
                            AND pm.HR_PRE_MED >= SYSDATE -2
                            AND hm.DH_MEDICACAO >= SYSDATE-1
                            AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                            NOT IN (SELECT hcaux.CD_ITPRE_MED || '-' || TO_CHAR(hcaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                                    FROM dbamv.HRITPRE_CONS hcaux
                                    WHERE hcaux.DH_MEDICACAO >= SYSDATE-1)
                                    --AND hcaux.SN_SUSPENSO <> 'S')
                            AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                            NOT IN (SELECT csmdaux.CD_ITPRE_MED || '-' || TO_CHAR(csmdaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                                    FROM dbamv.HORA_COMPONT_IT_PRESCRIC_CSMD csmdaux
                                    WHERE csmdaux.DH_MEDICACAO >= SYSDATE-1)
                            AND itpm.DH_CANCELADO IS NULL
                            GROUP BY  atd.CD_ATENDIMENTO, lt_set.DS_LEITO, atd.CD_PACIENTE, pac.NM_PACIENTE, pac.DT_NASCIMENTO, pac.NM_MAE";

    $result_pac  = oci_parse($conn_ora, $consulta_pac);

    @oci_execute($result_pac); 

    while($row_pac = oci_fetch_array($result_pac)){

        echo '<div class="row justify-content-md-center">';

        echo '<div 
              class="col-11" style="padding: 3px; border-radius: 3px; margin-top: 10px;
              color: #ffffff; background-color: #6996EF !important;">';
                                                        
 
             echo '<div class="row justify-content-md-center" style="padding: 1px 1em 0 1em; border-radius: 3px !important;">';
                echo '<div class="col-11" style="background-color: #6996EF !important; padding-top: 3px; padding-bottom: 3px;">';
                    echo '<b>'. $row_pac['CD_ATENDIMENTO'] . ' - ' . $row_pac['DS_LEITO'] . ' - ' . $row_pac['NM_PACIENTE'] . ' - PENDÊNCIAS: ' . $row_pac['QTD_PENDENCIA'] . ' ' .
                    '<button type="button" class="btn btn-primary" style="padding: 0px 6px 0px 6px !important;" data-toggle="modal" data-target="#detalhejust'.$id_paciente.'">
                    <i class="fas fa-info-circle"></i></button>'. '</b>';
                    
                echo '</div>';

                echo '<div onclick="mostrar_pac_'. $id_paciente . '()" class="col-1" style="background-color: #6996EF !important; padding-top: 3px; padding-bottom: 3px;">';
                    echo '<b> <i id="pac_bot_'.$id_paciente.'" class="fas fa-chevron-down"></i> </b>';           
                echo '</div>';

            echo '</div>';

        echo '</div>';          

        ///////////
        // MODAL //
        ///////////
        include 'modal_info_pac.php';

            /////////////
            //DETALHADO//
            /////////////

            $consulta_detalhado = "SELECT TO_CHAR(hm.DH_MEDICACAO, 'YYYY_MM_DD') AS ORD_PERIODO,
            TO_CHAR(hm.DH_MEDICACAO, 'DD/MM/YYYY') AS PERIODO,
            lt_set.CD_SETOR, lt_set.NM_SETOR,
            pm.CD_PRE_MED, pm.CD_ATENDIMENTO,
            pm.HR_PRE_MED, TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI') AS DH_MEDICACAO,
            itpm.CD_ITPRE_MED,
            esq.CD_TIP_ESQ, esq.DS_TIP_ESQ,
            tf.DS_TIP_FRE, tp.DS_TIP_PRESC
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
                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY')
                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED+1,'DD/MM/YYYY')
                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY')
                AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO+1,'DD/MM/YYYY')
            LEFT JOIN dbamv.TIP_PRESC tp
              ON tp.CD_TIP_PRESC = itpm.CD_TIP_PRESC
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
                        AND st.CD_SETOR = $var_frm_setor) lt_set
              ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
              AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
            WHERE prest.CD_TIP_PRESTA IN (4,8,9)
            AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR','MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
            AND pm.HR_PRE_MED >= SYSDATE -2
            AND hm.DH_MEDICACAO >= SYSDATE-1
            AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                NOT IN (SELECT hcaux.CD_ITPRE_MED || '-' || TO_CHAR(hcaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                        FROM dbamv.HRITPRE_CONS hcaux
                        WHERE hcaux.DH_MEDICACAO >= SYSDATE-1)
                        --AND hcaux.SN_SUSPENSO <> 'S')
            AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                NOT IN (SELECT csmdaux.CD_ITPRE_MED || '-' || TO_CHAR(csmdaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                            FROM dbamv.HORA_COMPONT_IT_PRESCRIC_CSMD csmdaux
                            WHERE csmdaux.DH_MEDICACAO >= SYSDATE-1)
            AND atd.CD_PACIENTE = ". $row_pac['CD_PACIENTE'] . "
            AND itpm.DH_CANCELADO IS NULL
            ORDER BY hm.DH_MEDICACAO ASC";

            $result_detalhado  = oci_parse($conn_ora, $consulta_detalhado);
        
            @oci_execute($result_detalhado); 


            echo '<div id="detalhe_' . $id_detalhado . '"                      
                class="col-11" style=" margin: 0 auto; border-radius: 3px; margin-top: 22px;
                border: solid 1px #6996EF;">';

                    echo "<div class='table-responsive col-md-12'>
                          <table class='table table-striped' cellspacing='0' cellpadding='0'>" . "<thead><tr>"; 
                            
                            echo "<th class='align-middle' style='text-align: center;'> Prescrição</th>
                                  <th class='align-middle' style='text-align: center;'> Esquema</th>	
                                  <th class='align-middle' style='text-align: center;'> Descrição</th>	
                                  <th class='align-middle' style='text-align: center;'> Horário Medicação</th>";	
        
                        echo "</tr></thead>";	     
                    
                            while($row_detalhado = oci_fetch_array($result_detalhado)){

                                        echo "<tr>";

                                            echo "<td style='text-align: center;'>" . $row_detalhado['CD_PRE_MED']. "<br>" . "</td>";
                                            echo "<td style='text-align: center;'>" . $row_detalhado['DS_TIP_ESQ']. "<br>" . "</td>";
                                            echo "<td style='text-align: center;'>" . $row_detalhado['DS_TIP_PRESC']. "<br>" . "</td>";
                                            echo "<td style='text-align: center;'>" . $row_detalhado['DH_MEDICACAO']. "<br>" . "</td>";
                                        
                                        echo "</tr>";                               

                            }

            ?>

            <script>

                var proc<?php echo $id_detalhado;?> = document.getElementById("<?php echo 'detalhe_' . $id_detalhado;?>");
                var proc_bot_<?php echo $id_detalhado;?> = document.getElementById("<?php echo 'proc_bot_' . $id_detalhado;?>");

                proc<?php echo $id_detalhado; ?>.style.display = 'none';

                function mostrar_pac_<?php echo $id_paciente;?>(){
                    if(proc<?php echo $id_detalhado;?>.style.display == 'none'){  

                        proc<?php echo $id_detalhado;?>.style.display = 'block';
                        proc_bot_<?php echo $id_detalhado;?>.classList.remove('fas','fa-chevron-down');
                        proc_bot_<?php echo $id_detalhado;?>.classList.add('fas','fa-chevron-up');
                                                
                    }else{

                        proc<?php echo $id_detalhado;?>.style.display = 'none';
                        proc_bot_<?php echo $id_detalhado;?>.classList.remove('fas','fa-chevron-up');
                        proc_bot_<?php echo $id_detalhado;?>.classList.add('fas','fa-chevron-down');
                        
                    }
                }

            </script>

            <?php 

                $id_detalhado = $id_detalhado + 1;                      

            echo "</table>";

            echo '</div>';  

        echo '</div>';
            
        echo '</div>';

        $id_paciente = $id_paciente + 1;

    }

?>        
       
    </div>
</div>

<?php

    include "rodape.php";

?>