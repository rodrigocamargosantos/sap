<? 
//Starta Sessao
session_start();

//Instancias
include_once('../config/config.php'); Config::Conf();
include_once($_SESSION['DirBaseSite']."includes/cabecalho.php"); 
?>
<!-- Funções de JavaScript -->
<script type="text/javascript">
</script>
<!-- Fim Funções de JavaScript -->
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="bg">
			<tr>
				<td align="center" valign="top">

					<table width="70%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bg_intera" bgcolor="#FFFFFF">
						<tr bgcolor="#ffffff">
							<td align="left" valign="top" class="bg_degrade">
								<table width="98%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											<!-- Include Topo-->
											<? include_once($_SESSION['DirBaseSite']."includes/topo.php"); ?>
										</td>
									</tr>          
									<tr  height="100%"> 
										<td height="100%" align="left" valign="top">

											<!-- Tabela Interna -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="interna">
												<tr>
													<td class="titulo_conteudo"><img src="<?=$_SESSION['UrlBaseSite']?>imagens/bullet.gif" style="padding-bottom:1px;"> Empresa</td>
												</tr>
												<tr>
													<td class="texto_conteudo">
														Uma empresa especializada em Gestão de Margem Consignável, oferecemos aos nossos convênios padrões de alta qualidade na gestão dos descontos facultativos em folha de pagamento, possível através da terceirização deste serviço, no qual nossa empresa se responsabiliza pelo atendimento ao servidor, oferecendo orientação jurídica e para as entidades consignatárias acompanhamento e suporte técnico (ex.: troca de arquivos e apoio a conciliação).<br />
														Através da implantação da gestão de margem consignável da Consignum nossos convênios podem contar com uma estrutura tecnológica e de atendimento voltadas ao saneamento dos problemas decorrentes dos processos de consignação em folha de pagamento. Com isso, além de oferecer aos seus colaboradores a viabilidade e a facilidade deste processo, nossos convênios podem também atender às expectativas das consignatárias estabelecendo um relacionamento transparente e salutar entre todas as partes envolvidas no processo.
													</td>
												</tr>
											</table>
											<!-- Fim Tabela Interna --> 

										</td>
									</tr>
									<tr>
										<td>
											<!-- Include Rodape -->
											<? include_once($_SESSION['DirBaseSite']."includes/rodape.php"); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>