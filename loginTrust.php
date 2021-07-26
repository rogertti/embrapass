<?php
    require_once('appConfig.php');

    //encrypt by openssl
    function encrypt($data, $k) {
        $l = strlen($k);
        
            if ($l < 16)
                $k = str_repeat($k, ceil(16/$l));

            if ($m = strlen($data)%8)
                $data .= str_repeat("\x00",  8 - $m);
                $val = openssl_encrypt($data, 'AES-256-OFB', $k, 0, $k);
                $val = base64_encode($val);

        return $val;
    }

    //controle de variável
    $msg = "Campo obrigat&oacute;rio vazio.";
    $lock = base64_encode('cripta');

        //depurando os campos
        if(empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if(empty($_POST['usuario'])) { die($msg); } else {
            $filtro = 1;
            $usuario = base64_decode($_POST['usuario']);
            $usuario = encrypt($_POST['usuario'], $lock);
        }
        if(empty($_POST['senha'])) { die($msg); } else {
            $filtro++;
            $senha = base64_decode($_POST['senha']);
            $senha = encrypt($_POST['senha'], $lock);
        }

        if($filtro == 2) {
            try {
                include_once('appConnection.php');

                //validando o login na tabela login
                $sql = $pdo->prepare("SELECT idlogin,nome,permissao FROM login WHERE usuario = :usuario AND senha = :senha");
                $sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $sql->bindParam(':senha', $senha, PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);
                        
                            if($lin->monitor == 'F') {
                                die('Usu&aacute;rio desativado no sistema.');
                            } else {
                                $_SESSION['id'] = $lin->idlogin;
                                $_SESSION['name_user'] = $lin->nome;
                                $_SESSION['key'] = $lin->permissao;
                                echo'true';
                            }

                        unset($lin);
                    } else {
                        //verifica se a tabela está vazia
                        $sql2 = $pdo->prepare("SELECT idlogin FROM login");
                        $sql2->execute();
                        $ret2 = $sql2->rowCount();
                        
                            if($ret2 == 0) {
                                rename('appInstallDone.php','appInstall.php');
                                echo'reload';
                            } else {
                                echo'Login inv&aacute;lido.';
                            }
                        
                        unset($sql2,$ret2);
                    }

                unset($pdo,$sql,$ret);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$lock,$filtro,$cfg,$e);
?>