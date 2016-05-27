<?php
	
	session_start();
	
	echo 'div_resultado|;|';
	
    include_once("../classes/classPaciente.php");
    include_once("../classes/classTratamento.php");
    include_once("../classes/classMatchCode.php");
    include_once ("../classes/classPersistencia.php");


	$_SESSION['_get'] = $_GET;
	$_SESSION['_post'] = $_POST;

    $action_form = "../controladores/estatisticas.php";
    $initState = 0;
    $matchcode = new MatchCode(1);

    $qtd_resultado_por_pagina = 10;
    if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
        $qtd_resultado_por_pagina = $_GET['qtdpag'];

    $pag_atual = (isset($_GET['pag'])&&($_GET['pag'])!=0) ? ($_GET['pag'])-1 : 0;
        
    $pers = new Persistencia();

    #modificar sql inicial - esse � executado ao abrir a pagina
    $sSql = "SELECT DISTINCT
                tratamento.id_tratamento,
                pessoa.sexo,
                DATEDIFF( tratamento.data_inic, pessoa.data_nasc ) AS Idade,
                pessoa.data_nasc,
                tratamento.dente,
                tratamento.data_inic,
                tratamento.data_term,
                tratamento.sucesso,
                match_code.id_match_code,
                tratamento.id_match_code,
                pessoa.id_pessoa,
                tratamento.id_pessoa
            FROM
                tratamento
            INNER JOIN match_code ON (tratamento.id_match_code=match_code.id_match_code)
            INNER JOIN pessoa ON (pessoa.id_pessoa=tratamento.id_pessoa)
            WHERE 1=1";

    #adicionar as condicoes de igualdade. Os post serao setados ao selecionar o campo ativar
    $sCondSql = "";
	if(isset($_POST['matchcode_active']) and $_POST['matchcode_active']==1 and isset($_GET['codes']) and $_GET['codes']!="," and $_GET['codes']!="")
	{
          $sCondSql .= " AND match_code.id_match_code IN (".$_GET['codes'].")";
	}
    if(isset($_POST['sexo_hd']) and $_POST['sexo_hd']!=-1)
	{
		$sCondSql .= " AND pessoa.sexo = '".$_POST['sexo_hd']."'";
	}
	if(isset($_POST['idademin_hd']) and $_POST['idademin_hd']!=-1 and $_POST['idademax_hd']!=-1)
	{
        $idademin_Dias = $_POST['idademin_hd']*365;
        $idademax_Dias = $_POST['idademax_hd']*365+365;
        $sCondSql .= " AND DATEDIFF( tratamento.data_inic, pessoa.data_nasc ) BETWEEN '".$idademin_Dias."' AND '".$idademax_Dias."'";
   	}
    if($_POST['dataIn_active']==1 and $_POST['dataFim_active']==1 and $_POST['dtinicio_hd']!='undefined-undefined-' and $_POST['dtfim_hd']!='undefined-undefined-')
	{
        $sCondSql .= " AND tratamento.data_inic = '".$_POST['dtinicio_hd']."' AND tratamento.data_term = '".$_POST['dtfim_hd']."'";
	}
    else
    {
       if($_POST['dataIn_active']==1 and $_POST['dtinicio_hd']!='undefined-undefined-')
	   {
           $data = date('Y')."-".date('m')."-".date('d');
           $sCondSql .= " AND tratamento.data_inic BETWEEN '".$_POST['dtinicio_hd']."' AND '".$data."'";
	   }
       if($_POST['dataFim_active'] and $_POST['dtfim_hd']!='undefined-undefined-')
	   {
           $data = explode("-", $_POST['dtfim_hd']);
           $data[0]=$data[0]-2; //dois anos atras
           $data = $data[0].'-'.$data[1].'-'.$data[2];
           $sCondSql .= " AND tratamento.data_term BETWEEN '".$data."' AND '".$_POST['dtfim_hd']."'";
	   }
	}
 	if(isset($_POST['sucesso_hd']) and $_POST['sucesso_hd']!=-1){
		$sCondSql .= " AND tratamento.sucesso = '".$_POST['sucesso_hd']."'";
	}
	
	if(isset($_POST['dentes']) and $_POST['dentes']!='-1' and $_POST['dentes_active']==1){
        $dentes = explode(",", $_POST['dentes']);
        $sCondSql .= " AND (tratamento.dente LIKE '".$dentes[0]."'";
        foreach($dentes as $ind=>$valor)
           if($ind!=0)   $sCondSql .= " OR tratamento.dente LIKE '".$valor."'";
		$sCondSql .= " )";
    }
	//echo $sSql.$sCondSql;
	//exit();
    //Quantidade de Sucessos, Insucessos, Pendentes e Cancelados
    $qtd_insucesso=0;
    $sSqlInSucesso = $sSql.$sCondSql." AND tratamento.sucesso = 0";
    $pers->bExecute($sSqlInSucesso);
    $qtd_insucesso=$pers->getDbNumRows();
    
    $qtd_sucesso=0;
    $sSqlSucesso = $sSql.$sCondSql." AND tratamento.sucesso = 1";
    $pers->bExecute($sSqlSucesso);
    $qtd_sucesso=$pers->getDbNumRows();
    
    $qtd_pendente=0;
    $sSqlPendente = $sSql.$sCondSql." AND tratamento.sucesso = 2";
    $pers->bExecute($sSqlPendente);
    $qtd_pendente=$pers->getDbNumRows();

    $qtd_cancelado=0;
    $sSqlCancelado = $sSql.$sCondSql." AND tratamento.sucesso = 3";
    $pers->bExecute($sSqlCancelado);
    $qtd_cancelado=$pers->getDbNumRows();

    $sSql .= $sCondSql.' ORDER BY match_code.id_match_code';
    $pers->bExecute($sSql);
    $qtd_registros = $pers->getDbNumRows();
    $qtd_paginas = ($qtd_registros%$qtd_resultado_por_pagina==0) ? ($qtd_registros/$qtd_resultado_por_pagina) : ((int)($qtd_registros/$qtd_resultado_por_pagina)+1);

    $cont = 0;
	//Primeira página
	if($pag_atual != 0){
		$primeira_pagina = '<button type="button" onclick="executa_filtro(1,'.$qtd_resultado_por_pagina.');">&laquo; Primeira</button>';
	}
	else{
		$primeira_pagina ='<button type="button" style="visibility:hidden">&laquo; Primeira</button>';
	}
	
	/*//Página Anterior
	if($pag_atual > 0){
		$pag_anterior = '<a onclick="executa_filtro('.($pag_atual).');"><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
	} else {
		$pag_anterior = '<a><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
	}
	
	//Próxima Página
	if($pag_atual < $qtd_paginas-1) {
		$prox_pagina = '<a onclick="executa_filtro('.($pag_atual+2).');"><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
	} else {
		$prox_pagina = '<a><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
	}*/
	
	//Última página
	if($pag_atual < ($qtd_paginas-1)) {
		$ultima_pagina = '<button type="button" onclick="executa_filtro('.($qtd_paginas).');">Última &raquo;</button>';
	} else {
		$ultima_pagina = '<button type="button" style="visibility:hidden">Última &raquo;</button>';
	}

    $paginacao = '
		<div id="navegacaoPaginas"><p>
		'.$primeira_pagina.'
		
	';
	if($qtd_paginas > 0){
		while($cont < $qtd_paginas){
			if($pag_atual == $cont){
				$paginacao .= '<span>'.($cont+1).'</span>';
			} else {
				$paginacao .= '<a onclick="executa_filtro('.($cont+1).','.$qtd_resultado_por_pagina.');" title="Ir para a página '.($cont+1).'">'.($cont+1).'</a>';
			}
			$cont++;
		}
	} else {
		$paginacao .= '<span>1</span>';
	}
	$paginacao .= '
		
		'.$ultima_pagina.'
		</p></div>
	';

	$linha_registro = $pag_atual * $qtd_resultado_por_pagina;
	$tabela = "";

	if(!$pers->getDbNumRows() > 0) {
		$tabela = '<tr>
				   	<td colspan="8" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
				   </tr>';
	} else {
		for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++){
			if($linha_registro < $qtd_registros) {
				$pers->bCarregaRegistroPorLinha($linha_registro);
				$res = $pers->getDbArrayDados();
				
				if($cont % 2 == 0){
					$cor_linha_tabela = "tableColor1";
				}else{
					$cor_linha_tabela = "tableColor2";
				}
				
                if($res['sexo']=='M')
                    $sexo='Masculino';
                else
                    $sexo='Feminino';
                switch($res['sucesso']){
                   case 0:
                      $sucesso='Insucesso';
                      break;
                   case 1:
                      $sucesso='Sucesso';
                      break;
                   case 2:
                      $sucesso='Pendente';
                      break;
                   case 3;
                      $sucesso='Cancelado';
                      break;
                }

                $inic_data = explode("-", $res['data_inic']);
			    $data_inic = $inic_data[2].'/'.$inic_data[1].'/'.$inic_data[0];
                $data1 = mktime(0,0,0,$inic_data[1],$inic_data[2],$inic_data[0]);
                
                if($res['data_term']!=""){
                   $term_data= explode("-",$res['data_term']);
                   $data_term = $term_data[2].'/'.$term_data[1].'/'.$term_data[0];
                   $data2 = mktime(0,0,0,$term_data[1],$term_data[2],$term_data[0]);
                }
                else{
                   $data_term="Em andamento";
                   $data2 = mktime(0,0,0,date("m"),date("d"),date("Y"));
                }

                $total=($data2 - $data1)/86400;
                $anos = floor($total/365);
                $meses = floor(($total-$anos*365)/30);
                $dias = ceil($total-$anos*365-$meses*30);

                //data do inicio do tratamento
                $idade = floor($res['Idade']/365);


                $tabela .= '
                  <tr class="'.$cor_linha_tabela.'">
                    <td class="matchCode">'.$res['id_match_code'].'</td>
                    <td class="sexo">'.$sexo.'</td>
                    <td class="numero">'.$idade.'</td>
                    <td class="data">'.$data_inic.'</td>
                    <td class="data">'.$data_term.'</td>
                    <td><em>'.$anos.'</em> ano(s), <em>'.$meses.'</em> mes(es) e <em>'.$dias.'</em> dia(s)</td>
                    <td class="dente">'.$res['dente'].'</td>
                    <td class="resultado">'.$sucesso.'</td>
                    <td class="opcoesEstatisticas">
						<span style="display:block; margin:auto; width:20px;">
							<a class="ir documentos" onclick=carrega_doc('.$res['id_tratamento'].') alt="Dados Tratamento" title="Dados Tratamento">Dados Tratamento</a>
						</span>
					</td>
                  </tr>
				  ';
				$linha_registro++;
			}
		}
	}
?>

<h3 class="tituloBox">Resultado</h3>
<div class="formularioDividido">
        <!-- ** ATENÇÃO - REMOVA O COMENTÁRIO ABAIXO QUANDO A PESQUISA FOR FEITA ** -->
        <!-- <div id="q_result">O pesquisa por "XXXXXXX" retornou #### resultados.</div> -->
        <!-- @.pack - Tabular Data Ends - Pages(Paginator) Begins -->
        <!-- ## Atenção - Páginas com número maior que 999 ainda não são suportadas por esse layout (overflow não aparecerá). Se for **realmente** preciso desta funcionalidade neste Beta basta falar - Se você mesmo quiser resolver esse problema basta pensar de modo tricky em um esquema **box-in-a-box** ## -->
        <?php
            echo "<p><strong>Total de Resultados:</strong> ".$qtd_registros."</p>";
            if($qtd_registros!=0){
               $porc_sucesso = ($qtd_sucesso)/($qtd_registros)*100;
               $porc_insucesso = ($qtd_insucesso)/($qtd_registros)*100;
               $porc_pendente = ($qtd_pendente)/($qtd_registros)*100;
               $porc_cancelado = ($qtd_cancelado)/($qtd_registros)*100;
               
               echo "<p><strong>Sucesso:</strong> ".sprintf('%.2f',$porc_sucesso)."% </p> ";
               echo "<p><strong>Insucesso:</strong> ".sprintf('%.2f',$porc_insucesso)."% </p>";
               echo "<p><strong>Pendente:</strong> ".sprintf('%.2f',$porc_pendente)."% </p> ";
               echo "<p><strong>Cancelado:</strong> ".sprintf('%.2f',$porc_cancelado)."% </p>";
               echo "<button onclick=\"abrir_popUp(".$qtd_sucesso.",".$qtd_insucesso.",".$qtd_pendente.",".$qtd_cancelado.");\" type=\"button\">Gerar Gráfico</button>";
            }
            //echo $paginacao;
		?>
</div>
		<div class="listarResultados" style="margin-bottom:10px;">
	          <label>Resultados por P&aacute;gina</label>
	          <select name="qnt_pag" id="qnt_pag" onchange="executa_filtro(<?php echo $pag_atual; ?>)">
	            <option <?php if($qtd_resultado_por_pagina==10) echo "selected='selected'"; ?> value="10" >10</option>
	            <option <?php if($qtd_resultado_por_pagina==20) echo "selected='selected'"; ?> value="20">20</option>
	            <option <?php if($qtd_resultado_por_pagina==30) echo "selected='selected'"; ?> value="30">30</option>
	          </select>
	        </div>


        <!-- @.pack - Pages Ends -->
        <!-- @.pack - Tabular Data Begins -->
        <!-- ## Para colunas ímpares, aplicar a classe '.o', como demonstrado abaixo ## -->
        <table title="Lista de Documentos do paciente" summary="Lista dos documentos do paciente">
         <thead>
          <tr>
            <th class="matchCode">Match Code</th>
            <th class="sexo">Sexo</th>
            <th class="numero">Idade</th>
            <th class="data">Início</th>
            <th class="data">Fim</th>
            <th>Tempo Gasto</th>
            <th class="dente">Dente</th>
            <th class="resultado">Resultado</th>
            <th class="opcoesEstatisticas">Opções</th>
          </tr>
         </thead>
			<?php
				echo $tabela;
            ?>
        </table>
        <!-- @.pack - Tabular Data Ends - Pages(Paginator) Begins -->
        <!-- ## Atenção - Páginas com número maior que 999 ainda não são suportadas por esse layout (overflow não aparecerá). Se for **realmente** preciso desta funcionalidade neste Beta basta falar - Se você mesmo quiser resolver esse problema basta pensar de modo tricky em um esquema **box-in-a-box** ## -->
        <p id="paginacao">Página <?php echo($pag_atual+1); ?> de  <?php echo($qtd_paginas); ?></p>
		<?php
			echo $paginacao;
		?>