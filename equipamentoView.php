<?php
    require_once('appConfig.php');

    try {
        include_once('appConnection.php');
        
        $py = md5('idequipamento');
        $sql = $pdo->prepare("SELECT anotacao FROM equipamento WHERE idequipamento = :idequipamento");
        $sql->bindParam(':idequipamento', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
                $lin->anotacao = str_replace('&nbsp;','',$lin->anotacao);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Anota&ccedil;&otilde;es</h4>
</div><!-- /.modal-header -->
<div class="modal-body overing">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            <?php echo htmlspecialchars_decode($lin->anotacao); ?>    
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>
</div>
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