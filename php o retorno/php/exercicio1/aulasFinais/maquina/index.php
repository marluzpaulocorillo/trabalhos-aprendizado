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
        $atual = date("Y");
        $nasc = $_GET['nasc'] ?? '2000';
        $ano = $_GET['ano'] ?? $atual
    ?>
    <main>
        <H1>Calculadora de Idades .</H1>
    
        <form action="$_SERVER['PHP_SELF']" method="get">
            <label for="nasc">Em que ano você nasceu?</label>
            <input type="number" name="nasc" id="nasc" min="1900" max="<?=$atual?>" value="<?= $nasc?>">
            <label for="ano">Quer saber sua idade em que ano? (atualmente estamos em <strong><?=$atual?></strong>)</label>
            <input type ="number" name="ano" id="ano" min="1900" value="<?=$ano?>">
            <input type="submit" value="Qual sera sua idade?">
        </form>          
    </main>
    <section> 
        <?php 
        $idade = $ano - $nasc;
        ?>
        <h2>Resultado final</h2>
        <p>Quem nasceu em <?=$nasc?> vai ter <strong><?=$idade?> anos</strong> em <?=$ano?> ! </p>  
    </section>

<button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>