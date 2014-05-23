<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();

include_once($_SESSION['DirBaseSite'].'convenio/convenio.class.php');

$OpAjax = $_GET['OpAjax'];

switch ($OpAjax){
     case "Fil": ##Filtro
        
          $Conv = new Convenio();

          try{
               echo $Conv->filtrar();
          }catch (Exception $E){
               echo $E->getMessage();
          }

     break;
	
     case "Vis" : ##Visualizar
         
          $Conv = new Convenio();

          try{		
               echo $Conv->visualizar();
          }catch (Exception $E){
               echo $E->getMessage();
          }
          
     break;
	
     case "Cad": ##Cadastrar
        
          $Conv = new Convenio();
          $Env = $_GET['Env'];	
		
          try{
               if($Env == true){
                    $Conv->cadastrar();
                    echo("true");
               }else{
                    //Variavel
                    $_POST['Op'] = 'Cad';
				
                    //Campos
                    $CampoUnidadeFederativa = $Conv->getCampoUnidadeFederativa();
                    $CampoOperando          = $Conv->getCampoOperando();
                    $CampoSituacao          = $Conv->getCampoSituacao();
				
                    //Arquivo Template
                    include_once($_SESSION['DirBaseSite'].'convenio/convenio.tpl.php');
               }
          }catch (Exception $E){
               echo $E->getMessage();
          }

     break;
	
     case "Alt": ##Alterar
        
          $Conv = new Convenio();
          $Env = $_GET['Env'];	
		
          try{
               if($Env == true){				
                    $Conv->alterar();
                    echo("true");
               }else{
                    //Variavel
                    $_POST['Op'] = 'Alt';
				
                    //Recuperando os POSTs
                    $Conv->getDados();

                    //Campos
                    $CampoUnidadeFederativa = $Conv->getCampoUnidadeFederativa();
                    $CampoOperando          = $Conv->getCampoOperando();
                    $CampoSituacao          = $Conv->getCampoSituacao();
				
                    //Arquivo Template
                    include_once($_SESSION['DirBaseSite'].'convenio/convenio.tpl.php');
               }
          }catch (Exception $E){
               echo $E->getMessage();
          }

     break;	
	
     case "Del": ##Remover

          $Conv = new Convenio();

          try{		
               $Conv->deletar();
          }catch (Exception $E){
               echo $E->getMessage();
          }

     break;
}