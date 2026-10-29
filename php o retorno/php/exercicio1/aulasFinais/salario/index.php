<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>desafio 6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <<?php 
        $minimo = 1621.00;
        $salario = $_GET['sal'] ?? 0;
    ?>
    <main>
        <h1>Informe seu salario</h1>
        <form action="<?=$_SERVER['PHP_SELF'] ?>" method="get">
            <label for="sal">Salário (R$)</label>
            <input type="number" name="sal" id="sal" value="<?= $salario ?>" step="0.01">
            <P>considerando o salario minimo de<strong> R$ <?= number_format($minimo, 2, ',', '.') ?></strong></P>
            <input type="submit" value="Calcular">
        </form>
    </main>
    <section>
        <h2>Resultado Final</h2>
        <?php 
            $tot = intdiv($salario, $minimo);
            $dif = $salario % $minimo;
            echo "<p> Ganha $tot salários mínimos + <strong>R$ " . number_format($dif, 2, ',', '.') . "</strong></p>";
        ?>
    </section>
    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>