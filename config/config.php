<?
class Config
{
	private static $Instancia;

	/**
	*	Construtor
	*/
	private function __construct()
	{
		$this->setDiretorios();
	}

	/**
	*	Padrão SINGLETON para Instanciar as Configuirações
	*/
	public static function Conf()
	{
		if (!isset(self::$Instancia))
		{
			self::$Instancia = new Config();
		}

		return self::$Instancia;
	}

	private function setDiretorios()
	{
		if(!isset($_SESSION['DirBase']))
		{
			//Aqui vem as únicas configurações que devem ser realizadas
			$Cliente    = "/sap/"; //Apenas Local

			//Configuração da URL Padrão de todo o sistema
			$LocalHost = "http://".$_SERVER['HTTP_HOST'];
			
			//Configuração do Caminho Padrão de todo o sistema
			$LocalDir  = $_SERVER['DOCUMENT_ROOT'];

			//Site & Sistema
			$_SESSION['DirBaseSite']       = $LocalDir.$Cliente;
			$_SESSION['UrlBaseSite']       = $LocalHost.$Cliente;
			
			//Rodape
			$_SESSION['WebSiteTitulo']   = "Sistema de Acompanhamento de Processamento";
			$_SESSION['WebSiteSigla']    = "S.A.P";
			$_SESSION['WebSiteEmpresa']  = "Consignum - Gestão de Margem Consignável";
			$_SESSION['WebSiteEndereco'] = "Av. Historiador Rubens de Mendonça, Ed. Com. Top Tower 17° Andar";
			$_SESSION['WebSiteTelefone'] = "(65) 3316-2200";
			$_SESSION['WebSiteEmail']	 = "consignum@consignum.com.br";
                        
               //TimeZone
               date_default_timezone_set('America/Cuiaba');

		}
	}
}
