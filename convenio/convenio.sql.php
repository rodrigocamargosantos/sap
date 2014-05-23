<?
class ConvenioSQL
{
     public function filtrarSql(){
          $Sql = "SELECT conv.id_convenio, conv.id_unidade_federativa, conv.sigla_convenio, conv.descricao, conv.st, conv.flag, conv.dt_inclusao, conv.dt_situacao,
					   uf.descricao AS unidade_federativa,
					   CASE 
						WHEN conv.operando = 1 THEN 'Sim'
						WHEN conv.operando = -1 THEN 'Não'
					   END AS operando,
					   CASE 
						WHEN conv.st = 1 THEN 'Ativo'
						WHEN conv.st = -1 THEN 'Inativo'
					   END AS situacao
				  FROM sap.convenio AS conv
			INNER JOIN sap.unidade_federativa AS uf ON conv.id_unidade_federativa = uf.id_unidade_federativa
			     WHERE 1 = 1";
				  
		if(!empty($_POST["id_unidade_federativa"]))
		{
			$Sql.= " AND uf.id_unidade_federativa = ".pg_escape_string($_POST["id_unidade_federativa"]);
		}
		
		if(!empty($_POST["sigla_convenio"]))
		{
			$Sql.= " AND conv.sigla_convenio LIKE '%".pg_escape_string($_POST["sigla_convenio"])."%'";
		}
		
		if(!empty($_POST["operando"]))
		{
			$Sql.= " AND conv.operando = ".pg_escape_string($_POST["operando"]);
		}
		
		if(!empty($_POST["st"]))
		{
			$Sql.= " AND conv.st = ".pg_escape_string($_POST["st"]);
		}
		
		$Sql.= " ORDER BY unidade_federativa ASC;";
		
          return $Sql;
     }
     
     public function visualizarSql(){
          $VAR[] = pg_escape_string($_POST['id_convenio']);

          $Sql = "SELECT conv.id_convenio, uf.descricao AS unidade_federativa, conv.sigla_convenio, conv.descricao,
                         to_char(conv.dt_inclusao, 'DD/MM/YYYY HH24:MI')AS dt_inclusao, 
                         to_char(conv.dt_situacao, 'DD/MM/YYYY HH24:MI')AS dt_situacao,
                         CASE 
                              WHEN conv.operando = 1 THEN 'Sim'
                              WHEN conv.operando = -1 THEN 'Não'
                         END AS operando,
                         CASE 
                              WHEN conv.st = 1 THEN 'Ativo'
                              WHEN conv.st = -1 THEN 'Inativo'
                         END AS situacao
                    FROM sap.convenio AS conv
              INNER JOIN sap.unidade_federativa AS uf ON conv.id_unidade_federativa = uf.id_unidade_federativa
                   WHERE conv.id_convenio = %s;";

          return vsprintf($Sql,$VAR);
     }

    public function cadastrarSql(){
          //TimeZone
          date_default_timezone_set('America/Cuiaba');

          $VAR[] = pg_escape_string($_POST['id_unidade_federativa']);
          $VAR[] = "'".pg_escape_string($_POST['sigla_convenio'])."'";
          $VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
          $VAR[] = pg_escape_string($_POST['operando']);
          $VAR[] = pg_escape_string($_POST['st']);
          $VAR[] = "'".date('Y-m-d H:i:s')."'";
          $VAR[] = "'".date('Y-m-d H:i:s')."'";

          $Sql = "INSERT INTO sap.convenio(id_unidade_federativa, sigla_convenio, descricao, operando, st, dt_inclusao, dt_situacao)
                                                            VALUES(%s, %s, %s, %s, %s, %s, %s)";

          return vsprintf($Sql,$VAR);
    }
	
     public function alterarSql(){
          //TimeZone
          date_default_timezone_set('America/Cuiaba');
	
          $VAR[] = pg_escape_string($_POST['id_unidade_federativa']);
          $VAR[] = "'".pg_escape_string($_POST['sigla_convenio'])."'";
          $VAR[] = "'".pg_escape_string($_POST['descricao'])."'";
          $VAR[] = pg_escape_string($_POST['operando']);
          $VAR[] = pg_escape_string($_POST['st']);
          $VAR[] = "'".date('Y-m-d H:i:s')."'";
          $VAR[] = pg_escape_string($_POST['id_convenio']);

          $Sql = "UPDATE sap.convenio
                             SET id_unidade_federativa = %s,
                                     sigla_convenio = %s,
                                     descricao = %s,
                                     operando = %s,
                                     st = %s,
                                 dt_situacao = %s
                           WHERE id_convenio = %s;";

          return vsprintf($Sql,$VAR);
    }
	
     public function deletarSql(){
          //TimeZone
          date_default_timezone_set('America/Cuiaba');	

          $VAR[] = "'".date('Y-m-d H:i:s')."'";
          $VAR[] = pg_escape_string($_POST['id_convenio']);

          $Sql = "UPDATE sap.convenio
                             SET st = -1,
                                 dt_situacao = %s
                           WHERE id_convenio = %s;";

          return vsprintf($Sql,$VAR);
     }
	
     public function getDadosSql(){
          $VAR[] = pg_escape_string($_POST['id_convenio']);

          $Sql = "SELECT conv.id_convenio, conv.id_unidade_federativa, conv.sigla_convenio, conv.descricao, conv.operando, conv.st
                            FROM sap.convenio AS conv
                       WHERE conv.id_convenio = %s;";

          return vsprintf($Sql,$VAR);
     }
	
     public function getUnidadeFederativaSql(){
          $Sql = "SELECT id_unidade_federativa, descricao as unidade_federativa
                                FROM sap.unidade_federativa
                                   WHERE st > 0				  	   
                            ORDER BY unidade_federativa ASC;";

          return $Sql;
     }
}