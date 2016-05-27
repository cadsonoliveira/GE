<?php
	include_once("../funcoes/common.php");
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classDocumentos.php");
	include_once("../classes/classTabela.php");

	echo $_GET['div_id'].'|;|';
	
	if($_GET['acao'] == "excluir") {
		$id_documento = $_GET['id_doc'];
		$id_paciente = $_GET['id_paciente'];
		#Instanciando objeto Documento
		$documento = new Documentos($id_documento);
		$caminho = "../documentos/pacientes/".$id_paciente."/outros_documentos/".$documento->getImagemCaminho();
	
		if($documento->bDelete()) {
                    if (file_exists ( $caminho )){
			unlink($caminho);                       
                    }
		}
	}
	
	$sql = "SELECT * FROM documentos WHERE id_pessoa=".$id_paciente;
	
	$pers = new Persistencia();
	$pers->bExecute($sql);
	
	$html_tabela_doc = '
				<table title="Lista de Documentos do paciente" summary="Lista dos documentos do paciente" style="margin-bottom:40px;">
						<thead>
							<tr>
								<th class="numero">Número</th>
								<th>Observações</th>
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
	
?>
