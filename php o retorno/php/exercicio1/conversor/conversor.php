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

    <?php
        $cotação = 5.17;
        $real = $_REQUEST["din"] ?? 0;
        $dolar = $real / $cotação;
        // formataçao simples 
        //echo "seus R$ $real, reais, convertido para dólar, fica U$ $dolar, dólares.";

        //formmataçao com number_format para formatar apenas com 2 casas decimais
        //echo "seus R$ " . number_format($real, 2,) . " reais, convertido para dólar, fica U$ " . number_format($dolar, 2) . " dólares.";

        //formataçao com number_format para formatar apenas com 2 casas decimais e com separador decimal e de milhar
        //echo "seus R$ " . number_format($real, 2, ',', '.') . " reais, convertido para dólar, fica U$ " . number_format($dolar, 2, ',', '.') . " dólares.";
        $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);

        echo "seus " . numfmt_format_currency($padrao, $real, "BRL") . " reais, convertido para dólar, fica " . numfmt_format_currency($padrao, $dolar, "USD") . " dólares.";
    ?>
    <button type="button" onclick="window.location.href='../../index.php'">Voltar</button>
    </main>
</body>
</html>