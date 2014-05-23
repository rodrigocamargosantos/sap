<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'icone/icone.class.php');

$OpAjax = $_REQUEST['OpAjax'];

switch ($OpAjax)
{
    case "Fil": ##Filtro
        
		$Ico = new Icone();

        try
        {		
			echo $Ico->filtrar();
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

        $Che = new Cheque();

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
        
		$Ico = new Icone();
		$Env = $_REQUEST['Env'];
		
        try
        {		    
            if($Env == true)
            {
				$Ico->cadastrar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Cad';		
				
				//Campos
				$CampoSituacao = $Ico->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'icone/icone.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Alt": ##Alterar
        
		$Ico = new Icone();
		$Env = $_REQUEST['Env'];	
		
        try
        {
            if($Env == true)
            {
				$Ico->alterar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Alt';
				
				//Recuperando os POSTs
				$Ico->getDados();
				
				//Campos
				$CampoSituacao = $Ico->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'icone/icone.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;	
	
    case "Del": ##Remover

        $Ico = new Icone();

        try
        {		
			$Ico->deletar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
}