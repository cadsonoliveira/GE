<?php
	//Antiga página agenda_geral.php

    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    } else {

        include_once("../classes/classPaciente.php");
        include_once("../classes/classTratamento.php");
        include_once("../classes/classMatchCode.php");
        include_once ("../classes/classPersistencia.php");
        include_once ("../classes/classConfiguracao.php");
        include_once ("../classes/classCombo.php");
        include_once ("../controladores/AJAX.agenda_geral.php");

        $hoje = date("d/m/Y");

        $str_calendario = carregaCalendario($hoje);
        $str_agenda = carregaAgenda($hoje, "08:00", "18:00", "30");

        $action_form = "../controladores/estatisticas.php";
        $initState = 0;
        $matchcode = new MatchCode(1);

        $config = new Configuracao(1);

        if(isset($_SESSION['PACIENTE']['ID'])){
            $paciente = new Paciente($_SESSION['PACIENTE']['ID']);
        }else {
            $paciente = new Paciente();
        }

        $lista_trat = lista_match_codes($paciente->getId(), $config->getIdEspecialidade(), 'TRATAMENTO');
        $lista_retrat = lista_match_codes($paciente->getId(), $config->getIdEspecialidade(), 'RETRATAMENTO');
		
		$consultas = (strlen($lista_trat)==0 && strlen($lista_retrat)==0) ? 'false' : 'true';
		
	$time = microtime()+time()*1000;
	
	
	}    
?>

<?php include_once("include/header.php") ?>
<div id="status"><p>Gravando Dados...</p></div>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>
    <div id="conteudo">
    	<?php include_once("include/dados_paciente.php") ?>
        <div id="dropshadow">
        <div id="breadcrumb">
            <ul>
                <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php" title="lista pacientes">pacientes</a><span class="breadcrumbDireita"></span>
                    <ul>
                        <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">agenda</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                    </ul>
                </li>
            </ul>
        </div> <!--Fecha div breadcrumb-->

        <div id="container" class="clearfix agenda">
        
        <h3 class="tituloBox">Calendário</h3>
        
        <div>
            <table title="Calendário" summary="Calendário com as datas já agendadas" id="calendario">
                <caption class="hidden">Calendário</caption>
                <thead>
                    <tr style="line-height:1.4em !important">
                        <th colspan="2" class="celulaBranco" style="text-align:left;">
                            <button id="prev_year" type="button">&laquo; ano anterior</button>
                            <button id="prev_month" type="button">&#8249; mês anterior</button>
                        </th>
                        <th colspan="3" class="celulaBranco">
                            <h4 id="calendar_date">Carregando...</h4>
                        </th>
                        <th colspan="2" class="celulaBranco" style="text-align:right;">
                            <button id="next_month" type="button">Próximo mês &#8250;</button>
                            <button id="next_year" type="button">Próximo ano &raquo;</button>
                        </th>
                    </tr>
                
                    <tr>
                        <th>Domingo</th>
                        <th>Segunda</th>
                        <th>Terça</th>
                        <th>Quarta</th>
                        <th>Quinta</th>
                        <th>Sexta</th>
                        <th>Sábado</th>
                    </tr>
                </thead>  
                
                <!--tbody gerado dinamicamente -->    
            </table>
        </div>
        
        <h3 class="tituloBox">Agendamentos do dia</h3>
        <div>
            <table id="agendamento">
                <thead>
                <tr>
                	<th colspan="4" class="celulaBranco"><h4 id="schedule_date">Carregando...</h4></th>
                </tr>
                    <tr>
                        <th rowspan="2" class="celulaHorarioAgenda">Horário</th>
                        <th colspan="3" style="border-bottom:2px solid" >Agendamento</th>
                    </tr>
                        <th class="celulaPacienteAgenda" style="text-align:center">Paciente</th>
                        <th class="celulaPlanoAgenda">Plano de Saúde</th>
                        <th class="celulaTratamentosAgenda" style="text-align:center;">Tratamentos</th>
                    <tr>
                    </tr>
                </thead>

               
				<!--tbody gerado dinamicamente -->
            </table>
        </div>
        
		<?php include_once("include/footer.php") ?>
        
		<script type="text/javascript">
			window.addEvent('domready',function(){
			$paciente = "<?php echo $paciente->getNome(); ?>";
			$paciente_id = <?php echo $paciente->getId(); ?>;
			$tem_tratamentos = <?php echo $consultas; ?>;
				
			var sch = new BSchedule('agendamento');
			sch.setData('<?php echo $str_agenda; ?>');
			sch.build();
			
			var cal = new BCalendar('calendario','calendar_date',['prev_year','prev_month','next_month','next_year']);
			cal.setData('<?php echo $str_calendario; ?>');
			
			cal.build();
			$('schedule_date').set('text','Dia '+cal.date.getDate()+' de '+cal.monthLabels[cal.date.getMonth()]);
			cal.addEvent('changeday',function(){
				nova_data = this.date.getDate() + "/" + (this.date.getMonth()+1) + "/" + this.date.getFullYear();
				$('schedule_date').set('text','Dia '+this.date.getDate()+' de '+this.monthLabels[this.date.getMonth()]);
				
				sch_req = new Request({
						onRequest:function(){ return true; },
						onSuccess:function(a){ this.setData(a); this.build(); }.bind(arguments[0]),
						'url':'../controladores/AJAX.agenda_geral.php',
						'method':'post'
					}
				).send('tipo=atualiza_agenda&data='+nova_data);
			}.pass(sch,cal));
			
			cal.addEvent('update',function(){
				if(!this.isUpdating){
					this.isUpdating=true;
					nova_data = this.date.getDate() + "/" + (this.date.getMonth()+1) + "/" + this.date.getFullYear();
					cal_req = new Request(
						{
							onRequest:function(){ return true; },
							onSuccess:function(a){ this.setData(a); this.build(); this.isUpdating=false; }.bind(this),
							'url':'../controladores/AJAX.agenda_geral.php',
							'method':'post'
						}
					).send('tipo=atualiza_calendario&data='+nova_data);
				}
			})
			
			sch.addEvent('changeschedule',function(){
				tratamentos = [];
				$each(this[0].data.hours[arguments[0]]['tratamentos'],function(a){ this.push(a[3]) }.bind(tratamentos));
				id_consulta = (this[0].data.hours[arguments[0]]['id_consulta']==null) ? '' : '&id_consulta='+this[0].data.hours[arguments[0]]['id_consulta'];
				nova_data = this[1].date.getDate() + "/" + (this[1].date.getMonth()+1) + "/" + this[1].date.getFullYear();
				sch_req = new Request(
					{
						onRequest:function(){
							//alert(JSON.encode(this));
						}.bind(this[0].data),
						onSuccess:function(a){
							$('status').set('html','<p>Dados Gravados com <b>Sucesso!</b></p>');
							$('status').set('class','st_success');
							$('status').fade('in');
							(function(){$('status').fade('out')}).delay(2000);
							
							this.changeDay(null,false);
						}.bind(this[1]),
						onFailure:function(){
							$('status').set('html','<p>Ocorreu um erro na comunicação com o Servidor. Verifique sua conexão com o Servidor.</p>');
							$('status').set('class','st_fail');
							$('status').fade('in');
							(function(){$('status').fade('out')}).delay(2000);
						},
						'url':'../controladores/AJAX.agenda_geral.php',
						'method':'post'
					}
				).send('tipo='+(this[0].data.hours[arguments[0]]['id_consulta']==null ? 'nova_consulta' : 'altera_consulta')+id_consulta+'&hora_inicio='+(arguments[0].replace(/_/,':'))+'&duracao='+(this[0].data.hours[arguments[0]]['len'])+'&tratamentos='+(tratamentos)+'&data='+nova_data);
				
				this[1].update();
			}.bind([sch,cal]));
			
			sch.addEvent('newevent',function(){
				_date = new Date();
				_date.setTime(<?php echo $time ?>);
				_cur_date = this[1].date;
				_cur_hour = parseTime(arguments[0].getParent('tr').getFirst('td.celulaHorarioAgenda').get('text'));
				_cur_date.setHours(_cur_hour[0]); _cur_date.setMinutes(_cur_hour[1]);
		
				if(!$tem_tratamentos){
					alert('Não é possível agendar um horário para um paciente que não possui tratamentos ou retratamentos.');
					return false;
				}
				
				if($paciente_id == 0){
					alert('Não é possível Editar a consulta de um paciente que não está selecionado.');
					return false;
				}
		
				if(compareDates(_date,_cur_date)){
					editEvent.run([arguments[0],'Nova Consulta','Agendar'],this[0]);																																																
				} else {
					alert('Não é possível Marcar Consultas em dias anteriores à data atual.');
				}
			}.bind([sch,cal]));
			
			sch.addEvent('editevent',function(){
				_date = new Date();
				_date.setTime(<?php echo $time ?>);
				_cur_date = this[1].date;
				_cur_hour = parseTime(arguments[0].getParent('tr').getFirst('td.celulaHorarioAgenda').get('text'));
				_cur_date.setHours(_cur_hour[0]); _cur_date.setMinutes(_cur_hour[1]);
				
				time_key = arguments[0].getParent('tr').getFirst('td.celulaHorarioAgenda').get('text').replace(/:/,'_');
				if($paciente_id != this[0].data.hours[time_key]['id_paciente'].toInt()){
					alert('Não é possível Editar a consulta de um paciente que não está selecionado.');
					return false;
				}
				
				if(compareDates(_date,_cur_date)){
					editEvent.run([arguments[0],'Editar Consulta','Alterar'],this[0]);
				} else {
					alert('Não é possível editar consultas em dias anteriores à data atual.');
				}
			}.bind([sch,cal]));
			
			sch.addEvent('removeevent',function(){
				if(confirm('Você deseja realmente desmarcar esta consulta?')){
					time_key = arguments[0].getParent('tr').getFirst('td.celulaHorarioAgenda').get('text').replace(/:/,'_');
					nova_data = this[0].date.getDate() + "/" + (this[0].date.getMonth()+1) + "/" + this[0].date.getFullYear();
					id_consulta = this[1].data.hours[time_key]['id_consulta'];
					this[1].data.hours[time_key] = null;
					sch_req = new Request(
						{
							onSuccess:function(a){
								$('status').set('html','<p>Dados Removidos com <b>Sucesso!</b></p>');
								$('status').set('class','st_success');
								$('status').fade('in');
								(function(){$('status').fade('out')}).delay(2000);
							},
							onFailure:function(){
								$('status').set('html','<p>Ocorreu um erro na comunicação com o Servidor. Verifique sua conexão com o Servidor.</p>');
								$('status').set('class','st_fail');
								$('status').fade('in');
								(function(){$('status').fade('out')}).delay(2000);
							},
							'url':'../controladores/AJAX.agenda_geral.php',
							'method':'post',
							'async': false
						}
					).send('tipo=remove_consulta&id_consulta=' + id_consulta);
					
					this[0].update();
					this[1].build();
				}
			}.bind([cal,sch]));
			
			/* Local Function */
			var editEvent = function(){
				md = new Modal(['BSCH']);
				md.setHeader(arguments[1]);
				
				//NOVA CONSULTA
				var fm = new Form('form_e',['dummy.php','post'],'field',null);
				fm.attach(md.win);
				
				parent_tr = arguments[0].getParent('tr');
		
				start_time_str = parent_tr.getFirst('td.celulaHorarioAgenda').get('text');
				start_time_key = start_time_str.replace(/:/,'_');
				start_time = parseTime(start_time_str);
		
				if(this.data.hours[start_time_key]==null){
					duration = this.gapTime-1;
					new_data = true;
				} else {
					duration = this.data.hours[start_time_key].len-1;
					new_data = false;
				};
				
				start_time_input = fm.newField('Início','consulta_inicio',35,'text',start_time_str);
				start_time_input.getParent('li').setStyle('float','left');
				start_time_input.set('readonly',true);
				
				em = (start_time[1]+duration)%60;
				eh = start_time[0];
				if(start_time[1]>0 && em==0) eh++;
				eh = (eh+Math.floor(duration/60))%24;
		
				end_time_str = eh+':'+(em>9 ? em : '0'+em);
				end_time_input = fm.newField('Fim','consulta_fim',35,'text',end_time_str);
				end_time_input.setStyle('margin','0 3px');
				end_time_input.getParent('li').setStyle('clear','right');
				end_time_input.set('readonly',true);
				eti_bt_min = new Element('button',{'type':'button','class':'time_changer','html':'<strong>-</strong>'}).inject(end_time_input,'before');
				eti_bt_add = new Element('button',{'type':'button','class':'time_changer','html':'<strong>+</strong>'}).inject(end_time_input,'after');				
		
				eti_bt_add.addEvent('click',function(){
					f_time = parseTime(arguments[1].get('value'));
					fh = f_time[0]; fm = f_time[1];
					e_time = parseTime(this.endTime);
					eh = e_time[0]; em = e_time[1];
					tm = (fm+1)%60;
					th = fh;
					if(tm==0) th = (th+1)%24;
					test_time_key = th+'_'+(tm>9 ? tm : '0'+tm);
					
					if(this.data.hours[test_time_key]==null && th<=eh && (th==eh ? tm<=em : true)){
						tm = (tm+this.gapTime-1)%60;
						f_time_str = th+':'+(tm>9 ? tm : '0'+tm);
						arguments[1].set('value',f_time_str);
					}
				}.pass([parent_tr,end_time_input],this));
				
				eti_bt_min.addEvent('click',function(){
					f_time = parseTime(arguments[1].get('value'));
					fh = f_time[0]; fm = f_time[1];
					i_time = parseTime(arguments[0].getFirst('td.celulaHorarioAgenda').get('text'));
					ih = i_time[0]; im = i_time[1];
					
					fm = (fm-this.gapTime)%60;
					if(fm<0){ fm=60+fm; fh = (fh-1)%24; }
					
					tm = (im+this.gapTime-1)%60;
					th = ih;
					if(tm==0) th=(th+1)%60;
					
					if(fh>=th && (fh==th ? fm>=tm : true)){
						f_time_str = fh+':'+(fm>9 ? fm : '0'+fm);
						arguments[1].set('value',f_time_str);
					}
				}.pass([parent_tr,end_time_input],this));
				
				check_list = fm.injectBlock("<?php echo $lista_trat.$lista_retrat ?>");
		
				consulta_nome = fm.newField('Nome do Paciente','consulta_nome',408 ,'text',(new_data ? $paciente : this.data.hours[start_time_key].nome));
				consulta_nome.set('readonly',true);
				
				fm.addEvent('success',function(){
					f_time = parseTime(arguments[1][0].get('value'));
					fh = f_time[0]; fm = f_time[1];
					i_time = parseTime(arguments[0].getFirst('td.celulaHorarioAgenda').get('text'));
					ih = i_time[0]; im = i_time[1];
					
					fm=(fm+1)%60;
					if(fm==0) fh=(fh+1)%24;
					
					len = (fh-ih)*60+fm-im;
					time_key = ih+'_'+(im>9 ? im : '0'+im);
					if(this.data.hours[time_key]==null) this.data.hours[time_key]={};
					this.data.hours[time_key]['len'] = len;
					
					this.data.hours[time_key]['nome'] = arguments[1][1].get('value');
					
					this.data.hours[time_key]['tratamentos'] = [];
					$each(arguments[2].form.getElements('input[type="checkbox"]'), function(a){
						_reg = new RegExp(/(\w*):(\w*)/);
						_alt_data = _reg.exec(a.get('alt'));
						if(a.get('checked')) this.push([a.get('name').toInt(),a.getParent('label').get('text').replace(/\[\w*\]/,''),_alt_data[1],_alt_data[2]]);
					}.bind(this.data.hours[time_key]['tratamentos']));
					
					this.changeSchedule(time_key);
					(function(a){a.fadeAndRemove()}.pass(arguments[3])).delay(1000);
				}.pass([parent_tr,[end_time_input,consulta_nome],fm,md],this));
		
				md.show();
				
				if(!new_data){
					for(i=0;i<this.data.hours[start_time_key]['tratamentos'].length;i++){
						$each(fm.form.getElements('input[name="'+this.data.hours[start_time_key]['tratamentos'][i][0]+'"]'),function(a){
							if(a.get('alt').replace(/:\w*/,'') == this[2]) a.set('checked',true);
						}.bind(this.data.hours[start_time_key]['tratamentos'][i]));
					}
				}
				
				sendBar = fm.attachSendBar([arguments[2],'mb'],[]);
				_buttons = sendBar.getElements('a');
				cancelButton = _buttons[1];
				sendButton = _buttons[0];
				_buttons.removeEvents('click');
				
				cancelButton.addEvent('click',function(md){ md.fadeAndRemove(); }.pass(md));
				sendButton.addEvent('click',function(){
					count = 0;
					$each(this.form.getElements('input[type="checkbox"]'),function(a){ if(a.get('checked')) count++; });
					if(count == 0){
						alert('Selecione ao menos um Tratamento/Retratamento');
					} else {
						this.sendData();
					}
				}.bind(fm));
			}
		})
		</script>

    </body>
</html>