<?php
    require_once('appConfig.php');

    //controle de variável
    $msg = "Campo obrigat&oacute;rio vazio.";

        //depurando os campos
        if(empty($_POST['idempresa'])) { die('reload'); }
        if(empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if(empty($_POST['nome'])) { die($msg); } else {
            $filtro = 1;
            $_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            $_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            $_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
        }
        if(!empty($_POST['anotacao'])){
            $_POST['anotacao'] = str_replace("'","&#39;",$_POST['anotacao']);
            $_POST['anotacao'] = str_replace('"','&#34;',$_POST['anotacao']);
            $_POST['anotacao'] = str_replace('%','&#37;',$_POST['anotacao']);
            $_POST['anotacao'] = htmlspecialchars($_POST['anotacao']);
            $_POST['anotacao'] = str_replace('&amp;','&',$_POST['anotacao']);
        }
        
        if($filtro == 1) {
            try {
                include_once('appConnection.php');

                //verificando duplicata
                $nome = strtolower($_POST['nome']);
                $sql = $pdo->prepare("SELECT idempresa,monitor FROM empresa WHERE nome = :nome AND idempresa <> :idempresa");
                $sql->bindParam(':nome', $nome, PDO::PARAM_STR);
                $sql->bindParam(':idempresa', $_POST['idempresa'], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);
                        $py = md5('idempresa');
                        
                        if($lin->monitor == 'T') {
                            die('Essa empresa j&aacute; est&aacute; cadastrada.');    
                        }
                        
                        if($lin->monitor == 'F') {
                            die('Essa empresa j&aacute; est&aacute; cadastrada, mas est&aacute; desativada. <a href="empresaActivate.php?'.$py.'='.$lin->idempresa.'" title="Ativar empresa">Clique para ativar.</a>');    
                        }
                    }

                unset($sql,$ret,$lin,$py,$nome);
                
                //insere no banco
                $sql = $pdo->prepare("UPDATE empresa SET nome = :nome,anotacao = :anotacao WHERE idempresa = :idempresa");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $sql->bindParam(':anotacao', $_POST['anotacao'], PDO::PARAM_STR);
                $sql->bindParam(':idempresa', $_POST['idempresa'], PDO::PARAM_INT);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    } else {
                        //verifica se deve abrir o cadastro do agregado
                        if($_POST['modalequipamento'] == 'T'){
                            $py = md5('idempresa');
                            echo'<url>newEquipamento.php?'.$py.'='.$_POST['idempresa'].'</url>';
                        } else {
                            echo'true';
                        }
                    }

                unset($pdo,$sql,$res,$idempresa,$py,$monitor);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } else {
            die('Algum campo n&atilde;o foi validado.');
        }

    unset($msg,$key,$filtro,$cfg,$e);
?>