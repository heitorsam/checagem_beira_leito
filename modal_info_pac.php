<?php
    
    //Consulta no banco para trazer informações dos pacientes

    $consulta_paci = "SELECT imp.paciente AS NOME, pac.apa_nomepcnte AS NOME2 ,SUBSTR(pac.APA_DATANASCIM,7,2) || '/' || SUBSTR(pac.APA_DATANASCIM,5,2) || '/' || SUBSTR(pac.APA_DATANASCIM,0,4) AS DATA_NASC,pac.APA_SEXOPCNTE AS SEXO,
                    pac.APA_LOGPCNTE AS ENDER,pac.APA_BAIRRO AS BAIRRO ,pac.APA_NUMPCNTE AS NUMERO, imp.nome_mae AS NOME_MAE,pac.apa_nomemae AS NOME_MAE2 ,imp.cpf AS CPF, imp.tel AS TEL
                    FROM APAC_PACIENTE pac
                    LEFT JOIN PACIENTE_IMPORTA imp
                    ON SUBSTR(TRANSLATE(pac.APA_NOMEPCNTE,'ÁÇÉÍÓÚÀÈÌÒÙÂÊÎÔÛÃÕËÜáçéíóúàèìòùâêîôûãõëü',
                                        'ACEIOUAEIOUAEIOUAOEUaceiouaeiouaeiouaoeu'),0,30) LIKE SUBSTR(TRANSLATE(imp.paciente,'ÁÇÉÍÓÚÀÈÌÒÙÂÊÎÔÛÃÕËÜáçéíóúàèìòùâêîôûãõëü',
                                        'ACEIOUAEIOUAEIOUAOEUaceiouaeiouaeiouaoeu'),0,30)
                    AND SUBSTR(pac.APA_DATANASCIM,7,2) || '/' || SUBSTR(pac.APA_DATANASCIM,5,2) || '/' || SUBSTR(pac.APA_DATANASCIM,0,4) = imp.data_nasc
                    WHERE APA_NUM =  '$var_apac'";

    $result_paci  = oci_parse($conn_ora, $consulta_paci);

    @oci_execute($result_paci); 

    @$row_paci = oci_fetch_array($result_paci);

?>

    <!--MODAL -->
<div class="modal" tabindex="-1" role="dialog" id="detalhejust<?php echo $id_procedimento; ?>">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" > 
            <div class="modal-header">
                <h5 class="modal-title">INFORMAÇÕES DO PACIENTE - <?php echo $row_paci['NOME'];?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                
            <div class="modal-body" style="text-align: left !important">   

                <div class='table-responsive col-md-12'>
                    <table class='table table-striped' cellspacing='0' cellpadding='0'>
                    <thead>
                        <tr>
                            <th class='align-middle' style='text-align: center;'> Nome</th>
                            <th class='align-middle' style='text-align: center;'> CPF </th>
                            <th class='align-middle' style='text-align: center;'> TEL </th>
                            <th class='align-middle' style='text-align: center;'> Data Nasci</th>		                                           
                            <th class='align-middle' style='text-align: center;'> Sexo </th>
                            <th class='align-middle' style='text-align: center;'> Endereço </th>
                            <th class='align-middle' style='text-align: center;'> Número </th>
                            <th class='align-middle' style='text-align: center;'> Bairro </th>
                            <th class='align-middle' style='text-align: center;'> Mãe </th>

                        </tr>
                    </thead>
                        <tr>
                            <!-- if para verificar se info existe ou não-->
                            <td style='text-align: center;'><br>
                            <?php if(!isset($row_paci['NOME'])){
                                    echo $row_paci['NOME2'];
                                }else{
                                    echo $row_paci['NOME'];}?></td>
                            <td style='text-align: center;'><br>
                                <?php if(!isset($row_paci['CPF'])){
                                    echo 'Sem Cadastro';
                                }else{
                                    echo $row_paci['CPF'];}?></td>
                            <td style='text-align: center;'><br>
                                <?php if(!isset($row_paci['TEL'])){
                                    echo 'Sem Cadastro';
                                }else{
                                    echo $row_paci['TEL'];}?></td>
                            <td style='text-align: center;'><br><?php echo $row_paci['DATA_NASC'];?></td>
                            <td style='text-align: center;'><br><?php echo $row_paci['SEXO'];?></td>
                            <td style='text-align: center;'><br><?php echo $row_paci['ENDER'];?></td>
                            <td style='text-align: center;'><br><?php echo $row_paci['NUMERO'];?></td>
                            <td style='text-align: center;'><br><?php echo $row_paci['BAIRRO'];?></td>
                            <td style='text-align: center;'><br>
                                <?php if(!isset($row_paci['NOME_MAE'])){
                                    echo $row_paci['NOME_MAE2'];
                                }else{
                                    echo $row_paci['NOME_MAE'];} ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div> 