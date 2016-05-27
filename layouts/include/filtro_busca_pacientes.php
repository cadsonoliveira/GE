        <p id="filtros">Filtros</p>
        <div class="boxLista">
            <p id="listaBusca">Listar
                <?php echo $indice_letras; ?>
            </p>
            <form class="listarResultados" id="grid" action="#" method="post">
                <p>
                    <label id="resultadosPagina" for="resultados_por_pagina">Resultados por Página:</label>
                    <select id="resultados_por_pagina" name="resultados_por_pagina" onchange="qtdpag('<?php echo $letra; ?>');">
                        <option <?php if($qtd_resultado_por_pagina==10) echo 'selected="selected"'; ?> value="10">10</option>
                        <option <?php if($qtd_resultado_por_pagina==20) echo 'selected="selected"'; ?> value="20">20</option>
                        <option <?php if($qtd_resultado_por_pagina==30) echo 'selected="selected"'; ?> value="30">30</option>
                    </select>
                </p>
            </form>
        </div><!--fecha boxLista-->