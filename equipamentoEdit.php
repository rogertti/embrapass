<?php
    require_once('appConfig.php');

    try {
        include_once('appConnection.php');
        
        $py = md5('idequipamento');
        $sql = $pdo->prepare("SELECT idequipamento,descricao,anotacao FROM equipamento WHERE idequipamento = :idequipamento");
        $sql->bindParam(':idequipamento', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
                $lin->anotacao = str_replace('&nbsp;','',$lin->anotacao);
?>
<form class="form-edit-equipamento">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Editar os dados da equipamento</h4>
    </div><!-- /.modal-header -->
    <div class="modal-body overing">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="idequipamentoedit" id="idequipamentoedit" value="<?php echo $lin->idequipamento; ?>">
                
                <div class="form-group">
                    <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
                    <input type="text" name="descricaoedit" id="descricaoedit" class="form-control" value="<?php echo $lin->descricao; ?>" maxlength="150" title="Informe a descri&ccedil;&atilde;o do equipamento" placeholder="Descri&ccedil;&atilde;o do equipamento" required>
                </div>
                <div class="form-group">
                    <label for="nome">Anota&ccedil;&atilde;o</label>
                    <textarea name="anotacaoedit2" id="anotacaoedit2" class="form-control" rows="6" title="Anota&ccedil;&otilde;es gerais" placeholder="Anota&ccedil;&otilde;es gerais"><?php echo $lin->anotacao; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="equipamento">Cadastrar outro Equipamento?</label>
                    <div class="input-group">
                        <span class="form-icheck"><input type="radio" name="modalequipamentoedit2" value="T"> Sim</span>
                        <span class="form-icheck"><input type="radio" name="modalequipamentoedit2" value="F" checked> N&atilde;o</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-edit-equipamento">Salvar</button>
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
        
        /* CKEDITOR */
        
        var initEditorAnotacaoEdit2 = (function () {
            function isWysiwygareaAvailable() {
                if (CKEDITOR.revision === ('%RE' + 'V%')) {
                    return true;
                }

                return !!CKEDITOR.plugins.get('wysiwygarea');
            }

            var wysiwygareaAvailable = isWysiwygareaAvailable(), isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

            return function () {
                var editorElement = CKEDITOR.document.getById('anotacaoedit2');

                if (isBBCodeBuiltIn) {
                    editorElement.setHtml(
                        'Hello world!\n\n' + 'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
                    );
                }

                if (wysiwygareaAvailable) {
                    CKEDITOR.replace('anotacaoedit2');
                } else {
                    editorElement.setAttribute('contenteditable', 'true');
                    CKEDITOR.inline('anotacaoedit2');
                }
            };
        }());
        
        initEditorAnotacaoEdit2();
        
        //editar equipamento

        $(".form-edit-equipamento").submit(function(e){
            e.preventDefault();

            var anotacao = CKEDITOR.instances.anotacaoedit2.getData();

            $.post("equipamentoUpdate.php", { idequipamento: $("#idequipamentoedit").val(), descricao: $("#descricaoedit").val(), anotacao: anotacao, modalequipamento: $("input[name='modalequipamentoedit2']:checked").val(), rand: Math.random()}, function (data) {
                $(".btn-edit-equipamento").html('<img src="img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                if(data == 'reload'){
                    $.smkAlert({text: 'Nem todos os plugins foram carregados, recarregando...', type: 'danger', time: 2});
                    location.reload();
                }else if(data == 'true'){
                    $.smkAlert({text: 'Dados da equipamento editados com sucesso.', type: 'success', time: 2});
                    window.setTimeout("location.href='inicio'", delay);
                }else if(data.match(/<url>/g)){
                    $.smkAlert({text: 'Dados da equipamento editados com sucesso.', type: 'success', time: 2});
                    data = data.replace("<url>","");
                    data = data.replace("</url>","");
                    $('#modal-edit-equipamento').modal('toggle');
                    $('#modal-new-equipamento').modal('show').find('.modal-content').load(data);
                }else{
                    $.smkAlert({text: data, type: 'warning', time: 3});
                }

                $(".btn-edit-equipamento").html('Salvar').fadeTo(fade, 1);
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

        unset($sql,$ret,$py,$py2);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }

    unset($pdo,$e,$cfg);
?>