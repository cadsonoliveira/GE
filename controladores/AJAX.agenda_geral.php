<?php

    include_once("../funcoes/common.php");
    include_once("../classes/classConsulta.php");
    include_once("../classes/classTratamento.php");
    include_once("../classes/classPlanoSaude.php");
    include_once("../classes/classPaciente.php");
    include_once("../classes/classMatchCode.php");
    include_once("../classes/classErConsultaTratamento.php");
    include_once("../classes/classPersistencia.php");

    if(isset($_POST['tipo']) && ($_POST['tipo'] == "atualiza_agenda")){
        $data = $_POST['data'];
        echo carregaAgenda($data);
        
    } elseif(isset($_POST['tipo']) && ($_POST['tipo'] == "atualiza_calendario")) {
        
        $data = $_POST['data'];
        echo carregaCalendario($data);

    } elseif ((isset($_POST['tipo'])) && ($_POST['tipo'] == "nova_consulta")) {

        $data = $_POST['data'];
        $vet_data = explode("/", $data);
        $data = $vet_data[2]."-".$vet_data[1]."-".$vet_data[0];
        $duracao = $_POST['duracao'];
        $hora_inicio = $_POST['hora_inicio'];

        $consulta = new Consulta();

        $consulta->setData($data);
        $consulta->setDuracao($duracao);
        $consulta->setHora($hora_inicio);
        $consulta->setStatus(0);
        $consulta->bUpdate();

        $vet_tratamentos = explode(",", $_POST['tratamentos']);

        $cont = 0;
        while($cont < sizeof($vet_tratamentos)){
            $er_ct = new ErConsultaTratamento();
            $er_ct->setIdConsulta($consulta->getId());
            $er_ct->setIdTratamento($vet_tratamentos[$cont]);
            $er_ct->bUpdate();
            $cont++;
        }

    } elseif ((isset($_POST['tipo'])) && ($_POST['tipo'] == "altera_consulta")) {
        
        $data = $_POST['data'];
        $vet_data = explode("/", $data);
        $data = $vet_data[2]."-".$vet_data[1]."-".$vet_data[0];
        $duracao = $_POST['duracao'];
        $hora_inicio = $_POST['hora_inicio'];

        $consulta = new Consulta($_POST['id_consulta']);

        $consulta->setData($data);
        $consulta->setDuracao($duracao);
        $consulta->setHora($hora_inicio);
        $consulta->setStatus(0);
        $consulta->bUpdate();
/*
        //Quando o usuário edita uma consulta, ele pode alterar os tratamentos referentes à consulta,
        //como não sabemos se ele simplesmente alterou o horario ou se alterou os tratamentos da consulta
        //nós apagamos todas as informações daquela consulta e criamos novas informações.
        $er_ct_delete = new ErConsultaTratamento();
        $er_ct_delete->bDeleteByIdConsulta($consulta->getId());
        
        $vet_tratamentos = explode(",", $_POST['tratamentos']);
        $cont = 0;
        while($cont < sizeof($vet_tratamentos))
        {
            $er_ct = new ErConsultaTratamento();
            $er_ct->setIdConsulta($consulta->getId());
            $er_ct->setIdTratamento($vet_tratamentos[$cont]);
            $er_ct->bUpdate();
            $cont++;
        }
*/
        $pers = new Persistencia();
        $sSql = "SELECT * FROM er_consulta_tratamento WHERE id_consulta=".$_POST['id_consulta'];
        $pers->bExecute($sSql);

        $vet_tratamentos = explode(",", $_POST['tratamentos']);
        $cont = 0;
        while($cont < $pers->getDbNumRows()){
            $flag = -1;
            $pers->bCarregaRegistroPorLinha($cont);
            $vet_result = $pers->getDbArrayDados();
            for($i=0 ; $i < sizeof($vet_tratamentos) ; $i++){
                if($vet_result['id_tratamento'] == $vet_tratamentos[$i]){
                    array_splice($vet_tratamentos, $i, 1);
                    $flag = 0;
                    break;
                }
            }
            if($flag == -1){
                $erct = new ErConsultaTratamento($vet_result['id_consulta_tratamento']);
                $erct->bDelete();
            }
            $cont++;
        }

        $cont = 0;
        while($cont < sizeof($vet_tratamentos)){
            $er_ct = new ErConsultaTratamento();
            $er_ct->setIdConsulta($consulta->getId());
            $er_ct->setIdTratamento($vet_tratamentos[$cont]);
            $er_ct->bUpdate();
            $cont++;
        }

        
    } elseif ((isset($_POST['tipo'])) && ($_POST['tipo'] == "remove_consulta")) {
        $consulta = new Consulta($_POST['id_consulta']);
        $consulta->bDelete();
        
    }

    
    function lista_match_codes($id_pessoa, $id_especialidade, $tipo){
        $lista = "<ul class='field'><li><label>".ucfirst(strtolower($tipo))."(s): </label>";

        $sSql = "SELECT m.id_match_code, m.descricao, m.tipo, t.id_tratamento, t.dente
                     FROM match_code m, tratamento t
                     WHERE m.id_especialidade = ".$id_especialidade."
                        AND m.tipo = '".$tipo."'
                        AND t.id_pessoa = ".$id_pessoa."
                        ANd t.id_match_code = m.id_match_code
                        AND t.status = 0
                     ORDER BY m.tipo, m.descricao";

        $pers = new Persistencia();

        $pers->bExecute($sSql);
        $cont = 0;
        //$lista .= "<ol class='check_list'><ol>";

        $num_registros = (($pers->getDbNumRows() % 2) == 0) ? $pers->getDbNumRows()/2 : (int)($pers->getDbNumRows()/2)+1;
        while($cont < $pers->getDbNumRows()){
            $pers->bCarregaRegistroPorLinha($cont);
            $vet_result = $pers->getDbArrayDados();

            if($cont == $num_registros){
                //$lista .= "</ol><ol>";
            }
            $desc = utf8_encode(strtolower($vet_result['descricao']));
            $desc = ucfirst($desc);
            $desc .= ' ['.$vet_result['dente'].']';
            $lista .= "<li><label><input type='checkbox' id='_".$vet_result['id_match_code']."' name='".$vet_result['id_match_code']."' value='".$vet_result['id_match_code']."' alt='".$vet_result['dente'].":".$vet_result['id_tratamento']."'/>".$desc."</label></li>";
            $cont++;
        }
        if($cont==0)
            $lista = '';
        else
            $lista .= "</ol></ol></li></ul>";

        return $lista;
    }

    function carregaCalendario($data){
        $vet_data = explode("/", $data);
        $dia = $vet_data[0];
        $mes = $vet_data[1];
        $ano = $vet_data[2];
        $data=encodeDate($data);

        $pers = new Persistencia();

        $num_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        $string_calendario = '{ "day":'.$dia.', "month":'.$mes.', "year":'.$ano.', "days":{';
        //cal.setData('{ "day":21,"month":6,"year":2009,"days":{1:["r",0], 2:["c",20], 3:["",30] } }');
        for($i=0;$i<$num_dias;$i++) {
            $sql = "SELECT count(*) as qtd FROM consulta WHERE DAY(data) = '".($i+1)."' AND MONTH(data) = '".$mes."' AND YEAR(data) = '".$ano."' ORDER BY data, hora";

            $pers->bExecute($sql);
            $pers->bDados();
            $vet_result = $pers->getDbArrayDados();
            $string_calendario .= ($i+1).':["",'.$vet_result['qtd'].']';
            if($i<($num_dias-1)){
                $string_calendario .= ', ';
            }
        }
        $string_calendario .= '} }';

        return $string_calendario;

    }


    function carregaAgenda($data, $hora_inicio="8:00", $hora_termino="18:00", $intervalo="30"){
        $vet_data = explode("/", $data);
        $dia = $vet_data[0];
        $mes = $vet_data[1];
        $ano = $vet_data[2];
        $data=encodeDate($data);

        $pers = new Persistencia();

        $string_agenda = '{ "cur_time":"'.(microtime()+time()*1000).'", "sel_date":"'.$data.'","start":"'.$hora_inicio.'", "end":"'.$hora_termino.'", "gap":"'.$intervalo.'", "hours":{';

        $sql = "SELECT * FROM consulta WHERE data = '".$data."' ORDER BY data, hora";
        $pers->bExecute($sql);
        $cont = 0;
        while($cont < $pers->getDbNumRows()){
            $pers->bCarregaRegistroPorLinha($cont);
            $vet_result = $pers->getDbArrayDados();

            $vet_hora = explode(":", $vet_result['hora']);
            $hora = (int)($vet_hora[0]).'_'.$vet_hora[1];
            $len = $vet_result['duracao'];
            
            $sql_cons_trat = "SELECT * FROM er_consulta_tratamento WHERE id_consulta=".$vet_result['id_consulta'];
            $pers_ct = new Persistencia();
            $pers_ct->bExecute($sql_cons_trat);

            if($pers_ct->getDbNumRows() > 0){
                $pers_ct->bCarregaRegistroPorLinha(0);

                $vet_result_ct = $pers_ct->getDbArrayDados();
                $tratamento = new Tratamento($vet_result_ct['id_tratamento']);
                $match_code = new MatchCode($tratamento->getIdMatchCode());
                $paciente = new Paciente($tratamento->getIdPessoa());
                $plano_saude = new PlanoSaude($paciente->getIdPlanoSaude());

                $string_agenda .= '"'.$hora.'":{"id_paciente":"'.$paciente->getId().'", "id_consulta":"'.$vet_result['id_consulta'].'", "len":"'.$len.'", "nome":"'.$paciente->getNome().'","telefone":"'.$paciente->getContato()->getTelefoneCelular().'","tratamentos":[ ';

                $cont_ct = 0;
                while($cont_ct < $pers_ct->getDbNumRows() ){
                    $pers_ct->bCarregaRegistroPorLinha($cont_ct);
                    $vet_result_ct2 = $pers_ct->getDbArrayDados();

                    $tratamento->getTratamentoById($vet_result_ct2['id_tratamento']);
                    $match_code->getMatchCodeById($tratamento->getIdMatchCode());

                    $desc = utf8_encode(strtolower(utf8_decode($match_code->getDescricao())));
                    $desc = ucfirst($desc);

                    $string_agenda .= '['.$match_code->getId().', "'.$desc.'", "'.$tratamento->getDente().'", "'.$vet_result_ct2['id_tratamento'].'"]';

                    if($cont_ct < ($pers_ct->getDbNumRows()-1)){
                        $string_agenda .= ', ';
                    }
                    $cont_ct++;
                }

                $string_agenda .= '], "plano":"'.$plano_saude->getNome().'", "plano_cod":"'.$plano_saude->getCodigo().'"}';

                if($cont < ($pers->getDbNumRows()-1)){
                    $string_agenda .= ', ';
                }
            }
            $cont++;
        }
        
        $string_agenda .= '} }';

        return $string_agenda;
    }
?>