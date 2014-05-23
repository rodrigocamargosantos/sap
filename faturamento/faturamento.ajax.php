<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'faturamento/faturamento.class.php');

$OpAjax = $_REQUEST['OpAjax'];

switch ($OpAjax)
{
    case "Fatu": ##Relatorio
        
		$Fat = new Faturamento();

        try
        {		
			echo $Fat->faturamento();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Info": ##Informacao
        
		$Fat = new Faturamento();

        try
        {		
			echo $Fat->conteudoModal();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Tit": ##Titulo
        
		$Fat = new Faturamento();

        try
        {		
			echo $Fat->tituloModal();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "CampoConvenio": ##Referente ao Campo Convenio

        $Fat = new Faturamento();

        try
        {		
			echo $Fat->getCampoConvenio();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
}