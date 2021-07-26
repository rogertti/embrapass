<?php
    require_once('appConfig.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 1;
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
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/icheck.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.responsive.bootstrap.min.css">
        <link rel="stylesheet" href="css/core.css">
        <link rel="stylesheet" href="css/skin-black.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition skin-black sidebar-mini sidebar-collapse">
        <!-- Site wrapper -->
        <div class="wrapper">
            <?php
                include_once('appHeader.php');
                include_once('appSidebar.php');
            ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>In&iacute;cio <span class="pull-right lead"><a data-toggle="modal" data-target="#modal-new-empresa" title="Clique para cadastrar uma nova empresa" href="#"><i class="fa fa-users"></i> Nova empresa</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            try {
                                include_once('appConnection.php');
                                
                                //buscando as empresas
                                $monitor = 'T';
                                $sql = $pdo->prepare("SELECT idempresa,nome,anotacao FROM empresa WHERE monitor = :monitor ORDER BY nome");
                                $sql->bindParam(':monitor', $monitor, PDO::PARAM_STR);
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $py = md5('idempresa');
                                        
                                        echo'
                                        <table class="table table-striped table-bordered table-hover table-data dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;"></th>
                                                    <th style="width: 250px;">Nome</th>
                                                    <th>Equipamento</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                        
                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                //buscando os equipamentos de cada empresa cadastrada
                                                $sql2 = $pdo->prepare("SELECT equipamento.idequipamento,equipamento.descricao,equipamento.anotacao FROM equipamento,empresa WHERE equipamento.monitor = :monitor AND equipamento.empresa_idempresa = empresa.idempresa AND empresa.idempresa = :idempresa ORDER BY equipamento.descricao");
                                                $sql2->bindParam(':monitor', $monitor, PDO::PARAM_STR);
                                                $sql2->bindParam(':idempresa', $lin->idempresa, PDO::PARAM_INT);
                                                $sql2->execute();
                                                $ret2 = $sql2->rowCount();
                                                
                                                    if($ret2 > 0) {
                                                        $equipamento = '';
                                                        $py2 = md5('idequipamento');
                                                        
                                                            while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                                                if($_SESSION['key'] == 'A'){
                                                                    $delete = '
                                                                    <span class="badge">
                                                                        <a class="text-white a-delete-equipamento" id="'.$py2.'-'.$lin2->idequipamento.'" href="#" title="Excluir o equipamento"><i class="fa fa-trash-o"></i></a>
                                                                    </span>'; 
                                                                }else{
                                                                    $delete = '';
                                                                }
                                                                
                                                                $equipamento .= '
                                                                <span class="label label-default" style="margin-right: 3px;">
                                                                    <a data-toggle="modal" data-target="#modal-view-equipamento" title="Visualizar as anota&ccedil;&otilde;es do equipamento" href="equipamentoView.php?'.$py2.'='.$lin2->idequipamento.'">'.strtoupper($lin2->descricao).'</a>
                                                                    '.$delete.'
                                                                    <span class="badge">
                                                                        <a class="text-white" data-toggle="modal" data-target="#modal-edit-equipamento" href="equipamentoEdit.php?'.$py2.'='.$lin2->idequipamento.'" title="Editar os dados do equipamento"><i class="fa fa-pencil"></i></a>
                                                                    </span>
                                                                </span>';
                                                                
                                                                unset($delete);
                                                            }
                                                    }
                                                
                                                if($_SESSION['key'] == 'A'){
                                                    $delete = '<span class="label label-danger"><a class="text-white a-delete-empresa" id="'.$py.'-'.$lin->idempresa.'" title="Excluir a empresa" href="#"><i class="fa fa-trash-o"></i></a></span>';
                                                }else{
                                                    $delete = '';
                                                }
                                                
                                                if(!empty($lin->anotacao)){
                                                    $view = '<span class="label label-primary"><a class="text-white" data-toggle="modal" data-target="#modal-view-empresa" title="Visualizar as anota&ccedil;&otilde;es da empresa" href="empresaView.php?'.$py.'='.$lin->idempresa.'"><i class="fa fa-folder"></i></a></span>';
                                                }else{
                                                    $view = '';
                                                }
                                                
                                                echo'
                                                <tr>
                                                    <td style="text-align: center;">
                                                        '.$delete.'
                                                        <span class="label label-warning"><a class="text-white" data-toggle="modal" data-target="#modal-edit-empresa" title="Editar os dados da empresa" href="empresaEdit.php?'.$py.'='.$lin->idempresa.'"><i class="fa fa-pencil"></i></a></span>
                                                        <span class="label label-info"><a class="text-white" data-toggle="modal" data-target="#modal-new-equipamento" title="Cadastrar um novo equipamento" href="equipamentoNew.php?'.$py.'='.$lin->idempresa.'"><i class="fa fa-desktop"></i></a></span>
                                                        '.$view.'
                                                    </td>
                                                    <td>'.$lin->nome.'</td>
                                                    <td>'.$equipamento.'</td>
                                                </tr>';
                                                
                                                unset($view,$sql2,$ret2,$lin2,$py2,$delete,$equipamento);
                                            }
                                        
                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Nome</th>
                                                    <th>Equipamento</th>
                                                </tr>
                                            </tfoot>
                                        </table>';
                                        
                                        unset($py,$lin,$monitor);
                                    } else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado.</h4>
                                            <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#modal-new-empresa" title="Clique para cadastrar um nova empresa" href="#">Nova empresa</a></p>
                                        </div>';
                                    }
                            }
                            catch(PDOException $e) {
                                echo'Erro ao conectar o servidor '.$e->getMessage();
                            }
                        ?>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            
            <div class="modal fade" id="modal-new-empresa" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-new-empresa">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Nova empresa</h4>
                            </div><!-- /.modal-header -->
                            <div class="modal-body overing">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="text text-danger" for="nome">Nome</label>
                                            <input type="text" name="nome" id="nome" class="form-control" maxlength="150" title="Informe o nome da empresa" placeholder="Nome da empresa" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nome">Anota&ccedil;&atilde;o</label>
                                            <textarea name="anotacao" id="anotacao" class="form-control" rows="6" title="Anota&ccedil;&otilde;es gerais" placeholder="Anota&ccedil;&otilde;es gerais"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="equipamento">Cadastrar Equipamento?</label>
                                            <div class="input-group">
                                                <span class="form-icheck"><input type="radio" name="modalequipamento" value="T" checked> Sim</span>
                                                <span class="form-icheck"><input type="radio" name="modalequipamento" value="F"> N&atilde;o</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-new-usuario">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modal-edit-empresa" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>
            
            <div class="modal fade" id="modal-view-empresa" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>
            
            <div class="modal fade" id="modal-new-equipamento" role="dialog" aria-hidden="true" style="overflow: auto !important;">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>
            
            <div class="modal fade" id="modal-view-equipamento" role="dialog" aria-hidden="true" style="z-index: 10000;">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>
            
            <div class="modal fade" id="modal-edit-equipamento" role="dialog" aria-hidden="true" style="z-index: 10000;">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>
        </div>
        <!-- ./wrapper -->

        <script src="js/jquery-2.2.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.slimscroll.min.js"></script>
        <script src="js/fastclick.min.js"></script>
        <script src="js/smoke.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="ckeditor/ckeditor.js"></script>
        <script src="js/ckeditor.init.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/dataTables.responsive.min.js"></script>
        <script src="js/dataTables.responsive.bootstrap.min.js"></script>
        <script src="js/core.js"></script>
        <script>
            (function ($) {
                var fade = 150, delay = 300;
                
                //nova empresa
        
                $(".form-new-empresa").submit(function(e){
                    e.preventDefault();

                    var anotacao = CKEDITOR.instances.anotacao.getData();
                    
                    $.post("empresaInsert.php", { nome: $("#nome").val(), anotacao: anotacao, modalequipamento: $("input[name='modalequipamento']:checked").val(), rand: Math.random()}, function (data) {
                        $(".btn-new-empresa").html('<img src="img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                        if(data == 'reload'){
                            $.smkAlert({text: 'Nem todos os plugins foram carregados, recarregando...', type: 'danger', time: 2});
                            location.reload();
                        }else if(data == 'true'){
                            $.smkAlert({text: 'Empresa cadastrada com sucesso.', type: 'success', time: 2});
                            window.setTimeout("location.href='inicio'", delay);
                        }else if(data.match(/<url>/g)){
                            $.smkAlert({text: 'Empresa cadastrada com sucesso.', type: 'success', time: 2});
                            data = data.replace("<url>","");
                            data = data.replace("</url>","");
                            $('#modal-new-empresa').modal('toggle');
                            $('#modal-new-equipamento').modal('show').find('.modal-content').load(data);
                        }else{
                            $.smkAlert({text: data, type: 'warning', time: 3});
                        }

                        $(".btn-new-empresa").html('Salvar').fadeTo(fade, 1);
                    });

                    return false;
                });

                //excluir empresa

                $(".table-data").on('click', '.a-delete-empresa', function(e){
                    e.preventDefault();

                    var click = this.id.split('-'),
                        py = click[0],
                        id = click[1];

                    $.smkConfirm({
                        text: 'Quer mesmo excluir a empresa?',
                        accept: 'Sim',
                        cancel: 'Não'
                    }, function (res) {
                        if (res) {
                            location.href = 'empresaDelete.php?' + py + '=' + id;
                        }
                    });
                });
                
                //excluir equipamento

                $(".table-data").on('click', '.a-delete-equipamento', function(e){
                    e.preventDefault();

                    var click = this.id.split('-'),
                        py = click[0],
                        id = click[1];

                    $.smkConfirm({
                        text: 'Quer mesmo excluir o equipamento?',
                        accept: 'Sim',
                        cancel: 'Não'
                    }, function (res) {
                        if (res) {
                            location.href = 'equipamentoDelete.php?' + py + '=' + id;
                        }
                    });
                });
            })(jQuery);
        </script>
    </body>
</html>