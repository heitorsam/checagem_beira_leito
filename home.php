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

            <h11><i class="far fa-check-square"></i> Checagem Beira Leito</h11>

            <div class="div_br"> </div>

            <a href="pendencias.php" class="botao_home" type="submit"><i class="fas fa-tasks"></i> Pendências </a></td></tr>
            
            <span class="espaco_pequeno"></span>
            
            <a href="impressao.php" class="botao_home" type="submit"><i class="fas fa-list-ol"></i> Lista Impressão </a></td></tr>

        <?php if(@$_SESSION['sn_admin'] == 'S'){ ?>

            <!--TITULO-->
            <h11><i class="fa fa-cogs" aria-hidden="true"></i> Administrador</h11>
            
            <div class="div_br"> </div>

            <a href="permissoes.php" class="botao_home_adm" type="submit"><i class="fas fa-user-cog"></i> Permissões</a></td></tr>

            <span class="espaco_pequeno"></span>

        <?php } ?>
            
<?php
    //RODAPE
    include 'rodape.php';
?>