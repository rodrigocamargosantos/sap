<?
class ProcessamentoSQL
{
     public function filtrarSql()
     {
          $Sql = "SELECT pro.id_processamento, LPAD(com.mes || '/' || com.ano, 7, '0')AS competencia, con.sigla_convenio || ' - ' || con.descricao AS convenio, 
                         tp.descricao AS tipo_processamento, sta.nome_status AS status,
                         CASE 
                           WHEN pro.liberado_faturamento = 1 THEN 'Sim'
                           WHEN pro.liberado_faturamento = -1 THEN 'Não'
                           WHEN pro.liberado_faturamento IS NULL THEN '-'
                         END AS liberado_faturamento,
                         CASE 
                           WHEN pro.st = 1 THEN 'Ativo'
                           WHEN pro.st = -1 THEN 'Inativo'
                         END AS situacao
                    FROM sap.processamento AS pro
              INNER JOIN sap.competencia as com ON com.id_competencia = pro.id_competencia
              INNER JOIN sap.convenio as con ON con.id_convenio = pro.id_convenio
              INNER JOIN sap.tipo_processamento as tp ON tp.id_tipo_processamento = pro.id_tipo_processamento
              INNER JOIN sap.status as sta ON sta.id_status = pro.id_status
                   WHERE 1 = 1";
				 
          if(!empty($_POST["id_unidade_federativa_filtro"]))
          {
               $Sql.= " AND con.id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa_filtro"]);
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

          if(!empty($_POST["id_tipo_processamento_filtro"]))
          {
               $Sql.= " AND tp.id_tipo_processamento = ".pg_escape_string($_POST["id_tipo_processamento_filtro"]);
          }

          if(!empty($_POST["liberado_faturamento"]))
          {
               $Sql.= " AND pro.liberado_faturamento = ".pg_escape_string($_POST["liberado_faturamento"]);
          }

          if(!empty($_POST["st"]))
          {
               $Sql.= " AND pro.st = ".pg_escape_string($_POST["st"]);
          }

          $Sql.= " ORDER BY com.ano ASC, com.mes ASC, con.sigla_convenio ASC;";
		
        return $Sql;
    }

    public function cadastrarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');
		
		$VAR[] = pg_escape_string($_POST['id_convenio']);
		$VAR[] = pg_escape_string($_POST['id_competencia']);
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
		$VAR[] = pg_escape_string($_POST['id_status']);
		
		$VAR[] = $_POST['dt_previsao_recebimento_arquivo'];
		$VAR[] = $_POST['dt_recebimento_arquivo'];
		$VAR[] = $_POST['dt_previsao_processamento'];
		$VAR[] = $_POST['dt_processamento'];
		$VAR[] = $_POST['dt_previsao_disponibilizacao'];
		$VAR[] = $_POST['dt_disponibilizacao'];
		$VAR[] = $_POST['dt_liberacao_faturamento'];
		
		$VAR[] = $_POST['tempo_estimado'];
		$VAR[] = $_POST['tempo_gasto'];
		
		$VAR[] = pg_escape_string($_POST['liberado_faturamento']);
		$VAR[] = "'".pg_escape_string($_POST['observacao'])."'";
		
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		
		$Sql = "INSERT INTO sap.processamento(id_convenio, id_competencia, id_tipo_processamento, id_status, dt_previsao_recebimento_arquivo, dt_recebimento_arquivo, dt_previsao_processamento, dt_processamento, dt_previsao_disponibilizacao, 
											  dt_disponibilizacao, dt_liberacao_faturamento, tempo_estimado, tempo_gasto, liberado_faturamento, observacao,st, dt_inclusao, dt_situacao)
									   VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);";
								  
        return  vsprintf($Sql,$VAR);
    }
	
     public function alterarSql(){		
          $VAR[] = pg_escape_string($_POST['id_convenio']);
          $VAR[] = pg_escape_string($_POST['id_competencia']);
          $VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
          $VAR[] = pg_escape_string($_POST['id_status']);

          $VAR[] = $_POST['dt_previsao_recebimento_arquivo'];
          $VAR[] = $_POST['dt_recebimento_arquivo'];
          $VAR[] = $_POST['dt_previsao_processamento'];
          $VAR[] = $_POST['dt_processamento'];
          $VAR[] = $_POST['dt_previsao_disponibilizacao'];
          $VAR[] = $_POST['dt_disponibilizacao'];
          $VAR[] = $_POST['dt_liberacao_faturamento'];

          $VAR[] = $_POST['tempo_estimado'];
          $VAR[] = $_POST['tempo_gasto'];

          $VAR[] = pg_escape_string($_POST['liberado_faturamento']);
          $VAR[] = "'".pg_escape_string($_POST['observacao'])."'";

          $VAR[] = pg_escape_string($_POST['st']);
          $VAR[] = "'".date('Y-m-d H:i:s')."'";
          $VAR[] = pg_escape_string($_POST['id_processamento']);
		
		
          $Sql = "UPDATE sap.processamento
                             SET id_convenio = %s,
                                     id_competencia = %s,
                                     id_tipo_processamento = %s,
                                     id_status = %s,

                                     dt_previsao_recebimento_arquivo = %s,
                                     dt_recebimento_arquivo = %s,
                                     dt_previsao_processamento = %s,
                                     dt_processamento = %s,
                                     dt_previsao_disponibilizacao = %s,
                                     dt_disponibilizacao = %s,
                                     dt_liberacao_faturamento = %s,

                                     tempo_estimado = %s,
                                     tempo_gasto = %s,

                                     liberado_faturamento = %s,
                                     observacao = %s,

                                     st = %s,
                                 dt_situacao = %s
                           WHERE id_processamento = %s;";
								  
          return vsprintf($Sql,$VAR);
     }
	
     public function deletarSql(){
          $VAR[] = "'".date('Y-m-d H:i:s')."'";
          $VAR[] = pg_escape_string($_POST['id_processamento']);

          $Sql = "UPDATE sap.processamento
                     SET st = -1,
                         dt_situacao = %s
                   WHERE id_processamento = %s;";
								  
          return vsprintf($Sql,$VAR);
     }
	
     public function getDadosSql(){
          $VAR[] = pg_escape_string($_POST['id_processamento']);
		
          $Sql = "SELECT pro.id_processamento, con.id_unidade_federativa, pro.id_convenio, pro.id_competencia, pro.id_tipo_processamento, 
                                     pro.id_status, pro.id_usuario_processamento, pro.id_usuario_conferencia, 
                                     to_char(pro.dt_previsao_recebimento_arquivo, 'DD/MM/YYYY HH24:MI')AS dt_previsao_recebimento_arquivo, 
                                     to_char(pro.dt_recebimento_arquivo, 'DD/MM/YYYY HH24:MI')AS dt_recebimento_arquivo, 
                                     to_char(pro.dt_previsao_processamento, 'DD/MM/YYYY HH24:MI')AS dt_previsao_processamento, 
                                     to_char(pro.dt_processamento, 'DD/MM/YYYY HH24:MI')AS dt_processamento, 
                                     to_char(pro.dt_previsao_disponibilizacao, 'DD/MM/YYYY HH24:MI')AS dt_previsao_disponibilizacao, 
                                     to_char(pro.dt_disponibilizacao, 'DD/MM/YYYY HH24:MI')AS dt_disponibilizacao, 
                                     to_char(pro.dt_liberacao_faturamento, 'DD/MM/YYYY HH24:MI')AS dt_liberacao_faturamento,
                                     to_char(pro.tempo_estimado, 'HH24:MI')AS tempo_estimado,
                                     to_char(pro.tempo_gasto, 'HH24:MI')AS tempo_gasto,
                                     pro.liberado_faturamento, pro.observacao, 
                                     pro.st, pro.flag, pro.dt_inclusao, pro.dt_situacao
                            FROM sap.processamento AS pro
                  INNER JOIN sap.convenio AS con ON pro.id_convenio = con.id_convenio
                           WHERE pro.id_processamento = %s;";
				 
          return vsprintf($Sql,$VAR);
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
     public function getConvenioFiltroSql()
     {
          $Sql = "SELECT id_convenio, sigla_convenio || ' - ' || descricao AS convenio
                    FROM sap.convenio
                   WHERE st > 0";	

          if(!empty($_POST["id_unidade_federativa_filtro"]))
          {
               $Sql.= " AND id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa_filtro"]);
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
	
	public function getCompetenciaIntervalSql()
	{
		 $Sql = "SELECT id_competencia, LPAD(mes || '/' || ano, 7, '0')AS competencia
				  FROM sap.competencia
				 WHERE CONCAT(ano, '-',LPAD(TRIM(to_char(mes, '99')),2,'0'), '-01') BETWEEN to_char(date(current_date - interval '2 month'), 'YYYY-MM-01') AND to_char(date(current_date + interval '2 month'), 'YYYY-MM-01')
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
	
	public function validarUnicoProcessamentoSql()
    {
		$VAR[] = pg_escape_string($_POST['id_convenio']);
		$VAR[] = pg_escape_string($_POST['id_competencia']);
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
		
        $Sql = " SELECT DISTINCT 1
				  FROM sap.processamento
				 WHERE id_convenio = %s
				   AND id_competencia = %s
				   AND id_tipo_processamento = %s
				   AND st > 0";
				   
		if(!empty($_POST["id_processamento"]))
		{
			$VAR[] = pg_escape_string($_POST['id_processamento']);
			
			$Sql.= " AND id_processamento NOT IN(%s)";
		}
			  
        return vsprintf($Sql,$VAR);
    }
	
	public function getMaxCompetenciaSql()
	{
		$VAR[] = pg_escape_string($_POST['id_convenio']);
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
	
		$Sql = "SELECT com.ano, com.mes
				  FROM sap.processamento AS pro
			INNER JOIN sap.competencia as com ON com.id_competencia = pro.id_competencia
				 WHERE pro.id_convenio = %s
				   AND pro.id_tipo_processamento = %s
				   AND pro.st > 0
			  ORDER BY com.ano DESC, com.mes DESC
				 LIMIT 1;";
				 
		return vsprintf($Sql,$VAR);
	}
	
	public function getIdCompetenciaSql()
	{
		$VAR[] = pg_escape_string($_POST['mes']);
		$VAR[] = pg_escape_string($_POST['ano']);
		
	
		 $Sql = "SELECT id_competencia
			      FROM sap.competencia
				 WHERE st > 0
				   AND mes = %s
				   AND ano = %s;";
			  
        return vsprintf($Sql,$VAR);		 
	}
}