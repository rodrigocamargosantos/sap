<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'config/grid.class.php');
include_once($_SESSION['DirBaseSite'].'processamento/processamento.sql.php');

class Processamento extends ProcessamentoSQL
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
          
          $Gr->setChave('id_processamento');
          $Gr->setTituloGrid("Listar Processamento");
          
          $Gr->setArrayColunas(array('competencia', 'convenio', 'tipo_processamento', 'status', 'liberado_faturamento', 'situacao'));
          $Gr->setArrayNomeColunas(array('Competência', 'Convênio', 'Tipo Processamento', 'Status', 'Liberado Faturamento', 'Situação'));
          
          $Gr->setArrayAlinhamentoColunas(array('competencia'=>'C', 
                                                 'convenio'=>'E',
                                                 'tipo_processamento'=>'E',
                                                 'status'=>'E',
                                                 'liberado_faturamento'=>'C',
                                                 'situacao'=>'C'));
          
          $Gr->setArrayAlinhamentoNomeColunas(array('Competência'=>'C', 
                                                     'Convênio'=>'E',
                                                     'Tipo Processamento'=>'E',
                                                     'Status'=>'E',
                                                     'Liberado Faturamento'=>'C',
                                                     'Situação'=>'C'));

          $Gr->setSql(parent::filtrarSql());

          return $Gr->gridPadrao();
     }
	
     public function visualizar(){
          //Codigo...
     }
	
     public function cadastrar()
     {
          $this->Con->conectar();
		
          $st = pg_escape_string($_POST['st']);		
          if($st > 0){
               $this->validarUnicoProcessamento();
          }
          
          //Validando Tipos de Processamento
          $this->validarTipoProcessamento();
		
          $_POST['dt_previsao_recebimento_arquivo'] = (!empty($_POST['dt_previsao_recebimento_arquivo'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_recebimento_arquivo']))."'" : 'NULL';
          $_POST['dt_recebimento_arquivo']          = (!empty($_POST['dt_recebimento_arquivo'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_recebimento_arquivo']))."'" : 'NULL';
          $_POST['dt_previsao_processamento']       = (!empty($_POST['dt_previsao_processamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_processamento']))."'" : 'NULL';
          $_POST['dt_processamento']                = (!empty($_POST['dt_processamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_processamento']))."'" : 'NULL';
          $_POST['dt_previsao_disponibilizacao']    = (!empty($_POST['dt_previsao_disponibilizacao'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_disponibilizacao']))."'" : 'NULL';
          $_POST['dt_disponibilizacao']             = (!empty($_POST['dt_disponibilizacao'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_disponibilizacao']))."'" : 'NULL';
          $_POST['dt_liberacao_faturamento']        = (!empty($_POST['dt_liberacao_faturamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_liberacao_faturamento']))."'" : 'NULL';

          $_POST['tempo_estimado']                = (!empty($_POST['tempo_estimado'])) ? "'".pg_escape_string($_POST['tempo_estimado'])."'" : 'NULL';
          $_POST['tempo_gasto']                   = (!empty($_POST['tempo_gasto'])) ? "'".pg_escape_string($_POST['tempo_gasto'])."'" : 'NULL';

          $_POST['liberado_faturamento']          = (!empty($_POST['liberado_faturamento'])) ? pg_escape_string($_POST['liberado_faturamento']) : 'NULL';

          $this->Con->executar(parent::cadastrarSql());

          if($_POST['recorrencia'] > 0 && $_POST['qtd_recorrencia'] > 0)
          {
               $this->cadastrarRecorrencia();
          }
     }
	
     public function cadastrarRecorrencia(){
          $this->Con->conectar();
		
          $Ano = $this->Con->execRLinha(parent::getMaxCompetenciaSql(), 'ano');
          $Mes = $this->Con->execRLinha(parent::getMaxCompetenciaSql(), 'mes');
		
          //Retirando Aspas
          $dt_previsao_processamento    = str_replace(chr(39), chr(0), $_POST['dt_previsao_processamento']);
          $dt_previsao_recebimento_arquivo = str_replace(chr(39), chr(0), $_POST['dt_previsao_recebimento_arquivo']);
          
          if(!empty($dt_previsao_processamento)){
               //POST dt_previsao_processamento ja esta convertido formato Americano
               $DiaDPP 	= $this->getPartDataHora($dt_previsao_processamento,'D');
               $MesDPP 	= $this->getPartDataHora($dt_previsao_processamento,'M');
               $AnoDPP 	= $this->getPartDataHora($dt_previsao_processamento,'A');
               $HoraDPP 	= $this->getPartDataHora($dt_previsao_processamento,'H');
               $MinutoDPP	= $this->getPartDataHora($dt_previsao_processamento,'I');
               //Minimo 01 Maximo 28
               $DiaDPP = ($DiaDPP > 28) ? 28 : $DiaDPP;
               
               $_POST['dt_previsao_processamento'] = "'".date('Y-m-d H:i:s', mktime($HoraDPP, $MinutoDPP, 0, $MesDPP + $I, $DiaDPP, $AnoDPP))."'";
          }
          
          if(!empty($dt_previsao_recebimento_arquivo)){
               //POST dt_previsao_recebimento_arquivo ja esta convertido formato Americano
               $DiaDPRA 	= $this->getPartDataHora($dt_previsao_recebimento_arquivo,'D');
               $MesDPRA 	= $this->getPartDataHora($dt_previsao_recebimento_arquivo,'M');
               $AnoDPRA 	= $this->getPartDataHora($dt_previsao_recebimento_arquivo,'A');
               $HoraDPRA 	= $this->getPartDataHora($dt_previsao_recebimento_arquivo,'H');
               $MinutoDPRA	= $this->getPartDataHora($dt_previsao_recebimento_arquivo,'I');
               //Minimo 01 Maximo 28
               $DiaDPRA = ($DiaDPRA > 28) ? 28 : $DiaDPRA;
               
               $_POST['dt_previsao_recebimento_arquivo'] = "'".date('Y-m-d H:i:s', mktime($HoraDPRA, $MinutoDPRA, 0, $MesDPRA + $I, $DiaDPRA, $AnoDPRA))."'";
          }
          
		
          for($I = 1; $I < $_POST['qtd_recorrencia']; $I++){	
               $_POST['mes'] = date('m', mktime(0, 0, 0, $Mes + $I, 01, $Ano));
               $_POST['ano'] = date('Y', mktime(0, 0, 0, $Mes + $I, 01, $Ano));

               $id_competencia = $this->Con->execRLinha(parent::getIdCompetenciaSql(), 'id_competencia');

               $_POST['id_competencia'] 			= $id_competencia;
               $_POST['id_status']		 			= 1; //Sempre verificar o id correto.
               
               $_POST['dt_recebimento_arquivo'] 		= 'NULL';
               $_POST['dt_processamento'] 				= 'NULL';
               $_POST['dt_previsao_disponibilizacao'] 	= 'NULL';
               $_POST['dt_disponibilizacao'] 			= 'NULL';
               $_POST['dt_liberacao_faturamento'] 		= 'NULL';			
               $_POST['tempo_estimado'] 				= 'NULL';
               $_POST['tempo_gasto'] 					= 'NULL';			
               $_POST['liberado_faturamento'] 			= 'NULL';

               $this->Con->executar(parent::cadastrarSql());	
          }
    }
	
     public function alterar(){
          $this->Con->conectar();
		
          $st = pg_escape_string($_POST['st']);		
          if($st > 0){
               $this->validarUnicoProcessamento();
          }
          
          //Validando Tipos de Processamento
          $this->validarTipoProcessamento();
		
          $_POST['dt_previsao_recebimento_arquivo'] = (!empty($_POST['dt_previsao_recebimento_arquivo'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_recebimento_arquivo']))."'" : 'NULL';
          $_POST['dt_recebimento_arquivo']          = (!empty($_POST['dt_recebimento_arquivo'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_recebimento_arquivo']))."'" : 'NULL';
          $_POST['dt_previsao_processamento']       = (!empty($_POST['dt_previsao_processamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_processamento']))."'" : 'NULL';
          $_POST['dt_processamento']                = (!empty($_POST['dt_processamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_processamento']))."'" : 'NULL';
          $_POST['dt_previsao_disponibilizacao']    = (!empty($_POST['dt_previsao_disponibilizacao'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_previsao_disponibilizacao']))."'" : 'NULL';
          $_POST['dt_disponibilizacao']             = (!empty($_POST['dt_disponibilizacao'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_disponibilizacao']))."'" : 'NULL';
          $_POST['dt_liberacao_faturamento']        = (!empty($_POST['dt_liberacao_faturamento'])) ? "'".pg_escape_string($this->convertDataHora($_POST['dt_liberacao_faturamento']))."'" : 'NULL';

          $_POST['tempo_estimado'] = (!empty($_POST['tempo_estimado'])) ? "'".pg_escape_string($_POST['tempo_estimado'])."'" : 'NULL';
          $_POST['tempo_gasto']    = (!empty($_POST['tempo_gasto'])) ? "'".pg_escape_string($_POST['tempo_gasto'])."'" : 'NULL';

          $_POST['liberado_faturamento'] = (!empty($_POST['liberado_faturamento'])) ? pg_escape_string($_POST['liberado_faturamento']) : 'NULL';

          $this->Con->executar(parent::alterarSql());
     }
	
     public function validarTipoProcessamento(){
          if($_POST['id_tipo_processamento'] != 2){
               unset($_POST['liberado_faturamento']);
               unset($_POST['dt_liberacao_faturamento']);
          }
          
          if($_POST['id_tipo_processamento'] == 1){
               unset($_POST['dt_previsao_recebimento_arquivo']);
               unset($_POST['dt_recebimento_arquivo']);
          }
     }
     
     public function validarUnicoProcessamento(){
          $this->Con->conectar();

          $Validar = $this->Con->execNLinhas(parent::validarUnicoProcessamentoSql());

          if($Validar > 0){
               throw new Exception('Esse Processamento já existe!');
          }
     }
	
     public function deletar(){
          $this->Con->conectar();
		
          $this->Con->executar(parent::deletarSql());
     }
	
     public function getDados(){
             $this->Con->conectar();

             $Array = $this->Con->execLinha(parent::getDadosSql());

             $_POST['id_processamento']		= $Array['id_processamento'];
             $_POST['id_unidade_federativa']	= $Array['id_unidade_federativa'];
             $_POST['id_convenio'] 		= $Array['id_convenio'];
             $_POST['id_competencia'] 		= $Array['id_competencia'];
             $_POST['id_tipo_processamento']	= $Array['id_tipo_processamento'];
             $_POST['id_status']		= $Array['id_status'];

             $_POST['dt_previsao_recebimento_arquivo']  = $Array['dt_previsao_recebimento_arquivo'];
             $_POST['dt_recebimento_arquivo']		= $Array['dt_recebimento_arquivo'];
             $_POST['dt_previsao_processamento']	= $Array['dt_previsao_processamento'];
             $_POST['dt_processamento']			= $Array['dt_processamento'];
             $_POST['dt_previsao_disponibilizacao']	= $Array['dt_previsao_disponibilizacao'];
             $_POST['dt_disponibilizacao']		= $Array['dt_disponibilizacao'];
             $_POST['dt_liberacao_faturamento']		= $Array['dt_liberacao_faturamento'];

             $_POST['tempo_estimado']   = $Array['tempo_estimado'];
             $_POST['tempo_gasto']      = $Array['tempo_gasto'];

             $_POST['liberado_faturamento']  = $Array['liberado_faturamento'];
             $_POST['observacao']            = $Array['observacao'];
             $_POST['st']                    = $Array['st'];
     }
	
	public function convertData($Data)
    {
		$ArrayData = preg_split('/[^[:digit:]]/', $Data);
		return implode('-',array_reverse($ArrayData));
    }
	
	public function convertDataHora($DataHora)
    {
		list($Data, $Hora) = preg_split('/[[:space:]]/',$DataHora);
		
		return $this->convertData($Data).chr(32).$Hora;
    }
	
	public function getPartDataHora($DataHora, $Part)
	{
		list($Data, $HMS) = preg_split('/[[:space:]]/',$DataHora);
		
		list($Ano, $Mes, $Dia) = preg_split('/[^[:digit:]]/', trim($Data));
		
		list($Hora, $Minuto) = preg_split('/[^[:digit:]]/', trim($HMS));
		
		switch($Part) 
		{
			case 'D':
				return $Dia;
			case 'M':
				return $Mes;
			case 'A':
				return $Ano;
			case 'H':
				return $Hora;
			case 'I':
				return $Minuto;
		}
	}
	
	public function getCampoConvenio()
	{
		$this->Con->conectar();
		
		if(!empty($_POST["id_unidade_federativa"])){
			$Array = $this->Con->execTodosArray(parent::getConvenioSql());
		
			$IdConvenio = $_POST['id_convenio'];
			
			$Buffer = '<select name="id_convenio" id="id_convenio" class="campo texto">';
			$Buffer.=(!empty($IdConvenio))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
			
			foreach($Array as $Rs)
			{
				if($Rs['id_convenio'] == $IdConvenio)
				{
					$Buffer.= '<option value="'.$Rs['id_convenio'].'" selected="selected">'.$Rs['convenio'].'</option>';
				}else{
					$Buffer.= '<option value="'.$Rs['id_convenio'].'">'.$Rs['convenio'].'</option>';
				}
			}
			$Buffer.= '</select>';
		}else{
			$Buffer = '<select name="id_convenio" id="id_convenio" class="campo texto" disabled>';
			$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione o Campo U.F...</option>';
			$Buffer.= '</select>';
		}
		$Buffer.= '<span class="obrigatorio">*</span>';
		
		return $Buffer;
	}
	
	public function getCampoConvenioFiltro()
	{
		$this->Con->conectar();
		
		$Array = $this->Con->execTodosArray(parent::getConvenioFiltroSql());
		
		$IdConvenio = $_POST['id_convenio'];
		
		$Buffer = '<select name="id_convenio" id="id_convenio" class="campo texto">';
		$Buffer.=(!empty($IdConvenio))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_convenio'] == $IdConvenio)
			{
				$Buffer.= '<option value="'.$Rs['id_convenio'].'" selected="selected">'.$Rs['convenio'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_convenio'].'">'.$Rs['convenio'].'</option>';
			}
		}
		$Buffer.= '</select>';      
		
		return $Buffer;
	}
	
	public function getCampoCompetencia()
	{
		$this->Con->conectar();

        $Array = ($_POST['Op'] == 'Cad') ? $this->Con->execTodosArray(parent::getCompetenciaIntervalSql()) : $this->Con->execTodosArray(parent::getCompetenciaSql());
		
		$IdCompetencia = ($_POST['Op'] == 'Cad') ? $this->Con->execRLinha(parent::getCompetenciaAtualSql(), 'id_competencia') : $_POST['id_competencia'];
		
		$Buffer = '<select name="id_competencia" id="id_competencia" class="campo texto">';
		$Buffer.=(!empty($IdCompetencia))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_competencia'] == $IdCompetencia)
			{
				$Buffer.= '<option value="'.$Rs['id_competencia'].'" selected="selected">'.$Rs['competencia'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_competencia'].'">'.$Rs['competencia'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
	
	public function getCampoTipoProcessamento()
	{
		$this->Con->conectar();

                $Array = $this->Con->execTodosArray(parent::getTipoProcessamentoSql());
		
		$IdTipoProcessamento = $_POST['id_tipo_processamento'];
		
		$Buffer = '<select name="id_tipo_processamento" id="id_tipo_processamento" class="campo texto" onchange="validaTipoProcessamento()">';
		$Buffer.=(!empty($IdTipoProcessamento))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_tipo_processamento'] == $IdTipoProcessamento)
			{
				$Buffer.= '<option value="'.$Rs['id_tipo_processamento'].'" selected="selected">'.$Rs['tipo_processamento'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_tipo_processamento'].'">'.$Rs['tipo_processamento'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
	
     public function getCampoTipoProcessamentoFiltro(){
          $this->Con->conectar();

          $Array = $this->Con->execTodosArray(parent::getTipoProcessamentoSql());

          $IdTipoProcessamento = $_POST['id_tipo_processamento_filtro'];

          $Buffer = '<select name="id_tipo_processamento_filtro" id="id_tipo_processamento_filtro" class="campo texto" onchange="validaTipoProcessamentoFiltro()">';
          $Buffer.=(!empty($IdTipoProcessamento))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';

     foreach($Array as $Rs)
     {
             if($Rs['id_tipo_processamento'] == $IdTipoProcessamento)
             {
                     $Buffer.= '<option value="'.$Rs['id_tipo_processamento'].'" selected="selected">'.$Rs['tipo_processamento'].'</option>';
             }else{
                     $Buffer.= '<option value="'.$Rs['id_tipo_processamento'].'">'.$Rs['tipo_processamento'].'</option>';
             }
     }

     return $Buffer.= '</select>';
     }
	
	
	public function getCampoStatus()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getStatusSql());
		
		$IdStatus = $_POST['id_status'];
		
		$Buffer = '<select name="id_status" id="id_status" class="campo texto">';
		$Buffer.=(!empty($IdStatus))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_status'] == $IdStatus)
			{
				$Buffer.= '<option value="'.$Rs['id_status'].'" selected="selected">'.$Rs['status'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_status'].'">'.$Rs['status'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
	
	public function getCampoLiberadoFaturamento()
	{
		$Padrao = $_POST['liberado_faturamento'];
		$Array = array('1' => 'Sim', '-1' => 'Não');
		
		$Buffer = '<select name="liberado_faturamento" id="liberado_faturamento" class="campo texto" onchange="validaDataLiberacaoFaturamento()">';
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
	
	public function getCampoUnidadeFederativa()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getUnidadeFederativaSql());
		
		$IdUnidadeFederativa = $_POST['id_unidade_federativa'];
		
		$Buffer = '<select name="id_unidade_federativa" id="id_unidade_federativa" class="campo texto"  onchange="campoConvenio()">';
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
        
        public function getCampoUnidadeFederativaFiltro()
	{
               $this->Con->conectar();

               $Array = $this->Con->execTodosArray(parent::getUnidadeFederativaSql());

               $IdUnidadeFederativa = $_POST['id_unidade_federativa_filtro'];

               $Buffer = '<select name="id_unidade_federativa_filtro" id="id_unidade_federativa_filtro" class="campo texto"  onchange="campoConvenioFiltro()">';
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