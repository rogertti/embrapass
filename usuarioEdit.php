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

    $key = base64_encode('cripta');

    try {
        include_once('appConnection.php');
        
        $py = md5('idusuario');
        $sql = $pdo->prepare("SELECT idlogin,nome,usuario,senha,email,permissao FROM login WHERE idlogin = :idlogin");
        $sql->bindParam(':idlogin', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-usuario">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Editar usu&aacute;rio</h4>
    </div><!-- /.modal-header -->
    <div class="modal-body overing">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="idusuario" id="idusuario" value="<?php echo $lin->idlogin; ?>">
                
                <div class="form-group">
                    <label class="text text-danger" for="nome2">Nome</label>
                    <input type="text" name="nome2" id="nome2" class="form-control" value="<?php echo $lin->nome; ?>" maxlength="150" title="Informe o nome do usu&aacute;rio" placeholder="Nome do usu&aacute;rio" required>
                </div>
                <div class="form-group">
                    <label class="text text-danger" for="usuario2">Usu&aacute;rio</label>
                    <div class="input-group col-md-4">
                        <input type="text" name="usuario2" id="usuario2" class="form-control" value="<?php echo base64_decode(decrypt($lin->usuario, $key)); ?>" maxlength="10" title="Crie o usu&aacute;rio para acessar o programa" placeholder="Usu&aacute;rio para login" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="text text-danger" for="senha2">Senha</label>
                    <div class="input-group col-md-4">
                        <input type="password" name="senha2" id="senha2" class="form-control" value="<?php echo base64_decode(decrypt($lin->senha, $key)); ?>" maxlength="10" title="Crie a senha para acessar o programa" placeholder="Senha para login" required>
                        <span class="input-group-addon">
                            <a class="a-show-password2" href="#"><i class="glyphicon glyphicon-eye-open"></i></a>
                            <a class="a-hide-password2 hide" href="#"><i class="glyphicon glyphicon-eye-close"></i></a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="text text-danger" for="email2">Email</label>
                    <input type="email" name="email2" id="email2" class="form-control" value="<?php echo $lin->email; ?>" maxlength="100" title="Digite um email para recupera&ccedil;&atilde;o" placeholder="Email para recupera&ccedil;&atilde;o" required>
                </div>
                <div class="form-group">
                    <label for="permissao2">Permiss&atilde;o</label>
                    <div class="input-group">
                    <?php
                        if($lin->permissao == 'A'){
                            echo'
                            <span class="form-icheck"><input type="radio" name="permissao2" value="A" checked> Administrador</span>
                            <span class="form-icheck"><input type="radio" name="permissao2" value="B"> Usu&aacute;rio</span>';
                        }
                
                        if($lin->permissao == 'B'){
                            echo'
                            <span class="form-icheck"><input type="radio" name="permissao2" value="A"> Administrador</span>
                            <span class="form-icheck"><input type="radio" name="permissao2" value="B" checked> Usu&aacute;rio</span>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-edit-usuario">Salvar</button>
    </div>
</form>
<script>
    (function ($) {
        var fade = 150, delay = 300;
        
        /* ICHECK */

        $("input[type='checkbox'], input[type='radio']").show(function () {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });
        
        /* SHOW/HIDE PASS */
    
        $("#senha2").show(function () {
            $(".a-show-password2").click(function (e) {
                e.preventDefault();

                $(this).addClass("hide");
                $(".a-hide-password2").removeClass("hide");
                $("#senha2").attr("type", "text");
            });

            $(".a-hide-password2").click(function (e) {
                e.preventDefault();

                $(this).addClass("hide");
                $(".a-show-password2").removeClass("hide");
                $("#senha2").attr("type", "password");
            });
        });
        
        //edita usuário
        
        $(".form-edit-usuario").submit(function (e) {
            e.preventDefault();

            var usuario = btoa($("#usuario2").val()), senha = btoa($("#senha2").val());

            $.post("usuarioUpdate.php", { idusuario: $("#idusuario").val(), nome: $("#nome2").val(), usuario: usuario, senha: senha, email: $("#email2").val(), permissao: $("input[name='permissao2']:checked").val(), rand: Math.random()}, function (data) {
                $(".btn-edit-usuario").html('<img src="img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch (data) {
                case 'true':
                    $.smkAlert({text: 'Dados do usu&aacute;rio editados com sucesso.', type: 'success', time: 1});
                    window.setTimeout("location.href='usuario'", delay);
                    break;

                default:
                    $.smkAlert({text: data, type: 'warning', time: 3});
                    break;
                }

                $(".btn-edit-usuario").html('Salvar').fadeTo(fade, 1);
            });

            return false;
        });
    })(jQuery);
</script>
<?php
            } else {
                echo'
                <div class="callout">
                    <h4>Par&acirc;mentro incorreto</h4>
                </div>';
            }

        unset($sql,$ret,$py,$key);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }

    unset($pdo,$e,$cfg);
?>