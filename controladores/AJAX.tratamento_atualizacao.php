<?php
/*
 * Parâmetros POST:
 * #type:
 *      'atualizar': tem como ação retornar a lista de imagens do tratamento
 *      'remover': tem como ação remover uma imagem
 * #id_tratamento:
 *      'id_tratamento': id do tratamento da lista de imagens
 * #id_imagem:
 *      'id_imagem': id da imagem a ser removida
 *
 * REMOVER:
 *      Retornar 1 caso a imagem tenha sido removida com sucesso. Do contrátio qualquer valor é aceito e é entendido
 *      como erro.
 */
	$type = (isset($_POST['type'])) ? $_POST['type'] : "";
    //$type = (isset($_GET['type'])) ? $_GET['type'] : "";
	
	if($type == "remover"){
		include_once("../classes/classPersistencia.php");
		$pers = new Persistencia();
		$sql = "DELETE FROM imagem WHERE id_imagem=".$_POST['id_imagem'];
		
		if($pers->bExecute($sql)){
			//sucesso
			echo "1";
		} else {
			//falha
			echo "0";
		}
	} elseif($type == "atualizar") {

        //$id_tratamento = (isset($_GET['id_tratamento'])) ? $_GET['id_tratamento'] : "";
		$id_tratamento = (int)$_POST['id_tratamento'];

		/************************************************************************************
		/*LISTA AS IMAGENS DA ULTIMA CONSULTA
		/************************************************************************************/
		$diretorio = "../temp/";
		$ponteiro_diretorio = opendir($diretorio);
		$sHTML = '';
		$caminho = array();

		while ($nome_itens = readdir($ponteiro_diretorio)) {
			$itens[]=$nome_itens;
		}
		$i=0;
		foreach ($itens as $listar) {
			if(!is_dir($listar)) {
				$array = explode('.',$listar);
				if (($array[1] == 'gif') || ($array[1] == 'jpg') || ($array[1] == 'JPG') || ($array[1] == 'JPEG') || ($array[1] == 'jpeg')|| ($array[1] == 'png'|| ($array[1] == 'avi'))) {
					$caminho[$i]='../temp/'.$listar;
					$nome[$i]='img'.$i;
					$i++;
				}
			}
		}
		$res = array();
		$res['cnt'] = $i;
		$res['html'] = '';
		$res['eval'] = '';
		$already = explode(",",$_POST['selected']);
		foreach($caminho as $sPath) {
			if(in_array($sPath,$already))
				continue;
            if(substr($sPath, -3) == 'avi'){
               $res['eval'] .= 'insereVideo(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.');';
               $res['html'] .= '
			   <li id="vid'.$i.'">
				   <div class="tools video">
					   <a href="javascript:;" onClick="insereVideo(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.',true)" class="inserir ir">Inserir</a>
					   <a href="'.$sPath.'" target="blank" title="Visualizar" class="view ir">Visualizar</a>
				   </div>
				   <img src="img/video_default.gif" class="pic" />
			   </li>';
            } else {
            	$res['eval'] .= 'insereImagem(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.');';
                $res['html'] .= '
				<li id="img'.$i.'">
					<div class="tools">
						<a href="javascript:;" onclick="insereImagem(\'imagens_selecionadas\',\''.$sPath.'\','.$id_tratamento.',true)" class="inserir ir">
							Inserir
						</a>
						<a href="javascript:;" title="Zoom" class="zoom ir">
							Zoom
						</a>
					</div>
					<img class="pic" src="'.$sPath.'" alt="'.$sPath.'"/>
				</li>';
            }
			$i++;
		}
		if($_POST['primeira'])
			$res['html'] = '';
		echo json_encode($res);
	}
?>