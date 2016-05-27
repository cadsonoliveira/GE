<?php
	//Antiga página configuracao_procedimentos.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once ("../classes/classPersistencia.php");
		include_once("../classes/classEspecialidade.php");

        if(isset($_GET['id']))
            $id = $_GET['id'];
        else
            $id=1;
        $especialidade = new Especialidade($id);
        $descricao_Especialidade = $especialidade->getDescricao();

        $combo_tipo = '<label>Tipo:</label><select name="tipo"><option value="TRATAMENTO">TRATAMENTO</option><option value="RETRATAMENTO">RETRATAMENTO</option></select>';
        $combo_tipo2 = '<label>Tipo:</label><select name="tipo"><option value="TRATAMENTO">TRATAMENTO</option><option value="RETRATAMENTO" selected>RETRATAMENTO</option></select>';

		$action_form = '../controladores/especialidadeGravar.php?id='.$id;
	}
	
	include_once("../funcoes/common_especialidades.php");
?>


<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

    <div id="conteudo">
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a>configurações</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda"></span><a href="especialidades.php">especialidades</a><span class="breadcrumbDireita"></span>
                                <ul>
                                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">editar especialidade</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
    
            <div id="container" class="clearfix">
			<?php include_once("include/conteudo_editar_especialidades.php") ?>
		<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
            var novo_procedimento = function(){
                    var md = new Modal(['modal']);
                    md.setHeader('Adicionar Procedimento');
        
                    var fm = new Form('form_e',['../controladores/matchcodeGravar.php','post'],'field','');
        
                    fm.addEvent('success',function(){
                        (function(){this.fadeAndRemove()}.bind(this)).delay(500);
                        xhSendPost("../controladores/AJAX.configuracao_procedimentos.php?div_id=container&id=" + <?php echo $id; ?>);
                    }.bind(md));
                    fm.attach(md.win);
        
                    descricao = fm.newField('Procedimento','descricao',300 ,'text','',100);
                    descricao.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });
                    //tratamento = fm.newField('Tipo','tipo',300 ,'text');
                    fm.injectBlock('<?php echo addslashes($combo_tipo); ?><input type="hidden" name="id_especialidade" value="'+arguments[0]+'" />');
        
                    fm.addEvent('request',function(a){
                        if(this.value.trim() == ""){
                            a.cancel();
                            a.failure();
                            alert('Informe um nome válido');
                        }
                    }.bind(descricao));
        
                    fm.attachSendBar(['Cadastrar Procedimento','mb']);
                    md.show();
            }
        
            var editar_procedimento = function(){
                   var md = new Modal(['modal']);
                   md.setHeader('Editar Procedimento');
        
                    var fm = new Form('form_e',['../controladores/matchcodeGravar.php','post'],'field','');
        
                    fm.addEvent('success',function(){
                        (function(){this.fadeAndRemove()}.bind(this)).delay(500);
                        xhSendPost("../controladores/AJAX.configuracao_procedimentos.php?div_id=container&id=" + <?php echo $id; ?>);
                    }.bind(md));
                    fm.attach(md.win);
        
                    descricao = fm.newField('Descrição','descricao',300 ,'text',arguments[2], 100);
                    descricao.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });
                    //tratamento = fm.newField('Tipo','tipo',300 ,'text');
        
                    if(arguments[3] == "TRATAMENTO")
                        fm.injectBlock('<?php echo addslashes($combo_tipo); ?><input type="hidden" name="id_especialidade" value="<?php echo $id; ?>" />');
                    else
                        fm.injectBlock('<?php echo addslashes($combo_tipo2); ?><input type="hidden" name="id_especialidade" value="<?php echo $id; ?>" />');
        
                    fm.injectBlock('<input type="hidden" name="id" value="'+arguments[0]+'" />');
                    
                    fm.addEvent('request',function(a){
                        if(this.value.trim() == ""){
                            a.cancel();
                            a.failure();
                            alert('Informe um nome válido');
                        }
                    }.bind(descricao));
        
                    fm.attachSendBar(['Atualizar Procedimento','mb']);
                    md.show();
            }
        
            function editar_procedimento(valor){
                location.href = "configuracao_procedimentos.php?id=" + valor;
            }
        
            function remover_procedimento(id_procedimento, id_especialidade){
                if(confirm('Deseja realmente remover este procedimento?')){
                    location.href="../controladores/matchcodeGravar.php?acao=excluir&id=" + id_procedimento + "&id_especialidade=" + id_especialidade;
                }
            }
        
     
            function selecionar_especialidade(valor,descricao){
                location.href="../controladores/configuracaoGravar.php?acao=editar&id=" + valor;
                alert('Especialidade \''+descricao+'\' selecionada com sucesso.');
            }
			
            function valida_campos(){
                if (valida_nome(document.cd_field.descricao))
                    document.cd_field.submit();
            }
        
            function qtdpag(){
                if($('qnt_pag')!=null)
                    location.href = "editar_especialidade.php?id=<?php echo $id; ?>&qtdpag="+$('qnt_pag').get('value');
            }
            function altera_pagina(id, pag){
                xhSendPost("../controladores/AJAX.configuracao_procedimentos.php?div_id=tabela_dados&qtdpag="+$('qnt_pag').get('value')+"&pag="+pag+"&id="+id);
            }
        </script>
    </body>
</html>