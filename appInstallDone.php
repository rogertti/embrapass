<?php
    require_once('appConfig.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Embrapass</title>
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/icheck.min.css">
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/core.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><strong>Instala&ccedil;&atilde;o</strong></a>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Crie um usu&aacute;rio para entrar no sistema.</p>

                <form class="form-install">
                    <div class="form-group has-feedback">
                        <input type="text" id="nome" name="nome" maxlength="150" class="form-control" placeholder="Nome" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" id="usuario" name="usuario" maxlength="10" class="form-control" placeholder="Usu&aacute;rio" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="senha" name="senha" maxlength="10" class="form-control" placeholder="Senha" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" id="email" name="email" maxlength="100" class="form-control" placeholder="Email" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <span class="form-icheck"><input type="radio" name="permissao" value="A"> Administrador</span>
                            <span class="form-icheck"><input type="radio" name="permissao" value="B" checked> Usu&aacute;rio</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-8 col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat btn-install">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <script src="js/jquery-2.2.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="js/smoke.min.js"></script>
        <script>
            (function ($) {
                var fade = 150, delay = 300;
                
                //icheck

                $("input[type='checkbox'], input[type='radio']").show(function () {
                    $("input[type='checkbox'], input[type='radio']").iCheck({
                        checkboxClass: 'icheckbox_minimal',
                        radioClass: 'iradio_minimal'
                    });
                });
                
                //smoke
                
                $("#senha").smkShowPass();
                
                //install

                $(".form-install").submit(function (e) {
                    e.preventDefault();
                    
                    var usuario = btoa($("#usuario").val()), senha = btoa($("#senha").val());

                    $.post("appInstallInsert.php", { nome: $("#nome").val(), usuario: usuario, senha: senha, email: $("#email").val(), permissao: $("input[name='permissao']:checked").val(), rand: Math.random()}, function (data) {
                        $(".btn-install").html('<img src="img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                        switch (data) {
                        case 'true':
                            $.smkAlert({text: 'Usu&aacute;rio criado com sucesso.', type: 'success', time: 1});
                            window.setTimeout("location.href='index'", delay);
                            break;

                        default:
                            $.smkAlert({text: data, type: 'warning', time: 3});
                            break;
                        }

                        $(".btn-install").html('Salvar').fadeTo(fade, 1);
                    });

                    return false;
                });
            })(jQuery);
        </script>
    </body>
</html>