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
         //$preço = 1000;
        //$porc = 20;
       // $formula = $preço * $porc / 100;
        //echo " $porc% de $preço é igual a $formula;"
        $preco = $_REQUEST['preco'] ?? '0';
        $reaj = $_REQUEST['reaj'] ?? '0';


    ?>
    <main>
        <H1>Reajuste de preços .</H1>
        <form action="<?= $_SERVER['PHP_SELF']?>" method="get">
            <label for="preco">Preço do produto (R$)</label>
            <input type="number" name="preco" id="preco" min="0.10" step="0.01" value="<?=$preco?>">

            <label for="reaj">qual será o percentual do reajuste? (<strong><span id="p">?</span>%</strong>)</label>

            <input type="range" name="reaj" id="reaj" min="0" max="100" step="1" oninput="mudaValor()" value="<?=$reaj?>">
            
            <input type="submit" value="REAJUSTAR">

        </form>
    
             
        
    </main>
    <?php  
        $aulmento = $preco * $reaj / 100;
        $novo = $preco + $aulmento;
    ?>
    <section> 
        <h2>Resultado do reajuste</h2>
        <p>O produto que custa R$<?=number_format($preco, 2,",", ".")?>, com <strong><?=$reaj?>% de almento</strong> vai passar a custar <strong>R$<?=number_format($novo, 2,",", ".")?> </strong> a partir de agora .</p>

       
    </section>
    <script>
        mudaValor()
        function mudaValor(){
            p.innerText = reaj.value; 

        }

    </script>
<button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>