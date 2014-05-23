<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'processamento/processamento.class.php'); 	$Pro = new Processamento();

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
                validaTipoProcessamentoFiltro();
		$("#id_unidade_federativa").removeAttr('onchange');
		$("#liberado_faturamento").removeAttr('onchange');
	});
        
     function filtrar(){
	
          var form = $("#FormFiltro");
          
          var pagina_atual         = $("#Manu #pagina_atual").val();
          var quantidade_registros = $("#Manu #quantidade_registros").val();
          
          //Atualizando Campo Hidden
          $("#FormFiltro #pagina_atual").val(pagina_atual);
          $("#FormFiltro #quantidade_registros").val(quantidade_registros);
          
          //Adicionando a action no form
          form.attr("action", URLBASESITE+"processamento/processamento.ajax.php?OpAjax=Fil");
	
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

          $("#FormFiltro #id_unidade_federativa_filtro").val("");
          $("#FormFiltro #id_competencia").val("");
          $("#FormFiltro #id_convenio").val("");
          $("#FormFiltro #id_status").val("");
          $("#FormFiltro #liberado_faturamento").val("");
          $("#FormFiltro #id_tipo_processamento_filtro").val("");
          $("#FormFiltro #st").val(1);

          limparPaginaAtual();
     }

	function cadastrar(){	
		$.ajax({
		  url: "processamento.ajax.php?OpAjax=Cad",
		  type: "POST",
		  datatype: "html"
		}).done(function( html )
				{
					$("#Manu").html(html);
					
					mascaras();
					validaOpAjax();
					validaQtdRecorrencia();
					validaTipoProcessamento();
					validaDataLiberacaoFaturamento();					
				}
			   );
	}
	
	function alterar(id_processamento){		
		$.ajax({
		  url: "processamento.ajax.php?OpAjax=Alt",
		  type: "POST",
		  data: {id_processamento: id_processamento},
		  datatype: "html"
		}).done(function( html )
				{
					$("#Manu").html(html);
					
					mascaras();
					validaOpAjax();
					validaQtdRecorrencia();
					validaTipoProcessamento();
					validaDataLiberacaoFaturamento();
				}
			   );
	}
	
	function deletar(id_processamento){	
		
		if (confirm("Deseja mesmo inativar este Processamento?")){
			$.post("processamento.ajax.php?OpAjax=Del", { id_processamento: id_processamento })
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
                
          //Desabilitando o Botao
          $("#Manu #FormManu :button").attr({
               disabled: true,
               class: "botaoOff texto"
          });
		
          //Adicionando a action no form
          form.attr("action", URLBASESITE+"processamento/processamento.ajax.php?Env=true&OpAjax="+opAjax);
		
          $.ajax({
               url: $(form).attr("action"),
               data: $(form).serialize(),
               type: "POST",
               dataType: "html",
               beforeSend: function( data ) {                

                    if(validacao()){
                         return true;
                    }else{
                         //Habilitando o Botao
                         $("#Manu #FormManu :button").attr({
                              disabled: false,
                              class: "botao texto"
                         });
                         return false;
                    }
               },
               success: function( request ) {

                    if(request == "true"){
                         if(opAjax == "Cad"){					
                              informacao('Cadastrado com sucesso!');
                              filtrar();
                         }else if(opAjax == "Alt"){
                              informacao('Alterado com sucesso!');
                              filtrar();
                         }
                    }else{					
                         alerta(request);
                    }

                    //Habilitando o Botao
                    $("#Manu #FormManu :button").attr({
                         disabled: false,
                         class: "botao texto"
                    });
               }
          });
		
          //Retirando a action no form
          form.attr("action", "");
     }
	
	function campoConvenio()
	{		
		var id_unidade_federativa = $("#Manu #id_unidade_federativa").val();
		$( "#Manu #container_convenio" ).load( URLBASESITE+"processamento/processamento.ajax.php?OpAjax=CampoConvenio", {"id_unidade_federativa":id_unidade_federativa});
	}
	
     function campoConvenioFiltro()
     {
          var id_unidade_federativa = $("#FormFiltro #id_unidade_federativa_filtro").val();
          $( "#FormFiltro #container_convenio_filtro" ).load( URLBASESITE+"processamento/processamento.ajax.php?OpAjax=CampoConvenioFiltro", {"id_unidade_federativa_filtro":id_unidade_federativa});
     }
	
	function validaOpAjax()
	{		
		var opAjax = $("#Manu #FormManu #OpAjax").val();
		
		if(opAjax == "Cad"){			
			$( "#Manu #rec" ).show();
		}else{
			$( "#Manu #rec" ).hide();
		}
	}
	
	function validaQtdRecorrencia()
	{		
		var recorrencia = $("#Manu input:radio[name=recorrencia]:checked").val();
		
		if(recorrencia == 1){			
			$( "#Manu #qtd_recorrencia" ).removeAttr('disabled');
			$( "#Manu #qrec" ).show();
			
		}else{
			$( "#Manu #qtd_recorrencia" ).attr('disabled', 'disabled');
			$( "#Manu #qrec" ).hide();			
		}
	}
	
     function validaTipoProcessamento(){		
          var id_tipo_processamento = $("#Manu #id_tipo_processamento").val();
		
          if(id_tipo_processamento == 2){
               $( "#Manu #dra" ).show();
               $( "#Manu #lf" ).show();
          }else if(id_tipo_processamento == 1){
               $( "#Manu #dra" ).hide();
               $( "#Manu #lf" ).hide();
          }else{
               $( "#Manu #dra" ).show();
               $( "#Manu #lf" ).hide();
          }
     }
        
	function validaTipoProcessamentoFiltro()
	{		
		var id_tipo_processamento = $("#FormFiltro #id_tipo_processamento_filtro").val();
		
		if(id_tipo_processamento == 2){			
			$( "#FormFiltro #lff" ).show();
		}else{
			$( "#FormFiltro #lff" ).hide();
		}
	}
	
	function validaDataLiberacaoFaturamento()
	{		
		var liberado_faturamento = $("#Manu #liberado_faturamento").val();
		
		if(liberado_faturamento == 1){			
			$( "#Manu #dt_liberacao_faturamento" ).removeAttr('disabled');
			
			$( "#Manu #dt_liberacao_faturamento" ).mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Liberado Faturamento não é uma data/hora valida.'); } } );
			
			$( "#Manu #dt_liberacao_faturamento" ).datetimepicker({ 
				showAnim: "clip",
				showOn: "button",
				buttonImage: "../imagens/calendar.png",
				buttonText: "Data Liberação Faturamento",
				buttonImageOnly: true,
				onClose: function( dataHora ) {
					validaDataHora($(this), 'ATENÇÃO: O campo Liberado Faturamento não é uma data/hora valida.');
				}
			});
			$( "#Manu #dlf" ).show();
			
		}else{
			$( "#Manu #dt_liberacao_faturamento" ).datetimepicker('destroy');
			$( "#Manu #dt_liberacao_faturamento" ).attr('disabled', 'disabled');
			$( "#Manu #dlf" ).hide();			
		}
	}
	
	function mascaras()
	{
		$("#Manu #dt_previsao_recebimento_arquivo").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Recebimento Arquivo não é uma data/hora valida.'); } } );
		$("#Manu #dt_recebimento_arquivo").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Recebimento Arquivo não é uma data/hora valida.'); } } );
		$("#Manu #dt_previsao_processamento").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Processamento não é uma data/hora valida.'); } } );
		$("#Manu #dt_processamento").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Processamento não é uma data/hora valida.'); } } );
		$("#Manu #dt_previsao_disponibilizacao").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Disponibilização não é uma data/hora valida.'); } } );
		$("#Manu #dt_disponibilizacao").mask("99/99/9999 99:99", {completed:function(){ validaDataHora($(this), 'ATENÇÃO: O campo Data Disponibilização não é uma data/hora valida.'); } } );
		
		$("#Manu #tempo_estimado").mask("99:99", {completed:function(){ validaHora($(this), 'ATENÇÃO: O campo Tempo Estimado não é uma hora valida.'); } } );
		$("#Manu #tempo_gasto").mask("99:99", {completed:function(){ validaHora($(this), 'ATENÇÃO: O campo Tempo Gasto não é uma hora valida.'); } } );               
                
		$( "#Manu #dt_previsao_recebimento_arquivo" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Recebimento Arquivo",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Recebimento Arquivo não é uma data/hora valida.');
			}
		});
		$( "#Manu #dt_recebimento_arquivo" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Recebimento Arquivo",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Recebimento Arquivo não é uma data/hora valida.');
			}
		});
		$( "#Manu #dt_previsao_processamento" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Previsão Processamento",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Processamento não é uma data/hora valida.');
			}
		});
		$( "#Manu #dt_processamento" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Processamento",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Processamento não é uma data/hora valida.');
			}
		});
		$( "#Manu #dt_previsao_disponibilizacao" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Previsão Disponibilização",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Previsão Disponibilização não é uma data/hora valida.');
			}
		});
		$( "#Manu #dt_disponibilizacao" ).datetimepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Data Disponibilização",
			buttonImageOnly: true,
			onClose: function( dataHora ) {
				validaDataHora($(this), 'ATENÇÃO: O campo Data Disponibilização não é uma data/hora valida.');
			}
		});
		
		$( "#Manu #tempo_estimado" ).timepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Tempo Estimado",
			buttonImageOnly: true,
			onClose: function( hora ) {
				validaHora($(this), 'ATENÇÃO: O campo Tempo Estimado não é uma data/hora valida.');
			}
		});		
		$( "#Manu #tempo_gasto" ).timepicker({ 
			showAnim: "clip",
			showOn: "button",
			buttonImage: "../imagens/calendar.png",
			buttonText: "Tempo Gasto",
			buttonImageOnly: true,
			onClose: function( hora ) {
				validaHora($(this), 'ATENÇÃO: O campo Tempo Gasto não é uma hora valida.');
			}
		});
	}
	
	function validaData(obj, msg)
	{
		var regex = /^(((((0?[1-9]|1\d|2[0-8])\/(0?[1-9]|1[0-2]))|((29|30)\/(0?[13456789]|1[0-2]))|(31\/(0?[13578]|1[02])))\/((19|20)?\d\d))$|((29\/0?2\/)((19|20)?(0[48]|[2468][048]|[13579][26])|(20)?00)))$/;
 
		resultado = regex.exec(obj.val());
		if(!resultado){
			obj.val('');
			alerta(msg);
		}
	}
	
	function validaHora(obj, msg)
	{	
		var regex = /^(([0-1][0-9]|[2][0-3]):([0-5][0-9]))$/;
 
		resultado = regex.exec(obj.val());
		if(!resultado){
			obj.val('');
			alerta(msg);
		}
	}
	
	function validaDataHora(obj, msg)
	{
		var dateHora = obj.val().split(' ');
		
		var regexData = /^(((((0?[1-9]|1\d|2[0-8])\/(0?[1-9]|1[0-2]))|((29|30)\/(0?[13456789]|1[0-2]))|(31\/(0?[13578]|1[02])))\/((19|20)?\d\d))$|((29\/0?2\/)((19|20)?(0[48]|[2468][048]|[13579][26])|(20)?00)))$/;
		var regexHora = /^(([0-1][0-9]|[2][0-3]):([0-5][0-9]))$/;	
	
		resultadoData = regexData.exec(dateHora[0]);
		resultadoHora = regexHora.exec(dateHora[1]);
		if(!resultadoData | resultadoHora){
			obj.val('');
			alerta(msg);
		}
	}
	
	function validacao(){	
		if ($("#Manu #FormManu #id_unidade_federativa").val() == ""){
			alerta("ATENÇÃO: O campo U.F não pode ser vazio.");
			$("#Manu #FormManu #id_unidade_federativa").focus();
			return false;
		}
		if ($("#Manu #FormManu #id_convenio").val() == ""){
			alerta("ATENÇÃO: O campo Convênio não pode ser vazio.");
			$("#Manu #FormManu #id_convenio").focus();
			return false;
		}
		if ($("#Manu #FormManu #id_competencia").val() == ""){
			alerta("ATENÇÃO: O campo Competência não pode ser vazio.");
			$("#Manu #FormManu #id_competencia").focus();
			return false;
		}
		if ($("#Manu #FormManu #id_tipo_processamento").val() == ""){
			alerta("ATENÇÃO: O campo Tipo Processamento não pode ser vazio.");
			$("#Manu #FormManu #id_tipo_processamento").focus();
			return false;
		}
		if ($("#Manu #FormManu #id_status").val() == ""){
			alerta("ATENÇÃO: O campo Status não pode ser vazio.");
			$("#Manu #FormManu #id_status").focus();
			return false;
		}
		if ($("#Manu #FormManu #dt_previsao_processamento").val() == ""){
			alerta("ATENÇÃO: O campo Data Previsão Processamento não pode ser vazio.");
			$("#Manu #FormManu #dt_previsao_processamento").focus();
			return false;
		}
		if ($("#Manu #FormManu #st").val() == ""){
			alerta("ATENÇÃO: O campo Situação não pode ser vazio.");
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
	
     function somenteNumero(e){
          var tecla = (window.event) ? event.keyCode : e.which;

          if(tecla>47 && tecla<58){
               return true;
          }else if(tecla==8 || tecla==0){
               return true;
          }else{
               return false;
          }
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
                       <td width="10%" align='right'>U.F:</td>
                       <td width="40%" align='left'><?echo $Pro->getCampoUnidadeFederativaFiltro();?></td>
                       <td width="10%" align='right'>Competência:</td>
                       <td width="30%" align='left'><?echo $Pro->getCampoCompetencia();?></td>	
                       <td width="10%">&nbsp;</td>
                 </tr>
                 <tr>
                       <td width="10%" align='right'>Convênio:</td>
                       <td width="40%" align='left'><div name="container_convenio" id="container_convenio_filtro"><?echo $Pro->getCampoConvenioFiltro();?></div></td>					
                       <td width="10%" align='right'>Status:</td>
                       <td width="30%" align='left'><?echo $Pro->getCampoStatus();?></td>					
                       <td width="10%">
                               <input type="button" name="Button" value="Filtrar" class="botao texto" onclick="limparPaginaAtual();" />
                               <input type="button" name="Button" value="Limpar" class="botao texto" onclick="limpar();" />
                               <input type="hidden" name="pagina_atual" id="pagina_atual" value="" />
                               <input type="hidden" name="quantidade_registros" id="quantidade_registros" value="" />
                       </td>
                 </tr>
                 <tr>
                       <td width="10%" align='right'>Tipo Processamento:</td>                
                       <td width="40%" align='left'><?echo $Pro->getCampoTipoProcessamentoFiltro();?></td>	
                       <td width="10%" align='right'>Situação:</td>                
                       <td width="30%" align='left'><?echo $Pro->getCampoSituacao();?></td>                                        				
                       <td width="10%">&nbsp;</td>
                 </tr>
                 </table>
                 <div name="lff" id="lff">
                  <table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
                       <tr>
                             <td width="10%" align='right'>Liberado Faturamento:</td>
                             <td width="40%" align='left'><?echo $Pro->getCampoLiberadoFaturamento();?></td>
                             <td width="10%">&nbsp;</td>
                             <td width="30%">&nbsp;</td>
                             <td width="10%">&nbsp;</td>
                       </tr>
                 </table>
                 </div>

               </form>            
            </td>
           </tr>
           <tr  height="100%"> 
            <td height="100%" align="left" valign="top">
			
            <!-- Tabela Interna -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
              <tr>
                <td align="left">
                    <img src="<?=$_SESSION['UrlBaseSite']?>imagens/bt_cad.gif" style="padding-bottom:1px; cursor: pointer;" onclick="cadastrar();">
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