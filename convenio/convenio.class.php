<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'config/grid.class.php');
include_once($_SESSION['DirBaseSite'].'convenio/convenio.sql.php');

class Convenio extends ConvenioSQL{
     private $Con;

     public function __construct(){
          $this->Con = new Conexao();
     }
	
     public function filtrar(){
          $Gr = new Grid();
          
          $Gr->setPaginaAtual($_POST['pagina_atual']);
          $Gr->setQuantidadeRegistros($_POST['quantidade_registros']);
          
          $Gr->setChave('id_convenio');
          $Gr->setTituloGrid("Listar Convênio");
          
          $Gr->setArrayColunas(array('unidade_federativa', 'sigla_convenio', 'descricao', 'operando', 'situacao'));
          $Gr->setArrayNomeColunas(array('U.F', 'Sigla', 'Descrição', 'Operando', 'Situação'));
          
          $Gr->setArrayAlinhamentoColunas(array('unidade_federativa'=>'E', 
                                                 'sigla_convenio'=>'C',
                                                 'descricao'=>'E',
                                                 'operando'=>'C',
                                                 'situacao'=>'C'));
          
          $Gr->setArrayAlinhamentoNomeColunas(array('U.F'=>'E', 
                                                     'Sigla'=>'C',
                                                     'Descrição'=>'E',
                                                     'Operando'=>'C',
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
                                             <td>".$Array['id_convenio']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Unidade Federativa:</td>
                                             <td>".$Array['unidade_federativa']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Sigla:</td>
                                             <td>".$Array['sigla_convenio']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Descrição:</td>
                                             <td>".$Array['descricao']."</td>
					</tr>
                                        <tr>
                                             <td style='font-weight:bold;' width='15%' align='right'>Operando:</td>
                                             <td>".$Array['operando']."</td>
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
		
		$_POST['id_convenio'] 			= $Array['id_convenio'];
		$_POST['id_unidade_federativa'] = $Array['id_unidade_federativa'];
		$_POST['sigla_convenio'] 		= $Array['sigla_convenio'];
		$_POST['descricao'] 			= $Array['descricao'];
		$_POST['operando']				= $Array['operando'];
		$_POST['st'] 					= $Array['st'];
	}
	
	public function getCampoUnidadeFederativa()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getUnidadeFederativaSql());
		
		$IdUnidadeFederativa = $_POST['id_unidade_federativa'];
		
		$Buffer = '<select name="id_unidade_federativa" id="id_unidade_federativa" class="campo texto">';
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_unidade_federativa'] == $IdUnidadeFederativa)
			{
				$Buffer.= '<option value="'.$Rs['id_unidade_federativa'].'" selected="selected">'.$Rs['unidade_federativa'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_unidade_federativa'].'">'.$Rs['unidade_federativa'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
	
	public function getCampoOperando()
	{
		$Operando = $_POST['operando'];
		$Array = array('1' => 'Sim', '-1' => 'Não');
		
		$Buffer = '<select name="operando" id="operando" class="campo texto">';
		$Buffer.=(!empty($Operando))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Key => $Value)
		{
			if($Key == $Operando)
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