<?
class LoginSQL
{
    public function verificaLoginSql($Login,$Senha)
    {
        $Sql = "SELECT a.LoginCod, a.LoginNome, a.LoginTipo,
					   CASE
						WHEN a.LoginTipo = 'C' THEN 'Cliente'
						WHEN a.LoginTipo = 'F' THEN 'Funcionario'
						WHEN a.LoginTipo = 'P' THEN 'Parceiro'
					   END LoginTipoNome,
					   CASE
						WHEN b.ClienteCod IS NOT NULL THEN b.ClienteCod
						WHEN c.FuncionarioCod IS NOT NULL THEN c.FuncionarioCod
						WHEN d.ParceiroCod IS NOT NULL THEN d.ParceiroCod
					   END Cod,
					   CASE
						WHEN b.ClienteNome IS NOT NULL THEN b.ClienteNome
						WHEN c.FuncionarioNome IS NOT NULL THEN c.FuncionarioNome
						WHEN d.ParceiroNome IS NOT NULL THEN d.ParceiroNome
					   END Nome,
					   CASE
						WHEN b.ClienteCPF IS NOT NULL THEN b.ClienteCPF
						WHEN c.FuncionarioCPF IS NOT NULL THEN c.FuncionarioCPF
						WHEN d.ParceiroCPF IS NOT NULL THEN d.ParceiroCPF
					   END CPF
				  FROM login a 
					   LEFT JOIN cliente b ON a.LoginCod = b.LoginCod
					   LEFT JOIN funcionario c ON a.LoginCod = c.LoginCod
					   LEFT JOIN parceiro d ON a.LoginCod = d.LoginCod
                 WHERE a.LoginNome  = '$Login'
				   AND a.LoginSenha = '$Senha'";
				   
        return $Sql;
    }
}