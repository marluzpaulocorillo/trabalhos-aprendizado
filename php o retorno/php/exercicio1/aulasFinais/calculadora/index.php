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
        $total = $_REQUEST['seg'] ?? 0;
    ?>
    <main>
        <h1>Calculadora de Tempo</h1>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
            <label for="seg">Qual o total de segundos ?</label>
            <input type="number" name="seg" id="seg" min="0" step="1" required value=",?=$total?>">
            <input type="submit" value="Calcular">

        </form>
    </main>

        <?php 
           $sobra = $total;
           $ano = (int)($sobra / 31_536_000);
           $semana = (int)($sobra / 604_800);
           $sobra = $sobra % 604_800;
           $dia =(int)($sobra/86_400);
           $sobra = $sobra % 86_400;
           $hora = (int)($sobra / 3_600);
           $sobra = $sobra % 3_600;
           $minuto = (int)($sobra / 60);
           $sobra = $sobra % 60;
           $segundo = $sobra;
           
        ?>
    <main>
        <section>
        <H1>Calculadora de Tempo !</H1>
        <p>Analizandoo valor que você forneceu, <strong><?=number_format($total, 0,",",".")  ?> segundos </strong> equivalem a um total de : </p>
        <ul>
            <li><?= $ano?> anos</li>
            <li><?= $semana?> semanas</li>
            <li><?= $dia ?> dias</li>
            <li><?= $hora ?> horas</li>
            <li><?= $minuto ?> minutos</li>
            <li><?= $segundo ?> segundos</li>
            
        </ul>
        
        </section>
    </main>
    <section> 
        <h2>UFAAA QUANTA CONTAS EM !!!! </h2>
       
    </section>

<button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>