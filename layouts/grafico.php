<?php
	//Antiga página grafico_geral.php
     $action_form="../controladores/graficoGerar.php?a=1";
     
     $total=0;

     $sucesso = $_GET['sucesso'];
     $insucesso = $_GET['insucesso'];
     $pendente = $_GET['pendente'];
     $cancelado = $_GET['cancelado'];
     /*
	 echo 'Sucesso: '.$sucesso;
	 echo '<br>';
	 echo 'Insucesso: '.$insucesso;
	 echo '<br>';
	 echo 'pendente: '.$pendente;
	 echo '<br>';
	 echo 'Cancelado: '.$cancelado;
	 echo '<br>';
	 
	 print_r($_GET);
	 
	 exit();*/
	 
     //persistencia dos checkboxes
     $suc_checked="";
     $insuc_checked="";
     $pend_checked="";
     $canc_checked="";

     if(isset($_GET['suc_acv'])){
        $total+=$sucesso;
        $action_form.="&sucesso=".$sucesso;
        $suc_checked="checked";
     }
     if(isset($_GET['ins_acv'])){
        $total+=$insucesso;
        $action_form.="&insucesso=".$insucesso;
        $insuc_checked="checked";
     }
     if(isset($_GET['pend_acv'])){
        $total+=$pendente;
        $action_form.="&pendente=".$pendente;
        $pend_checked="checked";
     }
     if(isset($_GET['canc_acv'])){
        $total+=$cancelado;
        $action_form.="&cancelado=".$cancelado;
        $canc_checked="checked";
     }
?>

<?php include_once("include/header.php") ?>
    <body lang="pt-br" class="clearfix">
    <!--[if lt IE 7]><p class=chromeframe>Seu navegador está <em>desatualizado!</em> <a href="http://browsehappy.com/">Faça download de um outro navegador</a> or <a href="http://www.google.com/chromeframe/?redirect=true">instale o Google Chrome Frame</a> para acessar este site.</p><![endif]-->
        <div id="tudo" style="min-width:500px;"> 
            <div id="topo">
            	 <h1><img src="img/logotipo_easySGE.png" alt="Easy SGE" style="display:block;"/><span style="font-size:0.41em; font-weight:normal; display:block; margin-left:12px;">sistema de gerenciamento de especialidades</span></h1>
            </div> <!--Fecha div topo-->
            
            <div id="conteudo" style="padding-top:20px;">
		        <div id="dropshadow">
                <div id="container" class="clearfix">
        
                <h3 class="tituloBox">Gráfico</h3>
                <div class="formularioDividido">
                <form name="cd_field" id="cd_field">
                    <fieldset id="filtroGrafico"><legend>Incluir</legend>
                    <p>
                        <input id="sucessos" name="sucesso_active" type="checkbox" value="sucessos" <?php echo $suc_checked?> />
                        <label for="sucessos">Sucessos</label>
                    </p>
                    
                    <p>
                        <input id="insucessos" name="insucesso_active" type="checkbox" value="insucessos" <?php echo $insuc_checked?> />
                        <label for="insucessos">Insucessos</label>
                    </p>
                    
                    <p>
                        <input id="pendentes" name="pendente_active" type="checkbox" value="pendentes" <?php echo $pend_checked?> />
                        <label for="pendentes">Pendentes</label>
                    </p>
                    
                    <p>
                        <input id="cancelados" name="cancelado_active" type="checkbox" value="cancelados" <?php echo $canc_checked?> />
                        <label for="cancelados">Cancelados</label>
                    </p>
                    <button type="button" onClick="atualiza_grafico();" >Atualizar Gráfico</button>
                    </fieldset>
                    <input name="sucesso_hd" id="sucesso_hd" type="hidden" value="<?php echo $sucesso?>" />
                    <input name="insucesso_hd" id="insucesso_hd" type="hidden" value="<?php echo $insucesso?>" />
                    <input name="pendente_hd" id="pendente_hd" type="hidden" value="<?php echo $pendente?>" />
                    <input name="cancelado_hd" id="cancelado_hd" type="hidden" value="<?php echo $cancelado?>" />
                </form>
                
                <div>
                    <p>Total de registro: <?php echo $total?></p>
                    <p><img src="<?php echo $action_form; ?>" alt="grafico" /></p>
                </div>
                </div>
                <p id="botoesFormulario">
                	<button type="button" class="botaoPositivo" onClick="javascript:window.print()">Imprimir</button>
                </p>
                
			<?php include_once("include/footer.php") ?>
            
		<script type="text/javascript">
           function atualiza_grafico(){
            sucesso=document.cd_field.sucesso_hd.value;
            insucesso=document.cd_field.insucesso_hd.value;
            pendente=document.cd_field.pendente_hd.value;
            cancelado=document.cd_field.cancelado_hd.value;
            caminho="grafico.php?sucesso="+sucesso+"&insucesso="+insucesso+"&pendente="+pendente+"&cancelado="+cancelado;
         
            nSelecionados=0;
        
            if(document.cd_field.sucesso_active.checked==true){
               caminho+="&suc_acv=1";
               nSelecionados++;
            }
            if(document.cd_field.insucesso_active.checked==true){
               caminho+="&ins_acv=1";
               nSelecionados++;
            }
            if(document.cd_field.pendente_active.checked==true){
               caminho+="&pend_acv=1";
               nSelecionados++;
            }
            if(document.cd_field.cancelado_active.checked==true){
               caminho+="&canc_acv=1";
               nSelecionados++;
            }
            if(nSelecionados!=0)
            location.href=caminho;
            else
				alert('Selecione pelo menos uma categoria.');
           }
        </script>
    </body>
</html>