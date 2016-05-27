<?php
    include_once ("../classes/classPersistencia.php");

    class Documentos extends Persistencia {
        private $iId;
        private $iIdPessoa;
        private $sObservacoes;
        private $sImagemCaminho;
        private $dDataDocumento;
        private $dDataCadastro;
		private $sReceituario;

        public function __construct($documento_id = 0) {
            parent::__construct();

            if($documento_id != 0) {
                $this->getDocumentoById($documento_id);
            }else {
                $this->iId = $documento_id;
                $this->iIdPessoa = 0;
                $this->sObservacoes = '';
                $this->sImagemCaminho = '';
                $this->dDataCadastro = '';
		$this->sReceituario = '';
            }
        }

        public function bFetchObject($sSql) {
            $this->bExecute($sSql);
            $this->bDados();

            $res = $this->getDbArrayDados();

            $this->setId(utf8_encode($res['id_documento']));
            $this->setIdPessoa(utf8_encode($res['id_pessoa']));
            $this->setObservacoes(utf8_encode($res['observacoes']));
            $this->setImagemCaminho(utf8_encode($res['imagem_caminho']));
            $this->setDataDocumento($res['data_documento']);
            $this->setDataCadastro($res['data_cadastro']);
	    $this->setReceituario(utf8_encode($res['receituario']));
        }

        public function getDocumentoById($documento_id) {
            $sSql = "SELECT * FROM documentos WHERE id_documento=".$documento_id;

            $this->bFetchObject($sSql);
        }

        public function bUpdate() {
            if(($this->getId()) == 0) {
            #INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
                $sSql = "INSERT INTO documentos (id_pessoa, observacoes, receituario, imagem_caminho, data_documento) VALUES (";
                $sSql .= " ".utf8_decode($this->getIdPessoa()).", ";
                $sSql .= " '".utf8_decode($this->getObservacoes())."', ";
		$sSql .= " '".utf8_decode($this->getReceituario())."', ";
                $sSql .= " '".utf8_decode($this->getImagemCaminho())."', ";
                $sSql .= " '".$this->getDataDocumento()."' )";

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Documento');
                    return false;
                } else {
                    $this->setId(mysql_insert_id());
                    return true;
                }
            } else {
            #ALTERAR UM ENDERECO NO BANCO DE DADOS
                $sSql = "UPDATE convenio SET ";
                $sSql .= " id_pessoa = ".utf8_decode($this->getIdPessoa()).", ";
                $sSql .= " observacoes = '".utf8_decode($this->getObservacoes())."', ";
		$sSql .= " receituario = '".utf8_decode($this->getReceituario())."', ";
                $sSql .= " imagem_caminho = '".utf8_decode($this->getImagemCaminho())."', ";
                $sSql .= " data_documento = '".$this->getDataDocumento()."' ";
                $sSql .= " WHERE id_convenio=".$this->getId();

                if(!$this->bExecute($sSql)) {
                    $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Documentos');
                    return false;
                } else {
                    return true;
                }

            }
        }

        public function bDelete() {
            $sSql = "DELETE FROM documentos WHERE id_documento=".$this->getId();

            if(!$this->bExecute($sSql)) {
                $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Documentos');
                return false;
            } else {
                return true;
            }
        }

        public function toString() {
            echo '### DOCUMENTOS ###<br>';
            echo 'ID..............:'.$this->iId.'<br>';
            echo 'PACIENTE........:'.$this->iIdPessoa.'<br>';
            echo 'OBSERVACOES.....:'.$this->sObservacoes.'<br>';
			echo 'RECEITUARIO.....:'.$this->sReceituario.'<br>';
            echo 'IMAGEM CAMINHO..:'.$this->sImagemCaminho.'<br>';
            echo 'DATA DOCUMENTO..:'.$this->dDataDocumento.'<br>';
            echo 'DATA CADASTRO...:'.$this->dDataCadastro.'<br>';
        }

        #MÉTODOS GET's DA CLASSE
        public function getId() {
            return $this->iId;
        }

        public function getIdPessoa() {
            return $this->iIdPessoa;
        }

        public function getObservacoes () {
            return $this->sObservacoes;
        }
		
	    public function getReceituario () {
            return $this->sReceituario;
        }

        public function getImagemCaminho() {
            return $this->sImagemCaminho;
        }

        public function getDataDocumento() {
            return $this->dDataDocumento;
        }

        public function getDataCadastro() {
            return $this->dDataCadastro;
        }

        #MÉTODOS SET's DA CLASSE
        public function setId($id) {
            $this->iId = $id;
        }

        public function setIdPessoa($id_pessoa) {
            $this->iIdPessoa = $id_pessoa;
        }

        public function setObservacoes($observacoes) {
            $this->sObservacoes = $observacoes;
        }

        public function setReceituario($receita) {
            $this->sReceituario = $receita;
        }		
		
        public function setImagemCaminho($imagem_caminho) {
            $this->sImagemCaminho = $imagem_caminho;
        }

        public function setDataDocumento ($data) {
            $this->dDataDocumento = $data;
        }

        public function setDataCadastro($data_cadastro) {
            $this->dDataCadastro = $data_cadastro;
        }


    }
?>
