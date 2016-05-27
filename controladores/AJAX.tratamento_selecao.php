<?php
    include_once("../classes/classCombo.php");
    include_once("../classes/classPaciente.php");
    include_once("../classes/classPersistencia.php");
	include_once("../classes/classConfiguracao.php");

	$config = new Configuracao(1);

    $paciente = new Paciente($_GET['id_paciente']);

    $vet_dentes = explode(",", $_GET['dentes']);
    $criterio_sql = "(";
    foreach($vet_dentes as $dente)
    {
        $criterio_sql .= 't.dente="'.strtoupper($dente).'" OR ';
    }
    
    $criterio_sql = substr($criterio_sql, 0, strlen($criterio_sql)-4).")";

    $sCondSql = "";
    if($_GET['status']!=-1)
    {
        if($_GET['status']==1)
            $sCondSql .= " AND (t.sucesso = '1' OR t.sucesso = '0')";
        else
            $sCondSql .= " AND t.sucesso = '".$_GET['status']."'";
    }
    $criterio_sql .= $sCondSql;
	



    #Carregando a combobox como multi-linhas
    #Status do tratamento:
    #	0 - Insucesso
    #	1 - Sucesso
    #	2 - Pendente
    #   3 - Cancelado

    $sSqlTratamentos = "SELECT t.id_tratamento, t.id_match_code, t.dente, concat(t.dente, ' - ', m.descricao) AS dente_descricao
                        FROM tratamento t, match_code m
                        WHERE t.id_match_code = m.id_match_code AND
							  t.id_match_code IN (SELECT id_match_code FROM match_code WHERE id_especialidade=".$config->getIdEspecialidade().") AND
                              t.id_pessoa=".$paciente->getId()." AND ".$criterio_sql." ORDER BY dente";
							  //echo $sSqlTratamentos;
							  //exit();

    $combo = new Combo();
    $combo->setSize("15");
    $combo->setClassOption('','');
    $combo_tratamento = $combo->sGetHTML($sSqlTratamentos,'id_tratamento','id_tratamento','dente_descricao', '','style="width:615px;" ondblclick="valida_campos();"');
    echo $combo_tratamento;

    $pers = new Persistencia();
    $pers->bExecute($sSqlTratamentos);

    $cont = 0;
    $dentes_em_tratamento = "";
    while($cont < $pers->getDbNumRows())
    {
            $pers->bCarregaRegistroPorLinha($cont);
            $vet = $pers->getDbArrayDados();
            $dentes_em_tratamento .= strtolower($vet['dente']);
            if($cont < $pers->getDbNumRows()-1)
            {
                    $dentes_em_tratamento .= ",";
            }
            $cont++;
    }

    echo '
        <input name="dentes_em_tratamento" id="dentes_em_tratamento" type="hidden" value="'.$dentes_em_tratamento.'" />
    ';

?>
