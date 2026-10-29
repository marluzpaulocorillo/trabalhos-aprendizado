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
        //area de declaração 
        $numero = $_GET['num'] ?? 01;    
    ?>
    <main>
        <H1>Informe o número</H1>
        <form action="<?= $_SERVER['PHP_SELF']?>" method="get">
            <label for="num">Número</label>
            <input type="number" name="num" id="num" value="<?= $numero ?>">
            <button type="submit">Calcular Raízes</button>
        </form>
    </main>
    <section> 
        <h2>Resultado final</h2>
        <?php 
            
            $raizQuadrada = sqrt($numero);
            $raizCubica =$numero ** (1/3);
            echo "<p>Analizando seu número informado <strong>$numero</strong></p>";
            echo "<p>A raiz quadrada de <strong>$numero</strong> é = <strong>".number_format ($raizQuadrada, 3, ",", ".")."</strong></p>";
            echo "<p>A raiz cúbica de <strong>$numero</strong> é = <strong>".number_format($raizCubica, 3, ",", ".")."</strong></p>";
        ?>

    </section>

    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>