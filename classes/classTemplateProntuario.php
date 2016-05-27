<?php

include_once("../classes/classPersistencia.php");

class TemplateProntuario extends Persistencia
{
    private $iInstancia;
    private $iId;
    private $sNomeTemplate;
    private $sMedicamento;
    private $sConcentracao;
    private $sQuantidade;
    private $sInstrucao;
    private $sObservacao;
    private $iCompartilhamento;
    private $iUsuario;
    private $iEspecialidade;

    public function __construct($template_id = 0) {
        parent::__construct();

        if($template_id != 0) {
            $this->getTemplateProntuarioById($template_id);
        }else{
            $this->iId = $template_id;
            $this->iInstancia = 0;
            $this->sNomeTemplate = '';
            $this->sMedicamento = '';
            $this->sConcentracao = '';
            $this->sInstrucao = '';
            $this->sObservacao = '';
            $this->sQuantidade = '';
            $this->iCompartilhamento = 0;
            $this->iUsuario = 0;
            $this->iEspecialidade = 0;
        }
    }
    
    
    public function getTemplateProntuarioById($id){
        $sSql= "SELECT `id`, `instancia`, `id_usuario`, `id_especialidade`, `nomeTemplate`, `medicamento`, `concentracao`, `quantidade`, `instrucao`, `observacao`, `compartilhamento` FROM templateprontuario WHERE ID=".$id;
        $this->bFetchObject($sSql);
    }


    public function bFetchObject($sSql) {
        $this->bExecute($sSql);
        $this->bDados();

        $res = $this->getDbArrayDados();

        $this->setId(utf8_encode($res['id']));
        $this->setInstancia(utf8_encode($res['instancia']));
        $this->setUsuarioId(utf8_encode($res['id_usuario']));
        $this->setEspecialidadeId(utf8_encode($res['id_especialidade']));
        $this->setNomeTemplate(utf8_encode($res['nomeTemplate']));
        $this->setMedicamento(utf8_encode($res['medicamento']));
        $this->setConcentracao(utf8_encode($res['concentracao']));
        $this->setObservacao(utf8_encode($res['quantidade']));
        $this->setInstrucao(utf8_encode($res['instrucao']));
        $this->setObservacao(utf8_encode($res['observacao']));
        $this->setCompartilhamento(utf8_encode($res['compartilhamento']));	
    }


    public function bUpdate() {
        if(($this->getId()) == 0) {
            #INSERIR UM NOVO TEMPLATE NO BANCO DE DADOS
            $sSql = "INSERT INTO `templateprontuario` ( `instancia`, `id_usuario`, `id_especialidade`, `nomeTemplate`, `medicamento`, `concentracao`, `quantidade`, `instrucao`, `observacao`, `compartilhamento`) VALUES (";
            $sSql.= " '".utf8_decode($this->getInstancia())."', ";
            $sSql.= " ".utf8_decode($this->getUsuarioId()).", ";
            $sSql.= " ".utf8_decode($this->getEspecialidadeId()).", "; 
            $sSql.= " '".utf8_decode($this->getNomeTemplate())."', ";
            $sSql.= " '".utf8_decode($this->getMedicamento())."', ";
            $sSql.= " '".utf8_decode($this->getConcentracao())."', ";
            $sSql.= " '".utf8_decode($this->getQuantidade())."', ";
            $sSql.= " '".utf8_decode($this->getInstrucao())."', ";
            $sSql.= " '".utf8_decode($this->getObservacao())."', ";
            $sSql.= " ".utf8_decode($this->getCompartilhamento())." )";

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Plano de Saude');
                return false;
            } else {
                $this->setId(mysql_insert_id());
                return true;
            }
        } else {
            #ALTERAR UM TEMPLATE PARA PRONTUÁRIO NO BANCO DE DADOS
            $sSql = "UPDATE templateprontuario SET ";
            $sSql .= " instancia = '".utf8_decode($this->getInstancia())."', ";
            $sSql .= " id_usuario = '".utf8_decode($this->getUsuarioId())."', ";
            $sSql .= " id_especialidade = ".utf8_decode($this->getEspecialidadeId()).", ";     
            $sSql .= " nomeTemplate = '".utf8_decode($this->getNomeTemplate())."', ";
            $sSql .= " medicamento = '".utf8_decode($this->getMedicamento())."', ";
            $sSql .= " concentracao = '".utf8_decode($this->getConcentracao())."', ";
            $sSql .= " quantidade = '".utf8_decode($this->getQuantidade())."', ";
            $sSql .= " instrucao = '".utf8_decode($this->getInstrucao())."', ";
            $sSql .= " observacao = '".utf8_decode($this->getObservacao())."', ";
            $sSql .= " compartilhamento = ".utf8_decode($this->getCompartilhamento())." ";
            $sSql .= " WHERE id =".$this->getId();

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Plano de Saude');
                return false;
            } else {
                return true;
            }
        }
    }

    public function bDelete() {
        $sSql = "DELETE FROM templateprontuario WHERE id=".$this->getId();

        if(!$this->bExecute($sSql)) {
            $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Template para Prontu�rio');
            return false;
        } else {
            return true;
        }
    }

    public function toString()
    {
        echo '### TEMPLATE PRONTUARIO ###<br>';
        echo 'INSTANCIA......:'.$this->iInstancia.'<br>';
	echo 'ID............:'.$this->iId.'<br>';
        echo 'NOME..........:'.$this->sNomeTemplate.'<br>';
	echo 'MEDICAMENTO...:'.$this->sMedicamento.'<br>';
	echo 'CONCENTRACAO..:'.$this->sConcentracao.'<br>';
        echo 'QUANTIDADE...:'.$this->sQuantidade.'<br>';
        echo 'INSTRUCAO.....:'.$this->sInstrucao.'<br>';
        echo 'OBSERVACAO....:'.$this->sObservacao.'<br>';
        echo 'PROFISSIONAL.....:'.$this->iUsuario .'<br>';
        echo 'ESPECIALDIADE....:'.$this->iEspecialidade.'<br>';
        echo 'COMPARTILHAMENTO....:'.$this->iCompartilhamento.'<br>';
    }

    public function getTemplatesByProfissional($prof, $instancia,$especialidade) {
        $sSql = "SELECT `id`, `instancia`, `id_usuario`, `id_especialidade`, `nomeTemplate`, `medicamento`, `concentracao`, `quantidade`, `instrucao`, `observacao`, `compartilhamento` FROM templateprontuario ";
        $sSql .= "   WHERE (instancia =".$instancia.")  and ((id_usuario=".$prof.") OR  ( compartilhamento = 3) OR (id_especialidade = ".$$especialidade." and compartilhamento = 2)) ";
        $this->bExecute($sSql);
        if($this->getDbNumRows())
            return true;
        else
            return false;       
    }

    #M�TODOS SET'S GET's DA CLASSE
    public function getInstancia() {
        return $this->iInstancia;
    }
	
    public function setInstancia($id) {
        $this->iInstancia = $id;
    }
	
    public function getId() {
        return $this->iId;
    }
	
    public function setId($id) {
        $this->iId = $id;
    }
		
    public function getCompartilhamento() {
        return $this->iCompartilhamento = 0;
    }
	
    public function setCompartilhamento($comp) {
        $this->iCompartilhamento = $comp;
    }
	
    public function getNomeTemplate() {
        return $this->sNomeTemplate;
    }
	
    public function setNomeTemplate($nome) {
        $this->sNomeTemplate = $nome;
    }
	
    public function getMedicamento() {
        return $this->sMedicamento;
    }
	
    public function setMedicamento($med) {
        $this->sMedicamento = $med;
    }
	
    public function getConcentracao() {
        return $this->sConcentracao;
    }
	
    public function setConcentracao($conc) {
        $this->sConcentracao = $conc;
    }
    public function getQuantidade() {
        return $this->sQuantidade;
    }
	
    public function setQuantidade($qtd) {
        $this->sQuantidade = $qtd;
    }
    public function getInstrucao() {
        return $this->sInstrucao;
    }
	
    public function setInstrucao($instr) {
        $this->sInstrucao = $instr;
    }
	
    public function getObservacao() {
        return $this->sObservacao;
    }
	
    public function setObservacao($obs) {
        $this->sObservacao = $obs;
    }
    public function getUsuarioId() {
        return $this->iUsuario;
    }
	
    public function setUsuarioId($user) {
        $this->iUsuario = $user;
    }
    public function getEspecialidadeId() {
        return $this->iEspecialidade;
    }
	
    public function setEspecialidadeId($esp) {
        $this->iEspecialidade = $esp;
    }


}
?>
