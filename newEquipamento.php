<?php
    $py = md5('idempresa');
?>
<form class="form-new-equipamento">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Novo equipamento</h4>
    </div><!-- /.modal-header -->
    <div class="modal-body overing">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_GET[''.$py.'']; ?>">
                
                <div class="form-group">
                    <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
                    <input type="text" name="descricao" id="descricao" class="form-control" maxlength="100" title="Informe a descri&ccedil;&atilde;o do equipamento" placeholder="Descri&ccedil;&atilde;o do equipamento" required>
                </div>
                <div class="form-group">
                    <label for="nome">Anota&ccedil;&atilde;o</label>
                    <textarea name="anotacao2" id="anotacao2" class="form-control" rows="6" title="Anota&ccedil;&otilde;es gerais" placeholder="Anota&ccedil;&otilde;es gerais"></textarea>
                </div>
                <div class="form-group">
                    <label for="equipamento">Cadastrar outro Equipamento?</label>
                    <div class="input-group">
                        <span class="form-icheck"><input type="radio" name="modalequipamento2" value="T" checked> Sim</span>
                        <span class="form-icheck"><input type="radio" name="modalequipamento2" value="F"> N&atilde;o</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-new-equipamento">Salvar</button>
    </div>
</form>
<script>
    (function($){
        var fade = 150, delay = 300;

        /* MODAL */
        
        $('#modal-new-equipamento').on('hidden.bs.modal', function() {
            location.reload();
        });
        
        /* ICHECK */

        $("input[type='checkbox'], input[type='radio']").show(function () {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });
        
        /* CKEDITOR */
        
        var initEditorAnotacaoEq = (function () {
            function isWysiwygareaAvailable() {
                if (CKEDITOR.revision === ('%RE' + 'V%')) {
                    return true;
                }

                return !!CKEDITOR.plugins.get('wysiwygarea');
            }

            var wysiwygareaAvailable = isWysiwygareaAvailable(), isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

            return function () {
                var editorElement = CKEDITOR.document.getById('anotacao2');

                if (isBBCodeBuiltIn) {
                    editorElement.setHtml(
                        'Hello world!\n\n' + 'I\'m an instance of [url=http://ckeditor.com]CKEditor[/url].'
                    );
                }

                if (wysiwygareaAvailable) {
                    CKEDITOR.replace('anotacao2');
                } else {
                    editorElement.setAttribute('contenteditable', 'true');
                    CKEDITOR.inline('anotacao2');
                }
            };
        }());
        
        initEditorAnotacaoEq();
        
        //novo equipamento
        
        $(".form-new-equipamento").submit(function(e){
            e.preventDefault();

            var anotacao = CKEDITOR.instances.anotacao2.getData();

            $.post("equipamentoInsert.php", { idempresa: $("#idempresa").val(), descricao: $("#descricao").val(), anotacao: anotacao, modalequipamento: $("input[name='modalequipamento2']:checked").val(), rand: Math.random()}, function (data) {
                $(".btn-new-equipamento").html('<img src="img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                if(data == 'reload'){
                    $.smkAlert({text: 'Nem todos os plugins foram carregados, recarregando...', type: 'danger', time: 2});
                    location.reload();
                }else if(data == 'true'){
                    $.smkAlert({text: 'Equipamento cadastrado com sucesso.', type: 'success', time: 2});
                    window.setTimeout("location.href='inicio'", delay);
                }else if(data.match(/<url>/g)){
                    $.smkAlert({text: 'Equipamento cadastrado com sucesso.', type: 'success', time: 2});
                    data = data.replace("<url>","");
                    data = data.replace("</url>","");
                    //$('#modal-new-equipamento').modal('toggle');
                    $('#modal-new-equipamento').modal('show').find('.modal-content').load(data);
                }else{
                    $.smkAlert({text: data, type: 'warning', time: 3});
                }

                $(".btn-new-equipamento").html('Salvar').fadeTo(fade, 1);
            });

            return false;
        });
    })(jQuery);
</script>