<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Guanabara</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Aula Guanabara</h1>
    <p>Antecessor ,Sucessor</p>

    <?php
        //$r = sqrt(5214);
        //echo "A resposta é = $r";

        $num = 17;

        $ant = $num - 1;
        $suc = $num + 1;

        echo "O antecessor de $num é $ant <br>";

        echo "O sucessor de $num é $suc";

        
    ?>
        <button type="button" onclick="window.location.href='index.php'">Voltar</button>
</body>
</html>