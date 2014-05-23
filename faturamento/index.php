<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');		$Con = new Conexao();
include_once($_SESSION['DirBaseSite'].'faturamento/faturamento.class.php');	$Fat = new Faturamento();

if($_SESSION['LoginTipo'] != "F") 
{
	//header("Location: ".$_SESSION['UrlBaseSite']);
	
}

include_once($_SESSION['DirBaseSite']."includes/cabecalho.php");
?>
<!-- Funções de JavaScript -->
<script type="text/javascript">
	$( document )
		.tooltip({
			track: true
		})
		.ready(function(){		
			faturamento();
	});
	
	function limpar(){
	
		$("#FormFiltro #id_unidade_federativa").val("");
		$("#FormFiltro #id_convenio").val("");
		$("#FormFiltro #id_competencia").val("");
		$("#FormFiltro #id_status").val("");
		$("#FormFiltro #liberado_faturamento").val("");
		
		campoConvenio();
		
		faturamento();
	}
	
	function faturamento(){
	
		var form = $("#FormFiltro");

		//Adicionando a action no form
		form.attr("action", URLBASESITE+"faturamento/faturamento.ajax.php?OpAjax=Fatu");
	
		$.ajax({
          url: $(form).attr("action"),
          data: $(form).serialize(),
          type: "POST",
          dataType: "html",
          beforeSend: function( data ) {
				//Validacao
				return true;
			},
          success: function( html ) {
				$("#Manu").html(html);
			}
        });
		
		//Retirando a action no form
		form.attr("action", "");
	}
	
	function modalRelatorio(id_processamento)
	{
		$.post( "faturamento.ajax.php?OpAjax=Tit", { id_processamento: id_processamento} )
		.done(function( titulo ) {
		
			$.ajax({
			  url: "faturamento.ajax.php?OpAjax=Info",
			  type: "POST",
			  data: {id_processamento: id_processamento},
			  datatype: "html"
			}).done(function( html ){
						
						$( "#Modal" ).html( html );
						
						$( "#Modal" ).dialog({
							modal: true,
							title: titulo,
							height: 500,
							width: 800,
							buttons: [ { text: "Ok", click: function() { $( this ).dialog( "close" ); } } ]
						});
		
					}
				   );
		
		});
	}
	
	function campoConvenio()
	{		
		var id_unidade_federativa = $("#FormFiltro #id_unidade_federativa").val();
		$( "#FormFiltro #container_convenio" ).load( URLBASESITE+"faturamento/faturamento.ajax.php?OpAjax=CampoConvenio", {"id_unidade_federativa":id_unidade_federativa});
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
        <td align="left" valign="top" class="bg_degrade">
		<table width="98%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
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
					<td>U.F:</td>
					<td><?echo $Fat->getCampoUnidadeFederativa();?></td>
					<td>Convênio:</td>
					<td><div name="container_convenio" id="container_convenio"><?echo $Fat->getCampoConvenio();?></div></td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>Competência:</td>
					<td><?echo $Fat->getCampoCompetencia();?></td>					
					<td>Liberado Faturamento:</td>
					<td><?echo $Fat->getCampoLiberadoFaturamento();?></td>
					<td>
						<input type="hidden" name="id_tipo_processamento" id="id_tipo_processamento" value="2" />
						<input type="button" name="Button" value="Filtrar" class="botao texto" onclick="faturamento();" />
						<input type="button" name="Button" value="Limpar" class="botao texto" onclick="limpar();" />
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
					<div name="Modal" id="Modal" style="display:none;"><!-- conteudo do ajax --></div>
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