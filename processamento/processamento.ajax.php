<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'processamento/processamento.class.php');

$OpAjax = $_GET['OpAjax'];

switch ($OpAjax){
     case "Fil": ##Filtro
        
          $Pro = new Processamento();

          try{		
               echo $Pro->filtrar();
          }catch (Exception $E){
               echo $E->getMessage();
          }

    break;
	
    case "Vis" : ##Visualizar

        /*
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
         
          */	

    break;
	
     case "Cad": ##Cadastrar
        
          $Pro = new Processamento();
          $Env = $_GET['Env'];	
		
          try
          {
               if($Env == true)
               {							
                    $Pro->cadastrar();
                    echo("true");
               }
               else
               {
                    //Variavel
                    $_POST['Op'] = 'Cad';

                    //Campos
                    $CampoUF                  = $Pro->getCampoUnidadeFederativa();
                    $CampoConvenio            = $Pro->getCampoConvenio();
                    $CampoCompetencia         = $Pro->getCampoCompetencia();
                    $CampoTipoProcessamento   = $Pro->getCampoTipoProcessamento();
                    $CampoStatus              = $Pro->getCampoStatus();
                    $CampoLiberadoFaturamento = $Pro->getCampoLiberadoFaturamento();				
                    $CampoSituacao            = $Pro->getCampoSituacao();

                    //Arquivo Template
                    include_once($_SESSION['DirBaseSite'].'processamento/processamento.tpl.php');
              }
          }
          catch (Exception $E)
          {
              echo $E->getMessage();
          }

    break;
	
	case "Alt": ##Alterar
        
		$Pro = new Processamento();
		$Env = $_REQUEST['Env'];	
		
        try
        {
            if($Env == true)
            {				
                $Pro->alterar();
				echo("true");
            }
            else
            {
				//Variavel
                $_POST['Op'] = 'Alt';
				
				//Recuperando os POSTs
				$Pro->getDados();
				
				//Campos
				$CampoUF					= $Pro->getCampoUnidadeFederativa();
				$CampoConvenio				= $Pro->getCampoConvenio();
				$CampoCompetencia			= $Pro->getCampoCompetencia();
				$CampoTipoProcessamento		= $Pro->getCampoTipoProcessamento();
				$CampoStatus				= $Pro->getCampoStatus();
				$CampoLiberadoFaturamento	= $Pro->getCampoLiberadoFaturamento();				
				$CampoSituacao				= $Pro->getCampoSituacao();
				
				//Arquivo Template
                include_once($_SESSION['DirBaseSite'].'processamento/processamento.tpl.php');
            }
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;	
	
    case "Del": ##Remover

        $Pro = new Processamento();

        try
        {		
			$Pro->deletar();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
	
	case "CampoConvenio": ##Referente ao Campo Convenio

        $Pro = new Processamento();

        try
        {		
			echo $Pro->getCampoConvenio();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
   
     case "CampoConvenioFiltro": ##Referente ao Campo Convenio

          $Pro = new Processamento();

          try
          {		
               echo $Pro->getCampoConvenioFiltro();
          }
          catch (Exception $E)
          {
               echo $E->getMessage();
          }

     break;
}