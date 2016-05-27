<?php
    include_once ("../classes/classPersistencia.php");
    include_once ("../classes/classEspecialidade.php");
    include_once ("../classes/classConfiguracao.php");
    
    echo $_GET['div_id'].'|;|';

    $erro = -1;
    if(isset($_GET['acao']) && $_GET['acao']=='excluir')
    {
        $persistencia = new Persistencia();
        $sSql = "SELECT * FROM match_code WHERE id_especialidade=".$_GET['id'];

        $persistencia->bExecute($sSql);

        $configuracao = new Configuracao(1);
        $id_especialidade = $configuracao->getIdEspecialidade();

        if($id_especialidade != $_GET['id']){
            if($persistencia->getDbNumRows() > 0){
               echo '<script> alert("Existe Procedimentos Para Esta Especialidade.\nRemova Todos os Procedimentos Antes de Excluir a Especialidade");</script>';
            } else {
                $especialidade = new Especialidade($_GET['id']);
                $especialidade->bDelete();
                $erro = 0;
            }
        } else {
            echo '<script> alert("Não é Possível Excluir a Especialidade Ativa");</script>';
        }
    }

    $config = new Configuracao(1);
    $especialidade = new Especialidade($config->getIdEspecialidade());

    $qtd_resultado_por_pagina = 10;
    if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
        $qtd_resultado_por_pagina = $_GET['qtdpag'];

    $pag_atual = (isset($_GET['pag'])&&$_GET['pag']!=0) ? ($_GET['pag']-1) : 0;

    $pers = new Persistencia();

    $sSql = "SELECT * FROM especialidade ORDER BY descricao";
    $pers->bExecute($sSql);


    $qtd_registros = $pers->getDbNumRows();
    $qtd_paginas = ($qtd_registros%$qtd_resultado_por_pagina==0) ? ($qtd_registros/$qtd_resultado_por_pagina) : ((int)($qtd_registros/$qtd_resultado_por_pagina)+1);

    $cont = 0;
    $page_base = 'especialidades.php?qtdpag='.$qtd_resultado_por_pagina;

    if(($qtd_registros > 0) && ($qtd_paginas > 1)){
        $primeira_pagina = '<a onclick="altera_pagina('.$qtd_resultado_por_pagina.',1)"<img src="img/p_f.gif" alt="Primeira" width="10" height="10" border="0" /></a>';
    } else {
        $primeira_pagina = '<a><img src="img/p_f.gif" alt="Primeira" width="10" height="10" border="0" /></a>';
    }
	
    if($pag_atual > 0){
        $pag_anterior = '<a onclick="altera_pagina('.$qtd_resultado_por_pagina.','.$pag_atual.')"><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
    } else {
        $pag_anterior = '<a><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
    }
	
    if($pag_atual < $qtd_paginas-1){
        $prox_pagina = '<a onclick="altera_pagina('.$qtd_resultado_por_pagina.','.($pag_atual+2).')"><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
    } else {
        $prox_pagina = '<a><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
    }

    if($pag_atual < ($qtd_paginas-1)){
        $ultima_pagina = '<a onclick="altera_pagina('.$qtd_resultado_por_pagina.','.$qtd_paginas.')"><img src="img/p_l.gif" alt="Última" width="10" height="10" border="0" /></a><br />';
    } else {
        $ultima_pagina = '<a><img src="img/p_l.gif" alt="Última" width="10" height="10" border="0" /></a><br />';
    }

    $paginacao = '
                    <div id="pages">
                    '.$primeira_pagina.'
                    '.$pag_anterior.'
            ';
    if($qtd_paginas > 0)
    {
        while($cont < $qtd_paginas)
        {
            if($pag_atual == $cont)
            {
                $paginacao .= '<span>'.($cont+1).'</span>';
            } else {
                $paginacao .= '<a onclick="altera_pagina('.$qtd_resultado_por_pagina.','.($cont+1).')" >'.($cont+1).'</a>';
            }
            $cont++;
        }
    } else {
        $paginacao .= '<span>1</span>';
    }
    $paginacao .= '
                    '.$prox_pagina.'
                    '.$ultima_pagina.'
                    </div>
            ';


    $linha_registro = $pag_atual * $qtd_resultado_por_pagina;
    $tabela = "";

    if(!$pers->getDbNumRows() > 0)
    {
        $tabela = '
            <tr>
                <td colspan="4" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
            </tr>';
    } else {
        for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++)
        {
            if($linha_registro < $qtd_registros)
            {
                $pers->bCarregaRegistroPorLinha($linha_registro);
                $res = $pers->getDbArrayDados();

                $tabela .= '
                    <tr>
                        <td onclick="editar_especialidade('.$res['id_especialidade'].');">'.$res['id_especialidade'].'</td>
                        <td onclick="editar_especialidade('.$res['id_especialidade'].');" class="o">'.utf8_encode($res['descricao']).'</td>
                        <td class="o">
                            <a onclick="editar_especialidade('.$res['id_especialidade'].');">
                                <img src="img/edit.gif" alt="Editar registro" title="Editar registro" width="16" height="16" border="0" />
                            </a>
                            <a onclick="selecionar_especialidade('.$res['id_especialidade'].',\''.$res['descricao'].'\');">
                                <img src="img/checked.gif" alt="Selecionar especialidade" title="Selecionar especialidade" width="16" height="16" border="0" />
                            </a>
                            <a onclick="remover_especialidade('.$res['id_especialidade'].','.$qtd_resultado_por_pagina.','.($pag_atual+1).');">
                                <img src="img/delete.gif" alt="Remover registro" title="Remover registro" width="16" height="16" border="0" />
                            </a>
                        </td>
                    </tr>
                    ';

                $linha_registro++;
            }
        }
    }

?>

<h2>Especialidades</h2>
<div class="bt_left"><a href="#" onclick="nova_especialidade();" class="md">Adicionar</a></div>
<div class="clear"></div>
<hr noshade="noshade" />
<h2>Selecione uma Especialidade</h2>
    <?php
    echo $paginacao;
    ?>
<div class="listarResultados" style="margin-bottom:10px;">
  <label>Resultados por P&aacute;gina</label>
    <select onchange="qtdpag();" id="qnt_pag" name="qnt_pag">
        <option <?php if($qtd_resultado_por_pagina==10) echo "selected='selected'"; ?> value="10">10</option>
        <option <?php if($qtd_resultado_por_pagina==20) echo "selected='selected'"; ?> value="20">20</option>
        <option <?php if($qtd_resultado_por_pagina==30) echo "selected='selected'"; ?> value="30">30</option>
    </select>
</div>
<!-- @.pack - Pages Ends -->
<!-- @.pack - Tabular Data Begins -->
<!-- ## Para colunas ímpares, aplicar a classe '.o', como demonstrado abaixo ## -->
<table cellpadding="0" cellspacing="1px" width="100%" class="ef_tr_highlight">
  <tr>
    <!-- ## Para Sort Desc a imagem: t_sort_d.gif - Para Sort Asc a imagem: t_sort_u.gif - Para Sem Sort a imagem: t_sort_n.gif ## -->
    <th width="81"><a href="#"><img src="img/t_sort_d.gif" alt="Ordenado:Decrescente" width="10" height="10" border="0" /></a> Número</th>
    <th class="o"><a href="#"><img src="img/t_sort_n.gif" alt="Ordenar" width="10" height="10" border="0" /></a> Nome</th>
    <th width="58" class="o">Opções</th>
  </tr>
    <?php
        echo $tabela;
    ?>
</table>
<!-- @.pack - Tabular Data Ends - Pages(Paginator) Begins -->
<!-- ## Atenção - Páginas com número maior que 999 ainda não são suportadas por esse layout (overflow não aparecerá). Se for **realmente** preciso desta funcionalidade neste Beta basta falar - Se você mesmo quiser resolver esse problema basta pensar de modo tricky em um esquema **box-in-a-box** ## -->
<!-- <div id="pages"><a href="#"><img src="img/p_f.gif" alt="Primeira" width="10" height="10" border="0" /></a><a href="#"><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a><span>1</span><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><a href="#">7</a><a href="#">8</a><a href="#">9</a><img src="img/dots.gif" width="10" height="10" class="dots" alt="" /><a href="#">14</a><a href="#">15</a><a href="#">16</a><a href="#"><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a><a href="#"><img src="img/p_l.gif" alt="Última" width="10" height="10" border="0" /></a></div> -->
    <?php
        echo $paginacao;
    ?>
<!-- @.pack - Pages Ends -->
