<?php 
    //CABECALHO
    include 'cabecalho.php';
?>

<div class="div_br"> </div>

         <!--MENSAGENS-->
         <?php
            include 'js/mensagens.php';
            include 'js/mensagens_usuario.php';
        ?>
                
            <div class="div_br"> </div>        

            <h11><i class="fas fa-signature"></i> Guia TISS</h11>

            <div class="div_br"> </div>

            <a href="gerar_documento.php" class="botao_home" type="submit"><i class="fas fa-file-signature"></i> Gerar Documento</a></td></tr>

            <div class="div_br"> </div>

            <h11><i class="fas fa-signature"></i> Checagem Beira Leito</h11>

            <div class="div_br"> </div>

            <a href="check_gerar_documento.php" class="botao_home" type="submit"><i class="fas fa-file-signature"></i> Gerar Documento</a></td></tr>
            
            <span class="espaco_pequeno"></span>
            
            <a href="check_visualizar_documento.php" class="botao_home" type="submit"><i class="far fa-file-alt"></i> Visualizar Documento</a></td></tr>

            <div class="div_br"> </div>

        <?php if(@$_SESSION['sn_admin'] == 'S'){ ?>

            <!--TITULO-->
            <h11><i class="fa fa-cogs" aria-hidden="true"></i> Administrador</h11>
            
            <div class="div_br"> </div>

            <a href="permissoes.php" class="botao_home_adm" type="submit"><i class="fas fa-user-cog"></i> Permissões</a></td></tr>
            <span class="espaco_pequeno"></span>
            <a href="cad_assinatura.php" class="botao_home_adm" type="submit"><i class="fas fa-user-nurse"></i> Cadastrar Assinatura</a></td></tr>
            

        <?php } ?>
            
<?php
    //RODAPE
    include 'rodape.php';
?>