<?php
	include_once('../bibliotecas/zip/zip_lib.php');
	include_once('../classes/classPersistencia.php');

	$id_pessoa = $_GET['id'];
	$nome_arquivo = isset($_GET['file']) ? $_GET['file'] : "";
	$id_tratamento = isset($_GET['id_tratamento']) ? $_GET['id_tratamento'] : "";

	switch($_GET['tipo']){
		case 1:
			download_file($caminho = "../documentos/pacientes/".$id_pessoa."/outros_documentos/".$nome_arquivo);
			break;
		case 2:
			download_zipfile($caminho = "../documentos/pacientes/".$id_pessoa."/tratamento/", $id_tratamento);
			break;
	}

	function download_zipfile($caminho, $id_tratamento){
		$arquivos = captura_documentos($id_tratamento);
		if(sizeof($arquivos) > 0) {
			$zipfile = new zipfile("tratamento-".date("dmY-his").".zip");
			
			foreach($arquivos as $listar){
				echo $caminho.$listar;
				$zipfile->addFileAndRead($caminho.$listar, $listar);
			}

			echo $zipfile->file();
		}
	}
	
	function download_file($file_name){
		if(is_file($file_name)){	 
			// required for IE
			if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }
		 
			// get the file mime type using the file extension
			switch(strtolower(substr(strrchr($file_name,'.'),1))){
				case 'pdf': $mime = 'application/pdf'; break;
				case 'zip': $mime = 'application/zip'; break;
				case 'jpeg':
				case 'jpg': $mime = 'image/jpg'; break;
				default: $mime = 'application/force-download';
			}
			header('Pragma: public');   // required
			header('Expires: 0');       // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
			header('Cache-Control: private',false);
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize($file_name));    // provide file size
			header('Connection: close');
			readfile($file_name);       // push it out
		}
	}
	
	function captura_documentos($id_tratamento){
		$pers = new Persistencia();
		$sSql = "SELECT caminho FROM imagem WHERE id_tratamento=".$id_tratamento;
		$pers->bExecute($sSql);
		
		$cont = 0;
		while($cont < $pers->getDbNumRows()){
			$pers->bCarregaRegistroPorLinha($cont);
			$vet_result = $pers->getDbArrayDados();
			$arquivos[] = $vet_result['caminho'];
			$cont++;
		}

		if($pers->getDbNumRows() > 0) {
			return $arquivos;
		} else {
			 /* echo '<script>alert("Não existem documentos para este tratamento.")</script>'; */
			header("Location: documentos_paciente.php");
		}
	}
	
	function lista_arquivos($diretorio) {	
		$ponteiro  = opendir($diretorio);
	
		while ($nome_itens = readdir($ponteiro)){
			$itens[] = $nome_itens;
		}
	
		sort($itens);
		foreach ($itens as $listar) {
			if ($listar != "." && $listar != ".."){ 
				if (!is_dir($listar)) { 
					$arquivos[]=$listar;
				}
		   }
		}
		
		foreach($arquivos as $listar)
		echo $listar;
		exit();
		return $arquivos;
	}
?>