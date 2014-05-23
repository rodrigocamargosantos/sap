<table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
	<? if($_POST['Op'] == 'Cad'){ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Cadastrar Processamento</td>
	</tr>
	<? }else{ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Alterar Processamento</td>
	</tr>
	<? } ?>
	<tr>
		<td class="texto_conteudo">		
			<form id="FormManu" name="FormManu" method="post" onsubmit="" action="">
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<tr>
						<td width="20%">U.F:</td>
						<td width="80%"><?echo $CampoUF?><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td width="20%">Convênio:</td>
						<td width="80%"><div name="container_convenio" id="container_convenio"><?echo $CampoConvenio?></div></td>
					</tr>
					<tr>
						<td width="20%">Competência:</td>
						<td width="80%"><?echo $CampoCompetencia?><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td width="20%">Tipo Processamento:</td>
						<td width="80%"><?echo $CampoTipoProcessamento?><span class="obrigatorio">*</span></td>
					</tr>
				</table>
				<? if($_POST['Op'] == 'Cad'){ ?>
				<div name="rec" id="rec">
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
						<tr>
							<td width="20%">Recorrência:</td>
							<td width="80%">
								<input type="radio" name="recorrencia" value="1" onclick="validaQtdRecorrencia()">Sim&nbsp;
								<input type="radio" name="recorrencia" value="-1" onclick="validaQtdRecorrencia()" checked>Não
							</td>
						</tr>
					</table>
					<div name="qrec" id="qrec">
						<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
							<tr>
								<td width="20%">Qtd. Recorrência:</td>
								<td width="80%"><input name="qtd_recorrencia" id="qtd_recorrencia" type="text" class="campo texto" maxlength="2" size="3" value="<?=$_POST['qtd_recorrencia']?>" onkeypress="return somenteNumero(event);"/></td>
							</tr>
						</table>
					</div>
				</div>
				<? } ?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<tr>
						<td width="20%">Status:</td>
						<td width="80%"><?echo $CampoStatus?><span class="obrigatorio">*</span></td>
					</tr>
					
					<tr>
						<td width="20%">Observação:</td>
						<td width="80%"><textarea name="observacao" id="observacao" class="campo texto" rows="12" cols="90"><?=$_POST['observacao']?></textarea></td>
					</tr>
                                </table>
                              <div name="dra" id="dra">
                                   <table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
                                        <tr>
                                             <td width="20%">Data Previsão Recebimento Arquivo:</td>
                                             <td width="80%"><input name="dt_previsao_recebimento_arquivo" id="dt_previsao_recebimento_arquivo" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_previsao_recebimento_arquivo']?>" /></td>
					</tr>
					<tr>
                                             <td width="20%">Data Recebimento Arquivo:</td>
                                             <td width="80%"><input name="dt_recebimento_arquivo" id="dt_recebimento_arquivo" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_recebimento_arquivo']?>" /></td>
					</tr>
                                   </table>
                              </div>
                             <table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">                                        
					<tr>
						<td width="20%">Data Previsão Processamento:</td>
						<td width="80%"><input name="dt_previsao_processamento" id="dt_previsao_processamento" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_previsao_processamento']?>" /><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td width="20%">Data Processamento:</td>
						<td width="80%"><input name="dt_processamento" id="dt_processamento" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_processamento']?>" /></td>
					</tr>
					<tr>
						<td width="20%">Data Previsão Disponibilização:</td>
						<td width="80%"><input name="dt_previsao_disponibilizacao" id="dt_previsao_disponibilizacao" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_previsao_disponibilizacao']?>" /></td>
					</tr>
					<tr>
						<td width="20%">Data Disponibilização:</td>
						<td width="80%"><input name="dt_disponibilizacao" id="dt_disponibilizacao" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_disponibilizacao']?>" /></td>
					</tr>
					
					<tr>
						<td width="20%">Tempo Estimado:</td>
						<td width="80%"><input name="tempo_estimado" id="tempo_estimado" type="text" size="5" class="campo texto" maxlength="5" value="<?=$_POST['tempo_estimado']?>" /></td>
					</tr>
					
					<tr>
						<td width="20%">Tempo Gasto:</td>
						<td width="80%"><input name="tempo_gasto" id="tempo_gasto" type="text" size="5" class="campo texto" maxlength="5" value="<?=$_POST['tempo_gasto']?>" /></td>
					</tr>
				</table>
				
				<div name="lf" id="lf">
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
						<tr>
							<td width="20%">Liberado Faturamento:</td>
							<td width="80%"><?echo $CampoLiberadoFaturamento?></td>
						</tr>
					</table>
					<div name="dlf" id="dlf">
						<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
							<tr>
								<td width="20%">Data Liberação Faturamento:</td>
								<td width="80%"><input name="dt_liberacao_faturamento" id="dt_liberacao_faturamento" type="text" size="20" class="campo texto" maxlength="16" value="<?=$_POST['dt_liberacao_faturamento']?>" /></td>
							</tr>
						</table>
					</div>
				</div>
						
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<tr>
						<td width="20%">Situação:</td>
						<td width="80%"><?echo $CampoSituacao?><span class="obrigatorio">*</span></td>
					</tr>
					<tr align="center">
						<td colspan="2">
						<? if($_POST['Op'] == 'Cad'){ ?>
							<input name="OpAjax" id="OpAjax" type="hidden" value="<?=$_POST['Op']?>" />
							<input type="button" name="Button" value="Cadastrar" class="botao texto" onclick="cadastrarAlterar();" />
							<input type="button" name="Button" value="Cancelar" class="botao texto" onclick="filtrar();" />
						<? }else{ ?>
							<input name="OpAjax" id="OpAjax" type="hidden" value="<?=$_POST['Op']?>" />
							<input name="id_processamento" id="id_processamento" type="hidden" value="<?=$_POST['id_processamento']?>" />
							<input type="button" name="Button" value="Alterar" class="botao texto" onclick="cadastrarAlterar();" />
							<input type="button" name="Button" value="Cancelar" class="botao texto" onclick="filtrar();" />
						<? } ?>
						</td>
					</tr>     
				</table>
			</form>		
		</td>
	</tr>			  
</table>