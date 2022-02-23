<?php

    if(isset($_POST['frm_nome'])){

        $var_nome = $_POST['frm_nome'];
        $var_idade = $_POST['frm_idade'];
        $var_sexo = $_POST['frm_sexo'];

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a3000fd09d.js" crossorigin="anonymous"></script>

</head>
<body style="padding: 21px";>

    <div style="width: 20%; font-size:20px; border-bottom: solid 1px #c3c3c3";>
        <i class="far fa-user"></i> Formulário
    </div>

    <form method="POST" action="formulario.php">

        <br>Nome:<br>
        <input type="text" value="<?php echo @$var_nome;?>" class="form-control"  style="width: 30%;" name="frm_nome" minlength="5" required>

        <br>Idade:<br>
        <input type="number"  value="<?php echo @$var_idade;?>"  class="form-control" style="width: 30%;" name="frm_idade" required>

        <br>Sexo:<br>
        <select name="frm_sexo" class="form-control" style="width: 30%;" required>
            <?php if($var_sexo == "F"){ ?>

                <option value="F">Feminino</option>
                <option value="M">Masculino</option>

            <?php }else{ ?>

                <option value="M">Masculino</option>
                <option value="F">Feminino</option>              

            <?php } ?>            

        </select>

        <br>
        <button type="submit" class="btn btn-primary">Enviar</button>

    </form>
    
</body>

<?php    
    
    if(isset($_POST['frm_nome'])){

        if($var_sexo == "M"){
            echo '<style>
                    .caixa_apresentacao{
    
                        width: 300px;
                        height: auto;
                        border: solid 2px blue;    
                        background-color: lightblue;
                    } 
                    
                 </style>';              
                        
        }else{
    
            echo '<style>
                    .caixa_apresentacao{
    
                        width: 300px;
                        height: auto;
                        border: solid 2px pink; 
                        background-color: lightpink;   
                    } 
                    
                 </style>';  
    
        }

        echo '<div class="caixa_apresentacao" style="text-align: center; margin-top: 20px; border-radius: 3px;">';

            echo "<br><br>";
            echo "Olá! eu sou o: " . $var_nome;

            if($var_idade >= 18){

                echo "<br><br>";
                echo "Sou maior de idade.";

            }else{

                echo "<br><br>";
                echo "Sou menor de idade.";

            }

            echo "<br><br><br>";

        echo '</div>';

    }

?>

</html>