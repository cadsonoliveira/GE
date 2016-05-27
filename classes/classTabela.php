<?php
include_once("classPersistencia.php");

class Tabela extends Persistencia {

    private $idTabela;
    private $arrayCampos;
    private $arrayAcoes;
    private $widthColunaAcoes;
    private $classeTabela;
    private $classeLinha1;
    private $classeLinha2;
    private $cellPadding;
    private $cellSpacing;
    private $border;
    private $width;
    private $sSql;

    public function getIdTabela() {
        return $this->idTabela;
    }
    public function getClasseTabela() {
        return $this->classeTabela;
    }
    public function getClasseLinha1() {
        return $this->classeLinha1;
    }
    public function getClasseLinha2() {
        return $this->classeLinha2;
    }
    public function getArrayAcoes() {
        return $this->arrayAcoesNome;
    }
    public function getWidthColunaAcoes() {
        return $this->arrayAcoesNome;
    }
    public function getCellPadding() {
        return $this->cellPadding;
    }
    public function getCellSpacing() {
        return $this->cellSpacing;
    }
    public function getWidth() {
        return $this->width;
    }
    public function getBorder() {
        return $this->border;
    }
    public function getSql() {
        return $this->sSql;
    }
    public function getArrayCampos() {
        return $this->arrayCampos;
    }
    public function Tabela($sql='', $table_id=0) {
        parent::__construct();
        $this->idTabela = $table_id;
        $this->sSql = $sql;

        $this->classeTabela = "ef_tr_highlight";
        $this->cellPadding = "0";
        $this->cellSpacing = "1px";
        $this->width = "100%";
        $this->border = "0";
    }

    public function _Tabela() {
        unset($this);
    }

    public function bAddCampo($campo, $descricao="", $atributos="") {
        if (isset($this->arrayCampos)) {
            $i = sizeof($this->arrayCampos);
        } else {
            $i = 0;
        }
        $this->arrayCampos[$i] = array("campo" =>$campo, "descricao" => $descricao, "atributos" => $atributos);
        return true;
    }
    public function bAddAcao($nome, $link, $imagem, $width, $evento) {
        if (isset($this->arrayAcoes)) {
            $i = sizeof($this->arrayAcoes);
        } else {
            $i = 0;
        }
        $this->arrayAcoes[$i] = array("nome" =>$nome, "link" => $link, "imagem" => $imagem, "width" => $width, "evento" => $evento);
        return true;
    }

    public function sGetHTML() {
        $this->bExecute($this->getSql());
        #CAPTURANDO COLUNAS DA TABELA
        $sColunas = "<tr>";
        for($i=0 ; $i < $this->getDbNumFields() ; $i++) {
            $campo = @mysql_field_name($this->getDbResExecute(), $i);
            foreach($this->arrayCampos as $field) {
                if($field['campo'] == $campo) {
                    $sColunas .= '
                        <th '.$field['atributos'].'>'.$field['descricao'].'</th>
                    ';
                }
            }
        }
        $sColunas .= '<th colspan="'.sizeof($this->arrayAcoes).'">Opera&ccedil;&otilde;es</th>';
        $sColunas .= "</tr>";
        #CAPTURANDO DADOS PARA PREENCHER A TABELA
        $id_obj = "";

        for($i=0 ; $i < $this->getDbNumRows() ; $i++) {
            $sColunas .= "<tr>";
            $this->bCarregaRegistroPorLinha($i);
            $vet_dados = $this->getDbArrayDados();
            for($j=0 ; $j < $this->getDbNumFields() ; $j++) {
                $campo = @mysql_field_name($this->getDbResExecute(), $j);
                foreach($this->arrayCampos as $field) {
                    if($field['campo'] == $campo) {
                        $valor = $vet_dados[$campo];
                        if(substr($campo, 0, 4) == "data")
                        {
                            $aux = substr($vet_dados[$campo], 0,10);
                            $valor = decodeDate($aux);
                        }

                        $sColunas .= '
                            <td>'.$valor.'</td>
			';				
                    }

                    $campo_id = substr($field['campo'], 0,2);
                    if($campo_id == "id" && $j==0)
                    {
                        $id_obj = $vet_dados[$campo];
                    }
                }
            }
            foreach($this->arrayAcoes as $acao) {
                $sColunas .= '
                    <td align="center" width="'.$acao['width'].'">
                        <a '.$acao['evento']['tipo'].'='.$acao['evento']['nome'].'(';
                foreach($acao['evento']['atributos'] as $ev) {
                    if($ev == "id") {
                       $sColunas .= $id_obj;
                    }
                }
                $sColunas .= ');
                        href="'.$acao['link'].'">
                        <img border="0" src="'.$acao['imagem'].'" alt="'.$acao['nome'].'" title="'.$acao['nome'].'" />
                        </a>
                    </td>
		';				
            }
            $sColunas .= "</tr>";

        }

        #MONTANDO A TABELA
        $tabela = '
            <table 	cellpadding="'.$this->getCellPadding().'"
                            cellspacing="'.$this->getCellSpacing().'"
                            width="'.$this->getWidth().'"
                            border="'.$this->getBorder().'"
                            class="'.$this->getClasseTabela().'" >
                    <!-- ## Para Sort Desc a imagem: t_sort_d.gif - Para Sort Asc a imagem: t_sort_u.gif - Para Sem Sort a imagem: t_sort_n.gif ## -->
        ';

        $tabela .= $sColunas;
        $tabela .= '
		</table>
	';
        echo $tabela;
    }
}

?>