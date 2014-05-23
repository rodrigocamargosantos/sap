<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'status/status.class.php'); 	$St = new Status();


if($_SESSION['LoginTipo'] != "F") 
{
	//header("Location: ".$_SESSION['UrlBaseSite']);
}

include_once($_SESSION['DirBaseSite']."includes/cabecalho.php");
?>
<!-- Funções de JavaScript -->
<script type="text/javascript">
     $(document).ready(function(){		
          filtrar();
     });
	
     function filtrar(){
	
          var form = $("#FormFiltro");
          
          var pagina_atual         = $("#Manu #pagina_atual").val();
          var quantidade_registros = $("#Manu #quantidade_registros").val();
          
          //Atualizando Campo Hidden
          $("#FormFiltro #pagina_atual").val(pagina_atual);
          $("#FormFiltro #quantidade_registros").val(quantidade_registros);
          
          //Adicionando a action no form
          form.attr("action", URLBASESITE+"status/status.ajax.php?OpAjax=Fil");
	
          $.ajax({
          url: $(form).attr("action"),
          data: $(form).serialize(),
          type: "POST",
          dataType: "html",
          beforeSend: function( data ){
                    //Validacao
                    return true;
               },
          success: function( html ){
                    $("#Manu").html(html);
               }
          });
		
          //Retirando a action no form
          form.attr("action", "");
     }
	
     function limpar(){

             $("#FormFiltro #nome_status").val("");
             $("#FormFiltro #st").val(1);

             limparPaginaAtual();
     }

	function cadastrar(){	
		$.ajax({
		  url: "status.ajax.php?OpAjax=Cad",
		  type: "POST",
		  datatype: "html"
		}).done(function( html )
				{
					$("#Manu").html(html);
				}
			   );
	}
	
	function alterar(id_status){		
		$.ajax({
		  url: "status.ajax.php?OpAjax=Alt",
		  type: "POST",
		  data: {id_status: id_status},
		  datatype: "html"
		}).done(function( html )
				{
					$("#Manu").html(html);
				}
			   );
	}
	
	function deletar(id_status){	
		
		if (confirm("Deseja mesmo inativar este Status?")){
			$.post("status.ajax.php?OpAjax=Del", { id_status: id_status })
			.done( function() {
				informacao('Inativado com sucesso!');
				filtrar();
			});
		}
	}
	
	function cadastrarAlterar(){
		
		//Variaveis do Form		
		var opAjax = $("#Manu #FormManu #OpAjax").val();		
		var form   = $("#Manu #FormManu");
		
		//Adicionando a action no form
		form.attr("action", URLBASESITE+"status/status.ajax.php?Env=true&OpAjax="+opAjax);
		
		$.ajax({
          url: $(form).attr("action"),
          data: $(form).serialize(),
          type: "POST",
          dataType: "html",
          beforeSend: function( data ) {
				//Validacao
				return validacao();
			},
          success: function( request ) {
			
				if(request == "true")
				{
					if(opAjax == "Cad")
					{					
						informacao('Cadastrado com sucesso!');
						filtrar();
					}
					else if(opAjax == "Alt")
					{
						informacao('Alterado com sucesso!');
						filtrar();
					}
				}else{					
					alerta(request);
				}
			}
        });
		
		//Retirando a action no form
		form.attr("action", "");
	}
	
	function validacao(){	
		if ($("#Manu #FormManu #id_icone").val() == ""){
			alerta("O campo Ícone não pode ser vazio.");
			$("#Manu #FormManu #id_icone").focus();
			return false;
		}
		if ($("#Manu #FormManu #nome_status").val() == ""){
			alerta("O campo Status não pode ser vazio.");
			$("#Manu #FormManu #nome_status").focus();
			return false;
		}
		if ($("#Manu #FormManu #st").val() == ""){
			alerta("O campo Situação não pode ser vazio.");
			$("#Manu #FormManu #st").focus();
			return false;
		}
		return true;
	}
	
	function alerta(texto)
	{
		$("#error-text").html(texto);	
		$('#error').show();						
		$(document).ready(function () {
			setTimeout( "$('#error').hide('slow');",3000 );
		});
	}
	
	function informacao(texto)
	{
		$("#alert-text").html(texto);
		$('#alert').show();
		$(document).ready(function () {
			setTimeout( "$('#alert').hide('slow');",3000 );
		});
	}
</script>
<!-- Fim Funções de JavaScript -->
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="bg">
  <tr>
    <td align="center" valign="top">
    
    <table width="70%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bg_intera" bgcolor="#FFFFFF">
      <tr bgcolor="#ffffff">
        <td align="left" valign="top" class="bg_degrade"><table width="98%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
            <!-- Include Topo-->
            <? include_once($_SESSION['DirBaseSite']."includes/topo.php"); ?>
            </td>
          </tr>
          <tr>           
            <td> 
            
               <form id="FormFiltro" name="FormFiltro" method="post" action="">
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
                         <tr>
                              <td>Status:</td>
                              <td><input name="nome_status" id="nome_status" type="text" class="campo texto" maxlength="100" value="" /></td>
                              <td>Situação:</td>                
                              <td><?echo $St->getCampoSituacao();?></td>
                              <td>
                                   <input type="button" name="Button" value="Filtrar" class="botao texto" onclick="limparPaginaAtual();" />
                                   <input type="button" name="Button" value="Limpar" class="botao texto" onclick="limpar();" />
                                   <input type="hidden" name="pagina_atual" id="pagina_atual" value="" />
                                   <input type="hidden" name="quantidade_registros" id="quantidade_registros" value="" />
                              </td>
                         </tr>
                    </table>
               </form>
            
             </td>
           </tr>
           <tr  height="100%"> 
            <td height="100%" align="left" valign="top">
			
            <!-- Tabela Interna -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
              <tr>
                <td align="left">
                    <img title="Cadastrar" src="<?=$_SESSION['UrlBaseSite']?>imagens/bt_cad.gif" style="padding-bottom:1px; cursor: pointer;" onclick="cadastrar();">
                </td>
              </tr>
              <tr>
                <td>					
					<div name="alert" id="alert" class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; display: none;">
						<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
						<strong>Informação:</strong> <span name="alert-text" id="alert-text"><!-- conteudo do ajax --></span></p>
					</div>					
					<div name="error" id="error" class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 0 .7em; display: none;">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
						<strong>Alerta:</strong> <span name="error-text" id="error-text"><!-- conteudo do ajax --></span></p>
					</div>
					<div name="Manu" id="Manu"><!-- conteudo do ajax --></div>
                </td>
              </tr>
            </table>
            <!-- Fim Tabela Interna --> 
           
            </td>
            </tr>
          <tr>
            <td>
            	 <!-- Include Rodape -->
        		<? include_once($_SESSION['DirBaseSite']."includes/rodape.php"); ?>
            </td>
            </tr>
        </table></td>
      </tr>
    </table>
    
    </td>
  </tr>
</table>
</body>
</html>