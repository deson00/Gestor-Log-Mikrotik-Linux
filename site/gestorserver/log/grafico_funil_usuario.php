<?php
// A sess�o precisa ser iniciada em cada p�gina diferente
if (!isset($_SESSION))
    session_start();
$nivel_necessario = 5;
// Verifica se n�o h� a vari�vel da sess�o que identifica o usu�rio
if (!isset($_SESSION['UsuarioID']) OR ( $_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    // Destr�i a sess�o por seguran�a
    session_destroy();
    // Redireciona o visitante de volta pro login
    header("Location: /site/login/index.php");
    exit;
}
?>
<?php set_time_limit(0); ?>
<?php
//$date = date('Y-m-d H:i');
//echo $date;
require_once('../../Connections/site.php');
$iduser = $_SESSION['iduser'];
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
$tipo = mysql_real_escape_string($_REQUEST['tipo']);
$conta = mysql_real_escape_string($_REQUEST['conta']);
$ip = mysql_real_escape_string($_REQUEST['ipusuariolog']);
//$data1br = date('d/m/Y', strtotime($data1));
//$data2br = date('d/m/Y', strtotime($data2));
//if ( $data1 == "") {
//   $data1 = date('Y-m-d', strtotime("-7 days")); 
//   
//  
//}
//if ( $data2 == "") {
//    
//   $data2 = date('Y-m-d'); 
//  
//}
//echo '<tr><td>Conta: '.$conta.'</td>  <td>'$tipo'</td>  <td>'$data1''$data2'</td> </tr>';
//echo $iduser;
//seleciona banco de dados
mysql_select_db($database_site, $site);

$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die("sql select erro");

if ($ip != "") {

    $sqlselecionausuariolog2 = "SELECT * FROM `usuario_log` where `ipusuariolog`= '$ip' ";
    $queryselecionausuariolog2 = mysql_query($sqlselecionausuariolog2) or die("sql select erro2");
    $row_rsselecionausuariolog2 = mysql_fetch_assoc($queryselecionausuariolog2);
    $usuariolog = $row_rsselecionausuariolog2['usuariolog'];
}

//linha original 
//$queryfiltro = "SELECT Message, Count(Message) AS ContMessage FROM SystemEvents WHERE `SysLogTag`='web-proxy,account' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' GROUP BY  Message ORDER BY `ContMessage` DESC LIMIT 0,30;";
$queryfiltro = "SELECT Message FROM SystemEvents WHERE `Message` LIKE '%$ip%' and `SysLogTag` like 'web-proxy,account' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' GROUP BY  Message ORDER BY `Message`";

//echo $queryfiltro;
$resultfiltro = mysql_query($queryfiltro) or die(mysql_error());
while ($row_rslistafiltrografico2 = mysql_fetch_assoc($resultfiltro)) {
      $dominio = $row_rslistafiltrografico2['Message'];
    //echo "<br>";   
    $posicao = strpos($dominio, 'http://');
    if ($posicao != "" || $posicao > 0) {
        $texto = substr($dominio, $posicao + 7); 
        $string = explode("/", $texto);
        $nomefiltrocategoria = $string[0];
        $acessos[] = $nomefiltrocategoria; 

        
    }
}
$acessos_cont = array_count_values($acessos);
arsort($acessos_cont);
//print_r($acessos_cont);

//foreach ($acessos_cont as $chave => $conteudo) {
foreach (array_slice($acessos_cont, 0, 30) as $chave => $conteudo) {    
   $tudo = "['$chave',$conteudo],";
   //echo "<br/>";  
    $tudo2 = $tudo2 . $tudo;
        
  }

//echo $tudo2;
//$array = array_unique($array);

//print_r($conta_valores);
//echo "<br>";
//print_r($acessos);
//inicio da busca por ocorrencia contendo o dominio da primeira busca
/*
foreach ($array as $dominiobusca) {
//echo $dominiobusca;
//echo "<br>";
    $queryfiltro2 = "SELECT COUNT(*) Total FROM SystemEvents WHERE `Message` LIKE '%$dominiobusca%' and `Message` LIKE '%$ip%'and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' ";
    $resultfiltro2 = mysql_query($queryfiltro2) or die(mysql_error());
    $row = mysql_fetch_assoc($resultfiltro2);
    //echo "<br>";
     $resultadototalfiltro = $row['Total'];
     //$acessos = ["$dominiobusca" => "$resultadototalfiltro"];

//echo "<br>";
//    echo $queryfiltro2;
//    echo "<br>";
//    echo $dominiobusca;
     
    if ($resultadototalfiltro <= 0) {

        $resultadototalfiltro = 0;
    } else {


        $tudo = "['$dominiobusca',       $resultadototalfiltro],";
        //echo "['$nomefiltrocategoria',       $resultadototalfiltro],";  
        $tudo2 = $tudo2 . $tudo;
    }

}
*/
//print_r($acessos);
//echo $tudo2;
?>
<form action="?pagina=estatistica" method="POST">

    <table border="0" align="center">

        <tbody>
            <tr>
                <td>Usuario:</td>
                <td><select name="ipusuariolog" id="usuariolog">

                        <?php
                        echo '<option value="">Todos</option>';
                        while ($row_rsselecionausuariolog = mysql_fetch_assoc($queryselecionausuariolog)) {

                            echo '<option value="' . $row_rsselecionausuariolog['ipusuariolog'] . '">' . $row_rsselecionausuariolog['usuariolog'] . '</option>';
                        }
                        ?>



                    </select></td>
                <td>Tipo:</td>
                <td><select name="tipografico" id="tipografico">

                        <?php
                        echo '<option value="pizza">Pizza</option>';
                        echo '<option value="funil" selected>Funil</option>';
                        ?>



                    </select></td>
                <td>De:</td>
                <td><input type="date" name="data1" value="<?php echo $data1; ?>"></td>
                <td>Até:</td>
                <td><input type="date" name="data2" value="<?php echo $data2; ?>"></td>
                <td><input type="submit" value="ok" onClick="document.getElementById('pop').style.display = 'block';" /></td>
            </tr>
        </tbody>
    </table>
</form>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/funnel.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 410px; max-width: 600px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'funnel'
    },
    title: {
        text: '<?php echo "Grafico de utilização por Dominio de $usuariolog IP: $ip " ?>'
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y:,.0f})',
                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '20%',
            neckHeight: '0%',
            width: '50%'
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Visitas',
        data: [
            <?php 
                echo $tudo2;
            ?>
        ]
    }]
});

</script>




<html>
    <head>

        <style>
            #pop{background-color:#b0c4de;display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:auto;height:auto;border:1px solid #d0d0d0}

        </style>
    </head>
    <body>
        <!--     <a onClick="document.getElementById('pop').style.display='block';" href="/site/gestorserver/log/?pagina=status">Processar Log</a>-->
        <div id="pop">
            <a href="#" onClick="document.getElementById('pop').style.display = 'none';"></a>
            <br />


            <p class="align-center"><img src="/site/images/progresso.gif" width="20" height="20" alt="progress"/>Aguarde... </p> 
            <p>Processando</p> 	




        </div>