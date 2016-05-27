function valida_campos(form){
	if(form.user.value == "" ){
		alert('O campo "Usuário" está vazio!');
		form.user.focus();
		return false;
		
	} else {
		if(form.pass.value == ""){
			alert('O campo "Senha" está vazio!');
			form.pass.focus();
			return false;
		} else {
			$('cd_field').submit();
		}
	}
}
