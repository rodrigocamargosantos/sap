<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rodape">
	<tr>
		<td width="90%">
			<div align="right" class="texto" style="height:80px;">
				<br><?echo $_SESSION['WebSiteEmpresa'];?>
				<br><?echo $_SESSION['WebSiteEndereco'];?>
				<br><?echo $_SESSION['WebSiteTelefone'];?>
				<br><?echo $_SESSION['WebSiteEmail'];?>
			</div>
		</td>
		<td width="10%">
			<div align="center" class="texto" style="width:90px; height:80px;">
				<a href="<?echo $_SESSION['UrlBaseSite'];?>"><img src="<?echo $_SESSION['UrlBaseSite'];?>imagens/logo_inferior.png" width="90" height="80"></a>
			</div>
		</td>
	</tr>
</table>