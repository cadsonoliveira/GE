<?php
    include_once("classPersistencia.php");
    
    class ErConsultaTratamento extends Persistencia
    {
        private $iId;
        private $iIdConsulta;
        private $iIdTratamento;

        public function __construct($consulta_tratamento_id = 0, $consulta_id = 0, $tratamento_id = 0)
        {
            parent::__construct();

            if($consulta_tratamento_id != 0) {
                $this->getErConsultaTratamentoById($consulta_tratamento_id);
            } elseif(($consulta_id != 0) && ($tratamento_id != 0)) {
                $this->getErCTByConsultaTramentoId($consulta_id, $tratamento_id);
            } else {
                $this->iId = $consulta_tratamento_id;
                $this->iIdConsulta = 0;
                $this->iIdTratamento = 0;
            }
        }

        public function bFetchObject($sSql)
        {
            $this->bExecute($sSql);
            $this->bDados();

            $res = $this->getDbArrayDados();
            $this->setId($res['id_consulta_tratamento']);
            $this->setIdConsulta($res['id_consulta']);
            $this->setIdTratamento($res['id_tratamento']);
        }


        public function getErCTByConsultaTramentoId($consulta_id, $tratamento_id)
        {
            $sSql = "SELECT * FROM er_consulta_tratamento WHERE id_consulta=".$consulta_id." AND id_tratamento=".$tratamento_id;
            $this->bFetchObject($sSql);
        }

        public function getErConsultaTratamentoById($consulta_tratamento_id)
        {
            $sSql = "SELECT * FROM er_consulta_tratamento WHERE id_consulta_tratamento=".$consulta_tratamento_id;
            $this->bFetchObject($sSql);
        }

        public function bUpdate()
        {
            if(($this->getId()) == 0)
            {
            #INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
                $sSql = "INSERT INTO er_consulta_tratamento (id_consulta, id_tratamento) VALUES (";
                $sSql .= " ".utf8_decode($this->getIdConsulta()).", ";
                $sSql .= " ".utf8_decode($this->getIdTratamento())." )";

                if(!$this->bExecute($sSql))
                {
                    $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto ErConsultaTratamento');
                    return false;
                } else {
                    $this->setId(mysql_insert_id());
                    return true;
                }
            } else {
            #ALTERAsR UM ENDERECO NO BANCO DE DADOS
                $sSql = "UPDATE consulta SET ";
                $sSql .= " id_consulta = ".utf8_decode($this->getIdConsulta()).", ";
                $sSql .= " id_tratamento = ".utf8_decode($this->getIdTratamento())." ";
                $sSql .= " WHERE id_consulta=".$this->getId();

                if(!$this->bExecute($sSql))
                {
                    $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de ErConsultaTratamento');
                    return false;
                } else {
                    return true;
                }

            }
        }

        public function bDeleteByIdConsulta($id_consulta)
        {
            $sSql = "DELETE FROM er_consulta_tratamento WHERE id_consulta=".$id_consulta;

            if(!$this->bExecute($sSql))
            {
                $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Consulta');
                return false;
            } else {
                return true;
            }
        }

        public function bDelete()
        {
            $sSql = "DELETE FROM er_consulta_tratamento WHERE id_consulta_tratamento=".$this->getId();

            if(!$this->bExecute($sSql))
            {
                $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Consulta');
                return false;
            } else {
                return true;
            }
        }

        public function toString()
        {
            echo '### RELACAO CONSULTA-TRATAMENTO ###<br>';
            echo 'ID.........:'.$this->iId.'<br>';
            echo 'CONSULTA...:'.$this->iIdConsulta.'<br>';
            echo 'TRATAMENTO.:'.$this->iIdTratamento.'<br>';
        }

        #MÉTODOS GET's DA CLASSE
        public function getId()
        {
            return $this->iId;
        }

        public function getIdConsulta()
        {
            return $this->iIdConsulta;
        }

        public function getIdTratamento()
        {
            return $this->iIdTratamento;
        }

        #MÉTODOS SET's DA CLASSE
        public function setId($id)
        {
            $this->iId = $id;
        }

        public function setIdConsulta($id_consulta)
        {
            $this->iIdConsulta = $id_consulta;
        }

        public function setIdTratamento($id_tratamento)
        {
            $this->iIdTratamento = $id_tratamento;
        }

    }

?>
