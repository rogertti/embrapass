<?php
    require_once('appConfig.php');

    try {
        include_once('appConnection.php');

        $py = md5('idequipamento');
        $monitor = 'F';
        $sql = $pdo->prepare("UPDATE equipamento SET monitor = :monitor WHERE idequipamento = :idequipamento");
        $sql->bindParam(':monitor', $monitor, PDO::PARAM_STR);
        $sql->bindParam(':idequipamento', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();
        
            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            } else {
                header('location:inicio');
            }

        unset($pdo,$sql,$res,$status,$py);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }   
?>