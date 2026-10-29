<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>desafio 6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    $valor1 = $_GET['v1'] ?? '';
    $peso1 = $_GET['p1'] ?? '';
    $valor2 = $_GET['v2'] ?? '';
    $peso2 = $_GET['p2'] ?? '';
    $enviado = isset($_GET['v1'], $_GET['v2'], $_GET['p1'], $_GET['p2']);

    if ($enviado) {
        $valor1 = (float) $valor1;
        $valor2 = (float) $valor2;
        $peso1 = (float) $peso1;
        $peso2 = (float) $peso2;

        $ma = ($valor1 + $valor2) / 2;
        $somaPesos = $peso1 + $peso2;

        if ($somaPesos > 0) {
            $mp = ($valor1 * $peso1 + $valor2 * $peso2) / $somaPesos;
        }
    }
    ?>
    <main>
        <H1>Suas Médias Aritméticas:</H1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <label for="v1">1° valor :</label>
            <input type="number" name="v1" id="v1" required value="<?= $valor1 ?>">
            <label for="p1"> 1° peso :</label>
            <input type="number" name="p1" id="p1" required value="<?= $peso1 ?>">
            <label for="v2">2° valor :</label>
            <input type="number" name="v2" id="v2" required value="<?= $valor2 ?>">
            <label for="p2"> 2° peso :</label>
            <input type="number" name="p2" id="p2" required value="<?= $peso2 ?>">
            <input type="submit" value="Calcular Média">
        </form>
        
    </main>
    <?php if ($enviado): ?>
    <section>
        <h2>Cálculos das Médias</h2>
        <p>Analisando os valores <?= $valor1 ?> e <?= $valor2 ?>:</p>
        <ul>
            <li>A <strong>Média Aritmética Simples:</strong> entre os valores é igual a <?= number_format($ma, 2, ",", ".") ?>.</li>
            <?php if ($somaPesos > 0): ?>
                <li>A <strong>Média Aritmética Ponderada:</strong> com pesos <?= $peso1 ?> e <?= $peso2 ?> é igual a <?= number_format($mp, 2, ",", ".") ?>.</li>
            <?php else: ?>
                <li>Não é possível calcular a média ponderada: a soma dos pesos precisa ser maior que zero.</li>
            <?php endif; ?>
        </ul>
    </section>
    <?php endif; ?>

<button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>