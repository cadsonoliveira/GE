<?php
    // Adiciona 'class="selected"' nos 'a' quando o link estiver ativo
	$pagina = basename($_SERVER['SCRIPT_NAME']);
	
	$class_inicio = ($pagina=="index.php") ? 'class="menuSelected"' : "";
	$class_pacientes = ($pagina=="pacientes.php" || $pagina=="cadastro_paciente.php" || $pagina=="documentos_paciente.php" || $pagina=="questionario_anamnese.php" || $pagina=="dados_tratamento.php")? 'class="menuSelected"' : "";
	$class_agenda = ($pagina == "agenda.php" || $pagina == "seleciona_paciente_para_agendamento.php") ? 'class="menuSelected"' : "";
	$class_planos = ($pagina=="planos_de_saude.php" || $pagina == "cadastro_plano_de_saude.php")  ? 'class="menuSelected"' : "";
	$class_tratamentos = ($pagina=="seleciona_paciente_para_tratamentos.php" || $pagina=="tratamentos.php" || $pagina=="cadastro_tratamento.php" || $pagina=="atualizacao_tratamento.php") ? 'class="menuSelected"' : "";
	$class_receituario = ($pagina == "receituario.php" || $pagina == "seleciona_paciente_receituario.php") ? 'class="menuSelected"' : "";
	$class_relatorios = ($pagina=="relatorio.php" || $pagina=="seleciona_paciente_relatorio.php") ? 'class="menuSelected"' : "";
	$class_estatisticas = ($pagina=="estatisticas.php" || $pagina=="dados_tratamento_estatistica.php") ? 'class="menuSelected"' : "";
	$class_configuracoes = ($pagina=="especialidades.php" || $pagina=="editar_especialidade.php" ||  $pagina=="usuarios.php" || $pagina == "cadastro_usuario.php" || $pagina=="editar_usuario.php") ? 'class="menuSelected"' : "";

?>

        <!--início menu-->
        <ul id="menu" class="clearfix">
            <li><a href="index.php"  <?php echo $class_inicio; ?>>INÍCIO</a></li>
            <li class="menuHover"><a id="menuPacientes" <?php echo $class_pacientes; ?>>PACIENTES</a>
                <ul class="submenu" style="width:185px;">
                    <li><a class="submenuPacientes" href="pacientes.php">LISTA DE PACIENTES</a></li>
                    <li><a class="submenuPacientes" href="cadastro_paciente.php">ADICIONAR PACIENTE</a></li>
					<li><a class="submenuPacientes" href="cadastro_paciente_online.php">ADICIONAR ONLINE</a></li>
					<li><a class="submenuPacientes" href="agendamentoOnline.php">AGENDAR ONLINE</a></li>
					<li><a class="submenuPacientes" href="agendaMarcacaoOnline.php">MARCAÇÃO ONLINE</a></li>
                </ul>
            </li>
            <li><a href="<?php if(isset($_SESSION['PACIENTE']['ID'])){echo "agenda.php";}else{echo "seleciona_paciente_para_agendamento.php";} ?>" <?php echo $class_agenda; ?>>AGENDA</a></li>
            <!--<li class="menuHover"><a id="menuPlanosdeSaude" <?php echo $class_planos; ?>>PLANOS DE SAÚDE</a>
                <ul class="submenu" style="width:165px;">
                    <li><a class="submenuPlanos" href="planos_de_saude.php">LISTA DE PLANOS</a></li>
                    <li><a class="submenuPlanos" href="cadastro_plano_de_saude.php">ADICIONAR PLANO</a></li>
                </ul>
            </li>-->
            <li class="menuHover"><a <?php echo $class_tratamentos; ?>>TRATAMENTOS</a>
                <ul class="submenu" style="width:225px;">
                    <li><a class="submenuPacientes" href="<?php if(isset($_SESSION['PACIENTE']['ID'])){echo "tratamentos.php";}else{echo "seleciona_paciente_para_tratamentos.php";} ?>">LISTA DE TRATAMENTOS</a></li>
                    <li><a class="submenuPacientes" href="<?php if(isset($_SESSION['PACIENTE']['ID'])){echo "cadastro_tratamento.php";}else{echo "seleciona_paciente_para_tratamentos.php";} ?>">ADICIONAR TRATAMENTOS</a></li>
                </ul>  
            </li>        
            <li><a href="<?php if(isset($_SESSION['PACIENTE']['ID'])){echo "receituario.php";}else{echo "seleciona_paciente_receituario.php";} ?>" <?php echo $class_receituario ?>>RECEITUÁRIO</a></li>
            <li><a href="<?php if(isset($_SESSION['PACIENTE']['ID'])){echo "relatorio.php";}else{echo "seleciona_paciente_relatorio.php";} ?>" <?php echo $class_relatorios; ?>>RELATÓRIO</a></li>
			<li class="menuHover"><a id="menuSMS" <?php echo $class_planos; ?>>SMS</a>
                <ul class="submenu" style="width:165px;">
                    <li><a class="submenuPlanos" href="sms.php">LEMBRETE DE CONSULTA</a></li>
                    <li><a class="submenuPlanos" href="cadastro_plano_de_saude.php">RELATÓRIO DE SMS</a></li>
                </ul>
            </li>
			
            <li id="menuAdjacente">
                <ul>
				 <li id="estatisticas" class="menuHover"><a href="estatisticas.php" <?php echo $class_estatisticas; ?>>ESTATÍSTICAS</a></li>
                    <li id="config" class="menuHover"><a <?php echo $class_configuracoes; ?>>CADASTROS</a>
                        <ul class="submenu" style="width:160px;">
                            <li><a class="submenuConfiguracoes" href="especialidades.php">ESPECIALIDADES</a></li>
                            <li><a class="submenuConfiguracoes" href="usuarios.php">PROFISSIONAIS</a></li>
							<li><a class="submenuConfiguracoes" href="usuarios.php">CUSTO DE ATENDIMENTO</a></li>
							<li><a class="submenuConfiguracoes" href="planos_de_saude.php">PLANOS DE SAÚDE</a></li>		
							<li><a class="submenuConfiguracoes" href="cadastro_plano_de_saude.php">ADICIONAR PLANO DE SAÚDE</a></li>		
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <!--fim menu-->