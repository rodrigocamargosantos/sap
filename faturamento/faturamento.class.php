<?
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');
include_once($_SESSION['DirBaseSite'].'faturamento/faturamento.sql.php');

class Faturamento extends FaturamentoSQL
{
    private $Con;

    public function __construct()
    {
        $this->Con = new Conexao();
    }
	
	public function faturamento()
	{
                $this->Con->conectar();
            
                //TimeZone
		date_default_timezone_set('America/Cuiaba');
                
                unset($_POST['AnoMesInicio']);
                unset($_POST['AnoMesFim']);
                
                if(!empty($_POST["id_competencia"]))
                {
                    $RsAnoMes = $this->Con->execLinha(parent::getMesAnoIdCompetencia());
                    
                    $Ano = $RsAnoMes['ano'];
                    $Mes = $RsAnoMes['mes'];
                    
                    $MKInicio = mktime(0, 0, 0, $Mes-2, 01, $Ano);
                    $MKFim    = mktime(0, 0, 0, $Mes, 01, $Ano);
                    
                    $AnoInicio	= date("Y", $MKInicio);
                    $AnoFim     = date("Y", $MKFim);
		
                    $MesInicio	= date("m", $MKInicio);		
                    $MesFim     = date("m", $MKFim);                    
                    
                    $_POST['AnoMesInicio'] = "'".$AnoInicio.$MesInicio."'";
                    $_POST['AnoMesFim'] = "'".$AnoFim.$MesFim."'";
                }
                else
                {
                    $MKInicio	= mktime(0, 0, 0, date('n')-2, 01, date('Y'));
                    $MKFim	= mktime(0, 0, 0, date('n'), 01, date('Y'));

                    $AnoInicio	= date("Y", $MKInicio);
                    $AnoFim	= date("Y", $MKFim);
		
                    $MesInicio	= date("m", $MKInicio);		
                    $MesFim	= date("m", $MKFim);
		
                    $_POST['AnoMesInicio'] = "'".$AnoInicio.$MesInicio."'";
                    $_POST['AnoMesFim'] = "'".$AnoFim.$MesFim."'";
                    
                }                
                
		$NLinhas = $this->Con->execNLinhas(parent::faturamentoConvenioSql());
		
		if($NLinhas > 0)
		{	
			//Titulo
			$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
			$Html.= "<tr><td class='titulo_conteudo' style='text-align:center;'>Acompanhamento de Faturamento</td></tr>";
			$Html.= "</table>";
			
			$Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
			$Html.= "<tr><td width='54px'>";
							
			//Chama Convenio
			$Html.= $this->faturamentoConvenio();
			
			$Html.= "</td>";
			$Html.= "<td>";
			
			//Chama Competencia
			$Html.= $this->faturamentoCompetencia();
			
			$Html.= "</td></tr>";
			$Html.= "</table>";
			
			//Chama Legenda
			$Html.= $this->faturamentoLegenda();
		}
		else
		{
			$Html =	"<table width='100%' border='0' cellspacing='0' cellpadding='0' class='interna'> 
						<tr> 
							<td><div class='semResultado'>Nenhum resultado encontrado.</div></td>
						</tr>
					</table>";
		}
		return $Html;
	}
	
	public function faturamentoConvenio()
	{
		$this->Con->conectar();
		$Array = $this->Con->execTodosArray(parent::faturamentoConvenioSql());
		
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
						<td title='".$Rs['convenio']."' class='textoEsquerda'>".$Rs['sigla']."</td>
					</tr>";
		}
		$Html.= "</table>";
		
		return $Html;
		
	}
	
	public function faturamentoCompetencia()
	{
		$this->Con->conectar();
                
                
		
		$HtmlFL = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$HtmlFL.= "<tr>
						<td class='textoCentro'>Faturamento Liberado</td>
				   </tr>";
		$HtmlFL.= "</table>";                
		
		//Competencia
		$ArrayC = $this->Con->execTodosArray(parent::faturamentoCompetenciaSql());
		$NLinhas = $this->Con->execNLinhas(parent::faturamentoCompetenciaSql());
		
		$Html = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		
		//Competencia Coluna
		$Html.= "<tr class='textoTituloGridRelatorio zebraA'>";		
		foreach($ArrayC as $RsC)
		{
			$Html.= "<td class='textoCentro'>".$this->getMesExt($RsC['mes'])."/".$RsC['ano']."</td>";
		}
		$Html.= "</tr>";
		
		//Titulo Coluna
		$Html.= "<tr class='textoGridRelatorio zebraB'>";
		$Html.= "<td colspan='$NLinhas' class='textoCentro'>Faturamento Liberado</td>";
		$Html.= "</tr>";
		
		
		$ArrayConv = $this->Con->execTodosArray(parent::faturamentoConvenioSql());
		foreach($ArrayConv as $RsConv)
		{
			$Zebra = (++$Contador % 2) ? 'zebraA' : 'zebraB';
		
			$Html.= "<tr class='textoGridRelatorio $Zebra'>";
			
			foreach($ArrayC as $RsC)
			{
				$Html.= "<td class='textoCentro'>";
				
				$Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
				$Html.= "<tr>";
				
				$Html.= "<td class='textoCentro'>";
					
				$RsFL = $this->Con->execLinha(parent::faturamentoLiberadoSql($RsConv['id_convenio'], $RsC['id_competencia']));
				
				if(!empty($RsFL)){
					$Html.= "<img title='".$RsFL['dt_liberacao_faturamento']."' style='cursor: pointer;' onclick='modalRelatorio(\"".$RsFL['id_processamento']."\")' src='".$_SESSION['UrlBaseSite']."imagens/check.png' height='16' width='16' />";
				}else{
					$Html.= "<img title='Faturamento não Liberado' src='".$_SESSION['UrlBaseSite']."imagens/nocheck.png' height='16' width='16' />";
				}			
				$Html.= "</td>";
				
				
				$Html.= "</tr>";
				$Html.= "</table>";
				
				$Html.= "</td>";
			}
			
			$Html.= "</tr>";
		}
		
		$Html.= "</table>";
		
		return $Html;
																			
	}
	
	public function faturamentoLegenda()
	{
		$this->Con->conectar();
		
		$Html = "<fieldset>
					<legend  class='legenda'>Legendas</legend>
					<table width='100%' border='0' cellspacing='0' cellpadding='0' class='interna'>
						<tr>
							<td>";
		//Concluido
		$Html.= "<div style='margin:2px; /*width:100px;*/ float:left;'>
					<img src='".$_SESSION['UrlBaseSite']."imagens/check.png' height='16' width='16' /><span class='textoLegenda'>Liberado</span>
				</div>";
		
		//Nao Concluido
		$Html.= "<div style='margin:2px; /*width:100px;*/ float:left;'>
					<img src='".$_SESSION['UrlBaseSite']."imagens/nocheck.png' height='16' width='16' /><span class='textoLegenda'>Não Liberado</span>
			     </div>";
					  
					  
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
		if(!empty($Rs['dt_disponibilizacao'])){
			$Html.= "<tr>
						<td class='textoTituloGrid' style='text-align: right;'>Data Disponibilização:</td>
						<td class='textoGrid'>".$Rs['dt_disponibilizacao']."</td>
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
				
		$Html.= "</table>";
		
		return $Html;
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
	
	/*
	** Campos
	*/	
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
	
	public function getCampoConvenio()
	{
		$this->Con->conectar();
		
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
		
		return $Buffer;
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
	/*
	** Campos
	*/
}