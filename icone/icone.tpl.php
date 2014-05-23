<table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
	<? if($_POST['Op'] == 'Cad'){ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Cadastrar Ícone</td>
	</tr>
	<? }else{ ?>
	<tr>
		<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Alterar Ícone</td>
	</tr>
	<? } ?>
	<tr>
		<td class="texto_conteudo">		
			<form id="FormManu" name="FormManu" method="post" enctype="multipart/form-data" onsubmit="" action="">
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
					<? if($_POST['Op'] == 'Cad'){ ?>
						<tr>
							<td>Ícone:</td>
							<td><input type="file" name="arquivo" id="arquivo" /> <br /> <?echo $Imagem;?></td>
						</tr>
					<? }else{ ?>
						<tr>
							<td valign="top">Ícone:</td>
							<td>
								<input type="file" name="arquivo" id="arquivo" /> 
								<br /> 
								<img src="<?=$_SESSION['UrlBaseSite']?>icone/icone.img.php?IdIcone=<?echo $_POST['id_icone']?>" height="16" width="16" /></td>
						</tr>
					<? } ?>
					
					<tr>
						<td>Descrição:</td>
                                                <td><input name="descricao" id="descricao" type="text" class="campo texto" size="60" maxlength="50" value="<?=$_POST['descricao']?>" /><span class="obrigatorio">*</span></td>
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
							<input name="id_icone" id="id_icone" type="hidden" value="<?=$_POST['id_icone']?>" />
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