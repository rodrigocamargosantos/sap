<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php'); 
include_once($_SESSION['DirBaseSite'].'login/login.sql.php');

class Login extends LoginSQL
{
    private $Con;

    public function Login()
    {
        $this->Con = new Conexao();
    }

    public function verificaLogin()
    {
        $this->Con->conectar();

		$Login = $_POST['Login'];
		$Senha = md5($_POST['Senha']);

        $Resut = mysql_query(parent::verificaLoginSql($Login, $Senha));
        $Linha = mysql_fetch_array($Resut);
		
        if(!empty($Linha['LoginCod']))
        {
            $_SESSION['LoginCod']  		= $Linha['LoginCod'];
            $_SESSION['LoginNome'] 		= $Linha['LoginNome'];
            $_SESSION['LoginTipo'] 		= $Linha['LoginTipo'];
			$_SESSION['LoginTipoNome'] 	= $Linha['LoginTipoNome'];
			$_SESSION['Cod'] 	   		= $Linha['Cod'];
			$_SESSION['Nome']	   		= $Linha['Nome'];
            $_SESSION['CPF']	   		= $Linha['CPF'];
			
			echo "<script>alert('Você foi logado com sucesso.')</script>";
			
			return true;
        }else{
			echo "<script>alert('Erro ao Logar')</script>";
            return false;
        }
    }
	
	public function deslogar()
	{
		unset($_SESSION['LoginCod']);
		unset($_SESSION['LoginNome']);
		unset($_SESSION['LoginTipo']);
		unset($_SESSION['LoginTipoNome']);
		unset($_SESSION['Cod']);
		unset($_SESSION['Nome']);
        unset($_SESSION['CPF']);
		
		echo "<script>alert('Deslogado com Sucesso.')</script>";
		echo "<script>location.href='".$_SESSION['UrlBaseSite']."'</script>";
			
		return;
	}
}