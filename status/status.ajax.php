<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'status/status.class.php');

$OpAjax = $_GET['OpAjax'];

switch ($OpAjax)
{
    case "Fil": ##Filtro
        
		$St = new Status();

        try
        {		
			echo $St->filtrar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
    case "Vis" : ##Visualizar

        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        $Che = new TipoProcessamento();

        try
        {
			echo($Che->visualizar());
        }
        catch (Exception $E)
        {
			echo($E->getMessage());
        }	

    break;
	
    case "Cad": ##Cadastrar
        
		$St = new Status();
		$Env = $_GET['Env'];	
		
        try
        {
            if($Env == true)
            {
				$St->cadastrar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Cad';
				
				//Campos
				$CampoIcone		= $St->getCampoIcone();
				$CampoSituacao	= $St->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'status/status.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Alt": ##Alterar
        
		$St = new Status();
		$Env = $_GET['Env'];	
		
        try
        {
            if($Env == true)
            {				
                $St->alterar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Alt';
				
				//Recuperando os POSTs
				$St->getDados();
				
				//Campos
				$CampoIcone		= $St->getCampoIcone();
				$CampoSituacao	= $St->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'status/status.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;	
	
    case "Del": ##Remover

        $St = new Status();

        try
        {		
			$St->deletar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
}