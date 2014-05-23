<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'relatorio/relatorio.class.php');

$OpAjax = $_REQUEST['OpAjax'];

switch ($OpAjax)
{
    case "Rela": ##Relatorio
        
		$Rel = new Relatorio();

        try
        {		
			echo $Rel->relatorio();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Info": ##Informacao
        
		$Rel = new Relatorio();

        try
        {		
			echo $Rel->conteudoModal();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
	
	case "Tit": ##Titulo
        
		$Rel = new Relatorio();

        try
        {		
			echo $Rel->tituloModal();
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

    break;
}