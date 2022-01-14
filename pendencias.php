<?php

    include "cabecalho.php";

    $id_paciente = 1;
    $id_procedimento = 1000000;

    if(isset($_POST['frm_setor'])){

        @$var_frm_setor = $_POST['frm_setor'];

    }else{

        @$var_frm_setor = 0;

    }    

    echo 'SETOR SELECIONADO = ' . $var_frm_setor . '</br></br>';

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

    $consulta_tot = "SELECT SUM(TOTAL) AS TOTAL
    FROM (SELECT pac.APA_CORPO,
                 pac.APA_CMP,
                 pac.APA_NUM,
                 pac.DS_PROCEDIMENTO,
                 SUM(pac.PAP_QTDPROD) AS QTD,
                 SUM(pac.VL_SERVICO_AMBULATORIAL) AS SOMA,
                 SUM(pac.PAP_QTDPROD) * SUM(pac.VL_SERVICO_AMBULATORIAL) AS TOTAL
            FROM apac.APAC_CONS_GERAL pac
            WHERE pac.APA_CMP = '$var_ano_mes'
           GROUP BY pac.APA_CORPO,
                    pac.APA_CMP,
                    pac.APA_NUM,
                    pac.APA_NOMEPCNTE,
                    pac.DS_PROCEDIMENTO) res";

    $result_tot  = oci_parse($conn_ora, $consulta_tot);

    @oci_execute($result_tot); 

    $row_tot = oci_fetch_array($result_tot);

    echo '<div class="row justify-content-md-center">';

        echo '<div 
            class="col-11" style="padding: 8px; border-radius: 3px; margin-top: 10px;
            color: #ffffff; background-color: #417ffa !important;">';
                                                        
            echo '<b>' . 'TOTAL - R$' . @number_format($row_tot['TOTAL'] , 2, ',', '.') . '</b>';

        echo '</div>';  
                    
    echo '</div>';  



    ////////////
    //PACIENTE//
    ////////////

    $consulta_pac = "SELECT res.APA_CORPO, res.APA_CMP, res.APA_NUM, res.APA_NOMEPCNTE,res.APA_NPRONT,
                        SUM(TOTAL) AS TOTAL
                        FROM(
                        SELECT pac.APA_CORPO,
                            pac.APA_CMP,
                            pac.APA_NUM,
                            pac.APA_NPRONT,
                            pac.APA_NOMEPCNTE,
                            pac.DS_PROCEDIMENTO,
                            SUM(pac.PAP_QTDPROD) AS QTD,
                            SUM(pac.VL_SERVICO_AMBULATORIAL) AS SOMA,
                            SUM(pac.PAP_QTDPROD) * SUM(pac.VL_SERVICO_AMBULATORIAL) AS TOTAL
                        FROM apac.APAC_CONS_GERAL pac
                        WHERE pac.APA_CMP = '$var_ano_mes'
                        GROUP BY pac.APA_CORPO, pac.APA_CMP, pac.APA_NUM,pac.APA_NPRONT, pac.APA_NOMEPCNTE, pac.DS_PROCEDIMENTO
                        ) res
                        GROUP BY res.APA_CORPO, res.APA_CMP, res.APA_NUM, res.APA_NOMEPCNTE,res.APA_NPRONT
                        ORDER BY res.APA_NOMEPCNTE";

    $result_pac  = oci_parse($conn_ora, $consulta_pac);

    @oci_execute($result_pac); 

    while($row_pac = oci_fetch_array($result_pac)){

        echo '<div class="row justify-content-md-center">';

        echo '<div 
              class="col-11" style="padding: 3px; border-radius: 3px; margin-top: 10px;
              color: #ffffff; background-color: #6996EF !important;">';
                                                        
 
             echo '<div class="row justify-content-md-center" style="padding: 1px 1em 0 1em; border-radius: 3px !important;">';
             $var_apac=$row_pac['APA_NUM'];
                            echo '<div class="col-11" style="background-color: #6996EF !important; padding-top: 3px; padding-bottom: 3px;">';
                                echo '<b> APAC: ' . $var_apac . ' - PRONTUÁRIO ' . $row_pac['APA_NPRONT'] . ' - '. $row_pac['APA_NOMEPCNTE'] . ' - R$' . @number_format($row_pac['TOTAL'] , 2, ',', '.')  . ' ' . '<button type="button" class="btn btn-primary" style="padding: 0px 6px 0px 6px !important;" data-toggle="modal" data-target="#detalhejust'.$id_procedimento.'">
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
            //DIALITICO//
            /////////////

            $consulta_dia = "SELECT res.APA_VARIA, res.APA_CIDPRI, res.DS_CID,
            SUM(TOTAL) AS TOTAL
            FROM(
                SELECT vg.APA_VARIA, vg.APA_CIDPRI, vg.DS_CID, vg.DS_PROCEDIMENTO,
                    SUM(vg.PAP_QTDPROD) AS QTD,
                    SUM(vg.VL_SERVICO_AMBULATORIAL) AS SOMA,
                    SUM(vg.PAP_QTDPROD) * SUM(vg.VL_SERVICO_AMBULATORIAL) AS TOTAL
                    FROM apac.APAC_CONS_GERAL vg
                   WHERE vg.APA_CMP = '$var_ano_mes'
                     AND vg.APA_CORPO = '". $row_pac['APA_CORPO'] . "' ".
                    "AND vg.APA_CMP = '". $row_pac['APA_CMP'] . "' ".
                    "AND vg.APA_NUM = '". $row_pac['APA_NUM'] . "' 
                GROUP BY vg.APA_VARIA, vg.APA_CIDPRI, vg.DS_CID, vg.DS_PROCEDIMENTO
            ) res
            GROUP BY res.APA_VARIA, res.APA_CIDPRI, res.DS_CID";

            $result_dia  = oci_parse($conn_ora, $consulta_dia);
        
            @oci_execute($result_dia); 
        
                while($row_dia = oci_fetch_array($result_dia)){

                    echo '<div id="pac_' . $id_paciente . '" 
                        class="col-11" style="padding: 4px; border-radius: 3px; 
                        margin-top: 10px; margin-bottom: 10px; background-color: #f9f9f9 !important;">';
   
                        echo '<div class="row justify-content-md-center" style="padding: 3px 1em 0 1em; border-radius: 3px !important;">';
                        
                            echo '<div class="col-11" style="background-color: #d8e5ff !important; padding-top: 3px; padding-bottom: 3px;">';
                                echo '<b>'. $row_dia['APA_CIDPRI'] .' - '. $row_dia['DS_CID'] . ' - R$' . @number_format($row_pac['TOTAL'] , 2, ',', '.') .'</b>';
                            echo '</div>';

                            echo '<div onclick="mostrar_proc_'. $id_procedimento . '()" class="col-1" style="background-color: #d8e5ff !important; padding-top: 3px; padding-bottom: 3px;">';
                                echo '<b> <i id="proc_bot_'.$id_procedimento.'" class="fas fa-chevron-down"></i> </b>';           
                            echo '</div>';

                        echo '</div>';



                        ////////////////
                        //PROCEDIMENTO//
                        ////////////////

                        $consulta_proc = "SELECT proc.PAC_CORPO_PROC, proc.PAP_CODPROC, proc.DS_PROCEDIMENTO,
                                        SUM(proc.PAP_QTDPROD) AS QTD,
                                        SUM(proc.VL_SERVICO_AMBULATORIAL) AS SOMA,
                                        SUM(proc.PAP_QTDPROD) * SUM(proc.VL_SERVICO_AMBULATORIAL) AS TOTAL
                                        FROM apac.APAC_CONS_GERAL proc
                                       WHERE proc.APA_CMP = '$var_ano_mes'
                                         AND proc.APA_CORPO = '". $row_pac['APA_CORPO'] . "' ".
                                        "AND proc.APA_CMP = '". $row_pac['APA_CMP'] . "' ".
                                        "AND proc.APA_NUM = '". $row_pac['APA_NUM'] . "' " .
                                        "AND proc.APA_VARIA = '". $row_dia['APA_VARIA'] . "' ".
                                        "AND proc.APA_CIDPRI = '". $row_dia['APA_CIDPRI'] . "' 
                                        GROUP BY proc.PAC_CORPO_PROC, proc.PAP_CODPROC, proc.DS_PROCEDIMENTO
                                        ORDER BY proc.DS_PROCEDIMENTO ASC";

                        $result_proc  = oci_parse($conn_ora, $consulta_proc);
                    
                        @oci_execute($result_proc); 


                        echo '<div id="proc_' . $id_procedimento . '"                      
                            class="col-11" style=" margin: 0 auto; border-radius: 3px; margin-top: 22px;
                            border: solid 1px #6996EF;">';

                            echo "<div class='table-responsive col-md-12'>
                                <table class='table table-striped' cellspacing='0' cellpadding='0'>" . "<thead><tr>"; 
                                    
                                    echo "<th class='align-middle' style='text-align: center;'> Cod. Procedimento</th>
                                          <th class='align-middle' style='text-align: center;'> Procedimento</th>	
                                          <th class='align-middle' style='text-align: center;'> Valor</th>		  
                                          <th class='align-middle' style='text-align: center;'> Quantidade</th>                                          
                                          <th class='align-middle' style='text-align: center;'> Total</th>";

                                    echo "</tr></thead>";	
                                    	

                                        while($row_proc = oci_fetch_array($result_proc)){

                                            echo "<tr>";

                                            $var_codproc =  $row_proc['PAP_CODPROC'];

                                                if (strlen($var_codproc) == 9){
                                                    echo "<td style='text-align: center;'>0" . $var_codproc . "<br>" . "</td>";
                                                }else{
                                                    echo "<td style='text-align: center;'>" . $var_codproc . "<br>" . "</td>";
                                                }
                                               
                                                echo "<td style='text-align: center;'>" . $row_proc['DS_PROCEDIMENTO']. "<br>" . "</td>";
                                                echo "<td style='text-align: center;'>R$" . $row_proc['SOMA']. "<br>" . "</td>";
                                                echo "<td style='text-align: center;'>" . $row_proc['QTD'] . "<br>" . "</td>";                                                
                                                echo "<td style='text-align: center;'>R$" . @number_format($row_proc['TOTAL'] , 2, ',', '.'). "<br>" . "</td>";
                                            echo "</tr>";
                                        }

                            echo "</table>";

                        echo "</div>";

                    echo '</div>';  

                echo '</div>';

                ?>

                <script>

                var proc<?php echo $id_procedimento;?> = document.getElementById("<?php echo 'proc_' . $id_procedimento;?>");
                var proc_bot_<?php echo $id_procedimento;?> = document.getElementById("<?php echo 'proc_bot_' . $id_procedimento;?>");

                proc<?php echo $id_procedimento; ?>.style.display = 'none';


                function mostrar_proc_<?php echo $id_procedimento;?>(){
                    if(proc<?php echo $id_procedimento;?>.style.display == 'none'){    
                        proc<?php echo $id_procedimento;?>.style.display = 'block';

                        proc_bot_<?php echo $id_procedimento;?>.classList.remove('fas','fa-chevron-down');
                        proc_bot_<?php echo $id_procedimento;?>.classList.add('fas','fa-chevron-up');
                        
                        

                    }else{
                        proc<?php echo $id_procedimento;?>.style.display = 'none';

                        proc_bot_<?php echo $id_procedimento;?>.classList.remove('fas','fa-chevron-up');
                        proc_bot_<?php echo $id_procedimento;?>.classList.add('fas','fa-chevron-down');
                       
                    }
                }

                </script>

                <?php 

                $id_procedimento = $id_procedimento + 1;

            }
            
        echo '</div>';

        ?>

        <script>

            var proc<?php echo $id_paciente;?> = document.getElementById("<?php echo 'pac_' . $id_paciente;?>");
            var pac_bot_<?php echo $id_paciente;?> = document.getElementById("<?php echo 'pac_bot_' . $id_paciente;?>");

            proc<?php echo $id_paciente; ?>.style.display = 'none';

            function mostrar_pac_<?php echo $id_paciente;?>(){
                if(proc<?php echo $id_paciente;?>.style.display == 'none'){    
                    proc<?php echo $id_paciente;?>.style.display = 'block';

                    pac_bot_<?php echo $id_paciente;?>.classList.remove('fas','fa-chevron-down');
                    pac_bot_<?php echo $id_paciente;?>.classList.add('fas','fa-chevron-up');

                }else{
                    proc<?php echo $id_paciente;?>.style.display = 'none';
                    pac_bot_<?php echo $id_paciente;?>.classList.remove('fas','fa-chevron-up');
                    pac_bot_<?php echo $id_paciente;?>.classList.add('fas','fa-chevron-down');

                }
            }

        </script>
        
        <?php 

        $id_paciente = $id_paciente + 1;

    }

?>        
       
    </div>
</div>

<?php

    include "rodape.php";

?>