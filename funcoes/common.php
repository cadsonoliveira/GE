<?php

/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 */
function unlinkRecursive($dir, $deleteRootToo){
    if(!$dh = @opendir($dir)) {
        return;
    }
    while (false !== ($obj = readdir($dh))){
        if($obj == '.' || $obj == '..') {
            continue;
        }
        if (!@unlink($dir . '/' . $obj)) {
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }
    closedir($dh);
    if ($deleteRootToo) {
        @rmdir($dir);
    }
    return;
}

function createName() {
    return date("dmYHms").rand(0, 100);
}

function decodeDate($data) {
    if($data != "") {
        $vet_data = explode("-", $data);
        return $vet_data[2]."/".$vet_data[1]."/".$vet_data[0];
    }
    return $data;
 
}

function encodeDate($data) {
    $vet_data = explode("/", $data);
    return $vet_data[2]."-".$vet_data[1]."-".$vet_data[0];
}

//Função que verifica a extensão
function extensao($arquivo){
    $tam = strlen($arquivo);

    //ext de 3 chars
    if( $arquivo[($tam)-4] == '.' ){
        $extensao = substr($arquivo,-3);
    }

    //ext de 4 chars
    elseif( $arquivo[($tam)-5] == '.' ){
        $extensao = substr($arquivo,-4);
    }

    //ext de 2 chars
    elseif( $arquivo[($tam)-3] == '.' ){
        $extensao = substr($arquivo,-2);
    }

    //Caso a extensão não tenha 2, 3 ou 4 chars ele não aceita e retorna Nulo.
    else{
        $extensao = NULL;
    }
    return $extensao;
}

?>