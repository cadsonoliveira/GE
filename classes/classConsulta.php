<?php
    include_once("classPersistencia.php");

    class Consulta extends Persistencia
    {

        private $iId;
        private $dData;
        private $hHora;
        private $iDuracao;
        private $iStatus;

        public function __construct($consulta_id = 0)
        {
            parent::__construct();

            if($consulta_id != 0)
            {
                $this->getConsultaById($consulta_id);
            }else
            {
                $this->iId = $consulta_id;
                $this->dData = '';
                $this->hHora = '';
                $this->iDuracao = 0;
                $this->iStatus = -1;
            }

        }

        public function bFetchObject($sSql)
        {
            $this->bExecute($sSql);
            $this->bDados();

            $res = $this->getDbArrayDados();

            $this->setId($res['id_consulta']);
            $this->setData($res['data']);
            $this->setHora($res['hora']);
            $this->setDuracao($res['duracao']);
            $this->setStatus($res['status']);
        }

        public function getConsultaById($consulta_id)
        {
            $sSql = "SELECT * FROM consulta WHERE id_consulta=".$consulta_id;

            $this->bFetchObject($sSql);
        }

        public function bUpdate()
        {
            if(($this->getId()) == 0)
            {
            #INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
                $sSql = "INSERT INTO consulta (data, hora, duracao, status) VALUES (";
                $sSql .= " '".$this->getData()."', ";
                $sSql .= " '".$this->getHora()."', ";
                $sSql .= " '".$this->getDuracao()."', ";
                $sSql .= " ".$this->getStatus()." )";

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Consulta');
                    return false;
                } else {
                    $this->setId(mysql_insert_id());
                    return true;
                }
            } else {
            #ALTERAR UM ENDERECO NO BANCO DE DADOS
                $sSql = "UPDATE consulta SET ";
                $sSql .= " data = '".$this->getData()."', ";
                $sSql .= " hora = '".$this->getHora()."', ";
                $sSql .= " duracao = '".$this->getDuracao()."', ";
                $sSql .= " status = ".$this->getStatus()." ";
                $sSql .= " WHERE id_consulta=".$this->getId();

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Consulta');
                    return false;
                } else {
                    return true;
                }

            }
        }

        public function bDelete()
        {
            //Remove todas as informações da consulta na tabela de entidade e relacionamento
            $sSql = "DELETE FROM er_consulta_tratamento WHERE id_consulta=".$this->getId();
            if($this->bExecute($sSql)) {
                //Remove a consulta do banco de dados
                $sSql = "DELETE FROM consulta WHERE id_consulta=".$this->getId();

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Consulta');
                    return false;
                } else {
                    return true;
                }
            }
        }

        public function toString()
        {
            echo '### CONSULTA ###<br>';
            echo 'ID.........:'.$this->iId.'<br>';
            echo 'DATA.......:'.$this->dData.'<br>';
            echo 'HORA.......:'.$this->hHora.'<br>';
            echo 'DURACAO....:'.$this->iDuracao.'<br>';
            echo 'STATUS.....:'.$this->iStatus.'<br>';
        }

        #MÉTODOS GET's DA CLASSE
        public function getId() {
            return $this->iId;
        }
        public function getData() {
            return $this->dData;
        }
        public function getHora() {
            return $this->hHora;
        }
        public function getDuracao() {
            return $this->iDuracao;
        }
        public function getStatus() {
            return $this->iStatus;
        }

        #MÉTODOS SET's DA CLASSE
        public function setId($id) {
            $this->iId = $id;
        }
        public function setData($data) {
            $this->dData = $data;
        }
        public function setHora($hora) {
            $this->hHora = $hora;
        }
        public function setDuracao($duracao) {
            $this->iDuracao = $duracao;
        }
        public function setStatus($status) {
            $this->iStatus = $status;
        }

    }
?>
