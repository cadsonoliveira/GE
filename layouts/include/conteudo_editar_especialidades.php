            <p><button class="botaoPositivo floatRight" type="button" onclick="novo_procedimento('<?php echo $id ?>');">Adicionar Procedimentos</button></p>
            <form action="<?php echo $action_form; ?>" method="post" style="clear:right;" name="grid">
                <fieldset>
                	<div class="elementosFormulario2" style="margin:20px 0;">
                    <label for="especialidade">Especialidade</label>
                    <input class="itensObrigatorios" id="especialidade" name="descricao" type="text" style="width:500px;" value="<?php echo $descricao_Especialidade; ?>"/>
                    </div>
                    <p id="filtros">Exibição</p>
                    <div class="boxLista">
                        <label id="resultadosPagina" for="resultados_por_pagina">Resultados por Página:</label>
                        <select id="qnt_pag" class="listarResultados" name="resultados_por_pagina" onchange="qtdpag();">
                            <option <?php if($qtd_resultado_por_pagina==10) echo "selected='selected'"; ?> value="10">10</option>
                            <option <?php if($qtd_resultado_por_pagina==20) echo "selected='selected'"; ?> value="20">20</option>
                            <option <?php if($qtd_resultado_por_pagina==30) echo "selected='selected'"; ?> value="30">30</option>
                        </select>
                    </div><!--fecha busca-->
                    
                     <p id="totalRegistros">Total de registros: <?php echo $qtd_registros ?></p>
                    
                    <table title="Lista de Procedimentos" summary="Lista completa de Procedimentos" class="habilitaHoverTabela">
                      <caption>Lista de procedimentos</caption>
                      <thead>
                          <tr>
                            <th class="matchCode">Match Code</th>
                            <th>Procedimento</th>
                            <th class="tipo">Tipo</th>
                            <th class="opcoesEspecialidades">Opções</th>
                          </tr>
                      </thead>
                      <tbody>
                      	<?php echo $tabela; ?>
                      </tbody>
                    </table>
                    
                    <?php include_once("paginacao_tabela.php") ?>
                    
                </fieldset>
                <p id="botoesFormulario">
                  <button id="botaoNegativo" type="button" onclick="location.href='especialidades.php'">Cancelar</button>
                  <button class="botaoPositivo" type="submit" onclick="valida_campos();">Atualizar Especialidade</button>
                </p>            
            </form>  
