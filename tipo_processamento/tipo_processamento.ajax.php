<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'tipo_processamento/tipo_processamento.class.php');

$OpAjax = $_REQUEST['OpAjax'];

switch ($OpAjax)
{
    case "Fil": ##Filtro
        
		$TP = new TipoProcessamento();

        try
        {		
			echo $TP->filtrar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
     case "Vis" : ##Visualizar
         
          $TP = new TipoProcessamento();

          try{		
               echo $TP->visualizar();
          }catch (Exception $E){
               echo $E->getMessage();
          }
          
     break;
	
    case "Cad": ##Cadastrar
        
		$TP = new TipoProcessamento();
		$Env = $_REQUEST['Env'];	
		
        try
        {
            if($Env == true)
            {
				$TP->cadastrar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Cad';
				
				//Campos
				$CampoIcone		= $TP->getCampoIcone();
				$CampoPadrao	= $TP->getCampoPadrao();
				$CampoSituacao	= $TP->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'tipo_processamento/tipo_processamento.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Alt": ##Alterar
        
		$TP = new TipoProcessamento();
		$Env = $_REQUEST['Env'];	
		
        try
        {
            if($Env == true)
            {				
                $TP->alterar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Alt';
				
				//Recuperando os POSTs
				$TP->getDados();
				
				//Campos
				$CampoIcone		= $TP->getCampoIcone();
				$CampoPadrao	= $TP->getCampoPadrao();
				$CampoSituacao	= $TP->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'tipo_processamento/tipo_processamento.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;	
	
    case "Del": ##Remover

        $TP = new TipoProcessamento();

        try
        {		
			$TP->deletar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
}