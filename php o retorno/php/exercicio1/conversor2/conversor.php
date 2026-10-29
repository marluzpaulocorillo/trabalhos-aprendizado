<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moedas</title>
    <link rel="stylesheet" href="style.css">    
</head>
<body>
    <main>

    <h1>Conversor de Moedas</h1>
<pre>
    <?php
        $inicio = date("m-d-Y",strtotime("-7 days"));
        $fim = date("m-d-Y");
        $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\''.$inicio.'\'&@dataFinalCotacao=\''.$fim.'\'&$top=1&$orderby=dataHoraCotacao%20desc&$format=json&$select=cotacaoCompra,dataHoraCotacao';

        $dados = json_decode(file_get_contents($url), true);

         //var_dump($dados);

         $cotação = $dados["value"][0]["cotacaoCompra"];

         //echo "Cotação foi $cotação";

        //$cotação = 5.17;
        $real = $_REQUEST["din"] ?? 0;
        $dolar = $real / $cotação;
         //formataçao simples 
       // echo "Seus R$ $real, reais, convertido para dólar,<br> fica U$ $dolar, dólares.";

        //formmataçao com number_format para formatar apenas com 2 casas decimais
        //echo "seus R$ " . number_format($real, 2,) . " reais, convertido para dólar, fica U$ " . number_format($dolar, 2) . " dólares.";

        //formataçao com number_format para formatar apenas com 2 casas decimais e com separador decimal e de milhar
        //echo "seus R$ " . number_format($real, 2, ',', '.') . " reais, convertido para dólar:<br>    Fica U$ " . number_format($dolar, 2, ',', '.') . " dólares.";
        $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);

        echo "seus " . numfmt_format_currency($padrao, $real, "BRL") . " reais, convertido para dólar:<br>    Fica " . numfmt_format_currency($padrao, $dolar, "USD") . " dólares.";
    ?>
</pre>
    <button type="button" onclick="window.location.href='../../index.php'">Voltar</button>
    </main>
</body>
</html>