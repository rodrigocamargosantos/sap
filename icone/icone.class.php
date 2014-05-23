<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'config/grid.class.php');
include_once($_SESSION['DirBaseSite'].'icone/icone.sql.php');

class Icone extends IconeSQL
{
    private $Con;

    public function __construct()
    {
        $this->Con = new Conexao();
    }
	
     public function filtrar(){
          $Gr = new Grid();
          
          $Gr->setPaginaAtual($_POST['pagina_atual']);
          $Gr->setQuantidadeRegistros($_POST['quantidade_registros']);
          
          $Gr->setChave('id_icone');
          $Gr->setTituloGrid("Listar Ícone");
          
          $Gr->setArrayColunas(array('descricao', 'situacao'));
          $Gr->setArrayNomeColunas(array('Descrição', 'Situação'));
          
          $Gr->setArrayAlinhamentoColunas(array('descricao'=>'E',
                                                 'situacao'=>'C'));
          
          $Gr->setArrayAlinhamentoNomeColunas(array('Descrição'=>'E',
                                                     'Situação'=>'C'));

          $Gr->setSql(parent::filtrarSql());

          return $Gr->gridPadrao();
     }

     public function visualizar(){
          //Codigo...
     }
	
    public function cadastrar()
    {
        try
		{
			if(empty($_FILES['arquivo']['tmp_name'])){
				throw new Exception('O campo Ícone não pode ser vazio.');
			}
			
			$this->Con->conectar();
			
			$Arquivo = $_FILES['arquivo'];		
			$Pathinfo = pathinfo($Arquivo['name']);		
			
			//Manipulando Arquivo
			$ArquivoString = file_get_contents($Arquivo['tmp_name']);		
			$_POST['icone'] = base64_encode($ArquivoString);
			$_POST['extensao'] = $Pathinfo['extension'];	
			
			$this->Con->executar(parent::cadastrarSql());
		}
		catch (Exception $E)
		{
			echo $E->getMessage();
		}

    }
	
	public function alterar()
    {
		try
		{
			$this->Con->conectar();
			
			if(empty($_FILES['arquivo']['tmp_name'])){
				$this->Con->executar(parent::alterarSemImgSql());
			}else{
				$Arquivo = $_FILES['arquivo'];		
				$Pathinfo = pathinfo($Arquivo['name']);		
				
				//Manipulando Arquivo
				$ArquivoString = file_get_contents($Arquivo['tmp_name']);		
				$_POST['icone'] = base64_encode($ArquivoString);
				$_POST['extensao'] = $Pathinfo['extension'];	
				
				$this->Con->executar(parent::alterarComImgSql());
			}
		}
		catch (Exception $E)
		{
			echo $E->getMessage();
		}
    }
	
	 public function deletar()
    {
        $this->Con->conectar();
		
		$this->Con->executar(parent::deletarSql());
    }
	
	public function getDados()
	{
		$this->Con->conectar();
		
		$Array = $this->Con->execLinha(parent::getDadosSql());
		
		$_POST['id_icone'] 	= $Array['id_icone'];
		$_POST['icone'] 	= $Array['icone'];
		$_POST['extensao'] 	= $Array['extensao'];
		$_POST['descricao'] = $Array['descricao'];
		$_POST['st'] 		= $Array['st'];
	}
	
	public function getImagem($IdIcone)
	{
		$this->Con->conectar();
		
		$_POST['id_icone'] = $IdIcone;
		
		$Array = $this->Con->execLinha(parent::getDadosSql());
		
		header("Content-type: image/png");
		echo base64_decode($Array['icone']);
	}
	
	public function getCampoSituacao()
	{
		$Situacao = (empty($_POST['st']))? 1 : $_POST['st'];
		$Array = array('1' => 'Ativo', '-1' => 'Inativo');
		
		$Buffer = '<select name="st" id="st" class="campo texto">';
		$Buffer.=(!empty($Situacao))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Key => $Value)
		{
			if($Key == $Situacao)
			{
				$Buffer.= '<option value="'.$Key.'" selected="selected">'.$Value.'</option>';
			}else{
				$Buffer.= '<option value="'.$Key.'">'.$Value.'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
}