<?
//Starta Sessao
session_start();

include_once($_SESSION['DirBaseSite'].'icone/icone.class.php');	$Ico = new Icone();

$Ico->getImagem($_GET['IdIcone']);