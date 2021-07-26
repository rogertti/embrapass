<?php
    require_once('appConfig.php');

    try {
        include_once('appConnection.php');

        $py = md5('idusuario');
        $monitor = 'F';
        $sql = $pdo->prepare("UPDATE login SET monitor = :monitor WHERE idlogin = :idlogin");
        $sql->bindParam(':monitor', $monitor, PDO::PARAM_STR);
        $sql->bindParam(':idlogin', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();
        
            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            } else {
                header('location:usuario');
            }

        unset($pdo,$sql,$res,$status,$py);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }   
?>