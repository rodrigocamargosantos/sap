<?
class TipoProcessamentoSQL
{
	public function filtrarSql()
    {
        $Sql = "SELECT tp.id_tipo_processamento, tp.id_icone, ico.descricao AS icone, tp.descricao, tp.flag, tp.dt_inclusao, tp.dt_situacao,
					   CASE 
						WHEN tp.st = 1 THEN 'Ativo'
						WHEN tp.st = -1 THEN 'Inativo'
					   END AS situacao,
					   CASE 
						WHEN tp.padrao = 1 THEN 'Sim'
						WHEN tp.padrao = -1 THEN 'Não'
					   END AS padrao					
				  FROM sap.tipo_processamento AS tp
			INNER JOIN sap.icone as ico ON tp.id_icone = ico.id_icone 
				 WHERE 1 = 1";
				 
		if(!empty($_POST["descricao"]))
		{
			$Sql.= " AND tp.descricao LIKE '%".pg_escape_string($_POST["descricao"])."%'";
		}
				  
		if(!empty($_POST["padrao"]))
		{
			$Sql.= " AND tp.padrao = ".pg_escape_string($_POST["padrao"]);
		}
		
		if(!empty($_POST["st"]))
		{
			$Sql.= " AND tp.st = ".pg_escape_string($_POST["st"]);
		}
		
		$Sql.= " ORDER BY tp.id_tipo_processamento DESC;";
		
        return $Sql;
    }
    
     public function visualizarSql(){
          $VAR[] = pg_escape_string($_POST['id_tipo_processamento']);

          $Sql = "SELECT tp.id_tipo_processamento,ico.descricao AS icone, ico.id_icone, tp.descricao,
                         to_char(tp.dt_inclusao, 'DD/MM/YYYY HH24:MI')AS dt_inclusao, 
                         to_char(tp.dt_situacao, 'DD/MM/YYYY HH24:MI')AS dt_situacao,
                         CASE 
                              WHEN tp.padrao = 1 THEN 'Sim'
                              WHEN tp.padrao = -1 THEN 'Não'
                         END AS padrao,
                         CASE 
                              WHEN tp.st = 1 THEN 'Ativo'
                              WHEN tp.st = -1 THEN 'Inativo'
                         END AS situacao
                    FROM sap.tipo_processamento AS tp
	      INNER JOIN sap.icone as ico ON tp.id_icone = ico.id_icone
                   WHERE tp.id_tipo_processamento = %s;";

          return vsprintf($Sql,$VAR);
     }

    public function cadastrarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');
	
		$VAR[] = pg_escape_string($_POST['id_icone']);
		$VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
		$VAR[] = pg_escape_string($_POST['padrao']);
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		
		$Sql = "INSERT INTO sap.tipo_processamento(id_icone, descricao, padrao, st, dt_inclusao, dt_situacao)
											VALUES(%s, %s, %s, %s, %s, %s);";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function alterarSql()
    {
        //TimeZone
		date_default_timezone_set('America/Cuiaba');
	
		$VAR[] = pg_escape_string($_POST['id_icone']);
		$VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
		$VAR[] = pg_escape_string($_POST['padrao']);
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
		
		$Sql = "UPDATE sap.tipo_processamento
				   SET id_icone = %s,
					   descricao = %s,
					   padrao = %s,
					   st = %s,
				       dt_situacao = %s
				 WHERE id_tipo_processamento = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function deletarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');	
		
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
		
		$Sql = "UPDATE sap.tipo_processamento
				   SET st = -1,
				       dt_situacao = %s
				 WHERE id_tipo_processamento = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function getDadosSql()
    {
		$VAR[] = pg_escape_string($_POST['id_tipo_processamento']);
		
        $Sql = "SELECT tp.id_tipo_processamento, tp.id_icone, tp.descricao, tp.padrao, tp.st
				  FROM sap.tipo_processamento AS tp
			     WHERE tp.id_tipo_processamento = %s;";
				 
        return vsprintf($Sql,$VAR);
    }
	
	public function getIconeSql()
    {
        $Sql = "SELECT id_icone, descricao as icone
			      FROM sap.icone
				 WHERE st > 0				  	   
			  ORDER BY icone ASC;";
			  
        return $Sql;
    }
}