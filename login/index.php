<? 
session_start();

//Instancias
include_once('../conexao/conexao.class.php'); $Con = new Conexao();
include_once($_SESSION['DirBaseSite'].'login/login.class.php'); $Classe = new Login();

if($_GET['Env'] == "Logar") 
{
	if($Classe->verificaLogin())
	{
		echo "<script>location.href='".$_SESSION['UrlBaseSite']."proposta/'</script>";
	}
}

include_once($_SESSION['DirBaseSite']."includes/cabecalho.php"); 
?>
<!-- Funções de JavaScript -->
<script type="text/javascript">
	function validacao(){
		
		d = document.FormLogin;
		if (d.Login.value == ""){
			alert("ATENÇÃO: O campo Login não pode ser vazio.");
			d.Login.focus();
			return false;
		}
		if (d.Senha.value == ""){
			alert("ATENÇÃO: O campo Senha não pode ser vazio.");
			d.Senha.focus();
			return false;
		}
		if (d.Senha.value.length < 6){
			alert("ATENÇÃO: O campo Senha tem que ser no mínimo 6 caracteres.");
			d.Senha.focus();
			return false;
		}
	}
</script>
<!-- Fim Funções de JavaScript -->
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="bg">
  <tr>
    <td align="center" valign="top">
    
    <table width="769" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bg_intera" bgcolor="#FFFFFF">
      <tr bgcolor="#ffffff">
        <td align="left" valign="top" class="bg_degrade"><table width="98%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
            <!-- Include Topo-->
            <? include_once($_SESSION['DirBaseSite']."includes/topo.php"); ?>
            </td>
            </tr>
          <tr  height="100%">
            <td height="100%" align="left" valign="top">
            
            <!-- Tabela Interna -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
              <tr>
                <td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Se Loga Como Cliente</td>
              </tr>
              <tr>
                <td class="texto_conteudo">
                
                	<form id="FormLogin" name="FormLogin" method="post"  onSubmit="return validacao()" action="?Env=Logar">

                        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="texto_conteudo">
                          <tr>
                            <td>Login:</td>
                            <td><input name="Login" id="Login" type="text" class="campo texto" maxlength="20" value="<?=$_POST['Login']?>" /><span class="obrigatorio">*</span></td>
                          </tr>
                          <tr>
                            <td>Senha:</td>
                            <td><input name="Senha" id="Senha" type="password" class="campo texto" maxlength="20" value="" /><span class="obrigatorio">*</span></td>
                          </tr>
                          <tr>
                            <td colspan="2"><input type="submit" name="Logar" id="Logar" value="logar" class="botao texto"></td>
                          </tr>
                        </table>
                      
                    </form>
                
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