<?php
    
    //Consulta no banco para trazer informações dos pacientes

    //$consulta_paci = "SELECT pac.NM_PACIENTE, pac.NR_CPF, pac.DT_NASCIMENTO, pac.NM_MAE
    //                  FROM dbamv.PACIENTE pac
    //                  WHERE pac.CD_PACIENTE = " . $row_pac['CD_PACIENTE'];

    //$result_paci  = oci_parse($conn_ora, $consulta_paci);

    //@oci_execute($result_paci); 

    //@$row_paci = oci_fetch_array($result_paci);

?>

<!--MODAL -->
<div class="modal" tabindex="-1" role="dialog" id="detalhepac">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" > 
            <div class="modal-header">
                <h5 class="modal-title">Informações do paciente</h5>
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
                    <tbody id="ret_info_pac">
                    <tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div> 

<!--JAVASCRIPT PARA ALIMENTAR A TABELA-->
<script type="text/javascript">

$(document).ready(function(){
    $(document).on('shown.bs.modal','.modal', function (event) {

         //DO EVENTS
        var button = $(event.relatedTarget)

        var var_pac = button.data('cdpac')
        console.log(var_pac);


            //LIMPANDO DIV result caso exista
            $('#result'). remove(); 
            
            //CRIADNO DA DIV
            const result = document.createElement("tr");
            result.id = 'result';


            //INICIANDO O INNER HTML
            result.innerHTML = "";

            //PASSANDO VALOR DO CAMPO PESQUISA E EXECUTANDO AJAX
            $.getJSON('call_info_pac.php?search=',{var_cd_paciente: var_pac, ajax: 'true'}, function(j){

                for (var i = 0; i < j.length; i++) {
                    //INCLUINDO RESULTADOS ENCONTRADOS
                    
                    console.log(j[i].paciente);            
                    result.innerHTML = "<td style='text-align: center;'>"+ j[i].paciente + "</td>"
                                        +"<td style='text-align: center;'>"+ j[i].cpf + "</td>"       
                                        +"<td style='text-align: center;'>"+ j[i].data_nasc + "</td>"     
                                        +"<td style='text-align: center;'>"+ j[i].nome_mae + "</td>";

                }
        
            });

            //ADICIONANDO A NOVA DIV DENTRO DO RETORNO
            document.getElementById("ret_info_pac").appendChild(result);

        });
});
        
</script>     