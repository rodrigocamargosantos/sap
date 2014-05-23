<?
class RelatorioSQL
{
	public function relatorioConvenioSql()
	{
		$Sql = "SELECT DISTINCT pro.id_convenio, con.sigla_convenio AS convenio
				  FROM sap.processamento AS pro
			INNER JOIN sap.convenio AS con ON con.id_convenio = pro.id_convenio
				 WHERE 1 = 1";
				 
		if(!empty($_POST["id_unidade_federativa"]))
		{
			$Sql.= " AND con.id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa"]);
		}
		
		if(!empty($_POST["id_competencia"]))
		{
			$Sql.= " AND pro.id_competencia = ".pg_escape_string($_POST["id_competencia"]);
		}
		
		if(!empty($_POST["id_convenio"]))
		{
			$Sql.= " AND pro.id_convenio = ".pg_escape_string($_POST["id_convenio"]);
		}
	
		$Sql.= " ORDER BY convenio ASC;";
		
		return $Sql;
	}
	
	public function relatorioCompetenciaSql()
	{
		//TimeZone
		date_default_timezone_set('America/Cuiaba');

		$MKInicio	= mktime(0, 0, 0, date('n')-1, date('d'), date('Y'));
		$MKFim		= mktime(0, 0, 0, date('n')+1, date('d'), date('Y'));

		$AnoInicio	= date("Y", $MKInicio);
		$AnoFim		= date("Y", $MKFim);
		
		$MesInicio	= date("n", $MKInicio);		
		$MesFim		= date("n", $MKFim);
	
		$Sql = "SELECT id_competencia, ano, mes, st, flag, dt_inclusao, dt_situacao
				  FROM sap.competencia
				 WHERE ano >= $AnoInicio
				   AND ano <= $AnoFim
				   AND mes >= $MesInicio
				   AND mes <= $MesFim
				   AND st > 0;";
		
		return $Sql;
	}
	
	public function relatorioTipoProcessamentoSql()
    {
        $Sql = "SELECT id_tipo_processamento, id_icone, descricao as tipo_processamento
			      FROM sap.tipo_processamento
				 WHERE st > 0
				   AND padrao > 0;";
			  
        return $Sql;
    }
	
	public function relatorioStatusSql($IdConvenio, $IdCompetencia, $IdTipoProcessamento)
    {
        $Sql = "SELECT pro.id_processamento, sta.id_status, sta.id_icone
				  FROM sap.processamento AS pro
			INNER JOIN sap.status as sta ON sta.id_status = pro.id_status
				 WHERE pro.id_convenio = $IdConvenio
				   AND pro.id_competencia = $IdCompetencia
				   AND pro.id_tipo_processamento = $IdTipoProcessamento;";
			  
        return $Sql;
    }
	
	public function tituloModalSql()
	{
		$VAR[] = pg_escape_string($_POST['id_processamento']);
	
		$Sql = "SELECT CONCAT(con.sigla_convenio, ' - ', tp.descricao, ' - ', LPAD(trim(to_char(com.mes, '99')), 2, '0'), '/', com.ano, ' - ', sta.nome_status)AS titulo
				  FROM sap.processamento AS pro
			INNER JOIN sap.competencia AS com ON com.id_competencia = pro.id_competencia
			INNER JOIN sap.convenio AS con ON con.id_convenio = pro.id_convenio
			INNER JOIN sap.tipo_processamento AS tp ON tp.id_tipo_processamento = pro.id_tipo_processamento
			INNER JOIN sap.status AS sta ON sta.id_status = pro.id_status
				 WHERE pro.id_processamento = %s;";
				 
		return vsprintf($Sql,$VAR);
	}
	
	public function conteudoModalSql()
    {
		$VAR[] = pg_escape_string($_POST['id_processamento']);
		
		$Sql = "SELECT pro.id_processamento, 
					   CONCAT(con.sigla_convenio, ' - ', con.descricao)AS convenio, 
					   LPAD(CONCAT(com.mes, '/', com.ano), 7, '0')AS competencia, 
					   tp.descricao AS tipo_processamento, 
					   sta.nome_status AS status, 
					   
					   pro.id_usuario_processamento, pro.id_usuario_conferencia, 
					   to_char(pro.dt_recebimento_arquivo, 'DD/MM/YYYY')AS dt_recebimento_arquivo, 
					   to_char(pro.dt_previsao_processamento, 'DD/MM/YYYY')AS dt_previsao_processamento, 
					   to_char(pro.dt_processamento, 'DD/MM/YYYY')AS dt_processamento, 
					   to_char(pro.dt_previsao_disponibilizacao, 'DD/MM/YYYY')AS dt_previsao_disponibilizacao, 
					   to_char(pro.dt_disponibilizacao, 'DD/MM/YYYY')AS dt_disponibilizacao, 
					   to_char(pro.dt_liberacao_faturamento, 'DD/MM/YYYY')AS dt_liberacao_faturamento, 
					   pro.tempo_estimado, pro.tempo_gasto, pro.observacao, 
					   pro.flag, pro.dt_inclusao, pro.dt_situacao,
					   CASE 
						WHEN pro.liberado_faturamento = 1 THEN 'Sim'
						WHEN pro.liberado_faturamento = -1 THEN 'Não'
					   END liberado_faturamento,
					   CASE 
						WHEN pro.st = 1 THEN 'Ativo'
						WHEN pro.st = -1 THEN 'Inativo'
					   END situacao
					   
				  FROM sap.processamento AS pro
			INNER JOIN sap.competencia AS com ON com.id_competencia = pro.id_competencia
			INNER JOIN sap.convenio AS con ON con.id_convenio = pro.id_convenio
			INNER JOIN sap.tipo_processamento AS tp ON tp.id_tipo_processamento = pro.id_tipo_processamento
			INNER JOIN sap.status AS sta ON sta.id_status = pro.id_status
				 WHERE pro.id_processamento = %s;";
				 
        return vsprintf($Sql,$VAR);
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	public function filtrarSql()
    {
        $Sql = "SELECT pro.id_processamento, LPAD(CONCAT(mes, '/', ano), 7, '0')AS competencia, CONCAT(con.sigla_convenio, ' - ', con.descricao)AS convenio, 
					   tp.descricao AS tipo_processamento, sta.nome_status AS status,
					   CASE 
						WHEN pro.liberado_faturamento = 1 THEN 'Sim'
						WHEN pro.liberado_faturamento = -1 THEN 'Não'
					   END liberado_faturamento,
					   CASE 
						WHEN pro.st = 1 THEN 'Ativo'
						WHEN pro.st = -1 THEN 'Inativo'
					   END situacao
				  FROM sap.processamento AS pro
			INNER JOIN sap.competencia as com ON com.id_competencia = pro.id_competencia
			INNER JOIN sap.convenio as con ON con.id_convenio = pro.id_convenio
			INNER JOIN sap.tipo_processamento as tp ON tp.id_tipo_processamento = pro.id_tipo_processamento
			INNER JOIN sap.status as sta ON sta.id_status = pro.id_status
				 WHERE 1 = 1";
				 
		if(!empty($_POST["id_unidade_federativa"]))
		{
			$Sql.= " AND con.id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa"]);
		}
		
		if(!empty($_POST["id_competencia"]))
		{
			$Sql.= " AND com.id_competencia = ".pg_escape_string($_POST["id_competencia"]);
		}
		
		if(!empty($_POST["id_convenio"]))
		{
			$Sql.= " AND con.id_convenio = ".pg_escape_string($_POST["id_convenio"]);
		}
		
		if(!empty($_POST["id_status"]))
		{
			$Sql.= " AND sta.id_status = ".pg_escape_string($_POST["id_status"]);
		}
		
		if(!empty($_POST["id_tipo_processamento"]))
		{
			$Sql.= " AND tp.id_tipo_processamento = ".pg_escape_string($_POST["id_tipo_processamento"]);
		}
		
		if(!empty($_POST["liberado_faturamento"]))
		{
			$Sql.= " AND pro.liberado_faturamento = ".pg_escape_string($_POST["liberado_faturamento"]);
		}
		
		if(!empty($_POST["st"]))
		{
			$Sql.= " AND pro.st = ".pg_escape_string($_POST["st"]);
		}
		
		$Sql.= " ORDER BY pro.id_processamento DESC;";
		
        return $Sql;
    }
	
	public function getLegendasTipoProcessamentoSql()
	{
		$Sql = "SELECT tp.id_tipo_processamento, tp.id_icone, tp.descricao,
					   ico.icone, ico.extensao
				  FROM sap.tipo_processamento AS tp
			INNER JOIN sap.icone AS ico ON tp.id_icone = ico.id_icone
				 WHERE tp.st = 1
				   AND tp.padrao = 1
				   AND ico.st = 1;";
		
		return $Sql;
	}
	
	public function getLegendasStatusSql()
	{
		$Sql = "SELECT sta.id_status, sta.id_icone, sta.nome_status,
					   ico.icone, ico.extensao
				  FROM sap.status AS sta
			INNER JOIN sap.icone AS ico ON sta.id_icone = ico.id_icone
				 WHERE sta.st = 1
				   AND ico.st = 1;";
		
		return $Sql;
	}
	
	
	public function getDaassfdosSql()
    {
		$VAR[] = pg_escape_string($_POST['id_processamento']);
		
		$Sql = "SELECT id_processamento, id_convenio, id_competencia, id_tipo_processamento, 
					   id_status, id_usuario_processamento, id_usuario_conferencia, 
					   to_char(dt_recebimento_arquivo, 'DD/MM/YYYY')AS dt_recebimento_arquivo, 
					   to_char(dt_previsao_processamento, 'DD/MM/YYYY')AS dt_previsao_processamento, 
					   to_char(dt_processamento, 'DD/MM/YYYY')AS dt_processamento, 
					   to_char(dt_previsao_disponibilizacao, 'DD/MM/YYYY')AS dt_previsao_disponibilizacao, 
					   to_char(dt_disponibilizacao, 'DD/MM/YYYY')AS dt_disponibilizacao, 
					   to_char(dt_liberacao_faturamento, 'DD/MM/YYYY')AS dt_liberacao_faturamento, 
					   tempo_estimado, tempo_gasto, liberado_faturamento, observacao, 
					   st, flag, dt_inclusao, dt_situacao
				  FROM sap.processamento
				 WHERE id_processamento = %s;";
				 
        return vsprintf($Sql,$VAR);
    }
	
	public function getConvenioSql()
    {
        $Sql = "SELECT id_convenio, CONCAT(sigla_convenio, ' - ', descricao)AS convenio
			      FROM sap.convenio
				 WHERE st > 0				  	   
			  ORDER BY convenio ASC;";
			  
        return $Sql;
    }
	public function getCompetenciaSql()
	{
		 $Sql = "SELECT id_competencia, LPAD(CONCAT(mes, '/', ano), 7, '0')AS competencia
			      FROM sap.competencia
				 WHERE st > 0				  	   
			  ORDER BY competencia ASC;";
			  
        return $Sql;
		 
	}
	
	public function getCompetenciaAtualSql()
	{
		 $Sql = "SELECT id_competencia
			      FROM sap.competencia
				 WHERE st > 0
				   AND mes = date_part('month', current_date)
				   AND ano = date_part('year', current_date);";
			  
        return $Sql;
		 
	}
	
	public function getTipoProcessamentoSql()
    {
        $Sql = "SELECT id_tipo_processamento, descricao AS tipo_processamento
			      FROM sap.tipo_processamento
				 WHERE st > 0				  	   
			  ORDER BY tipo_processamento ASC;";
			  
        return $Sql;
    }
	
	public function getStatusSql()
    {
        $Sql = "SELECT id_status, nome_status AS status
			      FROM sap.status
				 WHERE st > 0				  	   
			  ORDER BY status ASC;";
			  
        return $Sql;
    }
	
	public function getUnidadeFederativaSql()
    {
        $Sql = "SELECT id_unidade_federativa, descricao AS unidade_federativa
			      FROM sap.unidade_federativa
				 WHERE st > 0				  	   
			  ORDER BY unidade_federativa ASC;";
			  
        return $Sql;
    }
}