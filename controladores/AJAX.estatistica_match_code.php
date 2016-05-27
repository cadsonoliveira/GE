<?php

    include_once("../classes/classMatchCode.php");
    include_once("../classes/classPersistencia.php");

    $sHTML = '';

    $sHTML .= $_GET['div_id']."|;|";

    $criterio_sql = "";
    if($_GET['list_trat'] != "")
    {
        $vet_trat = explode(",",$_GET['list_trat']);
        foreach($vet_trat as $item)
        {
            $criterio_sql .= " OR id_match_code = ".$item;
        }
    }

    $sql = "SELECT * FROM match_code WHERE tipo='TRATAMENTO' AND ( id_match_code=-1 ".$criterio_sql." ) ORDER BY descricao";

    $pers = new Persistencia();
    $pers->bExecute($sql);
    $cont_trat = 0;
    $sHTML .= '<ol class="check_list" style="width:180px"><label>Tratamento(s):</label>';
    while($cont_trat < $pers->getDbNumRows())
    {
        $pers->bCarregaRegistroPorLinha($cont_trat);
        $vet_result = $pers->getDbArrayDados();
        $sHTML .= "<li style='padding:3px;border-bottom:solid #ccc 1px;font-size:10px;width:174px;'><span style='float:left;width:80%;'>".utf8_encode($vet_result['descricao'])."</span><a style='padding-top:7px;float:right;clear:right;' onclick=\"removeTratMatchCode(".$vet_result['id_match_code'].", false)\"><img src=\"img/delete.gif\" alt=\"Remover registro\" title=\"Remover registro\" width=\"16\" height=\"16\" border=\"0\"/></a></li>";
        $cont_trat++;
    }
    if($cont_trat == 0)
    {
       $sHTML .= '<span style="text-align:center;width:100%;">Lista está vazia</span>';
    }


    #RETRATAMENTO
    
    $criterio_sql = "";
    if($_GET['list_retrat'] != "")
    {
        $vet_retrat = explode(",",$_GET['list_retrat']);
        foreach($vet_retrat as $item)
        {
            $criterio_sql .= " OR id_match_code = ".$item;
        }
    }

    $sql = "SELECT * FROM match_code WHERE tipo='RETRATAMENTO' AND ( id_match_code=-1 ".$criterio_sql." ) ORDER BY descricao";

    $pers = new Persistencia();
    $pers->bExecute($sql);
    $cont_retrat = 0;
    $sHTML .= '<label>Retratamento(s):</label>';
    while($cont_retrat < $pers->getDbNumRows())
    {
        $pers->bCarregaRegistroPorLinha($cont_retrat);
        $vet_result = $pers->getDbArrayDados();
        $sHTML .= "<li style='padding:3px;border-bottom:solid #ccc 1px;font-size:10px;width:174px;'><span style='float:left;width:80%;'>".utf8_encode($vet_result['descricao'])."</span><a style='padding-top:7px;float:right;clear:right;' onclick=\"removeRetratMatchCode(".$vet_result['id_match_code'].", false)\"><img src=\"img/delete.gif\" alt=\"Remover registro\" title=\"Remover registro\" width=\"16\" height=\"16\" border=\"0\"/></a></li>";
        $cont_retrat++;
    }
    if($cont_retrat == 0)
    {
       $sHTML .= '<span style="text-align:center;width:100%;">Lista está vazia</span>';
    }
    $sHTML .= '</ol>';

    if(($cont_trat == 0) && ($cont_retrat == 0)){
        echo $_GET['div_id']."|;|";
        echo '<span>Nenhum item selecionado</span>';
    }else{
        echo $sHTML;
    }
?>