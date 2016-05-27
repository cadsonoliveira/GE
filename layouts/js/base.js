// Javascript by Abner Luís - alstudios.com.br

/* Notation - Main Vars
 *	$ GLOBAL SCOPES
 *	_ INTERNAL SCOPES
 */

/* --------------------------- DOM READY EVENT --------------------------- */
window.addEvent('domready', function(){

	//DEPRECATED - IE
	/*if(Browser.Engine.trident){
		// Input Boxes Highlight for IE
		var $input_objects = $$('input').extend($$('select'));
		for(i=0; i<$input_objects.length; i++){
			var _types = $input_objects[i].getProperties('type')['type'];
			if(_types!='radio' || _types!='checkbox'){
				$input_objects[i].addEvents({
					'blur':function(){
						var _class = this.get('class').replace(/focus/,'');
						this.set('class',_class);
					},
					'focus':function(){
						var _class = this.get('class');
						this.set('class','focus '+_class);
					}
				});
			}
		}
	}*/

	/* Status Objects */
	if($$('#status').length>0) $('status').fade('hide');
	if($$('#refresh').length>0) $('refresh').fade('hide');

	if($$('#login_home').length>0){
		_login = $('login_home');
		loginWin_sz = _login.getSize();
		window_sz = $(window).getSize();
		_login.setStyles({
			'top': (window_sz.y-loginWin_sz.y)/2+'px',
			'left': (window_sz.x-loginWin_sz.x)/2+'px'
		});
	}

	/* Drop Menus */
	var $drop_menus = $$('.sub_menu');
	for(i=0; i < $drop_menus.length; i++){
		_menu = $drop_menus[i];
		try {
			_menu.setStyle('height',((_menu.getSize())['height']-4)+'px');
		}
		catch (e){

		}
		_li = _menu.getParent('li');
		_a = _menu.getPrevious('a');

		_menu.setStyle('display','block');
		_menu.fade('hide');

		_a.addEvent('mouseenter',function(menu){
			menu.fade('in')
			if(this.get('class')=='active') this.set('class','active_hold');
			this.set('alt','1');
		}.pass(_menu,_a));
		_a.addEvent('mouseleave',function(menu){
			if(this.get('alt')=='0'){
				menu.fade('out');
				if(this.get('class')=='active_hold') this.set('class','active');
			}
		}.pass(_menu,_a));

		_li.addEvent('mouseleave',function(a){
			_a.set('alt','0');
			_a.fireEvent('mouseleave');
		}.pass(_a));

		_menu.addEvent('mouseenter',function(a){
			a.set('alt','1');
			this.set('alt','1');
		}.pass(_a,_menu));
		_menu.addEvent('mouseleave',function(a){
			a.set('alt','0');
			this.set('alt','0');
			a.fireEvent('mouseleave');
		}.pass(_a,_menu));
	}

	/* Set Sizes */
	var $rsz_objects = $$('[class^=iw]');
	for(i=0;i<$rsz_objects.length;i++){
		if($rsz_objects[i].get('class').contains('iw')){
			$rsz_objects[i].setStyle('width',(cTrim($rsz_objects[i].get('class'),'iw([0-9]*)',1)-8)+'px'); //Set Input Widths
		}
	}

	/* Table Lines Highlight */
	$tables = $$('table');
	if($tables.length>0){
		$each($tables, function(a){
			var _ef_p = a.get('class');
			if(_ef_p=='ef_tr_highlight') {
				_t_lines = a.getElements('tr');
				for(i=0;i<_t_lines.length;i++){
					_t_lines[i].addEvents({
						'mouseenter':function(){
							var _l_cols = this.getElements('td');
							for(j=0;j<_l_cols.length;j++){
								var _class = _l_cols[j].get('class');
								_l_cols[j].set('class','over '+_class);
							}
						},
						'mouseleave':function(){
							var _l_cols = this.getElements('td');
							for(j=0;j<_l_cols.length;j++){
								var _class = _l_cols[j].get('class').replace(/over/,'');
								_l_cols[j].set('class',_class);
							}
						}
					});
				}
			}
		});
	}

	/* Image Box Auto Sizing */
	if($$('#image_box').length>0){
		var _image_box = $('image_box');
		_image_box.getElement('ul').setStyle('width',130*_image_box.getElements('li').length);
	}
});



/* --------------------------- ESSENTIALS --------------------------- */

/* Regex Trimmer */
var cTrim = function(a,b,c) {
	_reg = new RegExp(eval('/'+b+'/'));
	s = _reg.exec(a);
	return (s==null) ? '' : s[c];
}

/* Fade */
var fadeObj = function(a){
	this.fade(a);
}

/* RDID Generator */
var RDID = function(){
	return (arguments[0]!=null ? arguments[0] : '')+$random(0,9)+''+$random(0,9);
}

/* Compare Dates */
var compareDates = function(){
	if(arguments[0]==null || arguments[1]==null) return false;
	_date = arguments[0];
	_cur_date = arguments[1];
	return (_cur_date.getTime() >= _date.getTime());
}

/* Days Amount */
var getDaysAmount = function(){
	month = arguments[0].getMonth();
	return (month==1 ? ((arguments[0].getFullYear()%4==0) ? 29 : 28) : (((month+1)%7)%2==0 && (month+1)%7!=0) ? 30 : 31);
}

/* Time Parser */
var parseTime = function(){
	if(arguments[0].length<4) return false;
	var _reg = new RegExp(/(\d*)\S(\d*)/);
	_time = _reg.exec(arguments[0]);
	if(_time.length<3) return false;

	h=_time[1].toInt(); m=_time[2].toInt();
	return [h,m];
}

/* --------------------------- COMMOM --------------------------- */

/* Cadastro Convenio Modal */
var cadastro_convenio = function(){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Convênios');

	/* #WARNING	Função a ser executada após Post */
	var fm = new Form('form_e',['../controladores/convenioGravar.php','post'],'field','');
	fm.addEvent('success',function(){
		recarrega_combo('convenio');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));

	fm.attach(md.win);

	campo_nome = fm.newField('Nome do Convênio','nome',300 ,'text');
	campo_nome.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

	cod_conv = fm.newField('Código do Convênio','code',300 ,'text');
	cod_conv.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

	fm.attachSendBar(['Cadastrar','mb']);
	md.show();
};

/* Cadastro Plano Modal */
var cadastro_plano = function(){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Planos de Saúde');

	var fm = new Form('form_e',['../controladores/planoSaudeGravar.php','post'],'field');
	fm.addEvent('success',function(){
		recarrega_combo('plano_saude');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	/* #NEW BEGINS */
	/*check_list_exemplo = fm.newCheckList('CheckList',[
		['CheckBox 1','checkbox1',1],
		['CheckBox 2','checkbox2',2],
		['CheckBox 3','checkbox3',3],
		['CheckBox 4','checkbox4',4],
		['CheckBox 5','checkbox5',5],
		['CheckBox 6','checkbox6',6],
		['CheckBox 7','checkbox7',7]
	]);*/

	//Exemplo de Evento
	/*$each(check_list_exemplo.getElements('label'), function(a){
		a.addEvent('click',function(){ alert('name: '+this.getElement('input').get('name') + '\nvalue: '+this.getElement('input').get('value')) });
	});*/
	/* NEW ENDS# */

	//v-- Valor		 v--- MAX LENGTH
	nome_plano = fm.newField('Nome do Plano','nome',300 ,'text','',	50);
	nome_plano.addEvent('change',function (event) { Mascara("STRING",this, event); });

	//Para voltar a cadastrar o código do plano descomentar
	//cod_plano = fm.newField('Código do Plano','codigo',200 ,'text');
	//cod_plano.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

	tel_fixo_plano = fm.newField('Telefone Fixo','tel_fixo',200 ,'text', '', 14);
	tel_fixo_plano.addEvent('keypress',function (event) { return Mascara("TEL",this, event); });

	tel_com_plano = fm.newField('Telefone Comercial','tel_com',200 ,'text', '', 14);
	tel_com_plano.addEvent('keypress', function (event) { return Mascara("TEL",this, event); });

	tel_cel_plano = fm.newField('Celular','tel_cel',200 ,'text', '', 16);
	tel_cel_plano.addEvent('keypress',function (event) { return Mascara("CEL",this, event); });

	email_plano = fm.newField('Email','email',200 ,'text');

	//fm.injectBlock('Procurar o <b>telefone</b> do Plano de Saúde Cadastrado ajudará você e a todos da equipe em agendamentos e pesquisas futuras.');

	fm.addEvent('request',function(a){
		if(this.value === ""){
			a.cancel();
			a.failure();

		}
	}.bind(nome_plano));

	//Para voltar a cadastrar o código do plano descomentar
	/*fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(cod_plano));*/

	fm.attachSendBar(['Cadastrar Plano','mb']);
	md.show();
};






/* Cadastro Template para prontuário/receituário */
var cadastro_prontuario_template = function(){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Template para Prontuários');

	var fm = new Form('form_e',['../controladores/templateProntuarioGravar.php','post'],'field');
	fm.addEvent('success',function(){
		 recarrega_combo('$combo_template_tratamento');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	//v-- Valor		 v--- MAX LENGTH
	nome_template = fm.newField('Nome do Template','nome_template',300 ,'text','',	50);
	nome_template.addEvent('change',function (event) { Mascara("STRING",this, event); });
        
        // Seleção de compartilhamento do template
        // Defindo os tipos de compartilhamento        
        check_list_compartilhamento = fm.newCheckList('Compartilhar Template',[
		['Não Compartilhar','nenhum',1],
		['Para mesma especialidade','Especialidade',2],
                ['Para Todos','Todos',3]
	]);
        
        // Exibindo opções de compartilhamento
        $each(check_list_compartilhamento.getElements('label'), function(a){
		a.addEvent('click',function(){ alert('name: '+this.getElement('input').get('name') + '\nvalue: '+this.getElement('input').get('value')) });
	});

	//Para voltar a cadastrar o código do plano descomentar
	//cod_plano = fm.newField('Código do Plano','codigo',200 ,'text');
	//cod_plano.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

	medicamento = fm.newField('Medicamento','medicamento',200 ,'text', '', 14);
	//tel_fixo_plano.addEvent('keypress',function (event) { return Mascara("TEL",this, event); });

	Concentracao = fm.newField('Concentração','concentracao',200 ,'text', '', 14);
	//tel_com_plano.addEvent('keypress', function (event) { return Mascara("TEL",this, event); });

	quantidade = fm.newField('Quantidade','quantidade',200 ,'text', '', 16);
	//tel_cel_plano.addEvent('keypress',function (event) { return Mascara("CEL",this, event); });

	instrucao = fm.newField('Modo de uso','instrucao',300 ,'text','', 80);
        observacao = fm.newField('Obsevação','observacao',300 ,'text');

	//fm.injectBlock('Procurar o <b>telefone</b> do Plano de Saúde Cadastrado ajudará você e a todos da equipe em agendamentos e pesquisas futuras.');

	fm.addEvent('request',function(a){
		if(this.value === ""){
			a.cancel();
			a.failure();

		}
	}.bind(nome_template));

	//Para voltar a cadastrar o código do plano descomentar
	/*fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(cod_plano));*/

	fm.attachSendBar(['Cadastrar Template','mb']);
	md.show();
};












/* Cadastro Plano Modal */
var cadastro_IMPRIMIR = function(){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Planos de Saúde');

	var fm = new Form('form_e',['../controladores/planoSaudeGravar.php','post'],'field');
	fm.addEvent('success',function(){
		recarrega_combo('plano_saude');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	/* #NEW BEGINS */
	/*check_list_exemplo = fm.newCheckList('CheckList',[
		['CheckBox 1','checkbox1',1],
		['CheckBox 2','checkbox2',2],
		['CheckBox 3','checkbox3',3],
		['CheckBox 4','checkbox4',4],
		['CheckBox 5','checkbox5',5],
		['CheckBox 6','checkbox6',6],
		['CheckBox 7','checkbox7',7]
	]);*/

	//Exemplo de Evento
	/*$each(check_list_exemplo.getElements('label'), function(a){
		a.addEvent('click',function(){ alert('name: '+this.getElement('input').get('name') + '\nvalue: '+this.getElement('input').get('value')) });
	});*/
	/* NEW ENDS# */

	//v-- Valor		 v--- MAX LENGTH
	nome_plano = fm.newField('Nome do Plano','nome',300 ,'text','',	50);
	nome_plano.addEvent('change',function (event) { Mascara("STRING",this, event); });

	//Para voltar a cadastrar o código do plano descomentar
	//cod_plano = fm.newField('Código do Plano','codigo',200 ,'text');
	//cod_plano.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

	tel_fixo_plano = fm.newField('Telefone Fixo','tel_fixo',200 ,'text', '', 14);
	tel_fixo_plano.addEvent('keypress',function (event) { return Mascara("TEL",this, event); });

	tel_com_plano = fm.newField('Telefone Comercial','tel_com',200 ,'text', '', 14);
	tel_com_plano.addEvent('keypress', function (event) { return Mascara("TEL",this, event); });

	tel_cel_plano = fm.newField('Celular','tel_cel',200 ,'text', '', 16);
	tel_cel_plano.addEvent('keypress',function (event) { return Mascara("CEL",this, event); });

	email_plano = fm.newField('Email','email',200 ,'text');

	//fm.injectBlock('Procurar o <b>telefone</b> do Plano de Saúde Cadastrado ajudará você e a todos da equipe em agendamentos e pesquisas futuras.');

	fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(nome_plano));

	//Para voltar a cadastrar o código do plano descomentar
	/*fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(cod_plano));*/

	fm.attachSendBar(['Cadastrar Plano','mb']);
	md.show();
}




/* Cadastro Plano Modal */
var cadastro_Template = function(){
	var md = new Modal(['modal']);
	md.setHeader('Template para tratamentos');

	var fm = new Form('form_e',['../controladores/planoSaudeGravar.php','post'],'field');
	fm.addEvent('success',function(){
		recarrega_combo('plano_saude');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	/* #NEW BEGINS */
	/*check_list_exemplo = fm.newCheckList('CheckList',[
		['CheckBox 1','checkbox1',1],
		['CheckBox 2','checkbox2',2],
		['CheckBox 3','checkbox3',3],
		['CheckBox 4','checkbox4',4],
		['CheckBox 5','checkbox5',5],
		['CheckBox 6','checkbox6',6],
		['CheckBox 7','checkbox7',7]
	]);*/

	//Exemplo de Evento
	/*$each(check_list_exemplo.getElements('label'), function(a){
		a.addEvent('click',function(){ alert('name: '+this.getElement('input').get('name') + '\nvalue: '+this.getElement('input').get('value')) });
	});*/
	/* NEW ENDS# */

	//v-- Valor		 v--- MAX LENGTH
	nome_template = fm.newField('Nome do Template','nome',100 ,'text','',	50);
	nome_template.addEvent('change',function (event) { Mascara("STRING",this, event); });

	//Para voltar a cadastrar o código do plano descomentar
	//cod_plano = fm.newField('Código do Plano','codigo',200 ,'text');
	//cod_plano.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });
	
	
	descricao_template = fm.newField('Descrição','descricao',400 ,'text');
	receita_template = fm.newField('Receita','Receita',400 ,'text');



	email_plano = fm.newField('Email','email',200 ,'text');

	//fm.injectBlock('Procurar o <b>telefone</b> do Plano de Saúde Cadastrado ajudará você e a todos da equipe em agendamentos e pesquisas futuras.');

	fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(nome_plano));

	//Para voltar a cadastrar o código do plano descomentar
	/*fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();

		}
	}.bind(cod_plano));*/

	fm.attachSendBar(['Cadastrar Template','mb']);
	md.show();
}

/* Cadastro Dentista Indicador Modal */
var cadastro_dentista = function(){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Dentista Indicador');

	/* #WARNING	 Função a ser executada após Post */
	var fm = new Form('form_e',['../controladores/dentistaEncaminhadorGravar.php','post'],'field','');
	fm.addEvent('success',function(){
		recarrega_combo('dentista_encaminhador');
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	nome_encaminhador = fm.newField('Nome','nome',300 ,'text');
	nome_encaminhador.addEvent('change',function (event) { Mascara("STRING",this, event); });

	cro_encaminhador = fm.newField('CRO','cro',200 ,'text');
	cro_encaminhador.addEvent('change',function (event) { return Mascara("STRING",this, event); });

	tel_fixo_encaminhador = fm.newField('Telefone Fixo','tel_fixo',200 ,'text');
	tel_fixo_encaminhador.addEvent('keypress',function (event) { return Mascara("TEL",this, event); });

	tel_com_encaminhador = fm.newField('Telefone Comercial','tel_com',200 ,'text');
	tel_com_encaminhador.addEvent('keypress',function (event) { return  Mascara("TEL",this, event); });

	tel_cel_encaminhador = fm.newField('Telefone Celular','tel_cel',200 ,'text');
	tel_cel_encaminhador.addEvent('keypress',function (event) { return Mascara("TEL",this, event); });

	fm.newField('Email','email',200 ,'text');

	fm.addEvent('request',function(a){
		if(this.value == ""){
			a.cancel();
			a.failure();
		}
	}.bind(nome_encaminhador));

	fm.attachSendBar(['Cadastrar','mb']);
	md.show();
}

/* Alteracao de senha*/
var altera_senha = function(){
	var md = new Modal(['modal']);
	md.setHeader('Alteração de Senha');

	var fm = new Form('form_e',['../controladores/usuarioGravar.php?acao=alterarSenha','post'],'field','');
	fm.addEvent('success',function(){
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	senha_antiga = fm.newField('Senha Antiga','senha_antiga',200 ,'password');
	nova_senha = fm.newField('Nova Senha','senha',200 ,'password');
	confirma_senha = fm.newField('Confirmar Nova Senha','conf_senha',200 ,'password');
	confirma_senha.addEvent('change', function () { if(this.value!=nova_senha.value) {alert('Senha não confere com a digitada inicialmente'); this.value=''; this.focus();}});

	fm.newField('','id',200 ,'hidden',document.cd_field.id_usuario.value);
	fm.injectBlock('Utilize números e caracteres para aumentar a segurança de sua senha.');
	fm.attachSendBar(['Alterar Senha','mb']);
	md.show();
}

/* Edição texto Home */
var edita_texto = function(){
	var md = new Modal(['modal']);
	md.setHeader('Editar texto');

	var fm = new Form('form_e',['../controladores/configuracaoGravar.php?acao=editaTexto','post'],'field','');
	fm.addEvent('success',function(){(
		function(){
			this.fadeAndRemove(); location.href = "index.php";
		}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);

	texto = fm.newField('Texto','texto',[450,12],'textarea',$('home_text').get('html'));

	fm.attachSendBar(['Atualizar Texto','mb']);
	md.show();

}

/* Cadastro Convenio Modal */
var cadastro_documentos = function(valor){
	var md = new Modal(['modal']);
	md.setHeader('Cadastro de Documentos');

	/* #WARNING	 Função a ser executada após Post */
	/*recarrega_tabela()*/
	var fm = new Form('form_e',['../controladores/documentosGravar.php?id_paciente='+ valor +'&acao=cadastrar','post'],'field','');
	fm.addEvent('success',function(){
		(function(){this.fadeAndRemove()}.bind(this)).delay(500);
	}.bind(md));
	fm.attach(md.win);


	/* EXEMPLO DE TEXTAREA */
/*	fm.newField('Teste','teste',400,'textarea');

	fm.newField('Teste2','teste2',300,'file');
*/
	obs = fm.newField('Observações','obs',[300, 4],'textarea');
	obs.addEvent('change',function () { Mascara("STRING",this, 'onChange'); });

        data = fm.newField('Data do Documento','data',300,'text');
        data.addEvent('change',function(){Mascara("DATA",this,'onChange'); });


	cam_doc = fm.newField('Caminho do documento','documento',300 ,'file');

	fm.attachSendBar(['Cadastrar','mb']);
	md.show();
}


/* Teeth One-by-One Marker */
var teeth_obo_mark = function(){
	var $ul_teeths = $$('.teeths');
	var $CUR_T = null;
	if($ul_teeths.length>0){
		_teeth_field = $('dente');
        switch(arguments[0]){
			case 'unmark':
				_ul_childs = $ul_teeths.getElements('td');
				$each(_ul_childs,function(a){
					a.set('alt','0');
					a.set('class','');
					$$('input[name=dente]').set('checked',false);
				});
				set_slide(_teeth_field.get('value').split(','));
				_teeth_field.set('value',-1);
				break;

			case 'mark':
				_ul_childs = $ul_teeths.getElements('td');
				$each(_ul_childs,function(a){
					a.set('alt','0');
					a.set('class','selected');
				});

				set_slide(['iA']);
				_teeth_field.set('value',100);
				break;

            default:
				_value = _teeth_field.get('value').split(',');
				for(i=0;i<$ul_teeths.length;i++){
					if(_value.contains('100')){
						teeth_obo_mark('mark');
					} else {
						_li_teeths = $ul_teeths[i].getElements('td');

						for(j=0; j<_li_teeths.length;j++){
							_li_teeths[j].addEvents({
							'click':function(a){
								if($CUR_T != this[0]){
									teeth_obo_mark('unmark');
									this[1].set('value',this[0].getElement('span').get('class'));
									this[0].set('class','selected');
									if($CUR_T!=null) $CUR_T.set('class','');
										$CUR_T = this[0];
									}
									set_slide([this[0].getElement('span').get('class')]);
								}.bind([_li_teeths[j],_teeth_field])
							});
						}
					}
				}
				set_slide(_teeth_field.get('value').split(','));
		}
	}
}

/*Teeth Multiple Marker */
var teeth_multi_mark = function(){
	var $ul_teeths = $$('.teeths');
	var $CUR_T = null;
	if($ul_teeths.length>0){
		_teeth_field = $('dentes');
        _value = new Array();
		if(arguments[0]!='update') _teeth_field.set('value','-1');

		runXHSend=(arguments[0]=='runXHSend') ? true: false;
		switch(arguments[0]){
			case 'unmark':
				_ul_childs = $ul_teeths.getElements('td');
				$each(_ul_childs,function(a){
					a.set('alt','0');
					a.set('class','');
					//$$('input[name=dentes_active]').set('checked',false); ?
				});
				if(arguments[1]){
					_ul_childs[0][0].set('alt','1');
					_ul_childs[0][0].fireEvent('click',true);
				}
				break;

			case 'mark':
				_ul_childs = $ul_teeths.getElements('td');
				$each(_ul_childs,function(a){
					a.set('alt','0');
					a.fireEvent('click');
				});
				if(arguments[1]){
					_ul_childs[0][0].set('alt','0');
					_ul_childs[0][0].fireEvent('click',true);
				}
				break;

			case 'update':
				//$$('input[name=dentes_active]').get('checked') ?
					up_req = new Request(
						{
							onSuccess:function(a){ $('div_selecao').set('html',a); },
							'url':'../controladores/AJAX.tratamento_selecao.php',
							'method':'get'
						}
					).send("dentes=" + _teeth_field.get('value') + "&id_paciente=" + $('id_paciente_aux').get('value')+ "&status=" + $('status_aux').get('value'));
				//}
				break;

			case 'setSelect': runXHSend=false;

			case 'set':
				if(arguments[1]!=null && $chk(arguments[1])){
					_mark_field = $(arguments[1]);
					_value = (_mark_field.get('value')).split(',');
					if(_value.length>0){
						if(_value.contains('100')){
							teeth_multi_mark('mark');
							return false;
						}
						$each(_value, function(a){
							_li = $$('.'+a[0]+a[1].toUpperCase()+ ((a.length==3) ? a[2] : '') ).getParent('td');
							_li.set('alt','0');
							_li.fireEvent('click',this[0]);

						}.bind([runXHSend]));
					}
				}
				break;

			default:
			{
				for(i=0;i<$ul_teeths.length;i++){
					_li_teeths = $ul_teeths[i].getElements('td');
					for(j=0; j<_li_teeths.length;j++){
						_li_teeths[j].addEvents({
							'click':function(a){
								_value = this[1].get('value').split(',');
								_class = this[0].getElement('span').get('class');
								if(this[0].get('alt')=='1'){
									_value.erase(_class);
									if(_value.contains('100')) _value.erase('100');

									this[1].set('value',_value);
									this[0].set('class','');
									this[0].set('alt','0');
								} else {
									_value.include(_class);

									this[0].set('class','selected');
									this[0].set('alt','1');

									if(_value.length >= 53){
										if(!_value.contains('100')) _value.include('100');
									} else {
										if(_value.contains('100')) _value.erase('100');
									}

									this[1].set('value',_value);
								}
								set_slide(_value);
								if(this[2] && ($type(a)=='event' || a==true)){
									xhreq = new Request(
										{
											onSuccess:function(a){ $('div_selecao').set('html',a); },
											'url':'../controladores/AJAX.tratamento_selecao.php',
											'method':'get'
										}
									).send("dentes=" + _value + "&id_paciente=" + $('id_paciente_aux').get('value')+ "&status=" + $('status_aux').get('value')+"&");
								}
							}.bind([_li_teeths[j],_teeth_field,runXHSend])
						});
					}
				}
			}
				break;
		}


		set_slide(_value);
	}
}

/* Set Slide Control */
var set_slide = function(){
	slide_targets = $$('.slide');


	_temp_value = arguments[0].filter(function(a){
		return a.test(/i[A-Z]*/);
	});

	if(_temp_value.length>0){
		$each(slide_targets, function(a){
			_toggler = a.getElement('.toggler');
			_class = _toggler.get('class');
			if(!_class.contains('nohide')){
				_toggler.set('class','nohide '+_toggler.get('class'));
			}
		});
	} else {
		$each(slide_targets, function(a){
			_toggler = a.getElement('.toggler');
			_class = (_toggler.get('class')).replace(/nohide /,'');
			_toggler.set('class',_class);
		});
	}
}

/* Slide Behavior */
var slide_blocks = function(){
	slide_targets = $$('.slide');
	if(slide_targets.length >0){
		$each(slide_targets, function(a){
			_slide_block = a.getElement('.slide_block');
			_toggler = a.getElement('.toggler');
			_toggler.addEvent('click',function(){
				_class = this.get('class');
				if(_class.contains('active')){
					if(!_class.contains('nohide')){
						arguments[0].fade('out');
						arguments[0].slide('out');
						_class = _class.replace(/active/,'');
						this.set('class',_class);
					}
				} else {
					arguments[0].fade('in');
					arguments[0].slide('in');
					this.set('class','active '+_class);
				}
			}.pass(_slide_block,_toggler));

			if(!_toggler.get('class').contains('nohide')){
				_slide_block.set('slide');
				_slide_block.slide('hide');
			} else {
				_toggler.set('class','active '+_toggler.get('class'));
			}
		});
	}
}

/* Remove Picture Button */
var tabs_remove_picture = function(){
	fields = $$('.tab_field');

	if(fields.length>0){
		$each(fields, function(a){
			$each(a.getElements('li'),function(b){
				var tools = b.getElement('div.tools');
                video = tools.get('class').contains('video');

				remove = tools.getElement('a.remove');
                if(video){
                    /* Not needed - yet.
                    view = tools.getElement('a.view');
                    view.removeEvents('click');*/
                } else {
                    full = tools.getElement('a.zoom');
                    full.removeEvents('click');
                }

                remove.removeEvents('click');

				if(a.get('alt')=='1'){
					request = new Request({
						'url':'../controladores/AJAX.tratamento_atualizacao.php',
						'method':'post',
						onComplete:function(a){
							if(a=='1'){
								$('status').set('html','<p>Imagem removida com sucesso!</p>');
								$('status').set('class','st_success');
							} else {
								$('status').set('html','<p>Ocorreu uma falha ao remover a imagem. Verifique se a imagem já foi removida.</p>');
								$('status').set('class','st_fail');
							}
							$('status').fade('in');
							(function(){$('status').fade('out')}).delay(2000);
						},
						onFailure:function(){
							$('status').set('html','<p>Ocorreu um erro na comunicação com o Servidor. Verifique sua conexão com o Servidor.</p>');
							$('status').set('class','st_fail');
							$('status').fade('in');
							(function(){$('status').fade('out')}).delay(5000);
						}
					});

					remove.addEvent('click',function(request){
					   if(confirm('Deseja realmente remover esta imagem?')){
						   id = this.get('alt');
						   request.send('type=remover&id_imagem='+id);
						   $clear(this.interval);
						   this.dispose();
					   }
					}.pass(request,b));
				} else {
					remove.addEvent('click',function(b,video){
                        if(!video) src = b.getElement('img.pic').get('src')
                        else src = this.get('alt');

                        remove_imagem('imagens_selecionadas',src);
					}.pass([b,video],remove))
				}

				if(!video){
                    full.addEvent('click',function(){
                        _img = this.getLast('img');
                        date = _img.get('alt');
                        show_full(this);
                    }.bind(b));
                }
			});
		});
	}
}

var show_full = function(_obj){
	if(_obj.get('class').contains('zoom'))
		_obj = _obj.getParent('li');

	_modal = new Modal(['modal','win']);
	//for(i=0; i<obj.childNodes.length; i++) if(obj.childNodes[i].src != null) {k=i;};
    img = _obj.getElement('.pic');
	_image = new Element('img',{'src':img.get('src'),'style':'position:absolute; top:-500000px'}).inject($(document.body));

	size = _image.getSize();

    /*size = _image.measure(function(){
        return this.getSize();
    });*/

    if(size.x > 800 || size.y > 600){
		if(size.x > 800){
			newSize_x = 800;
			newSize_y = 800/(size.x/size.y);
		} else {
			newSize_x = 600/(size.x/size.y);
			newSize_y = 600;
		}
		_image.setStyles({'width':newSize_x+'px','height':newSize_y+'px'});
	} else {
        newSize_x = size.x;
        newSize_y = size.y;
    }

	date = _image.get('alt');
	if(date!='' && date!=undefined){
		_modal.setHeader(date);
		_image.setStyle('margin-top','-6px');
		newSize_y+=41;
	}

	_modal.win.setStyles({
		'width': newSize_x,
		'height': newSize_y-7
	});
	_image.setStyles({'position':'relative','top':''}).inject(_modal.win);

	_modal.attach();
	_modal.show();
}


/* --------------------------- CLASSES --------------------------- */
/* BSchedule Class */
var BSchedule = new Class({
	Implements:Events,
	initialize:function(){
		if(arguments[0]==null) return false;
		this.table = $(arguments[0]);
		this.tbody = new Element('tbody');
		this.data;
		this.isAttached = false;
		this.startTime;
		this.currentTime;
		this.endTime;
		this.gapTime;
	},
	setData:function(){
		if(arguments[0]==null) return false;
		this.data = JSON.decode(arguments[0]);
	},
	changeSchedule:function(){
		this.fireEvent('changeschedule',arguments[0]);
	},
	newScheduleEvent:function(){
		this.fireEvent('newevent',arguments[0]);
	},
	editScheduleEvent:function(){
		this.fireEvent('editevent',arguments[0]);
	},
	removeScheduleEvent:function(){
		this.fireEvent('removeevent',arguments[0]);
	},
	build:function(){
		if(this.isAttached) this.tbody.set('html','');
		else this.attach();
		this.startTime = this.data.start;
		this.endTime = this.data.end;
		this.gapTime = this.data.gap.toInt();
		this.currentTime = this.data.cur_time.toInt();

		_start_time = parseTime(this.startTime);
		_end_time = parseTime(this.endTime);

		h=_start_time[0]; m=_start_time[1];
		eh=_end_time[0]; em=_end_time[1];
		linha = 0;
		while(h<=eh && (h==eh ? m<=em : true)){
			linha++;
			_cur_h=h+'_'+(m>9 ? m : '0'+m);
			
			if((this.data.hours.length > 0) || (this.data.hours[_cur_h]!=null)){
				cm=0; tm=0; th=0;
				hours_length=this.data.hours[_cur_h]['len'].toInt();

				while(cm<hours_length){
					_dsp_h=h+':'+(m>9 ? m : '0'+m);
					_act_box = new Element('div',{'class':'act_box'});
					_span = new Element('span',{'style':'float:right; width:16px; margin-top: 13px;'});
					_act_span = new Element('span').inject(_act_box);

					/* BSchedule Events Begins */
					_a_edit = new Element('a',{'class':'editarConsulta ir','href':'javascript:void(0)','title':'Editar Horário','html':'Editar'}).inject(_act_span);
					_a_remove = new Element('a',{'class':'desmarcarConsulta ir','href':'javascript:void(0)','title':'Desmarcar Horário','html':'Desmarcar Horário'}).inject(_act_span);
					_a_down = new Element('a',{'class':'diminuirConsulta ir','href':'javascript:void(0)','title':'Estender Horário','html':'+'+this.gapTime+'min'});
					_a_up = new Element('a',{'class':'estenderConsulta ir','href':'javascript:void(0)','title':'Estender Horário','html':'-'+this.gapTime+'min'});
					_a_down.addEvent('click',function(){
						parent_tr = this.getParent('tr');
						parent_class = parent_tr.getLast('td').get('class');
						next = (parent_tr.getNext('tr')!=null) ? parent_tr.getNext('tr').getLast('td') : null;

						if(next!=null){
							next_class = next.get('class');

							if(parent_class=='celulaTratamentosAgenda u' || parent_class=='celulaTratamentosAgenda mb'){
								if(next_class=='celulaTratamentosAgenda' || next_class==null){
									if(parent_class=='celulaTratamentosAgenda mb'){
										while(parent_tr.getLast('td').get('class')!='celulaTratamentosAgenda mt') parent_tr = parent_tr.getPrevious('tr');
									}
									td_h_tx = parent_tr.getFirst('td.celulaHorarioAgenda').get('text');
									time_h = parseTime(td_h_tx);

									_reg = new RegExp(/(\d*)-(\d*)-(\d*)/);
									sel_arg = _reg.exec(arguments[0].data.sel_date);
									sel_date = new Date();

									sel_date.setDate(sel_arg[3].toInt());
									sel_date.setMonth(sel_arg[2].toInt()-1);
									sel_date.setFullYear(sel_arg[1].toInt());
									sel_date.setHours(time_h[0]);
									sel_date.setMinutes(time_h[1]);
									sel_date.setSeconds(0);
									if(sel_date.getTime()<arguments[0].currentTime){
										alert('Não é possível alterar o horário de consultas em datas anteriores à atual.');
										return false;
									}

									_cur_time = td_h_tx.replace(/:/,'_');
									arguments[0].data.hours[_cur_time].len=arguments[0].data.hours[_cur_time].len.toInt()+arguments[0].gapTime;
								} else { //mt ou u
									alert('Náo é possível aumentar uma consulta sobre o horário de outra.');
									return false;
								}
							} else if(parent_class=='celulaTratamentosAgenda mt') {
								td_h_tx = parent_tr.getFirst('td.celulaHorarioAgenda').get('text');
								_cur_time = td_h_tx.replace(/:/,'_');

								_new_time = parseTime(td_h_tx);
								_h = _new_time[0]; _m = _new_time[1];

								_reg = new RegExp(/(\d*)-(\d*)-(\d*)/);
								sel_arg = _reg.exec(arguments[0].data.sel_date);
								sel_date = new Date();

								sel_date.setDate(sel_arg[3].toInt());
								sel_date.setMonth(sel_arg[2].toInt()-1);
								sel_date.setFullYear(sel_arg[1].toInt());
								sel_date.setHours(_h);
								sel_date.setMinutes(_m);
								sel_date.setSeconds(0);

								temp_date = new Date();
								temp_date.setTime(arguments[0].currentTime);
								if(sel_date.getTime()<arguments[0].currentTime){
									alert('Náo é possível alterar o horário de consultas em datas anteriores à atual.');
									return false;
								}

								_m = (_m+arguments[0].gapTime)%60;
								if(_m==0) _h = (_h+1)%24;
								_new_time_str = _h+'_'+(_m>9 ? _m : '0'+_m);

								arguments[0].data.hours[_cur_time]['len']=arguments[0].data.hours[_cur_time].len.toInt()-arguments[0].gapTime;
								arguments[0].data.hours[_new_time_str]=arguments[0].data.hours[_cur_time];
								arguments[0].data.hours[_cur_time]=null;
								_cur_time = _new_time_str;
							}
							//arguments[0].build();
							arguments[0].changeSchedule(_cur_time);
						} else {
							alert('Não é possível aumentar uma consulta\npara além dos limites de horário.');
							return false
						};
						/*} else {
							alert();
						}*/
					}.pass(this,_a_down));

					_a_up.addEvent('click',function(){
						parent_tr = this.getParent('tr');
						parent_class = parent_tr.getLast('td').get('class');

						if(parent_class=='celulaTratamentosAgenda mb' || parent_class=='celulaTratamentosAgenda u' || parent_class=='celulaTratamentosAgenda mt'){
							if(parent_class=='celulaTratamentosAgenda mb'){
								while(parent_tr.getLast('td').get('class')!='celulaTratamentosAgenda mt') parent_tr = parent_tr.getPrevious('tr');
								td_h_tx = parent_tr.getFirst('td.celulaHorarioAgenda').get('text');
								_cur_time = td_h_tx.replace(/:/,'_');

								time_h = parseTime(td_h_tx);

								_reg = new RegExp(/(\d*)-(\d*)-(\d*)/);
								sel_arg = _reg.exec(arguments[0].data.sel_date);
								sel_date = new Date();

								sel_date.setDate(sel_arg[3].toInt());
								sel_date.setMonth(sel_arg[2].toInt()-1);
								sel_date.setFullYear(sel_arg[1].toInt());
								sel_date.setHours(time_h[0]);
								sel_date.setMinutes(time_h[1]);
								sel_date.setSeconds(0);
								if(sel_date.getTime()<arguments[0].currentTime){
									alert('Não é possível alterar o horário de consultas em datas anteriores à atual.');
									return false;
								}

								arguments[0].data.hours[_cur_time].len=arguments[0].data.hours[_cur_time].len.toInt()-arguments[0].gapTime;
							} else {
								td_h_tx = parent_tr.getFirst('td.celulaHorarioAgenda').get('text');
								_cur_time = td_h_tx.replace(/:/,'_');

								time_h = parseTime(td_h_tx);

								_reg = new RegExp(/(\d*)-(\d*)-(\d*)/);
								sel_arg = _reg.exec(arguments[0].data.sel_date);
								sel_date = new Date();

								sel_date.setDate(sel_arg[3].toInt());
								sel_date.setMonth(sel_arg[2].toInt()-1);
								sel_date.setFullYear(sel_arg[1].toInt());
								sel_date.setHours(time_h[0]);
								sel_date.setMinutes(time_h[1]);
								sel_date.setSeconds(0);
								if(sel_date.getTime()<arguments[0].currentTime){
									alert('Não é possível alterar o horário de consultas em datas anteriores à atual.');
									return false;
								}

								_prev_time = parseTime(_cur_time);
								h = _prev_time[0]; m = _prev_time[1];
								m = (m-arguments[0].gapTime)%60;
								if(m<0){
									m = -m;
									h = (h-1)%24;
								}
								_prev_time_str = h+'_'+(m>9 ? m : '0'+m);

								if(arguments[0].data.hours[_prev_time_str]!=null || parent_tr.getPrevious('tr')==null){
									alert('Não é possível aumentar a duração de uma consulta sobre o horário\nde outra ou para além dos limites de horário');
									return false;
								}
								arguments[0].data.hours[_prev_time_str] = arguments[0].data.hours[_cur_time];
								arguments[0].data.hours[_prev_time_str].len=arguments[0].data.hours[_prev_time_str].len.toInt()+arguments[0].gapTime;
								arguments[0].data.hours[_cur_time] = null;

								_cur_time = _prev_time_str;
							}
						} else return false;

						//arguments[0].build();
						arguments[0].changeSchedule(_cur_time);
					}.pass(this,_a_up));

					_a_edit.addEvent('click',function(){
						arguments[0].editScheduleEvent(this);
					}.pass(this,_a_edit));

					_a_remove.addEvent('click',function(){
						arguments[0].removeScheduleEvent(this);
					}.pass(this,_a_remove));
					/* BSchedule Events Ends */

					_div = new Element('div');

					if(cm==0){
						_class = (hours_length<=this.gapTime) ? 'celulaPacienteAgenda u' : 'celulaPacienteAgenda mt';
						_class_t = (hours_length<=this.gapTime) ? 'u' : 'mt';
						_div_paciente = new Element('span',{'html':'<b>'+this.data.hours[_cur_h]['nome']+'</b></br>'+this.data.hours[_cur_h].telefone, 'class':'consultaMarcadaPaciente'});

						_tratamento_str = '';
						_tratamento_dentes = [];
						_temp = -1;
						for(i=0; i<this.data.hours[_cur_h].tratamentos.length;i++){
							if(this.data.hours[_cur_h]['tratamentos'][i][0]!=_temp){
								_tratamento_str += ((i!=0) ?  '], ' : '')+this.data.hours[_cur_h]['tratamentos'][i][1]+' ['+this.data.hours[_cur_h]['tratamentos'][i][2];
								_temp = this.data.hours[_cur_h]['tratamentos'][i][0];
							} else {
								_tratamento_str += ', '+this.data.hours[_cur_h]['tratamentos'][i][2];
							}
						}
						_tratamento_str += ']';
						_span_tratamento = new Element('span',{'html':_tratamento_str,'class':'consultaMarcadaTratamento'} );
						_div_tratamento = new Element('td',{'class':'celulaTratamentosAgenda ' + _class_t});
						_span_tratamento.inject(_div_tratamento);
						//_div_plano = new Element('td',{'class':'celulaPlanoAgenda ' + _class_t,'html':'<span class="consultaMarcadaPlano">'+this.data.hours[_cur_h]['plano']+'</span>'+this.data.hours[_cur_h]['plano_cod']});
						_div_plano = new Element('td',{'class':'celulaPlanoAgenda ' + _class_t,'html':'<span class="consultaMarcadaPlano">'+this.data.hours[_cur_h]['plano']+'</span>'});
						_a_up.inject(_span);
						_a_down.inject(_span);

					}else if(cm==(hours_length-this.gapTime)){
						_class = 'celulaTratamentosAgenda mb'
						_class_u = 'mb'
						_a_up.inject(_span);
						_a_down.inject(_span);
					}else {
						_class = 'celulaTratamentosAgenda mc';
						_class_u = 'mc'
					}

					if (linha%2==0)
						_tr = new Element('tr',{'class':'tableColor2'}).inject(this.tbody);
					else
						_tr = new Element('tr',{'class':'tableColor1'}).inject(this.tbody);
						_td_h = new Element('td',{'class':'celulaHorarioAgenda','text':_dsp_h}).inject(_tr);

						if(cm==0){
							_td = new Element('td',{'class':_class}).inject(_tr);
							_div_paciente.inject(_td);
							_div_plano.inject(_tr);_div_tratamento.inject(_tr);
							_a_edit.inject(_span_tratamento);
							_a_remove.inject(_span_tratamento);
							_span.inject(_div_tratamento);
						}else{
							_td = new Element('td', {'class':'celulaPacienteAgenda ' + _class_u}).inject(_tr);
							_span_paciente = new Element('span', {'class':'consultaMarcadaPaciente'});
							_span_paciente.inject(_td);
							_td = new Element('td', {'class':'celulaPlanoAgenda ' + _class_u}).inject(_tr);
							_span_plano = new Element('span', {'class':'consultaMarcadaPlano'});
							_span_plano.inject(_td);
							_td_t = new Element('td', {'class':_class}).inject(_tr);
							_span.inject(_td_t);
							_span_tratamento = new Element('span', {'class':'consultaMarcadaTratamento'});
							_span_tratamento.inject(_td_t);
						}

					cm+=this.gapTime;
					m=(m+this.gapTime)%60;
					if(m==0) h = (h+1)%24;
					if(cm<hours_length)
					linha++;
				}
			} else {
				_dsp_h=h+':'+(m>9 ? m : '0'+m);

				if (linha%2==0)
					_tr = new Element('tr',{'class':'tableColor2'}).inject(this.tbody);
				else
					 _tr = new Element('tr',{'class':'tableColor1'}).inject(this.tbody);
				_td_h = new Element('td',{'class':'celulaHorarioAgenda','text':_dsp_h}).inject(_tr);
				_td = new Element('td', {'class':'celulaPacienteAgenda'}).inject(_tr);
                                _td = new Element('td', {'class':'celulaPlanoAgenda'}).inject(_tr);
                                _td = new Element('td', {'class':'celulaTratamentosAgenda'}).inject(_tr);

				_span = new Element('span').inject(_td);
				_a = new Element('a',{'class':'marcarConsulta ir','href':'javascript:void(0);','title':'Marcar Horário','html':'Marcar Horário'}).inject(_span);
								
				_a.addEvent('click',function(){
					arguments[0].newScheduleEvent(this);
				}.pass(this,_a));

				m=(m+this.gapTime)%60;
				if(m==0) h=(h+1)%24;
			}
		}
	},
	attach:function(){
		if(!this.isAttached){
			this.tbody.inject(this.table);
			this.isAttached=true;
		}
	}
});

/* BCalendar Class */
var BCalendar = new Class({
	Implements: Events,
	initialize:function(){
		if(arguments[0]==null) return false;
		this.table = $(arguments[0]);
		this.date = new Date();
		this.tbody = new Element('tbody');
		this.data;
		this.selected;
		this.isAttached = false;
		this.isUpdating = false;
		this.calendarDate = null;
		this.monthLabels = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
		this.nav = new Object();

		if(arguments[1]!=null && $chk(arguments[1])) this.calendarDate = $(arguments[1]);

		if(arguments[2]!=null && arguments[2].length==4){
			this.nav['prevYear'] = $(arguments[2][0]);
			this.nav['nextYear'] = $(arguments[2][3]);
			this.nav['prevMonth'] = $(arguments[2][1]);
			this.nav['nextMonth'] = $(arguments[2][2]);

			this.nav['prevMonth'].addEvent('click',function(){
				_month = this.date.getMonth()-1;
				_year = this.date.getFullYear();
				_temp_date = this.date.getDate();
				if(_month == -1){ _month=11; _year--; }

				this.date.setFullYear(_year,_month,1);
				_days_amount = getDaysAmount(this.date);

				if(_temp_date > _days_amount) _temp_date = _days_amount;
				this.date.setDate(_temp_date);

				this.update();
			}.bind(this));

			this.nav['nextMonth'].addEvent('click',function(){
				_month = (this.date.getMonth()+1)%12;
				_year = this.date.getFullYear();
				_temp_date = this.date.getDate();
				if(_month == 0) _year++;

				this.date.setFullYear(_year,_month,1);
				_days_amount = getDaysAmount(this.date);

				if(_temp_date > _days_amount) _temp_date = _days_amount;
				this.date.setDate(_temp_date);

				this.update();
			}.bind(this));

			this.nav['prevYear'].addEvent('click',function(){
				this.date.setFullYear(this.date.getFullYear()-1);

				this.update();
			}.bind(this));

			this.nav['nextYear'].addEvent('click',function(){
				this.date.setFullYear(this.date.getFullYear()+1);

				this.update();
			}.bind(this));
		}
	},
	update:function(){
		this.fireEvent('update');
	},
	setData:function(){
		if(arguments[0]==null) return false;
		this.data = JSON.decode(arguments[0]);
		this.date.setFullYear(this.data.year, this.data.month-1, this.data.day);
	},
	changeDay:function(){
		if(arguments[1]==null || arguments[1]) this.date.setDate(arguments[0].getElement('span').get('text').toInt());
		this.fireEvent('changeday',arguments[0]);
	},
	setCalDate:function(){
		if(arguments[3]=null) return false;
		this.tbody.set('html','');
		this.date.setFullYear(arguments[2], arguments[1], arguments[0]);
		this.build();
	},
	build:function(){
		if(!this.isAttached){
			this.attach();
		} else {
			this.tbody.set('html','');
		}
		if(this.calendarDate!=null) this.calendarDate.set('text',this.monthLabels[this.date.getMonth()]+' de '+this.date.getFullYear());
		_temp = this.date.getDate();
		this.date.setDate(1);
		start_day = this.date.getDay();
		this.date.setDate(_temp);

		days_amount = getDaysAmount(this.date);
		weeks_amount = Math.ceil((days_amount+start_day)/7);
		k=0; i=0; j=0; l=0; day=0;
		_tr = null;

		while(j<weeks_amount){
			if(i==0){
				if(l==0 && _tr!=null) _tr.dispose();
				l=0;
				_tr = new Element('tr').inject(this.tbody);
			}
			day = k-start_day+1;
			if(k>=start_day && day<=days_amount){
				l++;
				_td_obj = new Object();
				_div_obj = new Object();
				if(this.data.days[day]!=null){
					_class = this.data.days[day][0];
					tx='';
					switch(_class){
						case 'r': tx='Recesso'; break;
						case 'c': tx='Cheio | ';
						default : tx+=this.data.days[day][1]+' Consultas'; break;
					}
					_td_obj = {'class':_class};
					_div_obj = {'html':tx};
				}

				_td = new Element('td',_td_obj).inject(_tr);
				_h = new Element('span',{'text':(day)}).inject(_td);
				_div = new Element('p',_div_obj).inject(_td);

				_td.addEvent('click',function(){
					if(arguments[0].selected!=null) arguments[0].selected.set('class',arguments[0].selected.get('alt'));
					arguments[0].selected = this;
					this.set('alt',this.get('class'));
					this.set('class','s');
					arguments[0].changeDay(this);
				}.pass(this,_td));
				if(this.date.getDate()==day) _td.fireEvent('click');
			} else {
				new Element('td').set('class','celulaBranco').inject(_tr);
			}
			k++;
			i = (i+1)%7;
			if(i==0) j++;
		}
	},
	attach:function(){
		if(!this.isAttached){
			this.tbody.inject(this.table);
			this.isAttached=true;
		}
	}
});

/* Form Class */
var Form = new Class({
	Implements: Events,
	initialize:function(){
		if(arguments[0]==null) return false;
		if(arguments[1][1]==null) arguments[1][1]='post';
		this.status_bar = new Element('div',{'class':'status'});

		var ul_obj = new Object();
		if(arguments[2]!=null) ul_obj['class']=arguments[2];

		this.form = new Element('form',{'id':arguments[0],'action':'?','enctype':'text/plain'});
		this.ul = new Element('ul',ul_obj);

		this.action_call = (arguments[3]!=null) ? arguments[3] : null;
		this.main_input = (arguments[3]!=null && arguments[3][0]!=null) ? arguments[3][0] : null;
		this.parent_input = (arguments[3]!=null && arguments[3][1]!=null) ? arguments[3][1] : null;

		this.isAttached = false;
		this.first = null;
		this.last = null;
		this.status_bar.isAttached = false;

		this.ul.inject(this.form);

		var form_send_obj = {
			url: arguments[1][0],
			method: arguments[1][1],
			urlEncoded:true,
			onRequest:function(){
				this.highlight('#FF0','#FFF');
				this.set('html','Enviando...');
				arguments[0].request(arguments[0].form.get('send'));
			}.pass(this,this.status_bar),
			onComplete:function(){
				this.highlight('#0F0','#FFF');
				this.set('html','Enviado!');
			}.bind(this.status_bar),
			onSuccess:function(){
				this.success(arguments[0],arguments[1]);
				/* O suporte à versão antiga da chamada de funções foi atualizada para esta implementação. */
			}.bind(this),
			onFailure:function(){
				this.highlight('#F00','#FFF');
				this.set('html','Ocorreu um Erro. Verifique a sua conexão com o servidor.<br>Cód. Erro '+arguments[0].status);
			}.bind(this.status_bar)
		};
		this.form.set('send', form_send_obj);
	},
	success:function(){
		this.fireEvent('success',[arguments[0],arguments[1]]);
	},
	request:function(){
		this.fireEvent('request',arguments[0]);
	},
	attach:function(){
		if(arguments[0]==null) return false;
		this.form.inject(arguments[0]);
	},
	injectBlock:function(){
		if(arguments[0]==null) return false;
		var li = new Element('li',{'html':'<p>'+arguments[0]+'</p>'});
		li.inject(this.ul);
	},
	newCheckList:function(){
		if(arguments[0]==null || arguments[1]==null || $type(arguments[1])!='array') return false;
		var ol = new Element('ol',{'class':'check_list'});
		_sub_ol_length = Math.ceil(arguments[1].length/2);
		var i=0;
		k=0;

		while(k<arguments[1].length){
			if(i==0) var _sub_ol = new Element('ol').inject(ol);
			var _ol_li = new Element('li').inject(_sub_ol);
			var _li_label = new Element('label').inject(_ol_li);
			var _li_check = new Element('input',{'name':arguments[1][k][1],'type':'checkbox','value':(arguments[1][k][2]==null ? 1 : arguments[1][k][2])}).inject(_li_label);
			_li_label.appendText(arguments[1][k][0]);

			i=(i+1)%_sub_ol_length;
			k++;
		}

		return this.newField(arguments[0],'#',null,'checklist',ol);
	}
	,
	newField:function(){
		if(!this.isAttached) this.attach();
		if(arguments[1]==null) return false;
		if(arguments[3]==null) arguments[3]='text';
		if(arguments[4]==null) arguments[4]='';
		var field_type;

		if(arguments[3]!='checklist'){
			var input_obj = {'name':arguments[1],'value':arguments[4]};
			if(arguments[5]!=null && arguments[5]!='') input_obj['maxlength'] = arguments[5];

			switch(arguments[3]){
				case 'textarea': field_type='textarea'; break;
				case 'file': {this.form.set('enctype','multipart/form-data'); this.form.set('encoding','multipart/form-data');} /* No-Break - Inherit default settings */
				/* If occours any more issues, add Rule Here */
				default: {field_type='input'; input_obj['type']=arguments[3]}; break;
			}

			var input = new Element(field_type,input_obj);
		} else {
			var input = arguments[4];
		}

		var li = new Element('li');

		if(arguments[0]!=null){
			var label = new Element('label',{'html':arguments[0]});
			label.inject(li);
		}
		if(arguments[2]!=null){
			if($type(arguments[2])=='array'){
				input.setStyle('width',(arguments[2][0]+'px'));
				input.setStyle('height',(6+14*arguments[2][1]+'px'));
			} else {
				input.setStyle('width',(arguments[2]+'px'));
			}
		}

		input.inject(li);
		li.inject(this.ul);

		if(this.first==null) this.first = input;
		this.last = input;

		//Return Input Object
		return input;
	},
    getSendBarButtons:function(){
        if(!this.isAttached) this.attach();
        return this.form.getElements('.send_bar a');
    },
	attachSendBar:function(){
		if(this.first!=null) this.last.addEvent('blur',function(){ if(this && $chk(this) && this.get('visible')) this.focus(); }.bind(this.first));

		if(!this.isAttached) this.attach();

		if(arguments.length<1) arguments[0]=['Enviar','mb'];
		if(arguments[0][2]==null) arguments[0][2]='bt_left';

		var div = new Element('div',{'class':'send_bar','html':''});

		var send_c = new Element('div',{'class':arguments[0][2]}).inject(div);

		if(arguments.length>=2 && ($type(arguments[1])=='boolean' ? arguments[1] : true )){
			if(arguments[1]==null) arguments[1]=['Limpar Campos','mb'];
			if(arguments[1][2]==null) arguments[1][2]='bt_right';
			var clear_c = new Element('div',{'class':arguments[1][2]}).inject(div);
		}

		var send_button = new Element('a',{'html':arguments[0][0],'class':arguments[0][1]});
		send_button.inject(send_c);
		send_button.addEvent('click',function(a){this.sendData()}.bind(this));
		if(arguments[1]){
			var clear_button = new Element('a',{'html':arguments[1][0],'class':arguments[1][1]});
			clear_button.inject(clear_c);
			clear_button.addEvent('click',function(a){
				this.getElements('input').set('value','');
			}.bind(this.form));
		}

		div.inject(this.form);

		return div;
	},
	attachStausBar:function(){
		this.status_bar.isAttached=true;
		this.status_bar.inject(this.form);
	},
	sendData:function(){
		if(!this.status_bar.isAttached) this.attachStausBar();
		this.form.send();
		var request = this.form.get('send');
	}
});

/* Modal Class */
var Modal = new Class({
	Implements: Events,
	initialize:function(){
		//Class Params
		if(arguments[0][0]==null) arguments[0][0] = RDID('BGID');
		if(arguments[0][1]==null) arguments[0][1] = RDID('WINID');

        if($$(arguments[0][0]).length>0) $(arguments[0][0]).dispose();
        if($$(arguments[0][1]).length>0) $(arguments[0][1]).dispose();

		//Modal Background
		var bg_obj = new Object();
		bg_obj['id'] = arguments[0][0];
		if(arguments[1]!=null)
			bg_obj['class'] = arguments[1][0];
		else
			bg_obj['class'] = 'g_modal';

		//Modal Win
		var win_obj = new Object();
		win_obj['id'] = arguments[0][1];
		if(arguments[1]!=null && arguments[1][0]!=null)
			win_obj['class'] = arguments[1][1];
		else
			win_obj['class'] = 'win';

		//Modal container
		var container_win = new Object();
		container_win['id'] = arguments[0][1];
		if(arguments[1]!=null && arguments[1][0]!=null)
			container_win['class'] = arguments[1][1];
		else
			container_win['class'] = 'winContainer';


		//Main Objects
		this.win = new Element('div',win_obj);
		this.bg = new Element('div',bg_obj);
		this.header = new Element('h1');
		this.closer = new Element('div',{'class':'closer','html':'fechar'}).inject(this.win,'top');

		//Class Vars
		this.bg.setStyle('opacity',0);
		this.win.setStyle('opacity',0);
		this.isAttached = false;
		this.header.isAttached = false;

		//To Get Real Size Later
		this.win.setStyle('top',-500000+'px');
	},
	attach:function(){
		if(arguments[0]==null) arguments[0]=$(document.body);
		this.win.inject(arguments[0],arguments[1]);
		this.bg.inject(arguments[0],arguments[1]);
		this.isAttached = true;
	},
	setWinContent:function(){
		this.win.set('html',arguments[0]);
	},
	setHeader:function(){
		if(arguments[0]==null) return false;
		this.header.set('html',arguments[0]);
		if(!this.header.isAttached) this.header.inject(this.closer,'after');
	},
	show:function(){
		if(!this.isAttached) this.attach();

		//Positioning
		modalWin_sz = this.win.getSize();
		window_sz = $(window).getSize();
		this.win.setStyles({
			'top': (window_sz.y-modalWin_sz.y)/2+'px',
			'left': (window_sz.x-modalWin_sz.x)/2+'px'
		});

		this.bg.fade(.7);

		fadeObj.delay(500,this.win,['in']);

		this.bg.addEvent('click',function(){this.fadeAndRemove()}.bind(this));
		this.closer.addEvent('click',function(){this.bg.fireEvent('click')}.bind(this));
		$(window).addEvent('keypress', function(e){if(e.key=='esc') this.bg.fireEvent('click')}.bind(this));
	},
	hide:function(){
		this.win.fade('out');
		fadeObj.delay(500,this.bg,['out']);
	},
	destroy:function(){
		this.bg.dispose();
		this.win.dispose();
	},
	fadeAndRemove:function(){
		this.fireEvent('exit');
		if(this.bg.getStyle('opacity')>0 && !arguments[0]) this.hide();
		a = function(){
			if(this.bg.getStyle('opacity')<=0)
				this.destroy();
			else
				this.fadeAndRemove(true);
		}.delay(500,this);
	}
});