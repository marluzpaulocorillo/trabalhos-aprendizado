<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>desafio 6</title>
    <link rel="stylesheet" href="style.css">
    <style>
        img.nota{
            height:30px;
        }
    </style>
</head>
<body>
    <?php 
        $saque = $_REQUEST['saque'] ?? 0;
        

    ?>
    <main>
        <H1>Caixa Eletronico </H1>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
            <label for="saque">Qual valor você deseja sacar? (R$) <sup>*</sup></label>
            <input type="number" name="saque" id="saque" step="1" require value="<?= $saque ?>">
            <p style="font-size: 0.6em"><sup>*</sup> Notas disponiveis: R$100, R$50, R$20, R$10, R$5, R$2 e R$1 </p>
             <input type="submit" value="Sacar">
        </form>
            
    </main>

    <?php 
        $resto = $saque;
        $tot100 = (int)($resto / 100);
        $resto = $resto % 100;
        $tot50 = (int)($resto / 50);
        $resto = $resto % 50;
        $tot20 = (int)($resto / 20);
        $resto = $resto % 20;
        $tot10 = (int)($resto / 10);
        $resto = $resto % 10;
        $tot5 = (int)($resto / 5);
        $resto = $resto % 5;
        $tot2 = (int)($resto / 2);
        $resto = $resto % 2;
        $tot1 = (int)($resto / 1);
        $resto = $resto % 1;
    ?>
    <section> 
        <h2>saque de R$<?= number_format($saque, 2, ",",".") ?> realizado</h2>
        <p>Nosso caixa eletronico vai entregar as seguintes notas: </p>
        <ul>
            <li><img src="imagens/100-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot100 ?></li>
            <li><img src="imagens/50-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot50 ?></li>
            <li><img src="imagens/20-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot20 ?></li>
            <li><img src="imagens/10-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot10 ?></li>
            <li><img src="imagens/5-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot5 ?></li>
            <li><img src="imagens/2-reais.jpg" alt="nota de 100" class="nota"> X <?= $tot2 ?></li>
            <li><img src="imagens/1-real.jpg" alt="nota de 100" class="nota"> X <?= $tot1 ?></li>
        </ul>
       
    </section>

<button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>