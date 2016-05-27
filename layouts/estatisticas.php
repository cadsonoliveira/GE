<?php
	//Antiga página estatisticas_geral.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../classes/classPaciente.php");
		include_once("../classes/classTratamento.php");
		include_once("../classes/classMatchCode.php");
		include_once("../classes/classPersistencia.php");
		include_once("../classes/classConfiguracao.php");
		include_once("../classes/classCombo.php");
	
		$action_form = "../controladores/estatisticas.php";
		$initState = 0;
		$matchcode = new MatchCode(1);
		$config = new Configuracao(1);
		/****
		 * Conteudo do modal - Selecionar Match Code
		 * Inicio
		 */
	
		/*TRATAMENTOS*/
		$lista_trat = "";
		
		$sSql = "SELECT id_match_code, descricao, tipo
				 FROM match_code
				 WHERE id_especialidade = ".$config->getIdEspecialidade()."
					AND tipo = 'TRATAMENTO'
				 ORDER BY tipo, descricao";
		$pers = new Persistencia();
		$pers->bExecute($sSql);
		$cont = 0;
		$num_registros = (($pers->getDbNumRows() % 2) == 0) ? $pers->getDbNumRows()/2 : (int)($pers->getDbNumRows()/2)+1;
		while($cont < $pers->getDbNumRows()){   
			$pers->bCarregaRegistroPorLinha($cont);
			$vet_result = $pers->getDbArrayDados();

			$desc = utf8_encode(strtolower($vet_result['descricao']));
			$desc = ucfirst($desc)." {".$vet_result['id_match_code']."}";
			$lista_trat .= "<span class=\"displayInline\">";
			$lista_trat .= "<input onclick='arrayTratMatchCode(".$vet_result['id_match_code'].", $(this).get(\"checked\"))' type='checkbox' id='_".$vet_result['id_match_code']."' name='".$vet_result['id_match_code']."' value='".$vet_result['id_match_code']."'/>".
			"<label onClick='document.getElementById(\"_".$vet_result['id_match_code']."\").click()' for='".$vet_result['id_match_code']."'>".$desc."</label>";
			$lista_trat .= "</span>";
			$cont++;
		}
	
		/*RETRATAMENTO*/

		$sSql = "SELECT id_match_code, descricao, tipo
				 FROM match_code
				 WHERE id_especialidade = ".$config->getIdEspecialidade()."
					AND tipo = 'RETRATAMENTO'
				 ORDER BY tipo, descricao";

		$pers = new Persistencia();
	
		$pers->bExecute($sSql);
		$cont = 0;
		$lista_retrat = "";
	   
		$num_registros = (($pers->getDbNumRows() % 2) == 0) ? $pers->getDbNumRows()/2 : (int)($pers->getDbNumRows()/2)+1;
		while($cont < $pers->getDbNumRows()){
			$pers->bCarregaRegistroPorLinha($cont);
			$vet_result = $pers->getDbArrayDados();

			$desc = utf8_encode(strtolower($vet_result['descricao']));
			$desc = ucfirst($desc)." {".$vet_result['id_match_code']."}";

			$lista_retrat .= "<span class=\"displayInline\">";
			$lista_retrat .= "<input onclick='arrayRetratMatchCode(".$vet_result['id_match_code'].", $(this).get(\"checked\"))' type='checkbox' id='_".$vet_result['id_match_code']."' name='".$vet_result['id_match_code']."' value='".$vet_result['id_match_code']."'/>".
			"<label onClick='document.getElementById(\"_".$vet_result['id_match_code']."\").click()' for='".$vet_result['id_match_code']."'>".$desc."</label>";
			$lista_retrat .= "</span>";
			$cont++;
		}
	
		 /****
		  * Conteudo do modal - Selecionar Match Code
		  * Fim
		  */
	
		$sSqlMatchCode = 'SELECT id_match_code, descricao FROM match_code ORDER BY id_match_code';
		$combo = new Combo();
		$combo->setSize("6");
		$combo->setClassSelect('iw323');
		$combo->setClassOption('','');
		$comboMC = $combo->sGetHTML($sSqlMatchCode,'match_code','id_match_code','descricao',1,'onchange="updateCampo(\'matchcode\')" MULTIPLE');
		
		/******
		*** Efetuando a persistencia do filtro na navegaçao da página
		*** INICIO
		***/
		$dataIn_active = '1';
		//POST armazenado na sessao (../controladores/AJAX.estatistica_geral.php)
		$match_codes_trat 	= (isset($_SESSION['_post']['match_codes_trat'])) ? $_SESSION['_post']['match_codes_trat'] : "";
		$match_codes_retrat = (isset($_SESSION['_post']['match_codes_retrat'])) ? $_SESSION['_post']['match_codes_retrat'] : "";
		$matchcode_active 	= (isset($_SESSION['_post']['matchcode_active'])) ? $_SESSION['_post']['matchcode_active'] : "";
		$sex 				= (isset($_SESSION['_post']['sex'])) ? $_SESSION['_post']['sex'] : "";
		$sexo_active 		= (isset($_SESSION['_post']['sexo_active'])) ? $_SESSION['_post']['sexo_active'] : "";
		$idade_min 			= (isset($_SESSION['_post']['idade_min'])) ? $_SESSION['_post']['idade_min'] : "";
		$idade_max 			= (isset($_SESSION['_post']['idade_max'])) ? $_SESSION['_post']['idade_max'] : "";
		$idade_active 		= (isset($_SESSION['_post']['idade_active'])) ? $_SESSION['_post']['idade_active'] : "";
		$dataInicio 		= (isset($_SESSION['_post']['inicio_tratamento'])) ? $_SESSION['_post']['inicio_tratamento'] : "";
		$dataIn_active	 	= (isset($_SESSION['_post'])) ? $_SESSION['_post']['dataIn_active'] : "-1";
		$dataFim 			= (isset($_SESSION['_post']['dataFim'])) ? $_SESSION['_post']['dataFim'] : "";
		$dataFim_active 	= (isset($_SESSION['_post']['dataFim_active'])) ? $_SESSION['_post']['dataFim_active'] : "-1";
		$resultado		 	= (isset($_SESSION['_post']['resultado'])) ? $_SESSION['_post']['resultado'] : "";
		$resultado_active 	= (isset($_SESSION['_post']['resultado_active'])) ? $_SESSION['_post']['resultado_active'] : "";
		$dentes_active 		= (isset($_SESSION['_post']['dentes_active'])) ? $_SESSION['_post']['dentes_active'] : "1";
		$matchcode_hd 		= (isset($_SESSION['_post']['matchcode_hd'])) ? $_SESSION['_post']['matchcode_hd'] : "-1";
		$sexo_hd 			= (isset($_SESSION['_post']['sexo_hd'])) ? $_SESSION['_post']['sexo_hd'] : "-1";
		$idademin_hd 		= (isset($_SESSION['_post']['idademin_hd'])) ? $_SESSION['_post']['idademin_hd'] : "-1";
		$idademax_hd 		= (isset($_SESSION['_post']['idademax_hd'])) ? $_SESSION['_post']['idademax_hd'] : "-1";
		$dtinicio_hd 		= (isset($_SESSION['_post']['dtinicio_hd'])) ? $_SESSION['_post']['dtinicio_hd'] : "-1";
		$dtfim_hd 			= (isset($_SESSION['_post']['dtfim_hd'])) ? $_SESSION['_post']['dtfim_hd'] : "-1";
		$sucesso_hd 		= (isset($_SESSION['_post']['sucesso_hd'])) ? $_SESSION['_post']['sucesso_hd'] : "-1";
		$dentes 			= (isset($_SESSION['_post']['dentes'])) ? $_SESSION['_post']['dentes'] : "-1";
	
		//GET armazenado na sessao (../controladores/AJAX.estatistica_geral.php)
		$codes 				= (isset($_SESSION['_get']['codes'])) ? $_SESSION['_get']['codes'] : "";
		$qtdpag 			= (isset($_SESSION['_get']['qtdpag'] )) ? $_SESSION['_get']['qtdpag'] : "10";
		
		//Apagando variaveis de sessao
		unset($_SESSION['_post']);
		unset($_SESSION['_get']);
		
		/******
		*** FIM
		*** Efetuando a persistencia do filtro na navegaçao da página
		***/
	}	
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>
    <div id="conteudo">
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">estatísticas</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
    
            <div id="container" class="clearfix">
                <form id="form" action="dados_tratamento_estatistica.php" method="POST" name="cd_field2">
                    <input type="hidden" value="" name="id_tratamento">
                </form>
            
                <form action="#" method="post" id="cd_field" name="form1">
                <h3 class="tituloBox">Filtros</h3>
                
                <div id="boxfiltroTratamentos" class="formularioDividido">
                    <p id="ativarFiltros"><a id="AtivarfiltrarTratamentos" onclick="ativaFiltros();" >Expandir</a></p>
                    <fieldset id="blocoTratamento">
                        <label>
                            <input name="matchcode_active" type="checkbox" onclick="enable_matchcode();" value="1" />
                            Ativar
                        </label>
                        <label id="matchCode" class="alinhamentoDireita">Match Code</label>
                        <div style="display: inline; float: left; width: 80%;">
                            <div class="opcoesTratamento">
                                <label class="tituloTratamentos"><span class="margemEsquerda">Tratamento</span></label>
                                <span class="margemEsquerda">
                                    <?php echo $lista_trat; ?>
                                </span>
                            </div>
                           <div class="opcoesTratamento">
                                <label class="tituloTratamentos"><span class="margemEsquerda">Retratamento</span></label>
                                <span class="margemEsquerda">
                                    <?php echo $lista_retrat; ?>
                                </span>
                            </div>
                        </div>
                        <div class="elementosFormulario2" style="clear:both">
                            <label id="sexo" class="alinhamentoDireita">Sexo</label>
                            <input id="masculino" name="sexo" type="radio" value="masculino" checked="checked" onclick="updateCampo('sexo');" disabled = "<?php echo $initState?>"/>
                            <label for="masculino">Masculino</label>
                            <input id="feminino" name="sexo" type="radio" value="feminino" onclick="updateCampo('sexo');" disabled = "<?php echo $initState?>"/>
                            <label for="feminino">Feminino</label>
                            <label>
                                <input name="sexo_active" type="checkbox" onclick="enable_sexo();" value="<?php echo $sexo_active; ?>" />
                                Ativar
                            </label>
                        </div>
                        <div class="elementosFormulario2">
                            <label id="idade" class="alinhamentoDireita">Idade</label>
                            <input  id="idade_min" name="idade_min" type="text" value="0"  disabled = "<?php echo $initState?>"/>
                            <label for="idade_min" onblur="if(valida_numero(document.form1.idade_min)==true) if(valida_idade()) updateCampo('idade');">mín</label>
                            <input  id="idade_max" name="idade_max" type="text" value="100" disabled = "<?php echo $initState?>" />
                            <label for="idade_max" onblur="if(valida_numero(document.form1.idade_max)==true) if(valida_idade()) updateCampo('idade');">máx</label>
                            <label>
                                <input name="idade_active" type="checkbox" onclick="enable_idade();" value="<?php echo $idade_active; ?>" />
                                Ativar
                            </label>
                        </div><!--fecha elementosFormulario2-->
                        <div class="elementosFormulario2">
                            <label id="inicioTratamento" class="alinhamentoDireita" for="inicio_tratamento">Início do tratamento</label>
                            <input name="inicio_tratamento" type="text" disabled = "<?php echo $initState?>" id="inicio_tratamento" onblur="valida_dtInicio();" onchange="updateCampo('dtInicio');" onkeypress="Mascara('DATA',this,event);" value="<?php echo $dataInicio; ?>"/>
                            <label>
                                <input name="dataIn_active" type="checkbox"  onclick="enable_dtinicio();" value="1" />
                                Ativar
                            </label>
                        </div><!--fecha elementosFormulario2-->
                        <div class="elementosFormulario2">
                        <label id="fimTratamento" class="alinhamentoDireita" for="fim_tratamento">Fim do tratamento</label>
                        <input disabled = "<?php echo $initState?>" id="fim_tratamento" name="fim_tratamento" type="text" onblur="valida_dtFim();" onchange="updateCampo('dtFim');" onkeypress="Mascara('DATA',this,event);" value="<?php echo $dataFim; ?>"/>
                        <label>
                            <input name="dataFim_active" type="checkbox" onclick="enable_dtfim();" value="1" />
                            Ativar
                        </label>
                        </div>
                        <div class="elementosFormulario2">
                        <label id="resultadoTratamento" class="alinhamentoDireita" for="resultado">Resultado</label>
                        <select disabled = "<?php echo $initState?>" id="resultado" name="resultado">
                            <option value="1">Sucesso</option>
                            <option value="0">Insucesso</option>
                            <option value="2">Pendente</option>
                            <option value="3">Cancelado</option>
                        </select>
                        <label>
                            <input name="resultado_active" type="checkbox" onclick="enable_result();" value="<?php echo $resultado_active; ?>" />
                            Ativar
                        </label>
                        </div>
                        <p id="filtrarDente" class="alinhamentoDireita">Filtrar por dente</p>
                        <?php include_once("include/arcada_dentaria.php") ?>
                            <span style="display:table; margin: 20px 0 0 768px;">
                                <button type="button" onclick="teeth_multi_mark('mark', true)">Marcar todos</button>
                                <button type="button" onclick="teeth_multi_mark('unmark', true)" style="margin-left:5px;">Desmarcar todos</button>
                                
                                <label>
                                    <input name="dentes_active" type="checkbox" value="<?php echo $dentes_active; ?>" />
                                    Ativar
                                </label>                                
                            </span>

                        <!--Hiddens com os valores dos campos utilizados na busca-->
                        <input name="matchcode_hd" id="matchcode_hd" type="hidden" value="<?php echo $matchcode_hd; ?>" />
                        <input name="sexo_hd" id="sexo_hd" type="hidden" value="<?php echo $sexo_hd; ?>" />
                        <input name="idademin_hd" id="idademin_hd" type="hidden" value="<?php echo $idademin_hd; ?>" />
                        <input name="idademax_hd" id="idademax_hd" type="hidden" value="<?php echo $idademax_hd; ?>" />
                        <input name="dtinicio_hd" id="dtinicio_hd" type="hidden" value="<?php echo $dtinicio_hd; ?>" />
                        <input name="dtfim_hd" id="dtfim_hd" type="hidden" value="<?php echo $dtfim_hd; ?>" />
                        <input name="sucesso_hd" id="sucesso_hd" type="hidden" value="<?php echo $sucesso_hd; ?>" />
                        <input name="dentes" id="dentes" type="hidden" value="<?php echo $dentes; ?>" />
                    </fieldset>
                    
                    <span id="botoesFormulario" class="clearfix" style="margin:auto; width:600px; display:block;">
                        <a href="#" onclick="limpar_filtros();" class="botaoPositivo" style="text-decoration:none; display:inline; float:left; line-height:49px;">Limpar Filtros</a>
                        <a href="#resultados" onclick="executa_filtro();" class="botaoPositivo" style="text-decoration:none; width:245px; height:52px; clear:none; float:left; line-height:49px; margin-left:20px;">Filtrar</a>
                        </span>
                    
                    </div>
                </form>
    
                <form id="grid" action="#" method="POST" name="grid">
                    <div id="div_resultado">
                        <!--conteúdo gerado dinamicamente-->
                    </div>
                </form>
            
			<?php include_once("include/footer.php") ?>
        
        <script src="js/Fx.Slide.js" type="text/javascript"></script>
        <script src="js/calendar.js" type="text/javascript"></script>
        <script type="text/javascript">

		var guardaTamanhoDiv = $('boxfiltroTratamentos').getStyle('height');
		$('boxfiltroTratamentos').set('style', 'height:20px');
		
			function ativaFiltros(){
				if($('AtivarfiltrarTratamentos').get('html') == "Expandir"){
					$('AtivarfiltrarTratamentos').set('html', 'Fechar');
					$('boxfiltroTratamentos').morph({'height': guardaTamanhoDiv});
				}else{
					$('AtivarfiltrarTratamentos').set('html', 'Expandir');
					$('boxfiltroTratamentos').morph({'height':'20px'});
				}
			}

			conj_trat_match_codes = new Array();
			conj_retrat_match_codes = new Array();
        
                /* Chama Teeth Marker Multi */
            window.addEvent('domready',function(){ teeth_multi_mark(); slide_blocks();});
        
                /*var select_matchcode = function(){
                    var md = new Modal(['modal']);
                    md.setHeader('Selecionar Match Code');
                    var fm = new Form('form_e',['dummy.php','post'],'field');
                    fm.addEvent('success',function(){
                        recarrega_div('div_list_match_code');
                        (function(){this.fadeAndRemove()}.bind(this)).delay(500);
                    }.bind(md));
                    fm.attach(md.win);
        
                    fm.injectBlock("<?php //echo '<ul>'.$lista_trat.$lista_retrat.'</ul>'; ?>");
        
                    fm.attachSendBar(['Selecionar','mb'], false);
        
                    md.show();
        
                    for(i=0; i<conj_trat_match_codes.length;i++){
                        $('_' + conj_trat_match_codes[i]).set('checked', true);
                    }
                    for(i=0; i<conj_retrat_match_codes.length;i++){
                        $('_' + conj_retrat_match_codes[i]).set('checked', true);
                    }
            }*/
        
            function carrega_doc(valor){
                document.cd_field2.id_tratamento.value = valor;
                document.cd_field2.submit();
            }
        
            function recarrega_div(div){
                xhSendPost('../controladores/AJAX.estatistica_match_code.php?div_id='+ div + '&list_trat=' + conj_trat_match_codes + "&list_retrat=" +conj_retrat_match_codes );
            }
        
            function removeTratMatchCode(valor, status){
                arrayTratMatchCode(valor, status);
                recarrega_div('div_list_match_code');
            }
        
            function removeRetratMatchCode(valor, status){
                arrayRetratMatchCode(valor, status);
                recarrega_div('div_list_match_code');
            }
        
            function arrayTratMatchCode(valor, status){
                if(status){
                    conj_trat_match_codes.include(valor);
                } else {
                    conj_trat_match_codes.erase(valor);
                }
            }
            function arrayRetratMatchCode(valor, status){
                if(status){
                    conj_retrat_match_codes.include(valor);
                } else {
                    conj_retrat_match_codes.erase(valor);
                }
            }
        
            function abrir_popUp(suc,insuc,pend,canc){
               caminho="grafico.php?sucesso="+suc;
               caminho+="&insucesso="+insuc;
               caminho+="&pendente="+pend;
               caminho+="&cancelado="+canc;
               caminho+="&suc_acv=1&ins_acv=1&pend_acv=1&canc_acv=1"
               window.open(caminho,"janela", "height=650,width=800,toolbar=0,location=0,directories=0,menubar=0,scrollbars=0,resizable=0");
            }
        
            //Executar Filtro
            function executa_filtro(pag){
                codes = new Array();
                if(document.form1.matchcode_active.checked==true){
                <?php
                    if($codes != ""){
                        ?>
                        codes.include(<?php echo $codes; ?>);
                        <?php
                    }
                ?>
                }
                if(conj_trat_match_codes!='')
                    codes.include(conj_trat_match_codes);
                if(conj_retrat_match_codes!='')
                    codes.include(conj_retrat_match_codes);
        
                caminho = "../controladores/AJAX.estatistica_geral.php?codes=" + codes;
        
                qtd_pag = <?php echo $qtdpag; ?>;
        
                if(document.grid.qnt_pag !=null) 
                    caminho += "&qtdpag=" + document.grid.qnt_pag.value;
                else {
                    if(qtd_pag != "")
                        caminho += "&qtdpag=" + qtd_pag;
                }
        
                if(pag!=null){
                    caminho += "&pag=" + pag;
                }      
                
                xhSendPost2(caminho, document.form1);
            }
            
            //Calendario
                window.addEvent('domready', function() { 	
                    dataI = new Calendar({ inicio_tratamento: 'd/m/Y' }, { direction: -1, tweak: {x: 6, y: 0}});
                    dataF = new Calendar({ fim_tratamento:    'd/m/Y' }, { direction: -1, tweak: {x: 6, y: 0}});
                });
            
            //Funcoes
            function limpar_filtros(){
                //document.form1.match_code.set('value', "<?php echo $matchcode->getDescricao()?>");
                document.form1.matchcode_active.checked=false;
                enable_matchcode();
                
                
                document.form1.masculino.checked=true;
                document.form1.sexo_active.checked=false;
                enable_sexo();
                
                document.form1.idade_min.set('value',0);
                document.form1.idade_max.set('value',100);
                document.form1.idade_active.checked=false;
                enable_idade();
                
                document.form1.inicio_tratamento.value="";
                document.form1.dataIn_active.checked=false;
                enable_dtinicio();
                
                document.form1.fim_tratamento.value="";
                document.form1.dataFim_active.checked=false;
                enable_dtfim();
                
                document.form1.resultado.set('value',0);
                document.form1.resultado_active.checked=false;
                enable_result();		
            }
            
            function enable_matchcode() {
                    if(document.form1.matchcode_active.checked==true){
                       //document.form1.match_code.disabled=false;
                       updateCampo('matchcode');
                    }else{
                       //document.form1.match_code.disabled=true;
                       document.form1.matchcode_hd.value=-1;
                    }
            }
            function enable_sexo(){
               if(document.form1.sexo_active.checked==true){
                  document.form1.masculino.disabled=false;
                  document.form1.feminino.disabled=false;
                  updateCampo('sexo');
               }
               else{
                  document.form1.masculino.disabled=true;
                  document.form1.feminino.disabled=true;
                  document.form1.sexo_hd.value=-1;
               }
               
            }
            function enable_idade(){
               if(document.form1.idade_active.checked==true){
                  document.form1.idade_min.disabled=false;
                  document.form1.idade_max.disabled=false;
                  updateCampo('idade');
               }
               else{
                  document.form1.idade_min.disabled=true;
                  document.form1.idade_max.disabled=true;
                  document.form1.idademin_hd.value=-1;
                  document.form1.idademax_hd.value=-1;
               }
            }
            function enable_dtinicio(){
               if(document.form1.dataIn_active.checked==true){
                 document.form1.inicio_tratamento.disabled=false;
                 updateCampo('inicio_tratamento');
               }
               else{
                    document.form1.inicio_tratamento.disabled=true;
                    document.form1.dtinicio_hd.value=-1;
               }
        
            }
            function enable_dtfim(){
               if(document.form1.dataFim_active.checked==true){
                  document.form1.fim_tratamento.disabled=false;
                  updateCampo('dataFim');
               }
               else{
                  document.form1.fim_tratamento.disabled=true;
                  document.form1.dtfim_hd.value=-1;
               }
            }
            function enable_result(){
               if(document.form1.resultado_active.checked==true)
               {
                  document.form1.resultado.disabled=false;
                  updateCampo('resultado');
               } else{
                  document.form1.resultado.disabled=true;
                  document.form1.sucesso_hd.value=-1;
               }
            }
            function valida_idade(){
               if(parseInt(document.form1.idade_min.value)>parseInt(document.form1.idade_max.value)){
                  alert("A idade mínima deve ser menor que a máxima.");
                  return false;
               }
               return true;
            }
        
            function valida_dtInicio(){
              if(valida_data(document.form1.inicio_tratamento)==true)
                 if(document.form1.dataFim_active.checked==true)
                    if(comparaDataMenor(document.form1.inicio_tratamento.value,document.form1.fim_tratamento.value)==false)
                    alert("A data inicial deve ser menor que a data final");
            }
        
            function valida_dtFim(){
              if(valida_data(document.form1.fim_tratamento)==true)
                 if(document.form1.dataIn_active.checked==true)
                    if(comparaDataMenor(document.form1.inicio_tratamento.value,document.form1.fim_tratamento.value)==false)
                    alert("A data final deve ser maior que a data inicial");
            }
        
            function updateCampo(campo){
               switch(campo){
                   case 'matchcode':
                    //document.getElementsById('matchcode_hd').value =;
                    /*
                     *
                    conj_match_codes = "" + conj_trat_match_codes;
                    if((conj_match_codes != "") && (conj_retrat_match_codes != ""))
                    {
                        conj_match_codes += ","
                    }
                    conj_match_codes += conj_retrat_match_codes;
            
                    document.form1.matchcode_hd.set('value',conj_match_codes);
                    alert(document.form1.matchcode_hd.get('value'));
                    */
                    //document.form1.matchcode_hd.set('value',codes);
                        break;
                   case 'sexo':
                      if(document.form1.feminino.checked==true)
                         document.form1.sexo_hd.value='F';
                      else
                         document.form1.sexo_hd.value='M';
                      break;
                   case 'idade':
                      document.form1.idademin_hd.value=document.form1.idade_min.value;
                      document.form1.idademax_hd.value=document.form1.idade_max.value;
                      break;
                   case 'inicio_tratamento':
                      var data=document.form1.inicio_tratamento.value;
                      data=data.split("/");
                      data=data[2]+'-'+data[1]+'-'+data[0];
                      document.form1.dtinicio_hd.value=data;
                      break;
                   case 'dataFim':
                      var data=document.form1.fim_tratamento.value;
                      data=data.split("/");
                      data=data[2]+'-'+data[1]+'-'+data[0];
                      document.form1.dtfim_hd.value=data;
                      break;
                   case 'resultado':
                      document.form1.sucesso_hd.value=document.form1.resultado.value;
                      break;
               }
            }
            executa_filtro();
        </script>
    </body>
</html>