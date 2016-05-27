<?php
include_once("../classes/classPessoa.php");
include_once("../classes/classPaciente.php");
include_once("../classes/classTratamento.php");
include_once("../classes/classMatchCode.php");

/************************************************************************************
 *CARREGA OS DADOS DO TRATAMENTO
/************************************************************************************/
$id_paciente = 0;
$id_tratamento = 0;
$status = '';
$data_inic = '';
$data_term = '';
$dente = '';
$descricao = '';
$tipo_trat = '';
$value = '- - MATCH CODE - -';
$tipo  = $_GET['tipo'];
$caminho_foto = '';

echo $_GET['div_id'].'|;|';
if(isset($_GET['paciente']) && $_GET['paciente']!="")
{

    $id_paciente = $_GET['paciente'];
    $paciente = new Paciente($id_paciente);

    if($paciente->getCaminhoFoto()!="")
        $caminho_foto ="../documentos/pacientes/".$id_paciente."/foto/".$paciente->getCaminhoFoto();
    else
        $caminho_foto = 'img/no_pic.gif';

    $nome = $paciente->getNome();

    if(isset($_GET['tratamento']) && $_GET['tratamento']!="")
    {
        $id_tratamento = $_GET['tratamento'];

        //$action_form = "../controladores/tratamentoGravar.php?tratamento=$id_tratamento&paciente=$id_paciente";

        $tratamento = new Tratamento($id_tratamento);
        $vet_data = explode("-", $tratamento->getDataInicio());
        $data_inic = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];

        if(($tratamento->getDataTermino() != "0000-00-00")&&($tratamento->getDataTermino() != NULL)){
            $vet_data = explode("-", $tratamento->getDataTermino());
            $data_term = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
        } else {
            $data_term = "";
        }

        $status = $tratamento->getStatus();
        $dente = $tratamento->getDente();
        $id_match_code = $tratamento->getIdMatchCode();

        if($id_match_code != NULL){
            $value = $id_match_code;
        }

        $match_code = new MatchCode($id_match_code);
        $descricao = $match_code->getDescricao();
        $tipo_trat = $match_code->getTipo();
    }

}

 if($tipo==0) //Atualiza descricao do tratament
 {
?>
<p><span class="formularioBold">Dente:</span> <?php echo (isset($dente))?$dente:"-"; ?></p>
<p><span class="formularioBold">Matchcode:</span> <?php echo (isset($match_code))?$match_code->getId():"-"; ?></p>
<p><span class="formularioBold">Descrição:</span> <?php echo (isset($match_code))?$match_code->getDescricao():"-"; ?></p>
<p><span class="formularioBold">Data Início:</span> <?php echo (isset($data_inic))?$data_inic:"-"; ?></p>
<p id="dataTermino"><span class="formularioBold">Data Término:</span> <?php $data_term; ?></p>
<input name="dente_hd" id="dente_hd" type="hidden" value="<?php echo (isset($dente))?$dente:'-';?>" />
<input name="matchcode_hd" id="matchcode_hd" type="hidden" value="<?php echo (isset($match_code))?$match_code->getId():'-'; ?>"  />
<input name="dtinicio_hd" id="dtinicio_hd" type="hidden" value="<?php echo (isset($data_inic))?$data_inic:'-'; ?>" />
<input name="dtfim_hd" id="dtfim_hd" type="hidden" value="<?php echo (isset($data_term))?$data_term:'-'; ?> " />

<?php
 }else
 if($tipo==1) //Atualiza a foto do paciente
     {
     ?>
        <h2>Foto</h2>
        <ul class="field">
        <li><img src="<?php echo $caminho_foto;?>" width="120" height="120" alt="Sem Foto" /></li>
        </ul>
     <?php
     }
 ?>
