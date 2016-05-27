<?php
	include_once("../classes/classPaciente.php");
	
	if(isset($_SESSION['PACIENTE']['ID'])){
		$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		$foto = $paciente->getCaminhoFoto();
        $nome_pac = $paciente->getNome();

		if(!isset($foto) || ($foto == "")){
			$caminho_foto = "img/usuario_foto.png";	
		}else{
			$caminho_foto = "../documentos/pacientes/".$paciente->getId()."/foto/".$foto;
		}
	}else{
		$caminho_foto = "img/usuario_foto.png";
		$nome_pac = "";
	}
?>
<div id="paciente" class="clearfix">
    <img src="<?php echo $caminho_foto; ?>" style="width:80px; height:80px;" alt="Foto <?php echo $nome_pac; ?>" />
    <p style="font-size:1.571em; font-weight:bold; margin-top:25px; display: block;">Paciente</p>
<p style="font-size:1.214em; display: inline;"><?php if($nome_pac == ""){echo("Nenhum paciente selecionado");}else{echo $nome_pac;};?></p>    
    <form action="#" id="localizar">
    	<p>
        <input id="buscaPacientes" type="text" name="chave" onfocus="javascript:this.value==this.defaultValue ? this.value = '' : ''" onblur="javascript:this.value == '' ? this.value = this.defaultValue : ''"  value="<?php echo (($chave != "") ? $chave : 'Localizar pacientes') ?>"/>
        </p>
    </form>

</div> <!--Fecha paciente-->