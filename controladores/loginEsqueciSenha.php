<?php

	include_once("../classes/classUsuario.php");

	if(isset($_POST['user']))
	{
		$caracteres = 'abcdxywzABCDZYWZ0123456789';
		$max = strlen($caracteres)-1;
		$password = null;
		for($i=0; $i < 8; $i++)
		{
			$password .= $caracteres{mt_rand(0, $max)};
		}
		
		$usu = new Usuario();
		$usu->getUsuarioByLogin($_POST['user']);
		if($usu->getId() != 0)
		{
			$usu->setSenha(md5($password));
			$usu->bUpdate();
			#enviando o e-mail para o dentista
				$para = $usu->getContato()->getEmail();
				$assunto = "Nova senha para acesso";
			
				$texto = "Sua nova senha para acesso é: ".$password;
		
				$headers = "MIME-Version: 1.0rn".
				"Content-type: text/html; charset=iso-8859-1rn".
				"From: 'Sistema de dentista' <".$para.">rn".
				"To: '".$usu->getNome()."' <".$para.">rn".
				"Date: ".date("r")."rn".
				"Subject: ".$assunto."rn";
				
				$envia = @mail($para,$assunto,$texto,$headers);
				
				/*
				echo "<script>alert('E-mail enviado com sucesso!')</script>";
				*/
	
			header("Location: ../layouts/login.php?tipo=4");
		} else {
	        header("Location: ../layouts/esqueci_senha.php?tipo=5");	
		}		
	}

?>
