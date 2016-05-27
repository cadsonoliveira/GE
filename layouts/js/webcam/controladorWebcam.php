<?php
    include_once("../../funcoes/common.php");
    
    // Captura pela Webcam
    if ($_REQUEST["bindata"] === NULL) {
        echo "Parâmetros inválidos.";
    }
    else {
        $img_data = base64_decode($_REQUEST["bindata"]);
        $img_size = strlen($img_data);
        if ($img_size < 1000000) { //limite 1 mega
            $img_filename = "../../documentos/pacientes/temp/".createName().".jpg";
            unlinkRecursive('../../documentos/pacientes/temp/', false);
            
            $img_file = fopen($img_filename, "w") or die("Impossível abrir o arquivo");
            fwrite($img_file, $img_data);
            fclose($img_file);
            echo "Tamanho do arquivo: $img_size bytes";
        }
        else {
            echo "Tamanho da imagem é muito grande";
        }
    }
?>