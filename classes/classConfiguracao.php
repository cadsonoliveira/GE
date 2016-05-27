<?php

    include_once("classPersistencia.php");

    class Configuracao extends Persistencia {
        private $iId;
        private $iIdEspecialidade;
        private $sTexto_Index;

        public function __construct($configuracao_id = 0) {
            parent::__construct();

            if($configuracao_id != 0) {
                $this->getConfiguracaoById($configuracao_id);
            } else {
                $this->iId = $configuracao_id;
                $this->iIdEspecialidade = "";
                $this->sTexto_Index = "";
            }
        }

        public function bFetchObject($sql) {
            $this->bExecute($sql);
            $this->bDados();

            $res = $this->getDbArrayDados();

            if($res['id_configuracao']!="") {
                $this->setId(utf8_encode($res['id_configuracao']));
                $this->setIdEspecialidade(utf8_encode($res['id_especialidade']));
                $this->setTextoIndex(utf8_encode($res['texto_index']));
            }
            else //se ainda nao existe registro de configuracao
            {
                $this->setId(0);
                $this->setIdEspecialidade("");
                $this->setTextoIndex("");
            }
        }

        public function getConfiguracaoById($configuracao_id) {
            $sSql= "SELECT * FROM configuracao WHERE id_configuracao=".$configuracao_id;

            $this->bFetchObject($sSql);
        }

        public function bUpdate() {
            if(($this->getId()) == 0) {
            #INSERIR UM NOVA ESPECIALIDADE   NO BANCO DE DADOS
                $sSql = "INSERT INTO configuracao (id_especialidade,texto_index) VALUES (";
                $sSql .= " '".utf8_decode($this->getIdEspecialidade())."', ";
                $sSql .= " '".utf8_decode($this->getTextoIndex())."' )";

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Configuracao');
                    return false;
                } else {
                    $this->setId(mysql_insert_id());
                    return true;
                }
            } else {
            #ALTERAR UM ENDERECO NO BANCO DE DADOS
                $sSql = "UPDATE configuracao SET ";
                $sSql .= " id_especialidade = '".utf8_decode($this->getIdEspecialidade())."', ";
                $sSql .= " texto_index = '".utf8_decode($this->getTextoIndex())."' ";

                $sSql .= " WHERE id_configuracao=".$this->getId();

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Configuracao');
                    return false;
                } else {
                    return true;
                }

            }
        }

        public function bDelete() {
            $sSql = "DELETE FROM configuracao WHERE id_configuracao=".$this->getId();

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Especialidade');
                return false;
            } else {
                return true;
            }
        }

        public function toString() {
            echo '### CONTATO ###<br>';
            echo 'ID...........:'.$this->iId.'<br>';
            echo 'ID ESPECIALIDADE.......:'.$this->iIdEspecialidade.'<br>';
            echo 'TEXTO INDEX.......:'.$this->sTextoIndex.'<br>';
        }

        ## M�TODO GET's DA CLASSE ##
        public function getId() {
            return $this->iId;
        }
        public function getIdEspecialidade() {
            return $this->iIdEspecialidade;
        }
        public function getTextoIndex() {
            return $this->sTextoIndex;
        }

        ## M�TODO SET's DA CLASSE ##
        public function setId($id) {
            $this->iId = $id;
        }
        public function setIdEspecialidade($id_especialidade) {
            $this->iIdEspecialidade = $id_especialidade;
        }
        public function setTextoIndex($texto_index) {
            $this->sTextoIndex = $texto_index;
        }
    }
?>
