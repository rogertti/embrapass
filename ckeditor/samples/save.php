<?php
    $dados = "";
    $dados .= $_POST['editor'].PHP_EOL;
    $dados .= " ".PHP_EOL;

    $arquivo = fopen("arquivo.txt", "a");
    fwrite($arquivo, $dados);
    fclose($arquivo);
    echo'true';
    unset($arquivo,$dados);
?>
