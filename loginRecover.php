<?php
    require_once('appConfig.php');

    function decrypt($data, $k) {
        $l = strlen($k);
        
            if ($l < 16)
                $k = str_repeat($k, ceil(16/$l));
                $data = base64_decode($data);
                $val = openssl_decrypt($data, 'AES-256-OFB', $k, 0, $k);
        
        return $val;
    }

    //controle de variável
    $msg = "Campo obrigat&oacute;rio vazio.";
    $key = base64_encode('cripta');

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(!empty($_POST['email'])) {
            try {
                include_once('appConnection.php');

                //buscando a senha na tabela login
                $sql = $pdo->prepare("SELECT idlogin,senha FROM login WHERE email = :email");
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);

                        //enviando a senha por email
                        require_once('phpmailer/PHPMailerAutoload.php');

                        $mail = new PHPMailer;
                        $mail->CharSet = "UTF-8";

                        /*$mail->IsSMTP();
                        $mail->Host = "smtp.smspro.net.br";
                        $mail->SMTPAuth = true;
                        $mail->Username = 'smspro@smspro.net.br';
                        $mail->Password = 'emb3974';
                        #$mail->SMTPSecure = "ssl";
                        $mail->Port = "587";*/

                        $mail->setFrom('lab@embracore.com.br', 'Embrapass - Não responda');
                        $mail->addAddress($_POST['email']);
                        $mail->addReplyTo($_POST['email'], 'Embrapass');
                        $mail->Subject = 'Recupere sua senha de acesso ao programa Embrapass';
                        $mail->IsHTML(true);
                        $mail->Body = 'A sua senha é <strong>'.base64_decode(decrypt($lin->senha, $key)).'</strong>';
                        $sent = $mail->Send();
                        $mail->ClearAllRecipients();
                        $mail->ClearAttachments();

                            if(!$sent) {
                                die('A senha n&atilde;o foi enviada. '.$mail->ErrorInfo);
                            } else {
                                echo'true';
                            }

                        unset($lin,$nome,$remetente,$assunto,$header,$conteudo);
                    } else {
                        echo'Esse email n&atilde;o &eacute; de um usu&aacute;rio cadastrado.';
                    }

                unset($pdo,$sql,$ret);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        }

    unset($msg,$key);
?>