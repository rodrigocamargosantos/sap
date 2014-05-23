<?
class IconeSQL
{
	public function filtrarSql()
    {
        $Sql = "SELECT ico.id_icone, ico.icone, ico.descricao, ico.extensao, ico.flag, ico.dt_inclusao, ico.dt_situacao,
					   CASE 
						WHEN ico.st = 1 THEN 'Ativo'
						WHEN ico.st = -1 THEN 'Inativo'
					   END AS situacao
				  FROM sap.icone AS ico
			     WHERE 1 = 1";
		
		if(!empty($_POST["descricao"]))
		{
			$Sql.= " AND ico.descricao LIKE '%".pg_escape_string($_POST["descricao"])."%'";
		}
		
		if(!empty($_POST["st"]))
		{
			$Sql.= " AND ico.st = ".pg_escape_string($_POST["st"]);
		}
		
		$Sql.= " ORDER BY ico.id_icone DESC;";
		
        return $Sql;
    }

    public function cadastrarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');
		
		$VAR[] = "'".pg_escape_string($_POST['icone'])."'";
		$VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
		$VAR[] = "'".pg_escape_string($_POST['extensao'])."'";
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		
		$Sql = "INSERT INTO sap.icone(icone, descricao, extensao, st, dt_inclusao, dt_situacao)
								VALUES (%s, %s, %s, %s, %s, %s);";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function alterarComImgSql()
    {
        //TimeZone
		date_default_timezone_set('America/Cuiaba');
	
		$VAR[] = "'".pg_escape_string($_POST['icone'])."'";
		$VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
		$VAR[] = "'".pg_escape_string($_POST['extensao'])."'";
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_icone']);
		
		$Sql = "UPDATE sap.icone
				   SET icone = %s,
					   descricao = %s,
					   extensao = %s,
					   st = %s,
				       dt_situacao = %s
				 WHERE id_icone = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function alterarSemImgSql()
    {
        //TimeZone
		date_default_timezone_set('America/Cuiaba');
	
		$VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
		$VAR[] = pg_escape_string($_POST['st']);
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_icone']);
		
		$Sql = "UPDATE sap.icone
				   SET descricao = %s,
					   st = %s,
				       dt_situacao = %s
				 WHERE id_icone = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function deletarSql()
    {
		//TimeZone
		date_default_timezone_set('America/Cuiaba');	
		
		$VAR[] = "'".date('Y-m-d H:i:s')."'";
		$VAR[] = pg_escape_string($_POST['id_icone']);
		
		$Sql = "UPDATE sap.icone
				   SET st = -1,
				       dt_situacao = %s
				 WHERE id_icone = %s;";
								  
        return vsprintf($Sql,$VAR);
    }
	
	public function getDadosSql()
    {
		$VAR[] = pg_escape_string($_POST['id_icone']);
		
		$Sql = "SELECT ico.id_icone, ico.icone, ico.descricao, ico.extensao, ico.flag, ico.dt_inclusao, ico.dt_situacao, ico.st
				  FROM sap.icone AS ico
			     WHERE ico.id_icone = %s;";
				 
        return vsprintf($Sql,$VAR);
    }
}