        <div id="tudo"> 
            <div id="topo">
                <h1><a href="index.php"><img src="img/logotipo_easySGE.gif" alt="Consultório Online" style="display:block;"/></a><span style="font-size:0.41em; font-weight:normal; display:block; margin-left:12px;">""</span></h1>
                <div id="infoUsuario">
                    <p id="boxPerfil">Olá, <strong><?php echo $_SESSION['USUARIO']['NOME']; ?>!</strong>| <a id="perfil" href="cadastro_usuario.php?acao=editar&amp;id=<?php echo $_SESSION['USUARIO']['ID']; ?>">Meu perfil</a> <a id="sair" href="login.php?tipo=3"><strong>Sair</strong></a> </p>
                  
				  <!--<p id="boxStatus"> -->
                  <!--   	<!--<img src="img/disconnect.png" width="16" height="16" alt="Sistema desconectado" /> <span class="formularioBold">Desconectado</span><span style="display:block;">-->
                     <!--   Licença Expira em 22/12/2016 -->
                        <!--</span>-->
                  <!--  </p>-->
					
                </div><!--fecha infoUsuario-->
            </div> <!--Fecha div topo-->