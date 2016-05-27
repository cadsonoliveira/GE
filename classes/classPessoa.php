<?php

	include_once("classPersistencia.php");
	include_once("classContato.php");
	include_once("classEndereco.php");

	class Pessoa extends Persistencia
	{
		private $iId;
		private $sNome;
		private $sSexo;
		private $sRG;
		private $sCpf;
		private $dDataNasc;
		private $oContato;
		private $oEndereco;
		
		public function __construct($pessoa_id = 0)
		{
			parent::__construct();
			
			if($pessoa_id != 0) {
				$this->getPessoaById($pessoa_id);
			} else {
				$this->iId = $pessoa_id;
				$this->sNome = "";
				$this->sSexo = "";
				$this->sRG = "";
				$this->dDataNasc = NULL;
				$this->sCpf = "";
				$this->oContato = new Contato(0);
				$this->oEndereco = new Endereco(0);
			}	
				
		}
		
		public function getPessoaById($pessoa_id) {
			$sSql = "SELECT * FROM pessoa WHERE id_pessoa=".$pessoa_id;
			$this->bFetchObject($sSql);
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			$res = $this->getDbArrayDados();
				
			$this->setId($res['id_pessoa']);
			$this->setNome(utf8_encode($res['nome']));
			$this->setSexo(utf8_encode($res['sexo']));
			$this->setRg(utf8_encode($res['rg']));
			$this->setCpf(utf8_encode($res['cpf']));
			$this->setDataNasc(utf8_encode($res['data_nasc']));
			$cont = new Contato($res['id_contato']);
			$this->setContato($cont);
			$end = new Endereco($res['id_endereco']);
			$this->setEndereco($end);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				$this->getContato()->bUpdate();
				$this->getEndereco()->bUpdate();
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO pessoa (nome, sexo, rg, cpf, data_nasc, id_contato, id_endereco) VALUES (";
				$sSql .= " '".utf8_decode($this->getNome())."', ";
				$sSql .= " '".utf8_decode($this->getSexo())."', ";
				$sSql .= " '".utf8_decode($this->getRg())."', ";
				$sSql .= " '".utf8_decode($this->getCpf())."', ";
				if(($this->getDataNasc() != NULL) || ($this->getDataNasc() != ""))
					$sSql .= " '".utf8_decode($this->getDataNasc())."', ";
				else
					$sSql .= " NULL, ";
				$sSql .= " ".utf8_decode($this->getContato()->getId()).", ";
				$sSql .= " ".utf8_decode($this->getEndereco()->getId())." )";
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Pessoa');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				$this->getContato()->bUpdate();
				$this->getEndereco()->bUpdate();
				$sSql = "UPDATE pessoa SET ";
				$sSql .= " nome = '".utf8_decode($this->getNome())."', ";
				$sSql .= " sexo = '".utf8_decode($this->getSexo())."', ";
				$sSql .= " rg = '".utf8_decode($this->getRg())."', ";
				$sSql .= " cpf = '".utf8_decode($this->getCpf())."', ";

				if(($this->getDataNasc() != NULL) || ($this->getDataNasc() != ""))
					$sSql .= " data_nasc = '".utf8_decode($this->getDataNasc())."', ";
				$sSql .= " id_contato = '".utf8_decode($this->getContato()->getId())."', ";
				$sSql .= " id_endereco = '".utf8_decode($this->getEndereco()->getId())."' ";
				$sSql .= " WHERE id_pessoa=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Pessoa');
					return false;
				} else {
					return true;
				}
				
			}
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM pessoa WHERE id_pessoa=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Pessoa');
				return false;
			} else {
				if($this->oContato->bDelete() && $this->oEndereco->bDelete()) {
					return true;
				} else {
					return false;
				}
			}
		}
		
		public function toString() {
			echo '### PESSOA ###<br>';
			echo 'ID.......:'.$this->iId.'<br>';
			echo 'NOME.....:'.$this->sNome.'<br>';
			echo 'SEXO.....:'.$this->sSexo.'<br>';
			echo 'RG.......:'.$this->sRG.'<br>';
			echo 'CPF......:'.$this->sCpf.'<br>';
			echo 'DATA NAS.:'.$this->dDataNasc.'<br>';
			$this->oContato->toString();
			$this->oEndereco->toString();
		}
		## MÉTODO GET's DA CLASSE ## 
		public function getId() {
			return $this->iId;
		}		
		public function getNome() {
			return $this->sNome;
		}
		public function getSexo() {
			return $this->sSexo;
		}
		public function getRG() {
			return $this->sRG;
		}
		public function getCpf() {
			return $this->sCpf;
		}		
		public function getDataNasc() {
			return $this->dDataNasc;
		}
		public function getIdade() {
			$dataNasc = $this->getDataNasc();
			if($dataNasc != "")
			{
				$idade = 0;
				
				#$data_atual = new date("d/m/Y");
				$ano_atual = date("Y");
				$mes_atual = date("m");
				$dia_atual = date("d");
				
				list ($ano_nasc, $mes_nasc, $dia_nasc) = preg_split ('[-]', $dataNasc);
				
				
				$idade = 0;
				
				if($ano_nasc < $ano_atual)
					$idade = $ano_atual - $ano_nasc;
				
				if(($mes_atual == $mes_nasc) && ($dia_atual < $dia_nasc))
				{
					$idade -= 1;
				} else {
					if($mes_atual < $mes_nasc)
					{
						$idade -= 1;
					}
				}
	
				return $idade;
			}
		}
		public function getContato() {
			return $this->oContato;
		}
		public function getEndereco() {
			return $this->oEndereco;
		}
		
		## MÉTODO SET's DA CLASSE ## 
		public function setId($id) {
			$this->iId = $id;
		}		
		public function setNome($nome) {
			$this->sNome = $nome;
		}
		public function setSexo($sexo) {
			$this->sSexo = substr($sexo,0,1);
		}
		public function setRG($rg) {
			$this->sRG = $rg;
		}
		public function setCpf($cpf) {
			$this->sCpf = $cpf;
		}
		public function setDataNasc($data_nasc) {
			$this->dDataNasc = $data_nasc;
		}
		public function setContato($obj_contato) {
			$this->oContato = $obj_contato;
		}
		public function setEndereco($obj_endereco) {
			$this->oEndereco = $obj_endereco;
		}
		

	}
?>
