<?php
    require_once('appConfig.php');

    //controle de variável
    $msg = "Campo obrigat&oacute;rio vazio.";

        //depurando os campos
        if(empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if(empty($_POST['descricao'])) { die($msg); } else {
            $filtro = 1;
            $_POST['descricao'] = str_replace("'","&#39;",$_POST['descricao']);
            $_POST['descricao'] = str_replace('"','&#34;',$_POST['descricao']);
            $_POST['descricao'] = str_replace('%','&#37;',$_POST['descricao']);
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
                $descricao = strtolower($_POST['descricao']);
                $sql = $pdo->prepare("SELECT idequipamento,monitor FROM equipamento WHERE descricao = :descricao AND empresa_idempresa = :idempresa");
                $sql->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                $sql->bindParam(':idempresa', $_POST['idempresa'], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);
                        $py = md5('idequipamento');
                        
                        if($lin->monitor == 'T') {
                            die('Esse equipamento j&aacute; est&aacute; cadastrado.');    
                        }
                        
                        if($lin->monitor == 'F') {
                            die('Esse equipamento j&aacute; est&aacute; cadastrado, mas est&aacute; desativado. <a href="equipamentoActivate.php?'.$py.'='.$lin->idequipamento.'" title="Ativar equipamento">Clique para ativar.</a>');    
                        }
                    }

                unset($sql,$ret,$lin,$py,$descricao);
                
                //insere no banco
                $monitor = 'T';
                $sql = $pdo->prepare("INSERT INTO equipamento (empresa_idempresa,descricao,anotacao,monitor) VALUES (:idempresa,:descricao,:anotacao,:monitor)");
                $sql->bindParam(':idempresa', $_POST['idempresa'], PDO::PARAM_INT);
                $sql->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR);
                $sql->bindParam(':anotacao', $_POST['anotacao'], PDO::PARAM_STR);
                $sql->bindParam(':monitor', $monitor, PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    } else {
                        //verifica se deve abrir o cadastro do agregado
                        if($_POST['modalequipamento'] == 'T'){
                            $py = md5('idempresa');
                            echo'<url>equipamentoNew.php?'.$py.'='.$_POST['idempresa'].'</url>';
                        } else {
                            echo'true';
                        }
                    }

                unset($pdo,$sql,$res,$idequipamento,$py,$monitor);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } else {
            die('Algum campo n&atilde;o foi validado.');
        }

    unset($msg,$key,$filtro,$cfg,$e);
?>