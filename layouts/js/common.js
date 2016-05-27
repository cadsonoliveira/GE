function getCaret(el) { 
  if (el.selectionStart) { 
    return el.selectionStart; 
  } else if (document.selection) { 
    el.focus(); 

    var r = document.selection.createRange(); 
    if (r == null) { 
      return 0; 
    } 

    var re = el.createTextRange(), 
        rc = re.duplicate(); 
    re.moveToBookmark(r.getBookmark()); 
    rc.setEndPoint('EndToStart', re); 

    return rc.text.length; 
  }  
  return 0; 
}

function getSelectionLenght(el){

	if (el.selectionStart) {
		return el.selectionEnd - el.selectionStart;
	}

	return 0; //função não suportada no IE8.
}

function setCaret(campo,pos){
	if(campo.setSelectionRange){
    			campo.setSelectionRange(pos,pos);
    }else if(campo.createTextRange){
	    		var range = campo.createTextRange();
	    		range.collapse(true);
	    		range.moveEnd('character', pos);
	    		range.moveStart('character', pos);
	    		range.select();
    }
}
/*
 * ### SCRIPT PARA MASCARAR CAMPOS ###
 * Como Utilizar:
 * <input name="cpf" type="text" maxlength="14" onkeypress="Mascara('CPF',this,event);">
 */
 
function Mascara(tipo, campo, teclaPress,recursive){
	var tecla;
    if (window.event){
        tecla = teclaPress.keyCode;
    } else {
        tecla = teclaPress.which;
    }
    if(tecla == undefined){
	    tecla = teclaPress.code;
    }
    
    var ret = false;
    if ( tecla != 9 && tecla != 8 && tecla != 0 ) {
    	var caret = getCaret(campo);
    	if(caret != campo.value.length){
    	    		ret = caret + 1;
    		if(getSelectionLenght(campo) == 0){
    		campo.value = campo.value.substring(0,caret) + String.fromCharCode(tecla) + campo.value.substring(caret);
    		}else{
	    		campo.value = campo.value.substring(0,caret) + String.fromCharCode(tecla) + campo.value.substring(caret+getSelectionLenght(campo));
    		}
    		
    	}
    	var s = new String(campo.value);
    	// Remove todos os caracteres � seguir: ( ) / - . e espa�o, para tratar a string de novo.
    	s = s.replace(/(\.|\(|\)|\/|\-| )+/g,'');
 
    tam = s.length + 1;
        switch (tipo){
            case 'STRING':
                campo.value = campo.value.toUpperCase();
                break;
				
            case 'CPF' :
				//desabilita letras
				if ((tecla<48)||(tecla>57)){
					return false;
				}else{
					if (tam > 3 && tam < 7)
						campo.value = s.substr(0,3) + '.' + s.substr(3, tam);
					if (tam >= 7)
						campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,3);
				}
                break;
				
            case 'NUMERAL' :
				//desabilita letras
				if ((tecla<48)||(tecla>57)){
					return false;
				}
                break;
				
            case 'RG' :
				if (tam > 0 && tam < 3){
					//desabilita numeral
					if (tecla>47 && tecla<58){
						return false;
					}
				}else{
					//desabilita letras
					if ((tecla<48)||(tecla>57)){
						return false;
					}
					if (tam > 2 && tam < 4)
							campo.value = s.substr(0,2).toUpperCase() +'-'+ s.substr(2);
					if (tam > 4 && tam < 8)
						campo.value = s.substr(0,2).toUpperCase() +'-'+ s.substr(2,2) + '.' + s.substr(4);
					if (tam >= 8)
						campo.value = s.substr(0,2).toUpperCase() +'-'+ s.substr(2,2) + '.' + s.substr(4,3) + '.' + s.substr(7,3);
					break;
				}
 
            case 'CNPJ' :
                if (tam > 2 && tam < 6)
                    campo.value = s.substr(0,2) + '.' + s.substr(2, tam);
                if (tam >= 6 && tam < 9)
                    campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,tam-5);
                if (tam >= 9 && tam < 13)
                    campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,tam-8);
                if (tam >= 13 && tam < 15)
                    campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,4)+ '-' + s.substr(12,2);
                break;
 
            case 'TEL' :
				if ((tecla<48)||(tecla>57)){
					return false;
				}else{
					if (tam > 2 && tam < 5)
						campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
				
					if (tam >= 5 && tam < 7)
						campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
			
					if (tam >= 7)
						campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,4) + '-' + s.substr(6,4);
					break;
				}
				
            case 'CEL' :
				//desabilita letras
				if ((tecla<48)||(tecla>57)){
					return false;
				}else{
					if(s.substr(0,2) == 11){
						if (tam > 2 && tam < 4){
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
						}
						
						if (tam > 3 && tam < 5)
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam) + '-';
			
						if (tam > 7 )
							
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,1)  + '-' + s.substr(3,4) + '-'  + s.substr(7,4);
							break;
					}else{
						//alert("else")
						if (tam > 2 && tam < 5)
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
					
						if (tam >= 5 && tam < 7)
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
				
						if (tam >= 7)
							campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,4) + '-' + s.substr(6,4);
						}
						break;
				}				
				
				
				
		
            case 'CEP' :
                if (tam >= 6)
                    campo.value = s.substr(0,5) + '-' + s.substr(5,tam);
                break;
 
            case 'DATA' :
				if ((tecla<48)||(tecla>57)){
					return false;
				}
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0,2) + '/' + s.substr(2, tam);
                if (tam > 4)
                    campo.value = s.substr(0,2) + '/' + s.substr(2,2) + '/' + s.substr(4,tam-4);
                break;
        }
    }
    if(ret !== false){
	    setCaret(campo,ret);
	    return false;
    }
}
/*
 
/*
 * ### SCRIPT PARA VALIDA��O DE E-MAILS ###
 * Como Utilizar:
 * valida_data(document.form1.email);
 * 'email' � o campo referente ao e-mail no formul�rio
 */	
function valida_email(valor){
    valor.value = valor.value.trim();
    
    if (valor.value != ""){
        if (valor.value.indexOf('.', 0) == -1){
            alert("Email incorreto!");
            valor.focus();
            return (false);
        }
        if (valor.value.indexOf(' ', 0) != -1){
            alert("Email incorreto! Verifique se existem espaços");
            valor.focus();
            return (false);
        }
        if (valor.value.indexOf('@', 0) == -1){
            alert("Email incorreto!");
            valor.focus();
            return (false);
        }
        if(valor.value[0]=='@'){
            alert("Email incorreto!");
            valor.focus();
            return (false);
        }
		
    } return true;

}
/*
 * ### SCRIPT PARA VALIDA��O DE DATAS ###
 * Como Utilizar:
 * valida_data(document.form1.data);
 * 'data' � o campo referente a data no formul�rio
 */ 
//Retirar os comentarios dentro da fun�ao caso nao seja possivel o cadastramento sem data de nascimento
function valida_data(campo, datas_futuras, eRequerido) {

    campo.value = campo.value.trim();

    var date = campo.value;
    if(date != ""){
        var array_data = new Array;
        var ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
        //vetor que contem o dia o mes e o ano
        array_data = date.split("/");

        if((datas_futuras == null) || (datas_futuras == false)){
            data_atual = new Date();
            ano_atual = data_atual.getFullYear();
            mes_atual = data_atual.getMonth() + 1;
            dia_atual = data_atual.getDate();
            flag = true;
            if(array_data[2] > ano_atual)
                flag = false;
            if(array_data[2] == ano_atual){
                if(array_data[1] > mes_atual)
                    flag = false;
                if(array_data[1] == mes_atual)
                    if(array_data[0] > dia_atual)
                        flag = false;
            }
            if(flag == false){
                alert('A data informada não está correta');
                campo.focus();
                return false;
            }
        }


        //Valido se a data esta no formato dd/mm/yyyy e se o dia tem 2 digitos e esta entre 01 e 31
        //se o mes tem d2 digitos e esta entre 01 e 12 e o ano se tem 4 digitos e esta entre 1000 e 2999
        if ( date.search(ExpReg) == -1 ){
            alert("Data Incorreta");
            campo.focus();
            return false;
        }
        //Valido os meses que nao tem 31 dias com execao de fevereiro
        else if ( ( ( array_data[1] == 4 ) || ( array_data[1] == 6 ) || ( array_data[1] == 9 ) || ( array_data[1] == 11 ) ) && ( array_data[0] > 30 ) ){
            alert("Data Incorreta");
            campo.focus();
            return false;
        }
        //Valido o mes de fevereiro
        else if ( array_data[1] == 2 ) {
            //Valido ano que nao e bissexto
            if ( ( array_data[0] > 28 ) && ( ( array_data[2] % 4 ) != 0 ) ) {
                alert("Data Incorreta");
                campo.focus();
                return false;
            }
            //Valido ano bissexto
            if ( ( array_data[0] > 29 ) && ( ( array_data[2] % 4 ) == 0 ) ) {
                alert("Data Incorreta");
                campo.focus();
                return false;
            }
        }
    } else {
		//if(eRequerido) {
		//	alert('O campo data está vazio, favor preenchê-lo!');
		//	campo.focus();
		//	return false;
		//}
	}
	return true;        	
}

/*
 * ### SCRIPT PARA VALIDA��O DE CPF's ###
 * Como Utilizar:
 * valida_cpf(document.form1.cpf);
 * 'cpf' � o campo referente ao CPF no formul�rio
 */
function valida_cpf(oCpf, oCpfComp){
    oCpf.value = oCpf.value.trim();
    cpf = oCpf.value.substring(0,3) + oCpf.value.substring(4,7) + oCpf.value.substring(8,11) + oCpfComp.value;

    var a = []; var b = new Number;var c = 11;

    if (cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999"){
        alert("O cpf informado não está correto");
        return false;
    }

    for (i=0; i<11; i++){
        a[i] = cpf.charAt(i);if (i < 9) b += (a[i] *  --c);
    }

    if ((x = b % 11) < 2) {
        a[9] = 0
    }else{
        a[9] = 11-x
    }b = 0;c = 11;

    for (y=0; y<10; y++) b += (a[y] *  c--); if ((x = b % 11) < 2) {
        a[10] = 0;
    }else{
        a[10] = 11-x;
    }

    if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]))
    {
        alert ("CPF Incorreto !!!")
        return false;
    }
    return true;
}

/*
 * ### SCRIPT PARA VERIFICACAO DE NOMES ###
 * Como Utilizar:
 * valida_nome(document.form1.nome);
 * 'email' é o campo referente ao e-mail no questionário
 */	
 
function valida_nome(valor, msg){
    valor.value = valor.value.trim();

    if (valor.value != ""){
        return true;
    }else{
        if(msg == null){
            msg = "Informe o nome";
        }
        alert(msg);
        valor.focus();
        return false;
    }
}

// * ### SCRIPT PARA VALIDA��O DE RG ###
// * Como Utilizar:
// * valida_rg(document.form1.rg);
// 

function valida_rg(valor)
{
    valor.value = valor.value.trim();
    if (valor.value != ""){
        return true;
    }else{
        return false;
    }
}


// * ### SCRIPT PARA VALIDA��O DE TELEFONES ###
// * Como Utilizar:
// * valida_tel(document.form1.tel);
// 

function valida_tel(valor){
    valor.value = valor.value.trim();
    if (valor.value != ""){
        var s = new String(valor.value);
        // Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string de novo.
        s = s.replace(/(\.|\(|\)|\/|\-| )+/g,'');
		
        if ((isNaN(s))||(s.length<10)){
            alert("Favor conferir o telefone indicado");
            valor.focus();
            return false;
        }
	  
    }return true;
}

// * ### SCRIPT PARA VALIDAÇÃO DO CAMPOS NUMÉRICOS ###
// * Como Utilizar:
// * valida_numero(document.form1.numro);
// 

function valida_numero(valor, msg){ 
    if (isNaN(valor.value)){
        if(msg == null)
            msg = "Por favor preencha o campo corretamente";
        alert(msg);
        valor.focus();
        return false;
    }else{
        return true;
    }
}


function valida_cep(valor){ 
    if (isNaN(valor.value)){
        alert("Por favor conferir o campo CEP");
        valor.focus();
        return false;
    }
  
    else{
        return true;
    }

}

 
/******************************************************************************
 *** CONEXAO AJAX
 *** INICIO
 ******************************************************************************/
function createXMLHttpRequest() {
    try{
        return new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){}
    try{
        return new ActiveXObject("Microsoft.XMLHTTP");
    }catch(e){}
    try{
        return new XMLHttpRequest();
    }catch(e){}
    alert("XMLHttpRequest not supported");
    return null;
}
	
function do_readyStateChange(){
    if (xhReq.readyState != 4)  {
        return;
    }
    sAux = xhReq.responseText;
    aAux = sAux.split("|;|");
    //alert(aAux[0]);
    if(aAux.length>=2) document.getElementById(aAux[0]).innerHTML = aAux[1];

    
}
	
function xhSendPost2(url,form){
    var form_string = getObj(form);
    xhReq.open("POST", url, true);
    xhReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    xhReq.onreadystatechange = do_readyStateChange;
    xhReq.send(form_string);
}

function xhSendPost(url){
    xhReq.open("POST", url, true);
    xhReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    xhReq.onreadystatechange = do_readyStateChange;
    xhReq.send(null);
}

function getObj(obj) {
    var getstr = "";
    for (i=0;i<obj.elements.length;i++){
        if (obj.elements[i].tagName == "INPUT") {
            if (obj.elements[i].type == "hidden") {
                getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
            }
            if (obj.elements[i].type == "text") {
                getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
            }
            if (obj.elements[i].type == "password") {
                getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
            }
            if (obj.elements[i].type == "checkbox") {
                if (obj.elements[i].checked) {
                    getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
                } else {
                    getstr += obj.elements[i].name + "=&";
                }
            }
	
            if (obj.elements[i].type == "radio") {
                if (obj.elements[i].checked) {
                    getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
                }
            }
        }
		 
        if (obj.elements[i].tagName == "SELECT") {
            var sel = obj.elements[i];
            if(sel.selectedIndex != -1)
                getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
        }
        if (obj.elements[i].type == "textarea") {
            getstr += obj.elements[i].name + "=" + obj.elements[i].innerHTML + "&";
        }
    }
    return getstr;
}
	
var xhReq = createXMLHttpRequest();
/******************************************************************************
 *** FIM
 *** CONEXAO AJAX
 ******************************************************************************/


function comparaDataMenor(data1,data2){
    if ( parseInt( data2.split( "/" )[2].toString() + data2.split( "/" )[1].toString() + data2.split( "/" )[0].toString() ) > parseInt( data1.split( "/" )[2].toString() + data1.split( "/" )[1].toString() + data1.split( "/" )[0].toString() ) ){
        return true;
    }else{
        return false;
    }
}

function tabProxCampo(campo, valor,next){
    if (valor.length==campo.maxLength){
        document.forms[0].elements[next].focus();
    
    }
}
/*
 * Funcao de data funcionando
 */
function calcular_idade(data, div_idade) {
	
    if (valida_data(document.cd_field.data_nasc)){
        data_atual = new Date();
        ano_atual = data_atual.getFullYear();
        mes_atual = data_atual.getMonth() + 1;
        dia_atual = data_atual.getDate();
		
        var array_data = data.split("/");

        dia_nasc = array_data[0];
        mes_nasc = array_data[1];
        ano_nasc = array_data[2];
		
        idade = 0;
		
        if(ano_nasc < ano_atual)
            idade = ano_atual - ano_nasc;
		
        if((mes_atual == mes_nasc) && (dia_atual < dia_nasc)){
            idade -= 1;
        } else {
            if(mes_atual < mes_nasc){
                idade -= 1;
            }
        }

        if(!isNaN(idade)){
            document.getElementById(div_idade).innerHTML = "Idade: " + idade + " anos.";
        } else {
            document.getElementById(div_idade).innerHTML = "";
        }
    }
}	
