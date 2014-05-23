<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'config/grid.class.php');
include_once($_SESSION['DirBaseSite'].'status/status.sql.php');

class Status extends StatusSQL
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
          
          $Gr->setChave('id_status');
          $Gr->setTituloGrid("Listar Status");
          
          $Gr->setArrayColunas(array('icone', 'nome_status', 'situacao'));
          $Gr->setArrayNomeColunas(array('Ícone', 'Status', 'Situação'));
          
          $Gr->setArrayAlinhamentoColunas(array('icone'=>'E', 
                                                 'nome_status'=>'E',
                                                 'situacao'=>'C'));
          
          $Gr->setArrayAlinhamentoNomeColunas(array('Ícone'=>'E', 
                                                     'Status'=>'E',
                                                     'Situação'=>'C'));

          $Gr->setSql(parent::filtrarSql());

          return $Gr->gridPadrao();
     }
	
	public function visualizar()
    {
        //Codigo...
    }
	
    public function cadastrar()
    {
        $this->Con->conectar();
		
		$st = pg_escape_string($_POST['st']);		
		if($st > 0)
		{
			$this->validarUnicoStatus();
		}
		
		$this->Con->executar(parent::cadastrarSql());
    }
	
	public function alterar()
    {
        $this->Con->conectar();
		
		$st = pg_escape_string($_POST['st']);		
		if($st > 0)
		{
			$this->validarUnicoStatus();
		}
		
		$this->Con->executar(parent::alterarSql());
    }
	
	 public function deletar()
    {
        $this->Con->conectar();
		
		$this->Con->executar(parent::deletarSql());
    }
	
	public function validarUnicoStatus()
	{
		$this->Con->conectar();
		
		$Validar = $this->Con->execNLinhas(parent::validarUnicoStatusSql());
		
		if($Validar > 0)
		{
			throw new Exception('Esse Status já existe!');
		}
	}
	
	public function getDados()
	{
		$this->Con->conectar();
		
		$Array = $this->Con->execLinha(parent::getDadosSql());
		
		$_POST['id_status']			= $Array['id_status'];
		$_POST['id_icone'] 			= $Array['id_icone'];
		$_POST['nome_status'] 		= $Array['nome_status'];
		$_POST['descricao_status']	= $Array['descricao_status'];
		$_POST['st']				= $Array['st'];
	}
	
	public function getCampoIcone()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getIconeSql());
		
		$IdIcone = $_POST['id_icone'];
		
		$Buffer = '<select name="id_icone" id="id_icone" class="campo texto">';
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_icone'] == $IdIcone)
			{
				$Buffer.= '<option value="'.$Rs['id_icone'].'" selected="selected">'.$Rs['icone'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_icone'].'">'.$Rs['icone'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
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