<table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
	<? if($_POST['Op'] == 'Cad'){ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Cadastrar Status</td>
	</tr>
	<? }else{ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Alterar Status</td>
	</tr>
	<? } ?>
	<tr>
		<td class="texto_conteudo">		
			<form id="FormManu" name="FormManu" method="post" onsubmit="" action="">
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<tr>
						<td>Ícone:</td>
						<td><?=$CampoIcone?><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Status:</td>
						<td><input name="nome_status" id="nome_status" type="text" class="campo texto" maxlength="100" value="<?=$_POST['nome_status']?>" /><span class="obrigatorio">*</span></td>
					</tr>
					<tr>
						<td>Descrição:</td>
						<td><input name="descricao_status" id="descricao_status" type="text" size="100" class="campo texto" maxlength="100" value="<?=$_POST['descricao_status']?>" /></td>
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
							<input name="id_status" id="id_status" type="hidden" value="<?=$_POST['id_status']?>" />
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