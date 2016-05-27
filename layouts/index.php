<?php
    session_start();
	
    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
        include_once ("../classes/classPersistencia.php");
        include_once ("../funcoes/common.php");

        $pers = new Persistencia();
		
		$sSql = "SELECT * FROM configuracao WHERE id_configuracao=1";
				$pers->bExecute($sSql);
				$pers->bDados();
				$res = $pers->getDbArrayDados();
		
				$texto_index=utf8_encode($res['texto_index']);
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

    <div id="conteudo">
        <div id="dropshadow">
        <div id="breadcrumb">
            <ul>
                <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">início</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
            </ul>
        </div> <!--Fecha div breadcrumb-->
		<div id="container" class="clearfix">
            <div id="conteudoHome">
            	<img src="img/cloud.png" alt="Meu Consultório OnLine" />
                <h3>HISTÓRIA</h3>
                <!-- id necessário para edição do texto-->
                <div id="home_text"><?php echo $texto_index;?></div>
                <p style="text-align:center; margin-bottom:50px;"><button type="button" onclick="edita_texto()">Editar texto</button></p>
                <img src="img/satisfacao.jpg" alt="Satisfação" />
            </div><!--fecha conteudoHome-->
 
		<?php include_once("include/footer.php") ?>
    </body>
</html>