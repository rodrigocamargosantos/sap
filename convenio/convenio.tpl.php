<table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
	<? if($_POST['Op'] == 'Cad'){ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Cadastrar Convênio</td>
	</tr>
	<? }else{ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Alterar Convênio</td>
	</tr>
	<? } ?>
	<tr>
		<td class="texto_conteudo">		
			<form id="FormManu" name="FormManu" method="post" onsubmit="" action="">
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<tr>
						<td>Unidade Federativa:</td>
						<td><?=$CampoUnidadeFederativa?><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Sigla:</td>
						<td><input name="sigla_convenio" id="sigla_convenio" type="text" class="campo texto" maxlength="5" value="<?=$_POST['sigla_convenio']?>" /><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Descrição:</td>
						<td><input name="descricao" id="descricao" type="text" size="100" class="campo texto" maxlength="100" value="<?=$_POST['descricao']?>" /><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Operando:</td>
						<td><?=$CampoOperando?><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Situação:</td>
						<td><?=$CampoSituacao?><span class="obrigatorio">*</span></td>
					</tr>
					<tr align="center">
						<td colspan="2">
						<? if($_POST['Op'] == 'Cad'){ ?>
							<input name="OpAjax" id="OpAjax" type="hidden" value="<?=$_POST['Op']?>" />
							<input type="button" name="Button" value="Cadastrar" class="botao texto" onclick="cadastrarAlterar();" />
							<input type="button" name="Button" value="Cancelar" class="botao texto" onclick="filtrar();" />
						<? }else{ ?>
							<input name="OpAjax" id="OpAjax" type="hidden" value="<?=$_POST['Op']?>" />
							<input name="id_convenio" id="id_convenio" type="hidden" value="<?=$_POST['id_convenio']?>" />
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