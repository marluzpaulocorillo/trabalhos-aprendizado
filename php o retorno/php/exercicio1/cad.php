<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Resultado do processamento</h1>
    </header>
   <main>
        <?php
        //php -S localhost:8000 (   para iniciar meu server)
            $n = $_GET["nome"] ?? "Nome não informado";
            $s = $_GET["sobrenome"] ?? "Sobrenome não informado";
            echo "<p>É um prazer em conhecer você, <strong>$n $s</strong>  ! <strong> ÒÒÒÒ GRANDE SER 🍆!!</strong></p>";
        ?>
        <p><a href="javascript:history.go(-1)">Voltar ao formulário</a></p>
        
   </main>
</body>
</html>