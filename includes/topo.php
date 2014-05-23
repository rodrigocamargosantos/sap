<?
	/*include_once($_SESSION['DirBaseSite'].'login/login.class.php'); $Login = new Login();
	if($_GET['Env'] == "Logar") 
	{
		$Login->verificaLogin();
	}
	if($_GET['Env'] == "Logout") 
	{
		$Login->deslogar();
	}*/
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div class="titulo"><a href="<?echo $_SESSION['UrlBaseSite'];?>"><img src="<?echo $_SESSION['UrlBaseSite'];?>imagens/logo.png" width="367" height="80" alt="<?echo $_SESSION['WebSiteEmpresa'];?>"></a></div></td>
        <td width="300" align="right" valign="middle">
			<table width="100" border="0" cellspacing="0" cellpadding="0" class="login">
			  <tr>
				<td>
				
				<table width="100%" border="0" cellspacing="2" cellpadding="2">
				  <tr>
					<td width="65"><center><img src="<?echo $_SESSION['UrlBaseSite'];?>imagens/locked.png" width="32" height="32" /></center></td>
					<td>
					
							<!-- Tabela Do Topo Para Login -->                
							<? if(!isset($_SESSION['LoginCod'])) { ?>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
							  <tr>
								<td height="25" align="right">utilize seu login para ter acesso a ....</td>
							  </tr>
							  <tr>
								<td>
								<form name="FormLogar" id="FormLogar" method="post" action="?Env=Logar">
								  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="texto">
									<tr>
									  <td width="50" height="23" align="right"><strong>Login:</strong></td>
									  <td><input name="Login" type="text" id="Login" size="25" maxlength="20" class="campo texto"></td>
									</tr>
									<tr>
									  <td height="23" align="right"><strong>Senha:</strong></td>
									  <td><!-- <input name="Senha" type="password"  class="campo texto" id="Senha" size="15"> -->
										<input type="submit" name="Logar" id="Logar" value="logar" class="botao texto"></td>
									</tr>
								  </table>
								</form>
								</td>
							  </tr>
							</table>
							
							<? } else { ?>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
							  <tr>
								<td height="25" align="center">Bem vindo(a) <strong><?echo $_SESSION['Nome']?>.</strong></td>
							  </tr>
							  <tr>
								<td height="25" align="center"> você está logado como <strong><?echo $_SESSION['LoginTipoNome']?></strong></td>
							  </tr>
							  
							  <tr>
								<td height="25" align="center"><a href="?Env=Logout" class="hoverMenu"><strong>clique aqui para sair</strong></a></td>
							  </tr>
							</table>                
							
							<? } ?>
							<!-- Fim Tabela Do Topo Para Login -->  
					
					</td>
				  </tr>
				</table>
				
				</td>
			  </tr>
			</table>
		</td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td>
	<br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="divisao_menu">
          <td></td>
        </tr>
        <tr class="menu">
          <td>
          	<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>empresa/" class="hoverMenu">Empresa</a></div>            
               <? //if($_SESSION['LoginTipo'] == "F") { ?>
				<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>convenio/" class="hoverMenu">Convênio</a></div>
                    <div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>icone/" class="hoverMenu">Ícone</a></div>
                    <div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>tipo_processamento/" class="hoverMenu">Tipo de Processamento</a></div>
				<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>status/" class="hoverMenu">Status</a></div>
				<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>processamento/" class="hoverMenu">Processamento</a></div>
               <? //}?>
			<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>relatorio/" class="hoverMenu">Relatório</a></div>
			<div class="menu_texto"> <a href="<?echo $_SESSION['UrlBaseSite'];?>faturamento/" class="hoverMenu">Faturamento</a></div>
            <div style="clear:both"></div>
		  </td>
        </tr>
      </table>
	  </td>
  </tr>
</table>