<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');		$Con = new Conexao();
include_once($_SESSION['DirBaseSite'].'relatorio/relatorio.class.php');	$Rel = new Relatorio();

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
			relatorio();
	});
	
	function relatorio(){
	
		var form = $("#FormFiltro");

		//Adicionando a action no form
		form.attr("action", URLBASESITE+"relatorio/relatorio.ajax.php?OpAjax=Rela");
	
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
		$.post( "relatorio.ajax.php?OpAjax=Tit", { id_processamento: id_processamento} )
		.done(function( titulo ) {
		
			$.ajax({
			  url: "relatorio.ajax.php?OpAjax=Info",
			  type: "POST",
			  data: {id_processamento: id_processamento},
			  datatype: "html"
			}).done(function( html ){
						
						$( "#Modal" ).html( html );
						
						$( "#Modal" ).dialog({
							modal: true,
							title: titulo,
							height: 600,
							width: 900,
							buttons: [ { text: "Ok", click: function() { $( this ).dialog( "close" ); } } ]
						});
		
					}
				   );
		
		});
	}
	
	function mascaras()
	{
		$("#Manu #dt_recebimento_arquivo").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Recebimento Arquivo não é uma data valida.'); }});
		
		$("#Manu #dt_previsao_processamento").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Previsão Processamento não é uma data valida.'); }});
		$("#Manu #dt_processamento").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Processamento não é uma data valida.'); }});
		
		$("#Manu #dt_previsao_disponibilizacao").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Previsão Disponibilização não é uma data valida.'); }});
		$("#Manu #dt_disponibilizacao").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Disponibilização não é uma data valida.'); }});
		
		$("#Manu #dt_liberacao_faturamento").mask("99/99/9999",{ completed:function(){ validaData(this, 'ATENÇÃO: O campo Data Liberação Faturamento não é uma data valida.'); }});
		
		$("#Manu #tempo_estimado").mask("99:99");
		$("#Manu #tempo_gasto").mask("99:99");
	}
	
	function validaData(obj, msg)
	{
		var regex = /^((((0?[1-9]|1\d|2[0-8])\/(0?[1-9]|1[0-2]))|((29|30)\/(0?[13456789]|1[0-2]))|(31\/(0?[13578]|1[02])))\/((19|20)?\d\d))$|((29\/0?2\/)((19|20)?(0[48]|[2468][048]|[13579][26])|(20)?00))$/;
 
		resultado = regex.exec(obj.val());
		if(!resultado){
			alert(msg);
			obj.val('');
		}
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
					<td><?echo $Rel->getCampoUnidadeFederativa();?></td>
					<td>Competência:</td>
					<td><?echo $Rel->getCampoCompetencia();?></td>	
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>Convênio:</td>
					<td><?echo $Rel->getCampoConvenio();?></td>					
					<td>Status:</td>
					<td><?echo $Rel->getCampoStatus();?></td>					
					<td>
						<input type="button" name="Button" value="Filtrar" class="botao texto" onclick="filtrar();" />
						<input type="reset" name="Button" value="Limpar" class="botao texto" />
					</td>
				  </tr>
				  <tr>
					<td>Liberado Faturamento:</td>
					<td><?echo $Rel->getCampoLiberadoFaturamento();?></td>
					<td>Tipo Processamento:</td>                
					<td><?echo $Rel->getCampoTipoProcessamento();?></td>					
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>Situação:</td>                
					<td><?echo $Rel->getCampoSituacao();?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
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