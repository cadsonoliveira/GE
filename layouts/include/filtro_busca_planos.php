<div id="paciente" class="clearfix">
    <form action="planos_de_saude.php" method="get" id="localizar" name="bs_field">
    	<!--Para voltar a cadastrar o código do plano descomentar-->
        <!--<select name="filtro">
                <option value="nome">Nome</option>
                <option value="codigo" <?php //echo (isset($_GET['filtro']) && ($_GET['filtro'] == "codigo")) ? "selected='selected'" : ""; ?>>Código</option>
        </select>-->
        <input id="buscaPlanos" type="text" name="chave" onfocus="javascript:this.value==this.defaultValue ? this.value = '' : ''" onblur="javascript:this.value == '' ? this.value = this.defaultValue : ''"  value="<?php echo (($chave != "") ? $chave : 'Localizar planos') ?>"/>
    </form>
</div> <!--Fecha paciente-->