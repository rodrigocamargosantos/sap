<?php
class Conexao extends Exception
{ 
    /*
    ** Atributos da Classe
    */
    private $Con;
    private $Host = 'localhost'; //host
    private $Port = '5432'; //port
    private $BDName = 'Sap'; //bdname
    private $User = 'postgres'; //user
    private $Password = 'asdzxc'; //password

    /**
    *	Cria uma conexão Persistente com o banco de dados PostreSQL
    *	@return VOID
    */
    public function conectar($Banco = null)
    { 
        if(empty($Banco)){
            $ConString = "host=$this->Host port=$this->Port dbname=$this->BDName user=$this->User password=$this->Password";
            $this->Con = pg_connect($ConString);
			if(!$this->Con){
				die('ATENÇÃO: Não foi possível conectar ao banco de dados.');
			}
        }else{
            $ConString = "host=$this->Host port=$this->Port dbname=$Banco user=$this->User password=$this->Password";
            $this->Con = @pg_connect($ConString);
			if(!$this->Con){
				die('ATENÇÃO: Não foi possível conectar ao banco de dados.');
			}
        }
    } 	
    
    public function fecharConexao()
    {
        @pg_close($this->Con);
    }

    /**
    *	Executando uma clausula SQL
    *	@param Sql String - Instrução SQL
    *	@return ResultSet
    */
    public function executar($Sql)
    {		
        if(($ResultSet = pg_query($this->Con, $Sql))){
            return $ResultSet; 
        }else{ 
            printf("ATENÇÃO: Erro INTERNO de SQL: $Sql <br />MOTIVO: %s<br />", pg_last_error($this->Con));
            return false;                 
        }
    }

    /**
    *	Executando um identificador e retornando seu numero de linhas
    *	@param RS ResultSet - ResultSet Executado
    *	@return Inteiro
    */
    public function nLinhas($ResultSet)
    {
        return ($NumeroLinhas = @pg_num_rows($ResultSet)) ? $NumeroLinhas : 0;
    }

    /**
    *	Executando um ResultSet e retornando um Array
    *	@param Sql String - Instrução SQL
    *	@return Array
    */
    public function linha($ResultSet)
    {
        return($Array = @pg_fetch_array($ResultSet, 0, PGSQL_BOTH)) ? @array_map("trim", $Array) : false;
    }

    /**
    *	Executando uma clusula SQL e retornandno um Array
    *	@param Sql String - Instrução SQL
    *	@return Array
    */
    public function execLinha($Sql)
    {
        $ResultSet = $this->executar($Sql);

        return($this->nLinhas($ResultSet) > 0) ? $this->linha($ResultSet) : 0;
    }

    /**
    *	Executando uma clusula SQL e retorna o valor de uma coluna especifica
    *	@param Sql String - Instrução SQL
    *	@return Array
    */
    public function execRLinha($Sql, $Coluna)
    {
        $ResultSet = $this->executar($Sql);

        if($this->nLinhas($ResultSet) > 0)
        {
            $Array  = $this->linha($ResultSet);
            $Retorno = $Array[$Coluna];
        }else{
            $Retorno = 0;
        }
        return $Retorno;
    }


    /**
    *	Executando uma clausula e retornando o numero de L afetadas
    *	@param Sql String - Instrução SQL
    *	@return Booleano
    */
    public function execNLinhas($Sql)
    {
        return($NumeroLinhas = $this->nLinhas($this->executar($Sql))) ? $NumeroLinhas :  false;
    }


    /**
    * Executa um comando SQL e retorna um array com Todos os resultados
    * @param string Instrução SQL
    * @return array() / false
    */
    public function execTodosArray($Sql, $Posicao = null, $Indice = null)
    {
        $ResultSet = $this->executar($Sql);
		$Rows = array();
        
		if($ResultSet !== false)
        {			
			$NumeroLinhas = $this->nLinhas($ResultSet);
			
			for($I = 0; $I < $NumeroLinhas; $I++)
			{
				$Row = @pg_fetch_array($ResultSet, $I, PGSQL_ASSOC);
				
				if(empty($Posicao))
				{
					if(empty($Indice))
					$Rows[] = $Row;
					else
					$Rows[$Row[$Indice]] = $Row;
				}
				else
				{
					if(empty($Indice))
					$Rows[] = $Row[$Posicao];
					else
					$Rows[$Row[$Indice]] = $Row[$Posicao];
				}
			}
        }
		
		return $Rows;
    }


    /**
    *	Inicia uma Transação 
    *	@return VOID
    */
    public function startTransaction()
    {
        $this->executar("SET AUTOCOMMIT = 0");
        $this->executar("START TRANSACTION");
    }

    /**
    *	Finaliza uma Transação - Commit se for vazio, senão Rollback
    *	@param Erro: String - Indicador de Erro
    *	@return Booleano
    */
    public function stopTransaction($Erro)
    {
        if(!empty($Erro))
        {
            $this->executar("ROLLBACK");
            $this->executar("SET AUTOCOMMIT = 1");
            return false;
        } 
        else
        {
            $this->executar("COMMIT");
            $this->executar("SET AUTOCOMMIT = 1");
            return true;
        }
    }		
}
