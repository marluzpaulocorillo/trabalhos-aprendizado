<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Números Aleatórios</title>
</head>
<body>
    <main>
        <h1>Trabalhando com números aleatórios</h1>
        <?php
            $min = 0;
            $max = 100;
            $numero = mt_rand($min, $max);
            echo "<p>Gerando um número aleatório entre $min e $max...<br>O valor gerado foi <strong>$numero</strong></p>";
        ?>
        <button onclick="document.location.reload()"><span class="refresh-icon" aria-hidden="true">&#x21bb;</span> Gerar outro</button>
    </main>
            <button type="button" onclick="window.location.href='../../index.php'">Voltar</button>
        
</body>
</html>