<script>

    var proc<?php echo $id_detalhado;?> = document.getElementById("<?php echo 'detalhe_' . $id_detalhado;?>");
    var proc_bot_<?php echo $id_detalhado;?> = document.getElementById("<?php echo 'proc_bot_' . $id_detalhado;?>");

    proc<?php echo $id_detalhado; ?>.style.display = 'none';

    function mostrar_pac_<?php echo $id_paciente;?>(z,x,y){
        
        if(proc<?php echo $id_detalhado;?>.style.display == 'none'){  

            //QUANDO EXIBIR
            proc<?php echo $id_detalhado;?>.style.display = 'block';
            proc_bot_<?php echo $id_detalhado;?>.classList.remove('fas','fa-chevron-down');
            proc_bot_<?php echo $id_detalhado;?>.classList.add('fas','fa-chevron-up');

            //alert('Teste exibição');

            //COLETANDO DADOS VIA AJAX E PREENCHENDO A TABELA DE DADOS

            var var_atd = z;
            var var_pac = x;
            var var_set = y;            

            //alert(var_pac);
            //alert(var_set);  

            //LIMPANDO DIV result caso exista
            $('#result<?php echo $id_detalhado;?>'). remove(); 
            
            //CRIADOR DA DIV
            const result = document.createElement("table");
            result.id = 'result' + <?php echo $id_detalhado;?>;

            //ADICIONANDO CLASSES
            result.classList.add('table');
            //result.classList.add('table-striped');

            //INICIANDO O INNER HTML
            result.innerHTML = "";

            result.innerHTML += "<thead><th class='align-middle' style='text-align: center;'> Prescrição</th>"
                                +"<th class='align-middle' style='text-align: center;'> Esquema</th>"	
                                +"<th class='align-middle' style='text-align: center;'> Descrição</th>"	
                                +"<th class='align-middle' style='text-align: center;'> Horário Medicação</th></thead>";    

            //PASSANDO VALOR DO CAMPO PESQUISA E EXECUTANDO AJAX
            $.getJSON('call_info_det_pend.php?search=',{var_atd_ajax: var_atd, var_cd_paciente: var_pac, var_cd_setor: var_set, ajax: 'true'}, function(j){

            for (var i = 0; i < j.length; i++) {

                //alert(i);

                if(i % 2 != 0){
                    
                    //INCLUINDO RESULTADOS ENCONTRADOS                                       
                    result.innerHTML += "<tr>"
                                        +"<td style='text-align: center; background-color: #f2f2f2;'>"+ j[i].res_prescricao + "</td>"
                                        +"<td style='text-align: center; background-color: #f2f2f2;'>"+ j[i].res_esquema + "</td>"       
                                        +"<td style='text-align: center; background-color: #f2f2f2;'>"+ j[i].res_descricao + "</td>"     
                                        +"<td style='text-align: center; background-color: #f2f2f2;'>"+ j[i].res_dh_medicacao + "</td>"
                                        +"</tr>";

                }else{

                //INCLUINDO RESULTADOS ENCONTRADOS                                       
                result.innerHTML += "<tr>"
                                    +"<td style='text-align: center;'>"+ j[i].res_prescricao + "</td>"
                                    +"<td style='text-align: center;'>"+ j[i].res_esquema + "</td>"       
                                    +"<td style='text-align: center;'>"+ j[i].res_descricao + "</td>"     
                                    +"<td style='text-align: center;'>"+ j[i].res_dh_medicacao + "</td>"
                                    +"</tr>";

                }

            }

        
            });

            //ADICIONANDO A NOVA DIV DENTRO DO RETORNO
            document.getElementById("det_pend_<?php echo $id_detalhado;?>").appendChild(result);
                                    
        }else{

            //QUANDO OCULTAR

            proc<?php echo $id_detalhado;?>.style.display = 'none';
            proc_bot_<?php echo $id_detalhado;?>.classList.remove('fas','fa-chevron-up');
            proc_bot_<?php echo $id_detalhado;?>.classList.add('fas','fa-chevron-down');
            
        }
    }

</script>