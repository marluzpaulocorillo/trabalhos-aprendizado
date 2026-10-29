<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>formularios retro alimentaveis</title>
    <link css rel="stylesheet" href="style.css">
</head>
<body>
    </<?php 
    // capturando os dados retro alimentados 
    $valor1 = $_GET['V1'] ?? 0;
    $valor2 = $_GET['V2'] ?? 0;
    
    ?>
    <main>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
            <label for="V1">Valor 1</label>
            <input type="number" name="V1" id="V1" value="<?= htmlspecialchars($valor1, ENT_QUOTES, 'UTF-8') ?>">
            <label for="V2">Valor 2</label>
            <input type="number" name="V2" id="V2" value="<?=$valor2?>">
            <input type="submit" value="Somar">
        </form>        
    </main>

    <section id="resultado">
        <h2>Resultado da Soma </h2>
        <?php 
            $soma = $valor1 + $valor2;
            echo "<p>A soma entre o  $valor1 e $valor2 <strong>é igual a  $soma</strong></p>";
        ?>
    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>