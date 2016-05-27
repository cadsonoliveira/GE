<?php

    include_once("classPessoa.php");

    class Usuario extends Pessoa
    {
        private $sLogin;
        private $sSenha;
        private $dUltimoAcesso;
        private $iTipoAcesso;
        private $sCro;
        private $especialidades;

        public function __construct($pessoa_id = 0)
        {
            parent::__construct($pessoa_id);

            if($pessoa_id != 0)
            {
                $this->getUsuarioById($pessoa_id);
            } else
            {
                $this->sLogin = "";
                $this->sSenha = "";
                $this->dUltimoAcesso = "";
                $this->iTipoAcesso = 0;
                $this->sCro = "";
            }
        }

        public function getUsuarioById($pessoa_id)
        {
            $sSql = "SELECT * FROM pessoa WHERE id_pessoa=".$pessoa_id;
            parent::bFetchObject($sSql);
            $sSql = "SELECT * FROM usuario WHERE id_pessoa=".$pessoa_id;
            $this->bFetchObject($sSql);
            $this->updateEspecialidades();
        }
        public function getUsuarioByLogin($pessoa_login)
        {
            $sSql = "SELECT * FROM usuario WHERE login='".$pessoa_login."'";
            $this->bFetchObject($sSql);
            $sSql = "SELECT * FROM pessoa WHERE id_pessoa=".$this->getId();
            parent::bFetchObject($sSql);
        }
        public function validaLogin($login)
        {
            $this->setLogin("");

            $sSql = "SELECT * FROM usuario WHERE login='".$login."'";
            $this->bFetchObject($sSql);

            if($this->getLogin() != "")
                return false;
            return true;
        }

        public function bFetchObject($sql)
        {
            $this->bExecute($sql);
            $this->bDados();

            $res = $this->getDbArrayDados();
            if(isset($res['login']))
            {
				parent::setId($res['id_pessoa']);
                $this->setLogin(utf8_encode($res['login']));
                $this->setSenha(utf8_encode($res['senha']));
                $this->setUltimoAcesso($res['ultimo_acesso']);
                $this->setTipoAcesso($res['tipo_acesso']);
                $this->setCRO($res['cro']);
            }
        }

        public function bUpdate()
        {
            if(($this->getId()) == 0)
            {
                parent::bUpdate();
                #INSERIR UM NOVO USUARIO NO BANCO DE DADOS
                $sSql = "INSERT INTO usuario (id_pessoa, login, senha, ultimo_acesso, tipo_acesso, cro) VALUES (";
                $sSql .= " ".utf8_decode($this->getId()).", ";
                $sSql .= " '".utf8_decode($this->getLogin())."', ";
                $sSql .= " '".utf8_decode($this->getSenha())."', ";
                $sSql .= " NOW(), ";
                $sSql .= " '".utf8_decode($this->getTipoAcesso())."',";
                $sSql .= " '".utf8_decode($this->getCRO())."') ";

                if(!$this->bExecute($sSql))
                {
                    $this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Usuario');
                    return false;
                } else
                {
                    return $this->updateEspecialidadesNoDB();
                }
            } else
            {
                parent::bUpdate();
                #ALTERAR UM ENDERECO NO BANCO DE DADOS
                $sSql = "UPDATE usuario SET ";

                $sSql .= " login = '".utf8_decode($this->getLogin())."', ";
                $sSql .= " senha = '".utf8_decode($this->getSenha())."', ";
                $sSql .= " ultimo_acesso = '".utf8_decode($this->getUltimoAcesso())."', ";
                $sSql .= " tipo_acesso = '".utf8_decode($this->getTipoAcesso())."', ";
                $sSql .= " cro = '".utf8_decode($this->getCRO())."' ";
                $sSql .= " WHERE id_pessoa=".$this->getId();

                if(!$this->bExecute($sSql))
                {
                    $this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Usuario');
                    return false;
                } else
                {
                    return $this->updateEspecialidadesNoDB();
                }

            }
        }

        public function bDelete()
        {
            $sSql = "DELETE FROM usuario WHERE id_pessoa=".$this->getId();
            if(!removeEspecialidadesNoDB()){
	             $this->imprimeErro('Ocorreu um erro ao tentar excluir as especialidades do Usuario');
                return false;
            }
            if(!$this->bExecute($sSql))
            {
                $this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Usuario');
                return false;
            } else
            {
                if(parent::bDelete())
                {
                
                    return true;
                }
            }
        }

        public function toString()
        {
            parent::toString();
            echo '### USUARIO ###<br>';
            echo 'LOGIN.....:'.$this->sLogin.'<br>';
            echo 'SENHA.....:'.$this->sSenha.'<br>';
            echo 'ULTI AC...:'.$this->dUltimoAcesso.'<br>';
            echo 'TIPO AC...:'.$this->iTipoAcesso.'<br>';
            echo 'CRO.......:'.$this->sCro.'<br>';
        }
        ## M�TODOS SOBRE ESPECIALIDADES ##
        public function updateEspecialidades(){
            $this->especialidades = array();
	        $sSql = "SELECT id_especialidade FROM especialidade_usuario WHERE id_pessoa=".(int) $this->getId();
	        $this->bExecute($sSql);
	        $this->bDados();
	        while($data = $this->getDbArrayDados()){
	        	$this->especialidades[] = (int) $data['id_especialidade'];
	        	$this->bDados();
	        }
            
        }
        public function updateEspecialidadesNoDB(){
	        if(!$this->removeEspecialidadesNoDB())
            	return false;
            foreach($this->especialidades as $key => $value){
		        $sSql = "INSERT INTO especialidade_usuario (id_pessoa,id_especialidade) VALUES (".$this->getId().",".$value.");";
		        if(!$this->bExecute($sSql))
            		return false;
	        }
	       
			return true;            
        }
        public function removeEspecialidadesNoDB(){
	        $sSql = "DELETE FROM especialidade_usuario WHERE id_pessoa=".(int)$this->getId();
			return $this->bExecute($sSql);            
        }
        ## M�TODO GET's DA CLASSE ##
        public function getLogin()
        {
            return $this->sLogin;
        }
        public function getSenha()
        {
            return $this->sSenha;
        }
        public function getUltimoAcesso()
        {
            return $this->dUltimoAcesso;
        }
        public function getTipoAcesso()
        {
            return $this->iTipoAcesso;
        }
        public function getCRO()
        {
            return $this->sCro;
        }
        public function getEspecialidades(){
	        return $this->especialidades;
        }
        ## M�TODO SET's DA CLASSE ##
        public function setLogin($login)
        {
            $this->sLogin = $login;
        }
        public function setSenha($senha)
        {
            $this->sSenha = $senha;
        }
        public function setUltimoAcesso($ultimo_acesso)
        {
            $this->dUltimoAcesso = $ultimo_acesso;
        }
        public function setTipoAcesso($tipo_acesso)
        {
            $this->iTipoAcesso = $tipo_acesso;
        }
        public function setCRO($cro)
        {
            $this->sCro = $cro;
        }
        public function setEspecialidades($especialidades_array){
	        $this->especialidades = $especialidades_array;
        }

    }
?>
