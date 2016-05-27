<?php
include_once("../classes/classPersistencia.php");

class PlanoSaude extends Persistencia
{
    private $iId;
    private $sCodigo;
    private	$sNome;
    private $iIdContato;

    public function __construct($plano_saude_id = 0) {
        parent::__construct();

        if($plano_saude_id != 0) {
            $this->getPlanoSaudeById($plano_saude_id);
        }else{

            $this->iId = $plano_saude_id;
            $this->sCodigo = '';
            $this->sNome = '';
            $this->iIdContato = 0;

        }

    }

    public function bFetchObject($sSql) {
        $this->bExecute($sSql);
        $this->bDados();

        $res = $this->getDbArrayDados();

        $this->setId(utf8_encode($res['id_plano_saude']));
        $this->setCodigo(utf8_encode($res['codigo']));
        $this->setNome(utf8_encode($res['nome']));
        $this->setIdContato(utf8_encode($res['id_contato']));
    }

    public function getPlanoSaudeById($plano_saude_id){
        $sSql= "SELECT * FROM planosaude WHERE id_plano_saude=".$plano_saude_id;
        $this->bFetchObject($sSql);
    }

    public function bUpdate() {
        if(($this->getId()) == 0) {
            #INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
            $sSql = "INSERT INTO planosaude (codigo, nome, id_contato) VALUES (";
            $sSql.= " '".utf8_decode($this->getCodigo())."', ";
            $sSql.= " '".utf8_decode($this->getNome())."', ";
            $sSql.= " ".utf8_decode($this->getIdContato())." )";

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Plano de Saude');
                return false;
            } else {
                $this->setId(mysql_insert_id());
                return true;
            }
        } else {
            #ALTERAR UM ENDERECO NO BANCO DE DADOS
            $sSql = "UPDATE planosaude SET ";
            $sSql .= " codigo = '".utf8_decode($this->getCodigo())."', ";
            $sSql .= " nome = '".utf8_decode($this->getNome())."', ";
            $sSql .= " id_contato = ".utf8_decode($this->getIdContato())." ";
            $sSql .= " WHERE id_plano_saude=".$this->getId();

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Plano de Saude');
                return false;
            } else {
                return true;
            }
        }
    }

    public function bDelete() {
        $sSql = "DELETE FROM planosaude WHERE id_plano_saude=".$this->getId();

        if(!$this->bExecute($sSql)) {
            $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Plano de Saude');
            return false;
        } else {
            return true;
        }
    }

    public function toString()
    {
        echo '### PLANO DE SAUDE ###<br>';
        echo 'ID.........:'.$this->iId.'<br>';
        echo 'CODIGO.....:'.$this->sCodigo.'<br>';
        echo 'NOME.......:'.$this->sNome.'<br>';
        echo 'CONTATO....:'.$this->iIdContato.'<br>';
    }

    public function existemPacientes(){
        $sSql = "SELECT id_pessoa FROM paciente WHERE id_plano_saude=".$this->getId();
        $this->bExecute($sSql);
        if($this->getDbNumRows())
        return true;
        else
        return false;            
    }

    #MÉTODOS GET's DA CLASSE
    public function getId() {
        return $this->iId;
    }

    public function getCodigo()
    {
        return $this->sCodigo;
    }

    public function getNome()
    {
        return $this->sNome;
    }

    public function getIdContato()
    {
        return $this->iIdContato;
    }

    #MÉTODOS SET's DA CLASSE

    public function setId($id) {
        $this->iId = $id;
    }

    public function setCodigo($codigo) {
        $this->sCodigo = $codigo;
    }

    public function setNome ($nome) {
        $this->sNome = $nome;
    }

    public function setIdContato ($contato_id) {
        $this->iIdContato = $contato_id;
    }

}
?>
