<?php
	//Antiga página tratamentos_atualizacao.php

    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../classes/classTratamento.php");
		include_once("../classes/classMatchCode.php");
		include_once("../classes/classPaciente.php");
		include_once("../classes/classCombo.php");
		include_once("../funcoes/common.php");
		
		/************************************************************************************
		 *CARREGA OS DADOS DO TRATAMENTO
		/************************************************************************************/
		$id_paciente = 0;
		$id_tratamento = 0;
		$status = '';
		$data_inic = '';
		$data_term = '';
		$dente = '';
		$tipo = '';
		$sub_tipo = '';
		$id_match_code = '';
		$nome = '';
		$value = '- - MATCH CODE - -';
		
		if(isset($_SESSION['PACIENTE']['ID']) && isset($_POST['id_tratamento'])) {
			$id_paciente = $_SESSION['PACIENTE']['ID'];
			$paciente = new Paciente($id_paciente);
			$id_tratamento = $_POST['id_tratamento'];
			$action_form = "../controladores/tratamentoGravar.php?tipo=atualizacao";
			$tratamento = new Tratamento($id_tratamento);
			$vet_data = explode("-", $tratamento->getDataInicio());
			$data_inic = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
		
			if(($tratamento->getDataTermino() != "0000-00-00")&&($tratamento->getDataTermino() != NULL)) {
				$vet_data = explode("-", $tratamento->getDataTermino());
				$data_term = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
			} else {
				$data_term = NULL;
			}
		
			$status = $tratamento->getStatus();
			$resultado = $tratamento->getSucesso();
			$dente = $tratamento->getDente();
			$id_match_code = $tratamento->getIdMatchCode();
			$observacoes = $tratamento->getDescricao();
		
			if($id_match_code != NULL) {
				$value = $id_match_code;
			}
		
			$match_code = new MatchCode($id_match_code);
			$tipo = $match_code->getDescricao();
			$sub_tipo = $match_code->getTipo();
	/******************************************************************************************
	/*LISTA IMAGENS DO TRATAMENTO
	/******************************************************************************************/
			$cam_img_tratamento = '../documentos/pacientes/'.$id_paciente.'/tratamento/';
			$img_tratamento_HTML = "";
		
			$persistencia = new Persistencia();
		
			$sSql = "SELECT id_imagem, caminho, data FROM imagem WHERE id_tratamento=$id_tratamento";
		
			$persistencia->bExecute($sSql);
			$persistencia->bDados();
		
		
			if($persistencia->getDbNumRows() != 0) {
				for($i=0;$i<$persistencia->getDbNumRows();$i++) {
		
					if($persistencia->bCarregaRegistroPorLinha($i)) {
		
						$vet = $persistencia->getDbArrayDados();
						$imagens_anteriores[$i][0] = $vet['caminho'];
						$imagens_anteriores[$i][1] = $vet['data'];
						$imagens_anteriores[$i][2] = $vet['id_imagem'];
					}
				}
				foreach($imagens_anteriores as $indice => $imagem ){
					if(substr($imagem[0], -3) == 'avi'){
						$img_tratamento_HTML .= '
						<li alt="'.$imagem[2].'" id="vid'.$i.'">
							<div class="tools video">
								<a href="javascript:;" title="Remover" class="remove ir">Remover</a>
								<a href="'.$cam_img_tratamento.$imagem[0].'" target="blank" class="view ir">Visualizar</a>
							</div>
							<img class="pic" src="img/video_default.gif" alt="'.decodeDate($imagem[1]).'"/>
						</li>';
					}else{
						$img_tratamento_HTML .= '

                                                <li>
                                                  <div class="tools">
                                                      <a href="javascript:;" title="Remover" class="remove ir">Remover</a>
                                                      <a href="'.$cam_img_tratamento.$imagem[0].'" title="Zoom" class="zoom ir">Zoom</a>
                                                  </div>

                                                        <img class="pic" src="'.$cam_img_tratamento.$imagem[0].'" data-caption="'.decodeDate($imagem[1]).'">
                                                </li>';
					}
				}
			}
		}
	/************************************************************************************
	/*LISTA AS IMAGENS DA ULTIMA CONSULTA
	/************************************************************************************/
		if($status == 0) {
			$diretorio = "../temp/";
			
			$ip = $_SERVER['REMOTE_ADDR'];
			require_once("../classes/classUser2SgeIvision.php");
			$commUser2SgeIvision = new CommUser2SgeIvision();
			$commUser2SgeIvision->getCommUser2SgeIvisionByIp($ip);
			$sHTML = '';
			if($commUser2SgeIvision->getRotulo() != ""){
				$diretorio .= $commUser2SgeIvision->getRotulo().'/';
				$ponteiro_diretorio = opendir($diretorio);
				$caminho = array();
		
				while ($nome_itens = readdir($ponteiro_diretorio)) {
					$itens[]=$nome_itens;
				}
				$i=0;
				foreach ($itens as $listar) {
					if(!is_dir($listar)) {
						$array = explode('.',$listar);
						if (($array[1] == 'gif') || ($array[1] == 'jpg') || ($array[1] == 'JPG') || ($array[1] == 'JPEG') || ($array[1] == 'jpeg')|| ($array[1] == 'png')|| ($array[1] == 'avi')) {
							$caminho[$i]=$diretorio.$listar;
							$nome[$i]='img'.$i;
							$i++;
						}
					}
				}
				$i = 0;
		
				foreach($caminho as $sPath) {
					if(substr($sPath, -3) == 'avi'){
							$sHTML .= '
							<li id="vid'.$i.'">
								<div class="tools video">
									<a class="inserir ir" href="javascript:;" onClick="insereVideo(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.',true)">Inserir</a>
									<a href="'.$sPath.'" target="blank" class="view ir"  alt="'.$sPath.'">Visualizar</a>
								</div>
								<img class="pic" src="img/video_default.gif" alt="Visualizar" />
							</li>';
						} else {
							$sHTML .= '
							<li id="img'.$i.'">
								<div class="tools">
									<a href="javascript:;" onClick="insereImagem(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.',true)" class="inserir ir">Inserir</a>
									<a href="javascript:;" title="Zoom" class="zoom ir" onclick="show_full(this)">Zoom</a>
								</div>
								<img class="pic" src="'.$sPath.'" alt="'.$sPath.'"/>
							</li>';
						}
						$i++;
					$i++;
				}
			}
		}

		$combo = new Combo();
		
		$combo->bAddItemCombo("0","EM TRATAMENTO");
		$combo->bAddItemCombo("1","CONCLUIDO");
		$combo->bAddItemCombo("2","CANCELADO");
		if(($status == 1) || ($status == 2)) {
			$combo_status = $combo->sGetHTML('','e_status','','',$status,'disabled','style="width:200px;"');
		}else {
			$combo_status = $combo->sGetHTML('','e_status','','',$status,'','style="width:200px;"');
		}
		//Os valores do campo sao 0-Insucesso, 1-Sucesso, 2-Pendente e 3-Cancelado
		$combo->bClearCombo();
		$combo->bAddItemCombo("0","INSUCESSO");
		$combo->bAddItemCombo("1","SUCESSO");
		$combo->bAddItemCombo("2","PENDENTE");
		$combo->bAddItemCombo("3","CANCELADO");
		
		$combo_resultado = $combo->sGetHTML('','resultado','','',$resultado,'','style="width:200px;"');
	}
	
	$nome_pac = "";
	if(isset($_SESSION['PACIENTE']['ID'])){
		$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		$nome_pac = utf8_encode(ucwords(strtolower(utf8_decode($paciente->getNome()))));
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>
<script src="js/mouseover_popup.js" language="javascript" type="text/javascript"></script>

    <!-- Image Preview Starts -->
        <div id="preview_div"></div>
    <div id="conteudo">
		
    	<?php include_once("include/dados_paciente.php") ?>
        <div id="dropshadow">
        <div id="breadcrumb">
            <ul>
                <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php">pacientes</a><span class="breadcrumbDireita"></span>
                    <ul>
                        <li><span class="breadcrumbEsquerda"></span><a href="tratamentos.php">tratamentos</a><span class="breadcrumbDireita"></span>
                            <ul>
                                <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">atualizar tratamento</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div> <!--Fecha div breadcrumb-->

        <div id="container" class="clearfix">
           <form action="<?php echo $action_form; ?>" id="cd_field" method="post" name="cd_field">
                <h3 class="tituloBox">Tratamento</h3>
                <div id="formularioTratamento" class="formularioDividido">
                    <fieldset>
                        <p style="margin-left:75px !important;">Tratamento&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tratamento->getIdMatchCode().' - '.$match_code->getDescricao(); ?></p>
                        <p style="margin-left:110px !important;">Dente&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tratamento->getDente(); ?></p>
                        <p style="margin-left:62px !important;">Data de Início&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data_inic; ?></p>
                        <div>
                            <label class="alinhamentoDireita2" for="e_status">Status do tratamento</label>
                            <?php echo $combo_status;?>
                        </div>
                        <div>                
                            <label class="alinhamentoDireita2" for="resultado_tratamento">Resultado</label>
                            <?php echo $combo_resultado;?>
                        </div>
                        <div>
                            <label class="alinhamentoDireita2" for="observacoes">Observação</label>
                            <textarea id="observacoes" name="sessao" cols="40" rows="10"><?php echo $observacoes?></textarea>
                        </div>
                    </fieldset>
                </div><!--fecha formularioTratamento-->
                
                <h3 class="tituloBox">Gerenciamento de Imagens</h3>
                <div id="gerenciamentoImagens" class="formularioDividido">
                    <fieldset>
                            <ul id="listaImagens">
                                <li>
                                    <a class="abaSelect" onclick="muda_tab(0)" title="Imagens selecionadas da última consulta">Imagens Selecionadas</a>
                                </li>
                                <li>
                                    <a onclick="muda_tab(1)" title="Imagens salvas do tratamento">Banco de Imagens</a>
                                </li>
                            </ul>
                            
                         <span style="background:#FFF; width:98%; height:10px; display:block; padding:0 10px 0 10px; float:left;"></span>
                        
                        <div id="imagens_selecionadas" onmouseout="limpaPreview()">
                        	<!--conteúdo gerado dinamicamente-->
                        </div><!--fecha imagens_selecionadas-->
                        
                        
                        <div id="banco_imagens">
                        <!--  <div class="visible-img" >-->
                            <ul class="clearing-thumbs" data-clearing>
                            <?php
                            echo $img_tratamento_HTML;
                            ?>
                            </ul>
                         </div>
                        <!-- </div>  -->
                        
                        <script>
                          document.write('<script src=' +
                          ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
                          '.js><\/script>')
                        </script>
                        <script src="js/foundation.min.js" type="text/javascript"></script>
                        <script>
                            $fdt(document).foundation();
                        </script>
                    </fieldset>
                </div><!--fecha gerenciamentoImagens-->
                
                <h3 class="tituloBox">Imagens da Última Consulta</h3>
                <div class="formularioDividido">
                	<span style="background:#FFF; width:98%; height:10px; display:block; padding:0 10px 0 10px;"></span>
                    <ul  id="images">
						<?php
                        echo $sHTML;
                        ?>
                    </ul><!--fecha formularioDividido-->
                </div>
                
                <p id="botoesFormulario">
                    <button id="botaoNegativo" type="button" onclick="voltar();">Cancelar</button>
                    <button class="botaoPositivo" type="button" onclick="submete();">Atualizar Tratamento</button>
                </p>
                <input type="hidden" name="apagar_imagens" id="apagar_imagens" value="false" />
                <input type="hidden" name="id_paciente" value="<?php echo $_POST['id_paciente']; ?>" />
                <input type="hidden" name="id_tratamento" value="<?php echo $_POST['id_tratamento']; ?>" />      
            </form>
            
			<?php include_once("include/footer.php") ?>
            
			<script type="text/javascript">
				/* Reload/Refresh Script Starts */
				var qtd_imagens = <?php echo (isset($i)) ? $i : 0; ?>;
				var reload_request = new Request({
						'url':'../controladores/AJAX.tratamento_atualizacao.php',
						'method':'post',
						'noCache':false,
						onRequest:function(){
							//$('refresh').fade('in');
						},
						onComplete:function(a){
							//(function(){$('refresh').fade('out')}).delay(1000);
							response = JSON.decode(a);
				
							// RETORNAR ESSE VALOR QUANDO TIVER RESPOSTA DA CAMERA
							//qtd_imagens = parseInt(response[0]);
				
							_links = $('images').getElements('a');
							for(i=0; i<_links.length; i++) $clear(_links[i].interval);
							eval(response.eval);
							$('images').set('html',response.html).getElements('a.zoom').addEvent('click',function(){
								show_full(this);
							});
							//$('images').setStyle('width',$('images').getElements('li').length*130+10+'px');
						},
						onFailure:function(){
							$('status').set('html','<p>Ocorreu um erro na comunicação com o Servidor. Verifique sua conexão com o Servidor.</p>');
							$('status').set('class','st_fail');
							$('status').fade('in');
							(function(){$('status').fade('out')}).delay(5000);
						}
					}
				);
				var primeiraVez = 1;
				var fazReload = function(){
						reload_request.send('type=atualizar&id_tratamento='+<?php echo $id_tratamento ?>+"&primeira=" + primeiraVez + "&selected=" + encodeURI(imagens_selecionadas.join(",")));
						primeiraVez = 0;
					}
				var teeth_reload = function(){
					(fazReload).periodical(3000);
				}
				
				window.addEvent('domready',function(){
					teeth_obo_mark();
					tabs_remove_picture();
					slide_blocks();
					teeth_reload();
					$('e_status').set('name','status');
					$('images').getElements('a.zoom').addEvent('click',function(){
						show_full(this);
					})
				});
				
				window.onload=function(){
				var divCarregando = document.getElementById("banco_imagens");
					divCarregando.style.display="none";
				}
				
				var imagens_selecionadas = new Array();
				var imagens_removidas = new Array();
				
				function insereImagem(div,caminho_imagem,id,acao_usuario) {
					var i = 0;
					var existe = false;
					if(acao_usuario){
					    //console.log("Acao do usuário?" + acao_usuario);
						for(i = 0; i < imagens_selecionadas.length; i++){
							if(imagens_removidas[i] == caminho_imagem){
								imagens_removidas.splice(i,1);
							}
						}
						if(document.getElementById("imagens_selecionadas").style.display == "none"){
							muda_tab(0);
						}
					}
					for(i = 0; i < imagens_selecionadas.length; i++){
						if(imagens_selecionadas[i] == caminho_imagem){
							existe = true;
						}
					}
					for(i = 0; i < imagens_removidas.length; i++){
						if(imagens_removidas[i] == caminho_imagem){
							existe = true;
						}
					}
					if(!existe){
						imagens_selecionadas.include(caminho_imagem);
					}
					recarrega_imagens(div,acao_usuario);
				}
				
				function insereVideo(div,caminho_video,id,acao_usuario) {
					var i = 0;
					var existe = false;
					if(acao_usuario){
						for(i = 0; i < imagens_removidas.length; i++){
							if(imagens_removidas[i] == caminho_video){
								imagens_removidas.splice(i,1);
							}
						}
						if(document.getElementById("imagens_selecionadas").style.display == "none"){
							muda_tab(0);
						}
					}
					for(i = 0; i < imagens_selecionadas.length; i++){
						if(imagens_selecionadas[i] == caminho_video){
							existe = true;
						}
					}
					for(i = 0; i < imagens_removidas.length; i++){
						if(imagens_removidas[i] == caminho_video){
							existe = true;
						}
					}
					if(!existe){
						imagens_selecionadas.include(caminho_video);
					}
					recarrega_imagens(div,acao_usuario);
				}
				
				function remove_imagem(div,valor) {
					var i;
				    var existe = false;
					for(i=0; i< imagens_selecionadas.length; i++) {
						if (imagens_selecionadas[i] == valor) {
							imagens_selecionadas.splice(i,1);
						}
					}
					for(i = 0; i < imagens_removidas.length; i++){
							if(imagens_removidas[i] == valor){
								existe = true;
							}
					}
					if(!existe){
						imagens_removidas.include(valor);
					}
					recarrega_imagens(div,true);
				}
				
				function recarrega_imagens(div,acao) {
					var i;
					var html = '<ul class="tab_field">';
					for(i =0;i < imagens_selecionadas.length; i++){
						ext = imagens_selecionadas[i].substr(imagens_selecionadas[i].length-3,3);
						html +='';
	
						if(ext=='avi'){
							html += '<li><div class="tools video"><a href="javascript:;" class="remove ir" title="Remover" alt="'+imagens_selecionadas[i]+'">Remover</a><a href="'+imagens_selecionadas[i]+'" target="blank" alt="'+imagens_selecionadas[i]+'" title="Visualizar" class="view ir">Visualizar</a></div><img class="pic" src="img/video_default.gif"/></li>';
						} else {
							html += '<li><div class="tools"><div class="tools"><a href="javascript:;" class="remove ir" title="Remover">Remover</a><a href="javascript:;" title="Zoom" class="zoom ir" onclick="show_full(this)">Zoom</a></div><img class="pic" src="'+imagens_selecionadas[i]+'" /></li>';
						}
						html += '<input name="img_'+i+'" type="hidden" value="'+imagens_selecionadas[i]+'"/>';
					}
					html += '</ul>';
					document.getElementById(div).innerHTML = html;
					tabs_remove_picture();
					if(acao)
						fazReload();
				}
				
				function submete() {
					//console.log(qtd_imagens);
					/*if(qtd_imagens > imagens_selecionadas.length){
						apagar_imagens = confirm("Apagar as fotos deste paciente?");
						if(apagar_imagens)
							document.getElementById('apagar_imagens').value = "true";
						else
							document.getElementById('apagar_imagens').value = "false";
					}*/
				
					if(document.getElementById('e_status').value!=0){
						status=(document.getElementById('e_status').value==1) ? 'CONCLUIDO': 'CANCELADO';
						if(confirm('Deseja realmente alterar o status para '+status+'?'))
						document.getElementById('cd_field').submit();
					}
					else
						 document.getElementById('cd_field').submit();
				}
				function voltar() {
					//$('cd_field2').submit();
					window.location.href="tratamentos.php";
				}
				
				function muda_tab(valor) {
					var el_atualizacao;
					var el_banco;
					var html='';
					el_atualizacao = document.getElementById('imagens_selecionadas');
					el_banco = document.getElementById('banco_imagens');
					if (valor == 0){
						html = ''+
						'<li><a class="abaSelect" onclick="muda_tab(0)" title="Imagens selecionadas da última consulta">Imagens Selecionadas</a></li>' +
						'<li><a class="" onclick="muda_tab(1)" title="Imagens salvas do tratamento"> Banco de Imagens</a></li>';
						el_atualizacao.style.display = "none";
						el_banco.style.display = "none";
					}
					if (valor == 1){
						html = ''+
						'<li><a class="" onclick="muda_tab(0)">Imagens Selecionadas</a></li>' +
						'<li><a class="abaSelect" onclick="muda_tab(1)"> Banco de Imagens</a></li>';
						el_atualizacao.style.display = "";
						el_banco.style.display="";
					}
					el_atualizacao.style.display = (el_atualizacao.style.display == 'none')?"":"none";
					document.getElementById('listaImagens').innerHTML = html;
				}
				
				function limpaPreview(){
					a = document.getElementById("preview_div");
					if (a.style.display != "none"){
						hidetrail();
					}
				}
            </script>             
    </body>
</html>