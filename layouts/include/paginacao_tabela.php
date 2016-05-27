    <p id="paginacao">Página <?php echo($pag_atual+1); ?> de <?php if($qtd_paginas == 0){echo "1";}else{echo $qtd_paginas;}; ?></p>
    <div id="navegacaoPaginas">
        <p>
            <?php echo $paginacao; ?>
        </p>
    </div><!--fecha navegacaoPaginas-->