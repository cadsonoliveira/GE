<?php
    session_start();

    $borda = 0; //para debugar a posição e tamnha das células

    //header("Content-type: application/pdf");
    //header("Content-Disposition: inline; filename=Relatorio_EasySGE.pdf");

    require_once("../bibliotecas/fpdf/fpdf.php");
    require_once("../bibliotecas/PEARmail/Mail.php");
    require_once("../bibliotecas/PEARmail/Mail/mime.php");
    include_once("../classes/classPersistencia.php");
    include_once("../classes/classUsuario.php");
    include_once("../classes/classPessoa.php");
    include_once("../classes/classContato.php");
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
            $this->Cell(191,18,iconv('utf-8','iso-8859-1','Relatório do Tratamento'),0,0,'R');
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
            $this->Cell(0,10,iconv('utf-8','iso-8859-1','Para:'),0,0,'L');
            //Page number
            if(sizeof($this->pages)!=1)
                $this->Cell(0,10,iconv('utf-8','iso-8859-1','Pág.').$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    if(isset($_POST['paciente']) && isset($_POST['paciente']) && isset($_POST['dente_hd']) && isset($_POST['matchcode_hd']) && isset($_POST['dtinicio_hd']) && isset($_POST['dtfim_hd'])){
        $paciente      = new Paciente($_POST['paciente']);
        $tratamento    = new Tratamento($_POST['tratamento']);
        $dentistaenc   = new DentistaEncaminhador($paciente->getIdDentistaEncaminhador());
        $contatodenc   = new Contato($dentistaenc->getIdContato());
        $dente         = $_POST['dente_hd'];
        $match_code    = new MatchCode($_POST['matchcode_hd']);
        $dtinicio      = $_POST['dtinicio_hd'];
        $dtfim         = $_POST['dtfim_hd'];
        $obstratamento = $_POST['obstratamento'];
        $obsadicionais = $_POST['obsadicionais'];
        $envdentista   = isset($_POST['enviar_dentista'])?$_POST['enviar_dentista']:"";
        $envsecretaria = isset($_POST['enviar_secretaria'])?$_POST['enviar_secretaria']:"";
        $envoutro      = isset($_POST['enviar_outro'])?$_POST['enviar_outro']:"";
        $imgantes      = new Imagem();
        //$sql_antes = "SELECT * FROM imagem WHERE id_tratamento=".$tratamento->getId()." AND indice=0 LIMIT 0,1";
        //$sql_depois = "SELECT * FROM imagem WHERE id_tratamento=".$tratamento->getId()." AND indice=1 LIMIT 0,1";

        $imgantes->bFetchObject("SELECT * FROM imagem WHERE id_tratamento=".$tratamento->getId()." AND indice=2 AND caminho NOT LIKE '%.avi' LIMIT 0,1");
        $imgdepois     = new Imagem();
        $imgdepois->bFetchObject("SELECT * FROM imagem WHERE id_tratamento=".$tratamento->getId()." AND indice=2 AND caminho NOT LIKE '%.avi' ORDER BY id_imagem DESC LIMIT 0,1");
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
        $pdf->Cell(191,5,iconv('utf-8','iso-8859-1','Relatório gerado em: '.date("d").'/'.date("m").'/'.date("y").' - '.date("H").':'.date("i").':'.date("s")),0,1,'R');

        //Dentista que realizou o tratamento
        $pdf->ln(2);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(23,6,iconv('utf-8','iso-8859-1','Dentista: '),$borda,0,'L');
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(120,6,iconv('utf-8','iso-8859-1',$pDentista->getNome()),$borda,1);
        $pdf->Cell(14,5,iconv('utf-8','iso-8859-1','CRO:'),$borda,0);
        $pdf->Cell(18,5,iconv('utf-8','iso-8859-1',$uDentista->getCRO()),$borda,1);
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

        //Dentista Encaminhador
        $pdf->ln(7);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(55,6,iconv('utf-8','iso-8859-1','Dentista Encaminhador: '),$borda,0,'L');
        $pdf->SetFont('Arial','',11);
        //$pdf->Cell(14,5,iconv('utf-8','iso-8859-1','Nome:'),0,0);
        $pdf->Cell(88,6,iconv('utf-8','iso-8859-1',$dentistaenc->getNome()),$borda,1);
        $pdf->Cell(14,5,iconv('utf-8','iso-8859-1','CRO:'),$borda,0);
        $pdf->Cell(18,5,iconv('utf-8','iso-8859-1',$dentistaenc->getCRO()),$borda,1);
        $pdf->Cell(25,5,iconv('utf-8','iso-8859-1','Telefone(s):'),$borda,0);
        if($contatodenc->getTelefoneFixo()!="")
            $pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$contatodenc->getTelefoneFixo()),$borda,0);
        if($contatodenc->getTelefoneCelular()!="")
            $pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$contatodenc->getTelefoneCelular()),$borda,0);
        if($contatodenc->getTelefoneComercial()!="")
            $pdf->Cell(35,5,iconv('utf-8','iso-8859-1',$contatodenc->getTelefoneComercial()),$borda,0);

        //Imagem Paciente
        if(is_readable($imgpaciente))
            $pdf->Image($imgpaciente,165,30,20);
        else
            $pdf->Image("../layouts/img/usuario_foto.png",165,30,20);
        //Dente
        $pdf->ln(7);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(17,6,iconv('utf-8','iso-8859-1','Dente: '),$borda,0);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(12,6,iconv('utf-8','iso-8859-1',$dente),$borda,1);

        //Imagem Tratamento (ANTES-0/DEPOIS-1)
        $pdf->ln(2);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(65,6,iconv('utf-8','iso-8859-1','Imagens do Tratamento'),$borda,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(90,7,iconv('utf-8','iso-8859-1','Inicio do Tratamento:'),$borda,0);
        $pdf->Cell(90,7,iconv('utf-8','iso-8859-1','Ultima imagem:'),$borda,1);
        $caminho_img = "../documentos/pacientes/".$_POST['paciente']."/tratamento/".$imgantes->getCaminho();
        if( ($imgantes->getCaminho()!="") && (is_readable($caminho_img)))
            $pdf->Image($caminho_img,11,105,'',58);
        else
            $pdf->Image("../layouts/img/no_pic.gif",11,105,'',58);
            
        $caminho_img = "../documentos/pacientes/".$_POST['paciente']."/tratamento/".$imgdepois->getCaminho();
        if($imgdepois->getCaminho()!="" && is_readable($caminho_img))
            $pdf->Image($caminho_img,101,105,'',58);
        else
            $pdf->Image("../layouts/img/no_pic.gif",101,105,'',58);
        //Tratamento
        $pdf->ln(64);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(55,5,iconv('utf-8','iso-8859-1','Tratamento Realizado: '),$borda,0);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(120,5,iconv('utf-8','iso-8859-1',$match_code->getDescricao()),$borda,1);
        //Comentario sobre o tratamento
        $pdf->ln(2);
        $pdf->MultiCell(150, 5, iconv('utf-8','iso-8859-1',$obstratamento));

        //Observacoes adicionais
        $pdf->ln(2);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(30,6,iconv('utf-8','iso-8859-1','Observação:'),$borda,1);
        $pdf->SetFont('Arial','',11);
        $pdf->ln(2);
        $pdf->MultiCell(150,5,iconv('utf-8','iso-8859-1',$obsadicionais));

        $pdf->SetTitle(iconv('utf-8','iso-8859-1','Relatório do Tratamento'));
        $pdf->SetAuthor(iconv('utf-8','iso-8859-1','Easy SGE'));
        $pdf->SetSubject(iconv('utf-8','iso-8859-1','Relatório gerado pelo sistema Consultorio Online'));
        $relatorio = $pdf->Output('RelatorioEasySGE.pdf','S');

        if($envdentista==1 || $envsecretaria==1 || $envoutro!=""){
            $to="";
            if($envdentista==1){
				// destinatario
                $to = $contatodenc->getEmail();
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
                    if($to!=""&&$sec->getContato()->getEmail()!="")
                        $to.= ','.$sec->getContato()->getEmail();
                    else
                        $to.= $sec->getContato()->getEmail();
                    $cont++;
                }
            }
            if($envoutro!=""){
                if($to!=""&&($envdentista==1||$envsecretaria==1))
                    $to.= ','.$envoutro;
                else
                    $to.= $envoutro;
            }

            // remetente

			//$from = "easyvision@ivision.ind.br";
			$from = "cronus_xp@yahoo.com.br";
			/*
			 * O código abaixo usa o remetente como o usuário do sistema.
			 */
			 
			 /***
			if($pDentista->getContato()->getEmail() == "")
				$from = $pDentista->getNome()."<teste@luizfernando.net>";
			else
				$from = $pDentista->getNome()."<".$pDentista->getContato()->getEmail().">";
			 ***/

            // assunto
            $subject = "Consultorio Online"; 

            // HEADER
            $headers = array('From' => $from,
                'Subject' => $subject);

            //Nao pode ter espaco dps de PDFMAIL
            //Corpo do email em HTML
            $htmlMessage = '
            <html>
            <body bgcolor="#ffffff">
            <p align="center">
            Verifique o relat&oacute;rio no PDF em anexo.<br>
            Enviado por <b style="font-size:18pt;">Consult&oacute;rio Online</b>
            </p>
			<b>Email enviado para: '.$to.'</b>
			<br />
			<br />
			<br />
			<br />
		    <p align="center">Mensagem encaminhada automaticamente, n&atilde;o deve ser respondida.<br>
            </body>
            </html>';
			//echo "MAIL";
            $mime = new Mail_Mime();
			//var_dump ($mime);
            $mime->setHtmlBody($htmlMessage);
            // Adiciona o pdf como anexo
            $mime->addAttachment($relatorio, 'application/pdf', 'Relatorio EasySGE.pdf', false, 'base64');
            $body = $mime->get();
            $hdrs = $mime->headers($headers);
			
			$params = array (
				'auth' => true, // Define que o SMTP requer autenticação.
				'host' => 'ssl://smtp.gmail.com', // Servidor SMTP
				'port' => '465',
				'username' => 'consultorio.online.bh@gmail.com', // Usuário do SMTP
				'password' => 'asdf123##' // Senha
			);	
			
			/*$params = array (
				'auth' => true, // Define que o SMTP requer autenticação.
				'host' => 'ssl://smtp.mail.yahoo.com', // Servidor SMTP
				'port' => '465',
				'username' => 'cronus_xp@yahoo.com.br', // Usuário do SMTP
				'password' => '-----', // Senha
				//'timeout' => 10
			);	
			
			/*$params = array (
				'auth' => true, // Define que o SMTP requer autenticação.
				'host' => 'mail.ivision.ind.br', // Servidor SMTP
				'port' => '25',
				'username' => 'easyvision@ivision.ind.br', // Usuário do SMTP
				'password' => 'new' // Senha
			);	*/	
			
			// configuracao do antigo servidor de emails...
           /* $params = array (
                'auth' => true, // Define que o SMTP requer autenticação.
                'host' => 'mail.luizfernando.net', // Servidor SMTP
                'username' => 'teste+luizfernando.net', // Usuário do SMTP
                'password' => '123' // Senha

            );
			//*/
			
            //para enviar com a funcao mail, utilizar $mail = &Mail::factory('mail');
            $mail = &Mail::factory('smtp',$params);
            // Envia o email para $to,
            // com os headers $hdrs,
            // e com a mensagem $body.
			
            $erro = $mail->send($to, $hdrs, $body);
	
            if (PEAR::isError($erro)){
                echo($erro->getMessage());
            }
        }
        //Faz o download do relatório
        $pdf->Output('Relatorio EasySGE.pdf','D');

    } else {
        echo "Ocorreu um erro ao gerar o relatorio!!";
    }

?>
