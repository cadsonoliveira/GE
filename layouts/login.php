<?php
    session_start();
    session_destroy();
?>

<?php include_once("include/header.php") ?>
        <div id="boxFloat"></div>
        <div id="boxLogin">
            <h1><img src="img/marca_simples.png" alt="Consultório Online" /></h1>
                <form action="../controladores/loginAutenticar.php" method="post" onsubmit="return valida_campos(this);">
                  <fieldset><legend>login</legend>
                    <label for="user">usuário</label>
                    <input id="user" name="user" type="text" />
                    <label for="pass">senha</label>
                    <input id="pass" name="pass" type="password" />
                    <button name="envia" type="submit">ENTRAR </button> 
					<p></p>
					<button name="envia" type="submit">CADASTRAR NOVO </button>
                    <p><a href="esqueci_senha.php" title="Pedido de nova senha.">Esqueci minha senha</a></p>    
                    </fieldset>                   
                </form>
        </div> <!-- fecha boxLogin-->                
		<div id="boxFeedbackLogin">
            <p id="atencaoFeedback">Atenção!!</p>
            <p id="feedback">
            <?php
                if(isset($_GET['tipo'])){
                    $msg = "";
                    switch($_GET['tipo']){
                        case 1:
                            $msg = "Usuário ou senha incorretos.";
                            break;
                        case 2:
                            $msg = "É necessário estar logado no sistema para utilizá-lo. Informe os dados acima.";
                            break;
                        case 3:
                            $msg = "Sessão encerrada com sucesso.";
                            break;
                        case 4:
                            $msg = "Sua senha foi enviada para o e-mail cadastrado.";
                            break;
                        case 5:
                            $msg = "Não conseguimos identificar seu usuário. Se o erro persitir entre em contato com o administrador do sistema.";
                            break;
                        default:
                            $msg = "Você não está logado no sistema, efetue o seu login informando os dados acima.";
                            break;
                    }
					echo($msg);
                }
            ?>
            </p>
		</div><!--fecha boxFeedbackLogin-->
        <script type="text/javascript" src="js/libs/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/validaForm.js"></script>  
        <?php
			if(isset($_GET['tipo'])){
				echo('<script type="text/javascript">'."
						$(document).ready(function() {
							$('#boxFeedbackLogin').addClass('boxFeedbackLoginShow');
							$('#boxFeedbackLogin').animate({'top': '+=100px'}, 'slow');
						});
					</script>");
			}
		?>        
    </body>
</html>