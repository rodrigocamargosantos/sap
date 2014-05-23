<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'relatorio/relatorio.sql.php');

class Relatorio extends RelatorioSQL
{
    private $Con;

    public function __construct()
    {
        $this->Con = new Conexao();
    }
	
	public function relatorio()
	{
		//Titulo
		$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$Html.= "<tr><td class='titulo_conteudo' style='text-align:center;'>Acompanhamento de Processamento</td></tr>";
		$Html.= "</table>";
		
		$Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$Html.= "<tr><td width='54px'>";
						
		//Chama Convenio
		$Html.= $this->relatorioConvenio();
		
		$Html.= "</td>";
		$Html.= "<td>";
		
		//Chama Competencia
		$Html.= $this->relatorioCompetencia();
		
		$Html.= "</td></tr>";
		$Html.= "</table>";
		
		//Chama Legenda
		$Html.= $this->relatorioLegenda();
		
		return $Html;
	}
	
	public function relatorioConvenio()
	{
		$this->Con->conectar();
		$Array = $this->Con->execTodosArray(parent::relatorioConvenioSql());
		
		$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";		
		$Html.= "<tr class='textoTituloGridRelatorio zebraA'>
					<td class='textoEsquerda'>Convênio</td>
				</tr>";
		$Html.= "<tr class='textoGridRelatorio zebraB'>
					<td class='textoEsquerda'>&nbsp;</td>
				</tr>";		
		
		foreach($Array as $Rs)
		{
			$Zebra = (++$Contador % 2) ? 'zebraA' : 'zebraB';
		
			$Html.= "<tr class='textoGridRelatorio $Zebra'>
						<td class='textoEsquerda'>".$Rs['convenio']."</td>
					</tr>";
		}
		$Html.= "</table>";
		
		return $Html;
		
	}
	
	public function relatorioCompetencia()
	{
		$this->Con->conectar();
		
		//Tipo Processamento
		$ArrayTP = $this->Con->execTodosArray(parent::relatorioTipoProcessamentoSql());
		
		$HtmlTP = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$HtmlTP.= "<tr>";
		
		foreach($ArrayTP as $RsTP)
		{
			$HtmlTP.= "<td class='textoCentro'>";
			$HtmlTP.= $RsTP['tipo_processamento'];
			//$HtmlTP.= "<img src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=".$RsTP['id_icone']."' height='16' width='16' />".$RsTP['tipo_processamento'];
			$HtmlTP.= "</td>";
		}
		$HtmlTP.= "</tr>";
		$HtmlTP.= "</table>";
		
		//Competencia
		$ArrayC = $this->Con->execTodosArray(parent::relatorioCompetenciaSql());
		
		$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		
		$Html.= "<tr class='textoTituloGridRelatorio zebraA'>";		
		foreach($ArrayC as $RsC)
		{
			$Html.= "<td class='textoCentro'>".$this->getMesExt($RsC['mes'])."/".$RsC['ano']."</td>";
		}
		$Html.= "</tr>";
		
		$Html.= "<tr class='textoGridRelatorio zebraB'>";
		foreach($ArrayC as $RsC)
		{
			$Html.= "<td class='textoCentro'>$HtmlTP</td>";
		}
		$Html.= "</tr>";
		
		
		$ArrayConv = $this->Con->execTodosArray(parent::relatorioConvenioSql());
		foreach($ArrayConv as $RsConv)
		{
			$Zebra = (++$Contador % 2) ? 'zebraA' : 'zebraB';
		
			$Html.= "<tr class='textoGridRelatorio $Zebra'>";
			
			foreach($ArrayC as $RsC)
			{
				$Html.= "<td class='textoCentro'>";
				
				$Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
				$Html.= "<tr>";
				
				foreach($ArrayTP as $RsTP)
				{
					$Html.= "<td class='textoCentro'>";
					
					$RsS = $this->Con->execLinha(parent::relatorioStatusSql($RsConv['id_convenio'], $RsC['id_competencia'], $RsTP['id_tipo_processamento']));
					
					if(!empty($RsS)){
						$Html.= "<img title='teste da Hilda' style='cursor: pointer;' onclick='modalRelatorio(\"".$RsS['id_processamento']."\")' src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=".$RsS['id_icone']."' height='16' width='16' />";
					}else{
						$Html.= "<img title='teste da Hilda' src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=8' height='16' width='16' />";
					}
					
					
					$Html.= "</td>";
				}
				$Html.= "</tr>";
				$Html.= "</table>";
				
				$Html.= "</td>";
			}
			
			$Html.= "</tr>";
		}
		
		$Html.= "</table>";
		
		return $Html;
																			
	}
	
	//public function relatorio
	
	public function relatorioLegenda()
	{
		$this->Con->conectar();
		
		$Html = "<fieldset>
					<legend  class='legenda'>Legendas</legend>
					<table width='100%' border='0' cellspacing='0' cellpadding='0' class='interna'>
						<tr>
							<td>";

        $ArrayTipoProcessamento = $this->Con->execTodosArray(parent::getLegendasTipoProcessamentoSql());		
		foreach($ArrayTipoProcessamento as $RsTipoProcessamento)
		{
			$Html .= "<div style='margin:2px; /*width:100px;*/ float:left;'>
						<img src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=".$RsTipoProcessamento['id_icone']."' height='16' width='16' /><span class='textoLegenda'>".$RsTipoProcessamento['descricao']."</span>
					  </div>";
		}
		
		$ArrayStatus = $this->Con->execTodosArray(parent::getLegendasStatusSql());		
		foreach($ArrayStatus as $RsStatus)
		{
			$Html .= "<div style='margin:2px; /*width:100px;*/ float:left;'>
						<img src='".$_SESSION['UrlBaseSite']."icone/icone.img.php?IdIcone=".$RsStatus['id_icone']."' height='16' width='16' /><span class='textoLegenda'>".$RsStatus['nome_status']."</span>
					  </div>";
		}
		
		$Html .= "			</td>
						</tr>
					</table>
				  </fieldset>";

		return $Html;		
	}
	
	public function getMesExt($Mes)
    {
        $Meses = array(1=>"Janeiro",2=>"Fevereiro",3=>"Março",4=>"Abril",5=>"Maio",6=>"Junho",7=>"Julho",8=>"Agosto",9=>"Setembro",10=>"Outubro",11=>"Novembro",12=>"Dezembro");

        return $Meses[$Mes];
    }
	
    public function getMesAbreExt($Mes)
    {
        $Meses = array(1=>"Jan",2=>"Fev",3=>"Mar",4=>"Abr",5=>"Maio",6=>"Jun",7=>"Jul",8=>"Ago",9=>"Set",10=>"Out",11=>"Nov",12=>"Dez");

        return $Meses[$Mes];
    }
	
	public function tituloModal()
	{
		$this->Con->conectar();
	
		return $this->Con->execRLinha(parent::tituloModalSql(), 'titulo');
	}
	
	public function conteudoModal()
	{
		$this->Con->conectar();
	
		$Rs = $this->Con->execLinha(parent::conteudoModalSql());
		
		$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='texto_conteudo'>";		
		
		if(!empty($Rs['convenio'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Convênio:</td>
						<td class='textoGrid'>".$Rs['convenio']."</td>
					</tr>";
		}		
		if(!empty($Rs['competencia'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Competência:</td>
						<td class='textoGrid'>".$Rs['competencia']."</td>
					</tr>";
		}		
		if(!empty($Rs['tipo_processamento'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Tipo de Processamento:</td>
						<td class='textoGrid'>".$Rs['tipo_processamento']."</td>
					</tr>";
		}
		if(!empty($Rs['status'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Status:</td>
						<td class='textoGrid'>".$Rs['status']."</td>
					</tr>";
		}
		if(!empty($Rs['observacao'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Observação:</td>
						<td class='textoGrid'>".$Rs['observacao']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_recebimento_arquivo'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Recebimento Arquivo:</td>
						<td class='textoGrid'>".$Rs['dt_recebimento_arquivo']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_previsao_processamento'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Previsão Processamento:</td>
						<td class='textoGrid'>".$Rs['dt_previsao_processamento']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_processamento'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Processamento:</td>
						<td class='textoGrid'>".$Rs['dt_processamento']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_previsao_disponibilizacao'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Previsão Disponibilização:</td>
						<td class='textoGrid'>".$Rs['dt_previsao_disponibilizacao']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_disponibilizacao'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Disponibilização:</td>
						<td class='textoGrid'>".$Rs['dt_disponibilizacao']."</td>
					</tr>";
		}
		if(!empty($Rs['tempo_estimado'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Tempo Estimado:</td>
						<td class='textoGrid'>".$Rs['tempo_estimado']."</td>
					</tr>";
		}
		if(!empty($Rs['tempo_gasto'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Tempo Gasto:</td>
						<td class='textoGrid'>".$Rs['tempo_gasto']."</td>
					</tr>";
		}
		if(!empty($Rs['liberado_faturamento'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Liberado Faturamento:</td>
						<td class='textoGrid'>".$Rs['liberado_faturamento']."</td>
					</tr>";
		}
		if(!empty($Rs['dt_liberacao_faturamento'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Liberação Faturamento:</td>
						<td class='textoGrid'>".$Rs['dt_liberacao_faturamento']."</td>
					</tr>";
		}		
		if(!empty($Rs['situacao'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Situação:</td>
						<td class='textoGrid'>".$Rs['situacao']."</td>
					</tr>";
		}
				
		$Html.= "</table>";
		
		return $Html;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function convertData($Data)
    {
		$ArrayData = preg_split('/[^[:digit:]]/', $Data);
		return implode('-',array_reverse($ArrayData));
    }
	
	public function getCampoConvenio()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getConvenioSql());
		
		$IdConvenio = $_POST['id_convenio'];
		
		$Buffer = '<select name="id_convenio" id="id_convenio" class="campo texto">';
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
		foreach($Array as $Rs)
		{
			if($Rs['id_convenio'] == $IdConvenio)
			{
				$Buffer.= '<option value="'.$Rs['id_convenio'].'" selected="selected">'.$Rs['convenio'].'</option>';
			}else{
				$Buffer.= '<option value="'.$Rs['id_convenio'].'">'.$Rs['convenio'].'</option>';
			}
		}
		
		return $Buffer.= '</select>';
	}
	
	public function getCampoCompetencia()
	{
		$this->Con->conectar();

        $Array = $this->Con->execTodosArray(parent::getCompetenciaSql());
		
		$IdCompetencia = ($_POST['Op'] == 'Cad') ? $this->Con->execRLinha(parent::getCompetenciaAtualSql(), 'id_competencia') : $_POST['id_competencia'];
		
		$Buffer = '<select name="id_competencia" id="id_competencia" class="campo texto">';
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
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
		
		$Buffer = '<select name="id_tipo_processamento" id="id_tipo_processamento" class="campo texto">';
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
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
		$Buffer.=(!empty($IdUnidadeFederativa))? '<option value="">Selecione...</option>' : '<option value="" selected="selected">Selecione...</option>';
		
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
		
		$Buffer = '<select name="liberado_faturamento" id="liberado_faturamento" class="campo texto">';
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