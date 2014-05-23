function limparPaginaAtual(){
     $("#Manu #pagina_atual").val(1);
     $("#FormFiltro #pagina_atual").val(1);

     filtrar();
}

function paginacao(pagina_atual, quantidade_registros){
     //Atualizando Campo Manu
     $("#Manu #pagina_atual").val(pagina_atual);
     $("#Manu #quantidade_registros").val(quantidade_registros);
          
     //Atualizando Campo Hidden
     $("#FormFiltro #pagina_atual").val(pagina_atual);
     $("#FormFiltro #quantidade_registros").val(quantidade_registros);
     
     filtrar();
}