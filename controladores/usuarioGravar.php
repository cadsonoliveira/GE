<?php
    include_once ("../classes/classUsuario.php");
    include_once ("../classes/classContato.php");
    include_once ("../classes/classEndereco.php");
    include_once ("../classes/classPersistencia.php");

    $login_valido = true;
	$success = "1";
    if(isset($_GET['acao']) && ($_GET['acao']=='excluir'))
    {
        $usuario = new Usuario($_GET['id']);
        $usuario->bDelete();
		

    } else {

        if(isset($_GET['acao']) && ($_GET['acao']=='alterarSenha'))
        {
            $id = $_POST['id'];
            $old_pass = md5($_POST['senha_antiga']);
            
            $pers = new Persistencia();

            $sSql = "SELECT * FROM usuario WHERE usuario.id_pessoa = ".$id." AND senha='".$old_pass."'";
            $pers->bExecute($sSql);

            if($pers->getDbNumRows() > 0)
            {
                $usuario = new Usuario($id);
                $usuario->setSenha(md5($_POST['senha']));
                $usuario->bUpdate();
            } else {
                //Não conseguiu efetuar a troca da senha
                //pois a senha antiga que foi informada não
                //confere com a que está no banco de dados
                //header("Location: ../layouts/usuarios_cadastro.php?acao=editar&id=".$id."&tipo=1");
				$success = "0";

            }

        } else {
            if(isset($_POST))
            {
            #CONTATO
                $tel_res	= addslashes($_POST['tel_res']);
                $tel_cel	= addslashes($_POST['tel_cel']);
                $tel_com	= addslashes($_POST['tel_com']);
                $email		= addslashes($_POST['mail']);

                $contato = new Contato();
                $contato->setTelefoneFixo($tel_res);
                $contato->setTelefoneComercial($tel_com);
                $contato->setTelefoneCelular($tel_cel);
                $contato->setEmail($email);

                #ENDERECO
                $logrdo		= addslashes($_POST['logrdo']);
                $numro		= addslashes($_POST['numro']);
                $compto		= addslashes($_POST['compto']);
                $cidade		= addslashes($_POST['cidade']);
                $bairro		= addslashes($_POST['bairro']);
                $sigla_est	= addslashes($_POST['estado']);

                if(($_POST['cep'] != "") && ($_POST['cep_comp']))
                {
                    $cep = addslashes($_POST['cep']).'-'.addslashes($_POST['cep_comp']);
                } else {
                    $cep = "";
                }

                $endereco = new Endereco();
                $endereco->setLogradouro($logrdo);
                $endereco->setNumero($numro);
                $endereco->setComplemento($compto);
                $endereco->setCidade($cidade);
                $endereco->setBairro($bairro);
                $endereco->setSiglaEstado($sigla_est);
                $endereco->setCep($cep);

                $nome = addslashes($_POST['nome']);
                if($_POST['data_nasc'] != "")
                {
                    $data 		= explode("/", $_POST['data_nasc']);
                    $data_nasc	= $data[2].'-'.$data[1].'-'.$data[0];
                } else {
                    $data_nasc = NULL;
                }


                $sexo = $_POST['sexo'];
                $cpf = ($_POST['cpf'] != "") ? $_POST['cpf'].'-'.$_POST['cpf_comp'] : "";
                $rg	= $_POST['rg'];

                $id = 0;
                if(isset($_GET) && isset($_GET['id']))
                {
                    $id = $_GET['id'];
                }

                $usuario = new Usuario($id);
                $cro = "";
                if(isset($_GET['acao']) && ($_GET['acao']=='editar'))
                {
                    $login      = $usuario->getLogin();
                    $senha      = $usuario->getSenha();
                    $tipo_acesso= $usuario->getTipoAcesso();
                    if($usuario->getTipoAcesso() == "Dentista")
                        $cro = $_POST['cro'];
                } else {
                    #TESTA VALIDADE DE UM LOGIN
                    if($usuario->validaLogin($_POST['login']))
                    {
                        $login      = $_POST['login'];
                        $senha      = md5($_POST['senha']);
                        $tipo_acesso= $_POST['acesso'];
                    } else {
                        $login_valido = false;
                    }
                    $cro = ($_POST['acesso'] == "Dentista") ? $_POST['cro'] : "";
                }
                $especialidades = array();
                foreach($_POST['especialidades'] as $k => $value){
	                $v = (int) $value;
	                if($v > 0)
	                	$especialidades[] = $v;
                }
                if($login_valido)
                {
                    $usuario->setLogin($login);
                    $usuario->setSenha($senha);
                    $usuario->setTipoAcesso($tipo_acesso);
                    $usuario->setNome($nome);
                    $usuario->setDataNasc($data_nasc);
                    $usuario->setSexo($sexo);
                    $usuario->setRg($rg);
                    $usuario->setCpf($cpf);
                    $usuario->setCRO($cro);
                    $usuario->setContato($contato);
                    $usuario->setEndereco($endereco);
                    $usuario->setEspecialidades($especialidades);
                    
                    $usuario->bUpdate();
                }
            }
        }
    }

    if($login_valido)
    {
       if(!(isset($_POST['noredir']) &&($_POST['noredir']=='1'))) {
          header("Location: ../layouts/usuarios.php");
       } else {
          echo '{"success":"'.$success.'"}';
       }

    } else {
        echo '
            <script>
                alert("Este login já existe");
                window.history.back();
            </script>
        ';
        //header("Location: ../layouts/usuarios.php");
    }

?>
