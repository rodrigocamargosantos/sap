<?
class StatusSQL
{
	public function filtrarSql()
    {
        $Sql = "SELECT st.id_status, st.id_icone, ico.descricao AS icone, st.nome_status, st.descricao_status, st.flag, st.dt_inclusao, st.dt_situacao,
					   CASE 
						WHEN st.st = 1 THEN 'Ativo'
						WHEN st.st = -1 THEN 'Inativo'
					   END AS situacao
				  FROM sap.status AS st
			INNER JOIN sap.icone as ico ON st.id_icone = ico.id_icone 
				 WHERE 1 = 1";
				 
		if(!empty($_POST["nome_status"]))
		{
			$Sql.= " AND st.nome_status ILIKE '%".pg_escape_string($_POST["nome_status"])."%'";
		}
		
		if(!empty($_POST["st"]))
		{
			$Sql.= " AND st.st = ".pg_escape_string($_POST["st"]);
		}
		
		$Sql.= " ORDER BY st.id_status DESC;";
		
        return $Sql;
    }

    public function cadastrarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');
	
		$VAR[] = pg_escape_string($_POST['id_icone']);
		$VAR[] = "'".pg_escape_string($_POST['nome_status'])."'";
		$VAR[] = "'".pg_escape_string($_POST['descricao_status'])."'";
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		
		$Sql = "INSERT INTO sap.status(id_icone, nome_status, descricao_status, st, dt_inclusao, dt_situacao)
								VALUES(%s, %s, %s, %s, %s, %s);";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function alterarSql()
    {
        //TimeZone
		date_default_timezone_set('America/Cuiaba');
		
		$VAR[] = pg_escape_string($_POST['id_icone']);
		$VAR[] = "'".pg_escape_string($_POST['nome_status'])."'";
		$VAR[] = "'".pg_escape_string($_POST['descricao_status'])."'";
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_status']);
		
		$Sql = "UPDATE sap.status
				   SET id_icone = %s,
					   nome_status = %s,
					   descricao_status = %s,
					   st = %s,
				       dt_situacao = %s
				 WHERE id_status = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function deletarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');	
		
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_status']);
		
		$Sql = "UPDATE sap.status
				   SET st = -1,
				       dt_situacao = %s
				 WHERE id_status = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function getDadosSql()
    {
		$VAR[] = pg_escape_string($_POST['id_status']);
		
        $Sql = "SELECT st.id_status, st.id_icone, st.nome_status, st.descricao_status, st.st
				  FROM sap.status AS st
			     WHERE st.id_status = %s;";
				 
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
	
	public function validarUnicoStatusSql()
    {
		$VAR[] = pg_escape_string($_POST['id_icone']);
		$VAR[] = "'".pg_escape_string($_POST['nome_status'])."'";
		
        $Sql = " SELECT DISTINCT 1
				  FROM sap.status
				 WHERE id_icone = %s
				   AND nome_status = %s
				   AND st > 0";
				   
		if(!empty($_POST["id_status"]))
		{
			$VAR[] = pg_escape_string($_POST['id_status']);
			
			$Sql.= " AND id_status NOT IN(%s)";
		}
			  
        return vsprintf($Sql,$VAR);
    }
}