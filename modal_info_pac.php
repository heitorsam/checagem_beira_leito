<?php
    
    //Consulta no banco para trazer informações dos pacientes

    $consulta_paci = "SELECT pac.NM_PACIENTE, pac.NR_CPF, pac.DT_NASCIMENTO, pac.NM_MAE
                      FROM dbamv.PACIENTE pac
                      WHERE pac.CD_PACIENTE = " . $row_pac['CD_PACIENTE'];

    $result_paci  = oci_parse($conn_ora, $consulta_paci);

    @oci_execute($result_paci); 

    @$row_paci = oci_fetch_array($result_paci);

?>

<!--MODAL -->
<div class="modal" tabindex="-1" role="dialog" id="detalhejust<?php echo $id_paciente;?>">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" > 
            <div class="modal-header">
                <h5 class="modal-title">INFORMAÇÕES DO PACIENTE - <?php echo $row_paci['NM_PACIENTE'];?> </h5>
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
                            <th class='align-middle' style='text-align: center;'> CPF</th>
                            <th class='align-middle' style='text-align: center;'> Data Nascimento</th>		                                           
                            <th class='align-middle' style='text-align: center;'> Mãe</th>

                        </tr>
                    </thead>
                        <tr>
                            <td style='text-align: center;'><br><?php echo @$row_paci['NM_PACIENTE'];?></td>
                            <td style='text-align: center;'><br><?php echo @$row_paci['NR_CPF'];?></td>
                            <td style='text-align: center;'><br><?php echo @$row_paci['DT_NASCIMENTO'];?></td>
                            <td style='text-align: center;'><br><?php echo @$row_paci['NM_MAE'];?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div> 