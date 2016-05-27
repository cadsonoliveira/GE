<?php
	session_start();
	
	$borda = 0; //para debugar a posição e tamnha das células
	
	//header("Content-type: application/pdf");
	//header("Content-Disposition: inline; filename=Receita_EasySGE.pdf");
	
	require_once("../bibliotecas/fpdf/fpdf.php");
	require_once("../bibliotecas/PEARmail/Mail.php");
	require_once("../bibliotecas/PEARmail/Mail/mime.php");
	
	include_once("../classes/classPessoa.php");
	include_once("../classes/classContato.php");
	include_once("../classes/classUsuario.php");
	include_once("../classes/classPaciente.php");
	include_once("../classes/classTratamento.php");
	include_once("../classes/classMatchCode.php");
	include_once("../classes/classImagem.php");
	include_once("../classes/classDentistaEncaminhador.php");
	
	class PDF extends FPDF{
		//Page header
		function Header(){
			//Logo
			$this->Image('../layouts/img/t_logo_blank_bg.png',10,9,80);
			//Arial bold 15
			$this->SetFont('Arial','B',18);
			//Title
			$this->Cell(191,18,iconv('utf-8','iso-8859-1','Receituário do Paciente'),0,0,'R');
			//Line break
			$this->Ln(12);
			$this->Line(10,22,200,22);
		}
		
		//Page footer
		function Footer(){
			//Position at 1.5 cm from bottom
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			
			//Page number
			$this->Cell(0,3,iconv('utf-8','iso-8859-1','Para:'),0,0,'L');
			$this->Cell(0,3,iconv('utf-8','iso-8859-1','Pág.').$this->PageNo().'/{nb}',0,1,'C');
			
			$this->Cell(0,4,iconv('utf-8','iso-8859-1','Qualquer anormalidade ou reação adversa entre em contato.'),0,1,'L');
			$this->Cell(0,4,iconv('utf-8','iso-8859-1','Telefone: (31) 3332-3232 ou (31) 3331-3232'),0,0,'L');
			   
		}
	}
	
	$envoutro = "";
	if(isset($_POST['paciente']) && isset($_POST['paciente'])){
		$paciente      = new Paciente($_POST['paciente']);
		$envpaciente   = isset($_POST['enviar_paciente'])?$_POST['enviar_paciente']:"";
		$envsecretaria = isset($_POST['enviar_secretaria'])?$_POST['enviar_secretaria']:"";
		$envoutro      = isset($_POST['enviar_outro'])?$_POST['enviar_outro']:"";
		$medicamentos  = isset($_POST['medicamentos'])?$_POST['medicamentos']:"";
		$posologia     = isset($_POST['posologia'])?$_POST['posologia']:"";
		$uDentista     = new Usuario($_SESSION['USUARIO']['ID']); //usuario eh sempre um dentista
		$pDentista     = new Pessoa($_SESSION['USUARIO']['ID']);
	
		if($paciente->getCaminhoFoto()!="")
			$imgpaciente   = "../documentos/pacientes/".$_POST['paciente']."/foto/".$paciente->getCaminhoFoto();
		else
			$imgpaciente   = "../layouts/img/usuario_foto.png";
	
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
	
		$pdf->SetFont('Arial','I',7);
		$pdf->Cell(191,5,iconv('utf-8','iso-8859-1','Receituário gerado em: '.date("d").'/'.date("m").'/'.date("y").' - '.date("H").':'.date("i").':'.date("s")),0,1,'R');
	
		//Dentista que realizou o tratamento
		$pdf->ln(2);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(23,6,iconv('utf-8','iso-8859-1','Dentista: '),$borda,0,'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(120,6,iconv('utf-8','iso-8859-1',$pDentista->getNome()),$borda,1);
		$pdf->Cell(14,5,iconv('utf-8','iso-8859-1','CRO:'),$borda,1);
		$pdf->Cell(18,5,iconv('utf-8','iso-8859-1',$uDentista->getCRO()),$borda,0);
		$pdf->Cell(25,5,iconv('utf-8','iso-8859-1','Telefone(s):'),$borda,0);
		if($pDentista->getContato()->getTelefoneFixo()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$pDentista->getContato()->getTelefoneFixo()),$borda,0);
		if($pDentista->getContato()->getTelefoneCelular()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$pDentista->getContato()->getTelefoneCelular()),$borda,0);
		if($pDentista->getContato()->getTelefoneComercial()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$pDentista->getContato()->getTelefoneComercial()),$borda,0);
	
	
		//Paciente
		$pdf->ln(7);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(23,6,iconv('utf-8','iso-8859-1','Paciente: '),$borda,0);
		$pdf->SetFont('Arial','',11);
		//$pdf->Cell(14,5,iconv('utf-8','iso-8859-1','Nome:'),0,0);
		$pdf->Cell(120,6,iconv('utf-8','iso-8859-1',$paciente->getNome()),$borda,1);
		$pdf->Cell(12,5,iconv('utf-8','iso-8859-1','Idade:'),$borda,0);
		$pdf->Cell(11,5,iconv('utf-8','iso-8859-1',$paciente->getIdade()),$borda,1);
		$pdf->Cell(25,5,iconv('utf-8','iso-8859-1','Telefone(s):'),$borda,0);
		if($paciente->getContato()->getTelefoneFixo()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$paciente->getContato()->getTelefoneFixo()),$borda,0);
		if($paciente->getContato()->getTelefoneCelular()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$paciente->getContato()->getTelefoneCelular()),$borda,0);
		if($paciente->getContato()->getTelefoneComercial()!="")
			$pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$paciente->getContato()->getTelefoneComercial()),$borda,0);
	
		//Imagem Paciente
		if(is_readable($imgpaciente))
			$pdf->Image($imgpaciente,165,30,20);
		else
			$pdf->Image("../layouts/img/usuario_foto.png",165,30,20);
	
		//Medicamentos
		$pdf->ln(15);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(55,5,iconv('utf-8','iso-8859-1','Medicamentos '),$borda,0);
		$pdf->SetFont('Arial','',11);
		//Comentario sobre o tratamento
		$pdf->ln(5);
		$pdf->MultiCell(150, 5, iconv('utf-8','iso-8859-1',$medicamentos));
	
		//Posologia
		$pdf->ln(8);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(30,6,iconv('utf-8','iso-8859-1','Posologia'),$borda,1);
		$pdf->SetFont('Arial','',11);
		$pdf->ln(5);
		$pdf->MultiCell(150,5,iconv('utf-8','iso-8859-1',$posologia));
	
		//Assinatura
		$pdf->ln(10);
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(30,6,iconv('utf-8','iso-8859-1','___________________________________'),$borda,1);
		$pdf->ln(1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(120,6,iconv('utf-8','iso-8859-1',$pDentista->getNome()),$borda,1);
		//$pdf->SetFont('Arial','',11);
		//$pdf->MultiCell(150,5,iconv('utf-8','iso-8859-1',$posologia));
	
		$pdf->SetTitle(iconv('utf-8','iso-8859-1','Receituário do Paciente'));
		$pdf->SetAuthor(iconv('utf-8','iso-8859-1','Easy SGE'));
		$pdf->SetSubject(iconv('utf-8','iso-8859-1','Receituário gerado pelo sistema Easy SGE'));
		$relatorio = $pdf->Output('ReceituarioEasySGE.pdf','S');
	
		if($envpaciente==1||$envsecretaria==1||$envoutro!=""){
				//destinatario
				$to = "";
				
				if($envpaciente==1){
					$to.= $paciente->getContato()->getEmail();
				}
				
				if($envsecretaria==1){
					$pers = new Persistencia();
	
					$sql = "SELECT id_pessoa FROM usuario WHERE usuario.tipo_acesso = 'Secretaria'";
	
					$pers->bExecute($sql);
					$cont = 0;
	
					while($cont < $pers->getDbNumRows()){
						$pers->bCarregaRegistroPorLinha($cont);
						$vet_resultado = $pers->getDbArrayDados();
						$sec = new Usuario($vet_resultado['id_pessoa']);
						if(($to != "") && ($sec->getContato()->getEmail() != ""))
							$to.= ','.$sec->getContato()->getEmail();
						else
							$to.= $sec->getContato()->getEmail();
						$cont++;
					}
				}
	
				if($envoutro!=""){
						if(($to !="") && ($envpaciente==1||$envsecretaria==1))
							$to.= ','.$envoutro;
						else
							$to.= $envoutro;
				}
				// remetente
				
				
				$from = "easyvision@ivision.ind.br";
				/*
				 * O c�digo abaixo usa o remetente como o usu�rio do sistema.
				 */
				 
				 /***
				if($pDentista->getContato()->getEmail() == "")
					$from = $pDentista->getNome()."<teste@luizfernando.net>";
				else
					$from = $pDentista->getNome()."<".$pDentista->getContato()->getEmail().">";
				 ***/
				
				// assunto
				$subject = "Receitu&aacute;rio do Paciente - Easy SGE";
	
				// HEADER
				$headers = array('From' => $from,
				'Subject' => $subject);
	
				//Nao pode ter espaco dps de PDFMAIL
				//Corpo do email em HTML
					$htmlMessage = '
					<html>
					<body bgcolor="#ffffff">
					<p align="center">
					Verifique o receitu&aacute;rio no PDF em anexo.<br>
					Enviado por <b style="font-size:18pt;">EasySGE</b>
					</p>
					<b>Email enviados para: '.$to.'</b>
					</body>
					</html>';
	
				$mime = new Mail_Mime();
				$mime->setHtmlBody($htmlMessage);
				// Adiciona o pdf como anexo
				$mime->addAttachment($relatorio, 'application/pdf', 'Receituario EasySGE.pdf', false, 'base64');
				$body = $mime->get();
				$hdrs = $mime->headers($headers);
				$params = array (
					'auth' => true, // Define que o SMTP requer autentica��o.
					'host' => 'ssl://smtp.gmail.com', // Servidor SMTP
					'port' => '465',
					'username' => 'easysge2@gmail.com', // Usu�rio do SMTP
					'password' => 'asdf123##' // Senha
				);
				/*$params = array (
					'auth' => true, // Define que o SMTP requer autentica��o.
					'host' => 'mail.ivision.ind.br', // Servidor SMTP
					'port' => '25',
					'username' => 'easyvision@ivision.ind.br', // Usu�rio do SMTP
					'password' => 'new' // Senha
				);*/
				//para enviar com a funcao mail, utilizar $mail = &Mail::factory('mail');
				$mail = &Mail::factory('smtp',$params);
				// Envia o email para $to,
				// com os headers $hdrs,
				// e com a mensagem $body.
				$erro = $mail->send($to, $hdrs, $body);
				if (PEAR::isError($erro)) { 
					print($erro->getMessage());
				}
		}
		//Faz o download do relat�rio
		$pdf->Output('ReceituarioEasySGE.pdf','D');
	}
	else{
		echo "Ocorreu um erro ao gerar o relatorio!!";
	}
?>