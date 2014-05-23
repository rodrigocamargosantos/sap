<?
class FaturamentoSQL
{
	public function faturamentoConvenioSql()
	{
		$Sql = "SELECT DISTINCT pro.id_convenio, con.sigla_convenio AS sigla, con.descricao AS convenio
				  FROM sap.processamento AS pro
			INNER JOIN sap.convenio AS con ON con.id_convenio = pro.id_convenio
			INNER JOIN sap.competencia AS comp ON comp.id_competencia = pro.id_competencia
				 WHERE pro.st > 0
                                   AND comp.ano || LPAD(TRIM(to_char(comp.mes,'00')), 2,'0') BETWEEN ".$_POST['AnoMesInicio']." AND ".$_POST['AnoMesFim']."
				   AND pro.id_tipo_processamento = ".pg_escape_string($_POST["id_tipo_processamento"]);
				 
		if(!empty($_POST["id_unidade_federativa"]))
		{
			$Sql.= " AND con.id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa"]);
		}
		
		if(!empty($_POST["id_convenio"]))
		{
			$Sql.= " AND pro.id_convenio = ".pg_escape_string($_POST["id_convenio"]);
		}
		
		if(!empty($_POST["id_status"]))
		{
			$Sql.= " AND pro.id_status = ".pg_escape_string($_POST["id_status"]);
		}
		
		if(!empty($_POST["liberado_faturamento"]))
		{
			$Sql.= " AND pro.liberado_faturamento = ".pg_escape_string($_POST["liberado_faturamento"]);
		}
		
		$Sql.= " ORDER BY sigla ASC;";
		
		return $Sql;
	}
	
	public function faturamentoCompetenciaSql()
	{
            $Sql = "SELECT id_competencia, ano, mes, st, flag, dt_inclusao, dt_situacao
                      FROM sap.competencia
                     WHERE st > 0
                       AND ano || LPAD(TRIM(to_char(mes,'00')), 2,'0') BETWEEN ".$_POST['AnoMesInicio']." AND ".$_POST['AnoMesFim'].";";

            return $Sql;
	}
	
	public function faturamentoLiberadoSql($IdConvenio, $IdCompetencia)
    {
        $Sql = "SELECT pro.id_processamento, to_char(pro.dt_liberacao_faturamento, 'DD/MM/YYYY HH24:MI')AS dt_liberacao_faturamento
				  FROM sap.processamento AS pro
				 WHERE pro.id_convenio = $IdConvenio
				   AND pro.id_competencia = $IdCompetencia
				   AND pro.liberado_faturamento > 0
				   AND pro.id_tipo_processamento = ".pg_escape_string($_POST["id_tipo_processamento"]);
			  
        return $Sql;
    }	
	
	/*
	** Modal
	*/
	public function tituloModalSql()
	{
		$VAR[] = pg_escape_string($_POST['id_processamento']);
	
		$Sql = "SELECT con.sigla_convenio || ' - ' || tp.descricao || ' - ' || LPAD(com.mes || '/' || com.ano, 7, '0') || ' - ' || sta.nome_status AS titulo
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
					   con.sigla_convenio || ' - ' || con.descricao AS convenio, 
					   LPAD(com.mes || '/' || com.ano, 7, '0')AS competencia, 
					   tp.descricao AS tipo_processamento, 
					   sta.nome_status AS status, 
					   
					   pro.id_usuario_processamento, pro.id_usuario_conferencia,
					   to_char(pro.dt_disponibilizacao, 'DD/MM/YYYY')AS dt_disponibilizacao, 
					   to_char(pro.dt_liberacao_faturamento, 'DD/MM/YYYY HH24:MI')AS dt_liberacao_faturamento,
					   CASE 
						WHEN pro.liberado_faturamento = 1 THEN 'Sim'
						WHEN pro.liberado_faturamento = -1 THEN 'Não'
					   END AS liberado_faturamento
					   
				  FROM sap.processamento AS pro
			INNER JOIN sap.competencia AS com ON com.id_competencia = pro.id_competencia
			INNER JOIN sap.convenio AS con ON con.id_convenio = pro.id_convenio
			INNER JOIN sap.tipo_processamento AS tp ON tp.id_tipo_processamento = pro.id_tipo_processamento
			INNER JOIN sap.status AS sta ON sta.id_status = pro.id_status
				 WHERE pro.id_processamento = %s;";
				 
        return vsprintf($Sql,$VAR);
    }
	/*
	** Modal
	*/

    public function getMesAnoIdCompetencia()
    {
        $Sql = "SELECT ano, mes
                  FROM sap.competencia
                 WHERE st > 0
                   AND id_competencia = ".pg_escape_string($_POST["id_competencia"]);

         return $Sql;
    }




    /*
	** Campos
	*/	
	public function getUnidadeFederativaSql()
    {
        $Sql = "SELECT id_unidade_federativa, descricao AS unidade_federativa
			      FROM sap.unidade_federativa
				 WHERE st > 0				  	   
			  ORDER BY unidade_federativa ASC;";
			  
        return $Sql;
    }
	
	public function getConvenioSql()
    {
		$Sql = "SELECT id_convenio, sigla_convenio || ' - ' || descricao AS convenio
			      FROM sap.convenio
				 WHERE st > 0";	
			  
		if(!empty($_POST["id_unidade_federativa"]))
		{
			$Sql.= " AND id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa"]);
		}
		
		$Sql.= " ORDER BY convenio ASC;";
			  
        return $Sql;
    }
	
	public function getCompetenciaSql()
	{
		 $Sql = "SELECT id_competencia, LPAD(mes || '/' || ano, 7, '0')AS competencia
			      FROM sap.competencia
				 WHERE st > 0				  	   
			  ORDER BY ano ASC, mes ASC;";
			  
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
	/*
	** Campos
	*/	
}