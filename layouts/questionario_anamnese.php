<?php
	//Antiga página anamnese_cadastro.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    }

	include_once ("../classes/classPaciente.php");
	include_once("../classes/classAnamnese.php");

	$str_saudavel_sim = '';
	$str_saudavel_nao = '';
	$str_saudavel_mais = '';

	$str_visitas_medico_sim = '';
	$str_visitas_medico_nao = '';
	$str_visitas_medico_mais = '';

	$str_medicacao_rotina_sim = '';
	$str_medicacao_rotina_nao = '';
	$str_medicacao_rotina_mais = '';

	$str_alergias_sim = '';
	$str_alergias_nao = '';
	$str_alergias_mais = '';

	$str_doencas_previas_sim = '';
	$str_doencas_previas_nao = '';
	$str_doencas_previas_mais = '';

	$str_cirurgias_previas_sim = '';
	$str_cirurgias_previas_nao = '';
	$str_cirurgias_previas_mais = '';

	$str_doenca_hepatite_sim = '';
	$str_doenca_diabetis_sim = '';
	$str_doenca_febre_reumatica_sim = '';
	$str_doenca_arterial_sim = '';
	$str_doenca_pressao_arterial_sim = '';

	$str_acidentes_sim = '';
	$str_acidentes_nao = '';
	$str_acidentes_mais = '';
	
	$str_dificuldade_abertura_boca_sim = '';
	$str_dificuldade_abertura_boca_nao = '';
			
	$str_dificuldade_salivacao_sim = '';
	$str_dificuldade_salivacao_nao = '';
			
	$str_dificuldade_ansia_vomito_sim = '';
	$str_dificuldade_ansia_vomito_nao = '';
			
	$str_dificuldade_dor_costas_sim = '';
	$str_dificuldade_dor_costas_nao = '';
			
	$str_traumatismo_dentario_sim = '';
	$str_traumatismo_dentario_nao = '';

	$str_recomendacao_medicas = '';

	$str_restricao_medicamentos_sim = '';
	$str_restricao_medicamentos_nao = '';
	$str_restricao_medicamentos_mais = '';

	$str_visitas_regulares_sim = '';
	$str_visitas_regulares_nao = '';
	
	$str_tempo_ultimo_tratamento = '';

	$str_info_adicional = '';
	
	$id_anamnese = '0';
	
	if(isset($_GET['id']) && isset($_GET['acao'])){		
		if($_GET['acao']=='editar'){
			$id_pessoa = $_GET['id'];
			$anamnese = new Anamnese();
			$anamnese->getAnamneseByPaciente($id_pessoa);
			$id_anamnese = $anamnese->getId();
			
			$str_saudavel_sim = ($anamnese->getBoaSaude()=='S') ? 'checked="true"' : '';
			$str_saudavel_nao = ($anamnese->getBoaSaude()=='N') ? 'checked="true"' : '';
			$str_saudavel_mais = utf8_encode($anamnese->getBoaSaudePorque());

			$str_visitas_medico_sim = ($anamnese->getVisitasFreqMedico()=='S') ? 'checked="true"' : '';
			$str_visitas_medico_nao = ($anamnese->getVisitasFreqMedico()=='N') ? 'checked="true"' : '';
			$str_visitas_medico_mais = utf8_encode($anamnese->getVisitasFreqMedicoMotivo());

			$str_medicacao_rotina_sim = ($anamnese->getMedicacaoRotina()=='S') ? 'checked="true"' : '';
			$str_medicacao_rotina_nao = ($anamnese->getMedicacaoRotina()=='N') ? 'checked="true"' : '';
			$str_medicacao_rotina_mais = utf8_encode($anamnese->getMedicacaoRotinaQual());

			$str_alergias_sim = ($anamnese->getAlergia()=='S') ? 'checked="true"' : '';
			$str_alergias_nao = ($anamnese->getAlergia()=='N') ? 'checked="true"' : '';
			$str_alergias_mais = utf8_encode($anamnese->getAlergiaDeQue());

			$str_doencas_previas_sim = ($anamnese->getDoencasPrevias()=='S') ? 'checked="true"' : '';
			$str_doencas_previas_nao = ($anamnese->getDoencasPrevias()=='N') ? 'checked="true"' : '';
			$str_doencas_previas_mais = utf8_encode($anamnese->getDoencasPreviasQual());

			$str_cirurgias_previas_sim = ($anamnese->getCirurgiasPrevias()=='S') ? 'checked="true"' : '';
			$str_cirurgias_previas_nao = ($anamnese->getCirurgiasPrevias()=='N') ? 'checked="true"' : '';
			$str_cirurgias_previas_mais = utf8_encode($anamnese->getCirurgiasPreviasQual());
			
			$str_doenca_hepatite_sim = ($anamnese->getHepatite()=='S') ? 'checked="true"' : '';
			$str_doenca_diabetis_sim = ($anamnese->getDiabetis()=='S') ? 'checked="true"' : '';
			$str_doenca_febre_reumatica_sim = ($anamnese->getFebreReumatica()=='S') ? 'checked="true"' : '';
			$str_doenca_arterial_sim = ($anamnese->getDoencasArterial()=='S') ? 'checked="true"' : '';
			$str_doenca_pressao_arterial_sim = ($anamnese->getPressaoArterial()=='S') ? 'checked="true"' : '';
		
			$str_acidentes_sim = ($anamnese->getAcidFraturasOdont()=='S') ? 'checked="true"' : '';
			$str_acidentes_nao = ($anamnese->getAcidFraturasOdont()=='N') ? 'checked="true"' : '';
			$str_acidentes_mais = utf8_encode($anamnese->getAcidFraturasOdontQual());
			
			$str_dificuldade_abertura_boca_sim = ($anamnese->getDificAbertBoca()=='S') ? 'checked="true"' : '';
			$str_dificuldade_abertura_boca_nao = ($anamnese->getDificAbertBoca()=='N') ? 'checked="true"' : '';
					
			$str_dificuldade_salivacao_sim = ($anamnese->getMuitaSalivacao()=='S') ? 'checked="true"' : '';
			$str_dificuldade_salivacao_nao = ($anamnese->getMuitaSalivacao()=='N') ? 'checked="true"' : '';
					
			$str_dificuldade_ansia_vomito_sim = ($anamnese->getAnsiaVomito()=='S') ? 'checked="true"' : '';
			$str_dificuldade_ansia_vomito_nao = ($anamnese->getAnsiaVomito()=='N') ? 'checked="true"' : '';
					
			$str_dificuldade_dor_costas_sim = ($anamnese->getDorNasCostas()=='S') ? 'checked="true"' : '';
			$str_dificuldade_dor_costas_nao = ($anamnese->getDorNasCostas()=='N') ? 'checked="true"' : '';
					
			$str_traumatismo_dentario_sim = ($anamnese->getTraumatismoDentario()=='S') ? 'checked="true"' : '';
			$str_traumatismo_dentario_nao = ($anamnese->getTraumatismoDentario()=='N') ? 'checked="true"' : '';

			$str_recomendacao_medicas = utf8_encode($anamnese->getRecomendacaoMedica());

			$str_restricao_medicamentos_sim = ($anamnese->getRestricaoMedicamentos()=='S') ? 'checked="true"' : '';
			$str_restricao_medicamentos_nao = ($anamnese->getRestricaoMedicamentos()=='N') ? 'checked="true"' : '';
			$str_restricao_medicamentos_mais = utf8_encode($anamnese->getRestricaoMedicamentosQual());

			$str_visitas_regulares_sim = ($anamnese->getVisitaRegularDent()=='S') ? 'checked="true"' : '';
			$str_visitas_regulares_nao = ($anamnese->getVisitaRegularDent()=='N') ? 'checked="true"' : '';
			
			$str_tempo_ultimo_tratamento = $anamnese->getUltimoTratOdont();

			$str_info_adicional = utf8_encode($anamnese->getInfAdicionalImportante());
		}
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
    <div id="conteudo">
        <?php include_once("include/dados_paciente.php") ?>
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php" title="lista pacientes">pacientes</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">questionário anamnese</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
    
            <div id="container" class="clearfix">
                <form action="../controladores/anamneseGravar.php" method="post" name="cd_field" id="cd_field">
                    <input type="hidden" name="id_pessoa" value="<?php echo $_SESSION['PACIENTE']['ID']; ?>" />
                    <input type="hidden" name="id_anamnese" value="<?php echo $id_anamnese; ?>" />
                    
    
                    <h3 class="tituloBox">Dados Pessoais</h3>
                    <div class="formularioDividido">
                        <fieldset class="more"><legend>Apresenta boa Saúde?</legend>
                            <input id="apresenta_boa_saude" name="saudavel" type="radio" value="S"  <?php echo $str_saudavel_sim; ?> />
                            <label for="apresenta_boa_saude">Sim</label>
                            <input id="nao_apresenta_boa_saude" alt="sel" name="saudavel" type="radio" value="N" <?php echo $str_saudavel_nao; ?> />
                            <label for="nao_apresenta_boa_saude">Não</label>
                            <span id="saudavel_mais">
                            <label class="elementosFormulario" for="porque_nao_apresenta_boa_saude">Porquê?</label>
                            <textarea id="porque_nao_apresenta_boa_saude" name="saudavel_mais" cols="50" rows="4"><?php echo $str_saudavel_mais; ?></textarea>
                            </span>
                        </fieldset>
                        
                        <fieldset class="more double"><legend>Visitas frequentes ao médico?</legend>
                            <input id="visita_frequente_medico" alt="sel" name="visitas_medico" type="radio" value="S" <?php echo $str_visitas_medico_sim; ?> />
                            <label for="visita_frequente_medico">Sim</label>
                            <input id="nao_visita_frequente_medico" name="visitas_medico" type="radio" value="N" <?php echo $str_visitas_medico_nao; ?> />
                            <label for="nao_visita_frequente_medico">Não</label>
                            <span id="visitas_medico_mais">
                                <label class="elementosFormulario" for="motivo_visitas_medico">Motivo?</label>
                                <textarea id="motivo_visitas_medico" name="visitas_medico_mais" cols="50" rows="4"><?php echo $str_visitas_medico_mais; ?></textarea>
                            </span>
                        </fieldset>
                        
                        <fieldset class="more double"><legend>Utiliza alguma medicação de rotina?</legend>
                          <input id="utiliza_medicacao_rotina" alt="sel" name="medicacao_rotina" type="radio" value="S" <?php echo $str_medicacao_rotina_sim; ?> />
                          <label for="utiliza_medicacao_rotina">Sim</label>
                          <input id="nao_utiliza_medicacao_rotina" name="medicacao_rotina" type="radio" value="N" <?php echo $str_medicacao_rotina_nao; ?> />
                          <label for="nao_utiliza_medicacao_rotina">Não</label>
                          <span id="medicacao_rotina_mais">
                            <label class="elementosFormulario" for="utiliza_qual_medicacao">Qual(is)?</label>
                            <textarea id="utiliza_qual_medicacao" name="medicacao_rotina_mais" cols="50" rows="4"><?php echo $str_medicacao_rotina_mais; ?></textarea>
                          </span>
                        </fieldset>
                        
                        <fieldset class="more"><legend>Alergias?</legend>
                          <input id="alergico" alt="sel" name="alergias" type="radio" value="S" <?php echo $str_alergias_sim; ?> />
                          <label for="alergico">Sim</label>
                          <input id="sem_alergia" name="alergias" type="radio" value="N" <?php echo $str_alergias_nao; ?> />
                          <label for="sem_alergia">Não</label>
                          <span id="alergias_mais">
                            <label class="elementosFormulario" for="alergia_de_que">De Quê?</label>
                            <textarea id="alergia_de_que" name="alergias_mais" cols="50" rows="4"><?php echo $str_alergias_mais; ?></textarea>
                          </span>
                        </fieldset>
                        
                        <fieldset class="more"><legend>Doenças Prévias?</legend>
                          <input id="tem_doenca_previa" alt="sel" name="doencas_previas" type="radio" value="S" <?php echo $str_doencas_previas_sim; ?> />
                          <label for="tem_doenca_previa">Sim</label>
                          <input id="nao_tem_doenca_previa" name="doencas_previas" type="radio" value="N" <?php echo $str_doencas_previas_nao; ?> />
                          <label for="nao_tem_doenca_previa">Não</label>
                          <span id="doencas_previas_mais">
                            <label class="elementosFormulario" for="qual_doenca_previa">Qual(is)?</label>
                            <textarea id="qual_doenca_previa" name="doencas_previas_mais" cols="50" rows="4"><?php echo $str_doencas_previas_mais; ?></textarea>
                          </span>
                        </fieldset>
                        
                        <fieldset class="more"><legend>Cirurgias Prévias?</legend>
                            <input id="fez_cirugia" alt="sel" name="cirurgias_previas" type="radio" value="S" <?php echo $str_cirurgias_previas_sim; ?> />
                            <label for="fez_cirugia">Sim</label>
                            <input id="nao_fez_cirugia" name="cirurgias_previas" type="radio" value="N" <?php echo $str_cirurgias_previas_nao; ?> />
                            <label for="nao_fez_cirugia">Não</label>
                            <span id="cirurgias_previas_mais">
                                <label class="elementosFormulario" for="qual_cirugia_previa">Qual(is)?</label>
                                <textarea id="qual_cirugia_previa" name="cirurgias_previas_mais" cols="50" rows="4"><?php echo $str_cirurgias_previas_mais; ?></textarea>
                            </span>
                        </fieldset>
                        
                        <fieldset><legend>Já apresentou alguma das doenças:</legend>
                          <input id="hepatite" name="doenca_hepatite" type="checkbox" value="S"  <?php echo $str_doenca_hepatite_sim; ?> />
                          <label for="hepatite">Hepatite</label>
                          <input id="diabetes" name="doenca_diabetis" type="checkbox" value="S" <?php echo $str_doenca_diabetis_sim; ?> />
                          <label for="diabetes">Diabetes</label>
                          <input id="febre_reumatica" name="doenca_febre_reumatica" type="checkbox" value="S" <?php echo $str_doenca_febre_reumatica_sim; ?> />
                          <label for="febre_reumatica">Febre Reumática</label>
                          <input id="doenca_arterial" name="doenca_arterial" type="checkbox" value="S" <?php echo $str_doenca_arterial_sim; ?> />
                          <label for="doenca_arterial">Doença Arterial</label>
                          <input id="pressao_arterial" name="doenca_pressao_arterial" type="checkbox" value="S" <?php echo $str_doenca_pressao_arterial_sim; ?> />
                          <label for="pressao_arterial">Pressão Arterial</label>
                        </fieldset>
                        
                        <fieldset class="more double"><legend>Acidentes e Fraturas odontológicas?</legend>
                            <input id="sofreu_acidente_fratura_odontologica" alt="sel" name="acidentes" type="radio" value="S" <?php echo $str_acidentes_sim; ?> />
                            <label for="sofreu_acidente_fratura_odontologica">Sim</label>
                            <input id="nao_sofreu_acidente_fratura_odontologica" name="acidentes" type="radio" value="N" <?php echo $str_acidentes_nao; ?> />
                            <label for="nao_sofreu_acidente_fratura_odontologica">Não</label>
                            <span id="acidentes_mais">
                                <label class="elementosFormulario" for="qual_acidente_fratura_odontologica">Qual(is)?</label>
                                <textarea id="qual_acidente_fratura_odontologica" name="acidentes_mais" cols="50" rows="4"><?php echo $str_acidentes_mais; ?></textarea>
                            </span> 
                        </fieldset>      
                    </fieldset>
                    </div><!--formularioDividido-->
                    
                    <h3 class="tituloBox">Dificuldades ao tratamento odontológico</h3>
            
                    <div class="formularioDividido">
                        <fieldset><legend>Dificuldade de abertura de boca?</legend>
                            <input id="dificuldade_abertura_boca" name="dificuldade_abertura_boca" type="radio" value="S" <?php echo $str_dificuldade_abertura_boca_sim; ?> />
                            <label for="dificuldade_abertura_boca">Sim</label>
                            <input id="sem_dificuldade_abertura_boca" name="dificuldade_abertura_boca" type="radio" value="N" <?php echo $str_dificuldade_abertura_boca_nao; ?> />
                            <label for="sem_dificuldade_abertura_boca">Não</label>
                        </fieldset>
                        
                        <fieldset><legend>Muita salivação?</legend>
                            <input id="saliva_muito" name="dificuldade_salivacao" type="radio" value="S" <?php echo $str_dificuldade_salivacao_sim; ?> />
                            <label for="saliva_muito">Sim</label>
                            <input id="nao_saliva_muito" name="dificuldade_salivacao" type="radio" value="N" <?php echo $str_dificuldade_salivacao_nao; ?> />
                            <label for="nao_saliva_muito">Não</label>
                        </fieldset>
                        
                        <fieldset><legend>Muita ânsia de vômito?</legend>
                            <input id="tem_ansia_vomito" name="dificuldade_ansia_vomito" type="radio" value="S" <?php echo $str_dificuldade_ansia_vomito_sim; ?> />
                            <label for="tem_ansia_vomito">Sim</label>
                            <input id="nao_tem_ansia_vomito" name="dificuldade_ansia_vomito" type="radio" value="N" <?php echo $str_dificuldade_ansia_vomito_nao; ?> />
                            <label for="nao_tem_ansia_vomito">Não</label>
                        </fieldset>
                        
                        <fieldset><legend>Dor nas costas?</legend>
                            <input id="tem_dor_costas" name="dificuldade_dor_costas" type="radio" value="S" <?php echo $str_dificuldade_dor_costas_sim; ?> />
                            <label for="tem_dor_costas">Sim</label>
                            <input id="nao_tem_dor_costas" name="dificuldade_dor_costas" type="radio" value="N" <?php echo $str_dificuldade_dor_costas_nao; ?> />
                            <label for="nao_tem_dor_costas">Não</label>
                        </fieldset>
                        
                        <fieldset><legend>Já sofreu algum tipo de traumatismo dentário?</legend>
                            <input id="sofreu_traumatismo_dentario" name="traumatismo_dentario" type="radio" value="S" <?php echo $str_traumatismo_dentario_sim; ?> />
                            <label for="sofreu_traumatismo_dentario">Sim</label>
                            <input id="nao_sofreu_traumatismo_dentario" name="traumatismo_dentario" type="radio" value="N" <?php echo $str_traumatismo_dentario_nao; ?> />
                            <label for="nao_sofreu_traumatismo_dentario">Não</label>
                        </fieldset>
                        
                        <fieldset>
                            <label class="elementosFormulario" for="recomendacao_medica">Alguma recomendação médica?</label>
                            <textarea id="recomendacao_medica" name="recomendacao_medica" cols="50" rows="4"><?php echo $str_recomendacao_medicas; ?></textarea>
                        </fieldset>
                        
                        <fieldset class="more double"><legend>Restrição à medicamentos?</legend>
                            <input id="tem_restricao_medicamento" alt="sel" name="restricao_medicamentos" type="radio" value="S" <?php echo $str_restricao_medicamentos_sim; ?> />
                            <label for="tem_restricao_medicamento">Sim</label>
                            <input id="nao_tem_restricao_medicamento" name="restricao_medicamentos" type="radio" value="N" <?php echo $str_restricao_medicamentos_nao; ?> />
                            <label for="nao_tem_restricao_medicamento">Não</label>
                            <span id="restricao_medicamentos_mais">
                                <label class="elementosFormulario" for="qual_medicamento_restrito">Qual(is)?</label>
                                <textarea id="qual_medicamento_restrito" name="restricao_medicamentos_mais" cols="50" rows="4"><?php echo $str_restricao_medicamentos_mais; ?></textarea>
                            </span>
                        </fieldset>
                        
                        <fieldset><legend>Visitas regulares ao dentista?</legend>
                            <input id="visita_regularmente_dentista" name="visitas_regulares" type="radio" value="S" <?php echo $str_visitas_regulares_sim; ?> />
                            <label for="visita_regularmente_dentista">Sim</label>
                            <input id="nao_visita_regularmente_dentista" name="visitas_regulares" type="radio" value="N" <?php echo $str_visitas_regulares_nao; ?> />
                            <label for="nao_visita_regularmente_dentista">Não</label>
                        </fieldset>
                        
                        <fieldset>
                            <label class="elementosFormulario" for="ultimo_tratamento_odontologico">Último tratamento odontológico:</label>
                            <select id="ultimo_tratamento_odontologico" name="tempo_ultimo_tratamento">
                              <option value="<?php echo $str_tempo_ultimo_tratamento; ?>">
                                    <?php if($str_tempo_ultimo_tratamento==""){echo("----");}
                                        else{
                                          if($str_tempo_ultimo_tratamento == "12"){
                                               echo("1 ano");
                                           }else if($str_tempo_ultimo_tratamento == "13"){
                                               echo("Mais de 1 ano");
                                           }else if($str_tempo_ultimo_tratamento == "24"){
                                               echo("Mais de 2 ano");
                                           }else if($str_tempo_ultimo_tratamento == "36"){
                                               echo("Mais de 3 ano");
                                           }else if($str_tempo_ultimo_tratamento == "48"){
                                               echo("Mais de 4 ano");
                                           }else if($str_tempo_ultimo_tratamento == "60"){
                                               echo("Mais de 5 ano");
                                           } else{
                                               echo($str_tempo_ultimo_tratamento." meses");
                                           }
                                        }
                                    ?>
                              </option>
                              <option value="1">1 mês</option>
                              <option value="2">2 meses</option>
                              <option value="3">3 meses</option>
                              <option value="4">4 meses</option>
                              <option value="5">5 meses</option>
                              <option value="6">6 meses</option>
                              <option value="7">7 meses</option>
                              <option value="8">8 meses</option>
                              <option value="9">9 meses</option>
                              <option value="10">10 meses</option>
                              <option value="11">11 meses</option>
                              <option value="12">1 ano</option>
                              <option value="13">Mais de 1 ano</option>
                              <option value="24">Mais de 2 ano</option>
                              <option value="36">Mais de 3 ano</option>
                              <option value="48">Mais de 4 ano</option>
                              <option value="60">Mais de 5 ano</option>
                            </select>
                        </fieldset>
                        
                        <fieldset>
                            <label class="elementosFormulario" for="informacao_adicional"> Alguma informação adicional que considera importante?</label>
                            <textarea id="informacao_adicional" name="info_adicional" cols="50" rows="4"><?php echo $str_info_adicional; ?></textarea>
                        </fieldset>                         
                    </div><!--formularioDividido-->
                    
                    <p id="botoesFormulario">
                        <button id="botaoNegativo" onClick="location.href='pacientes.php'" type="button">Cancelar</button>
                        <button class="botaoPositivo" type="button" onClick="valida_campos();">Salvar Questionário</button>
                    </p>
                </form>
			<?php include_once("include/footer.php") ?>
    
		<script src="js/libs/mootools-core-1.4.5-full-compat.js" type="text/javascript"></script>
        <script src="js/base.js" type="text/javascript"></script>
        <script src="js/common.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/micoxUpload.js"></script>
        <script type="text/javascript">
                window.addEvent('domready',function(){
                    $$('.more').each(function(a){
                        _elements = a.getElements('input');
                        _toggler = null;
                        for(i=0; i<_elements.length; i++) if(_elements[i].get('alt')=='sel') _toggler = _elements[i];
                        if(_toggler == null) return false;
        
                        $(_toggler.get('name')+'_mais').setStyle('display','none');
        
                        _elements.each(function(element){
                            element.addEvent('click',function(){
                                more = $(this.get('name')+'_mais');
        
                                if((this.get('checked') || this.get('selected')) && this.get('alt')=='sel')
                                    more.setStyle('display','block');
                                else
                                    more.setStyle('display','none');
                            });
                        }.bind(_toggler));
        
                        _toggler.fireEvent('click');
        
                    });
                });
            
            function numbersOnly(obj){
                if(obj == null) return false;
                value = obj.get('value');
                reg = /\d*/;
                res = reg.exec(value);
                
                obj.set('value',res);
            }
            
            function valida_campos() {
                msg = '';
                _more = $$('.more');
                for(i=0; i<_more.length; i++){
                    a = _more[i];
                
                    _elements = a.getElements('input');
                    for(j=0; j<_elements.length; j++){
                        b = _elements[j];
                
                        if(b.get('alt') == 'sel' && b.get('checked')){
                            text = $(b.get('name')+'_mais').getElement('textarea');
                            if(text.get('value') == ''){
                                msg += ' * '+a.getElement('label').get('text')+'\n';
                                text.setStyle('border','solid 1px #f00');
                            } else {
                                text.setStyle('border','solid 1px #0f0'); //correto
                                // Se não quiser destacar basta usar essa linha ao invés da de cima: text.setStyle('border','');
                            }
                        }
                    }
                }
                if(msg == ''){
                    $('cd_field').submit();
                } else {
                    alert('Os campos:\n'+msg+'Não foram respondidos corretamente.');
                }
            }
        </script>
    </body>
</html>