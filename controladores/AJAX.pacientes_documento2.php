<?php
	include_once("../funcoes/common.php");
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classDocumentos.php");
	include_once("../classes/classTabela.php");
        include_once("../classes/classTemplateProntuario.php");

	echo $_GET['div_id'].'|;|';
	
	if($_GET['acao'] == "excluir") {
		$id_documento = $_GET['id_doc'];
		$id_paciente = $_GET['$id_paciente'];
		#Instanciando objeto Documento
		$documento = new Documentos($id_documento);
		$caminho = "../documentos/pacientes/".$id_paciente."/outros_documentos/".$documento->getImagemCaminho();
	
		if($documento->bDelete()) {
                    if (file_exists ( $caminho )){
			unlink($caminho);
                    }
		}
	}
        elseif($_GET['acao'] == "recarrega_combo"){
			carrega_combobox($_GET['combo']);
	}
	elseif($_GET['acao'] == "nova_receita") {
		
            $id_documento = $_GET['id_doc'];
            $id_paciente = $_GET['$id_paciente'];
                
            /*$id_template = $_GET['id_template'];
                $id_paciente = $_GET['$id_paciente'];
		#Instanciando objeto TemplateProntuario
                $oReceita = new TemplateProntuario( $id_template );
                $text.=  str_pad($oReceita->getMedicamento(),  30 , "-")."--"; 
                $text.=  str_pad($oReceita->getConcentracao(), 20 , "-")."--";                     
                $text.=  str_pad($oReceita->getQuantidade(), 20 , "-")."\r\n"; 
                $text.=  $oReceita->getInstrucao()."<br>";
                $text.=  $oReceita->getObservacao()."<br><br>";*/

                //$receita_atual = document.getElementById("prontuario_documento").value;
               // $nova_receita = receita_atual + $text;
                //receita_atual.concat(template_receita);
                //document.getElementById("prontuario_documento").innerHTML = $nova_receita;
	}
        
	$sql = "SELECT * FROM documentos WHERE id_pessoa=".$id_paciente;
	
	$pers = new Persistencia();
	$pers->bExecute($sql);
	
	$html_tabela_doc = '
				<table title="Lista de Documentos do paciente" summary="Lista dos documentos do paciente" style="margin-bottom:40px;">
						<thead>
							<tr>
								<th class="numero">Número</th>
								<th>Descrição / Receituário </th>
								<th class="data">Data do documento</th>
								<th class="data">Data de cadastro</th>
								<th class="operacoes">Operações</th>
							</tr>
						</thead>
						<tbody>
			';
	
	$cont = 0;
		if(!$pers->getDbNumRows() > 0) {
			$html_tabela_doc .= '<tr>
						<td colspan="4" style="text-align:center;">Nenhum documento!</td>
							   </tr>';
		} else {
			while($cont < $pers->getDbNumRows()) {
				$pers->bCarregaRegistroPorLinha($cont);
				$result = $pers->getDbArrayDados();
		
						if($cont % 2 == 0){
					$cor_linha_tabela = "tableColor1";
				}else{
					$cor_linha_tabela = "tableColor2";
				}
			
				$html_tabela_doc .= '
					<tr class="'.$cor_linha_tabela.'">
						<td class="numero">'.$result['id_documento'].'</td>
						<td class="observacoes">'.utf8_encode($result['observacoes']).'</td>
                                                <td class="receituario">'.utf8_encode($result['receituario']).'</td>
						<td class="data">'.decodeDate($result['data_documento']).'</td>
						<td class="data">'.decodeDate(substr($result['data_cadastro'], 0, 10)).'</td>
						<td class="operacoes">
							<span style="display:block; margin:auto; width:80px;">
								<a class="visualizarDocumentos ir" href="#div_doc_a" onclick="visualiza_doc(\''.$result['imagem_caminho'].'\','.$id_paciente.', \''.decodeDate($result['data_documento']).'\', \''.decodeDate(substr($result['data_cadastro'],0, 10)).'\')" title="Visualizar Documento">Visualizar Documento</a>
								<a class="download ir" href="#div_doc_a" onclick="download_doc(\''.$result['imagem_caminho'].'\','.$id_paciente.', 1, '.$result['id_documento'].')" title="Download do Documento">Download</a>
								<a class="excluir ir" href="#div_doc_a" onclick="remove_doc('.$result['id_documento'].');" title="Excluir Documento">Excluir Documento</a>
							</span>
						</td>
					</tr>
				';
				$cont++;
			}
		}
	$html_tabela_doc .= '</tbody></table>';

	echo $html_tabela_doc;
        
        
        
        
        function carrega_combobox($tipo){
		$combo = new Combo();
		
		$paciente = new Paciente();
		
		if($tipo == "$template_tratamento"){
			echo '<label for="plano">$ENUM_SELECIONE_TEMPLATE</label>';
                        $sSql = "SELECT `id`, `instancia`, `id_usuario`, `id_especialidade`, `nomeTemplate`, `medicamento`, `concentracao`, `quantidade`, `instrucao`, `observacao`, `compartilhamento` FROM templateprontuario ";
                        //$sSql .= "   WHERE (instancia =".$instancia.")  and ((id_usuario=".$prof.") OR  ( compartilhamento = 3) OR (id_especialidade = ".$$especialidade." and compartilhamento = 2))";  
                        $combo->bAddItemCombo("-1",$ENUM_SELECIONE_TEMPLATE);
			$combo_template_tratamento = "&nbsp;".$combo->sGetHTML($sSql,'tratamento','id', 'nomeTemplate','-','onChange="atualiza(this.value)"','');
                       

			//echo '<button class="espacamentoEsquerda" type="button" onclick="cadastro_plano();">Cadastrar novo Plano de Saúde</button>';
			
		}

	}
	
?>

