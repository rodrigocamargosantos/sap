<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'config/grid.class.php');
include_once($_SESSION['DirBaseSite'].'tipo_processamento/tipo_processamento.sql.php');

class TipoProcessamento extends TipoProcessamentoSQL
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
          
          $Gr->setChave('id_tipo_processamento');
          $Gr->setTituloGrid("Listar Tipo de Processamento");
          
          $Gr->setArrayColunas(array('icone', 'descricao', 'padrao', 'situacao'));
          $Gr->setArrayNomeColunas(array('Icone', 'Descrição', 'Padrão', 'Situação'));
          
          $Gr->setArrayAlinhamentoColunas(array('icone'=>'E', 
                                                 'descricao'=>'E',
                                                 'padrao'=>'C',
                                                 'situacao'=>'C'));
          
          $Gr->setArrayAlinhamentoNomeColunas(array('Icone'=>'E', 
                                                     'Descrição'=>'E',
                                                     'Padrão'=>'C',
                                                     'Situação'=>'C'));

          $Gr->setSql(parent::filtrarSql());

          return $Gr->gridPadrao();
     }
	
     public function visualizar(){
          $this->Con->conectar();
		
          $Array = $this->Con->execLinha(parent::visualizarSql());
          
          $Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='interna'>
                         <tr>
                              <td class='titulo_conteudo'><img src='".$_SESSION['UrlBaseSite']."imagens/bullet.gif' style='padding-bottom:1px;'> Visualizar Convênio</td>
                         </tr>
                         <tr>
                              <td class='texto_conteudo'>
                                   <table width='100%' border='0' cellspacing='1' cellpadding='1' class='texto_conteudo'>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Código:</td>
                                             <td>".$Array['id_tipo_processamento']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Ícone:</td>
                                             <td>".$Array['icone']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Imagem:</td>
                                             <td><img src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=".$Array['id_icone']."' height='16' width='16' /></td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Descrição:</td>
                                             <td>".$Array['descricao']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Padrão:</td>
                                             <td>".$Array['padrao']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Situação:</td>
                                             <td>".$Array['situacao']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Data Inclusão:</td>
                                             <td>".$Array['dt_inclusao']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Data Última Alteração:</td>
                                             <td>".$Array['dt_situacao']."</td>
					</tr>
                                   </table>
                              </td>
                         </tr>
                         <tr>
                              <td align='center'><input type='button' name='Button' value='Voltar' class='botao texto' onclick='filtrar();' /></td>
                         </tr>
                    </table>";
          
          return $Html;

     }
	
    public function cadastrar()
    {
        $this->Con->conectar();
		
		$this->Con->executar(parent::cadastrarSql());
    }
	
	public function alterar()
    {
        $this->Con->conectar();
		
		$this->Con->executar(parent::alterarSql());
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
		
		$_POST['id_tipo_processamento']	= $Array['id_tipo_processamento'];
		$_POST['id_icone'] 				= $Array['id_icone'];
		$_POST['descricao'] 			= $Array['descricao'];
		$_POST['padrao'] 				= $Array['padrao'];
		$_POST['st'] 					= $Array['st'];
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
	
	public function getCampoPadrao()
	{
		$Padrao = $_POST['padrao'];
		$Array = array('1' => 'Sim', '-1' => 'Não');
		
		$Buffer = '<select name="padrao" id="padrao" class="campo texto">';
		$Buffer.=(!empty($Padrao))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Key => $Value)
		{
			if($Key == $Padrao)
			{
				$Buffer.= '<option value="'.$Key.'" selected="selected">'.$Value.'</option>';
			}else{
				$Buffer.= '<option value="'.$Key.'">'.$Value.'</option>';
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