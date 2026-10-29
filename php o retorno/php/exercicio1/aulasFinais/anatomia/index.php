<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio: Anatomia de uma divisão</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php
        $dividendo = $_GET['d1'] ?? 0;
        $divisor = $_GET['d2'] ?? 1;
    ?>
    <main>
        <h1>Desafio: Anatomia de uma divisão</h1>
        <form action="" method="get">
            <label for="d1">Dividendo</label>
            <input type="number" name="d1" id="d1" min="0" value="<?=$dividendo?>">
            <label for="d2">Divisor</label>
            <input type="number" name="d2" id="d2" min="01" value="<?=$divisor?>">
            <input type="submit" value="Analizar">
        </form>
    </main>

    <section>
        <H2>Estrutura da divisão</H2>
            <?php
                $quociente = intdiv($dividendo, $divisor);
                $resto = $dividendo % $divisor;
               //echo abaixo apenas para lista 
                // echo "<ul>";
                   // echo "<li>Dividendo: $dividendo</li>";
                    //echo "<li>Divisor: $divisor</li>";
                    //echo "<li>Quociente: $quociente</li>";
                    //echo "<li>Resto: $resto</li>";
                //echo "</ul>";
            ?>

            <table class="divisao">
                <tr>
                    <td><?=$dividendo?></td>
                    <td><?=$divisor?></td>
                </tr> 
                <tr>
                    <td><?=$resto?></td>
                    <td><?=$quociente?></td>
                </tr> 
            </table>
    </section>
        <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>