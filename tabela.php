<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

TABELA:
</br></br>

<table>
    <!--TABLE ROW (LINHA)-->
    <tr>
        <!--TABLE HEADER (CABECALHO)-->
        <th>Código</th>
        <th>Nome</th>
        <th>Idade</th>
    </tr>

    <!--LINHA 01 CONTEUDO-->
    <tr>
        <td>01</td>
        <td>Heitor</td>
        <td>28</td>
    </tr>

    <!--LINHA 02 CONTEUDO-->
    <tr>
        <td>02</td>
        <td>Aldrik</td>
        <td>20</td>
    </tr>

    <!--LINHA 03 CONTEUDO-->
    <tr>
        <td>03</td>
        <td>Rafael</td>
        <td>22</td>
    </tr>

</table>

</br></br>

<table class="table table-striped" style="width: 20%;">
    <!--TABLE ROW (LINHA)-->
    <tr style="background-color: white;">
        <!--TABLE HEADER (CABECALHO)-->
        <th>Código</th>
        <th>Nome</th>
        <th>Idade</th>
    </tr>

    <!--LINHA 01 CONTEUDO-->
    <tr>
        <td>01</td>
        <td>Heitor</td>
        <td>28</td>
    </tr>

    <!--LINHA 02 CONTEUDO-->
    <tr style="background-color: white;">
        <td>02</td>
        <td>Aldrik</td>
        <td>20</td>
    </tr>

    <!--LINHA 03 CONTEUDO-->
    <tr>
        <td>03</td>
        <td>Rafael</td>
        <td>22</td>
    </tr>

</table>

<style>

    table{
        background-color: lightblue;
        border-radius:4px;
        border-spacing: 0px;
        text-align: center;
        padding: 8px;   

    }

    th{
        background-color: white;
        color: red;       
        border-bottom: solid 1px #c3c3c3;
        padding: 5px;    
    }

    td{
        background-color: white;  
        border-bottom: solid 1px #c3c3c3;   
        padding: 5px;   
    }


</style>