<?php
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	error_reporting(0);
	include ("../php/controller/seguranca.php");
	include("Report/Report.php");
	protegePagina(); // Chama a funчуo que protege a pсgina

	$arrayProtocolo = array('HTTP/1.1' => 'http://');

$acao = $_GET["acao"];

	$end = $arrayProtocolo[$_SERVER['SERVER_PROTOCOL']].$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

if ($acao == "empLabSemana"){
	$report = new Report("emprestimoSemanaLab.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));

	$report->showRelatorio();
}

if ($acao == "empFerSemana"){
	$report = new Report("emprestimosSemana.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));

	$report->showRelatorio();
}

if ($acao == "requisitante"){
	$report = new Report("requisitante.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));

	$report->showRelatorio();
}

if ($acao == "ferramentas"){
	$report = new Report("ferramenta_mais_usada.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));
	$report->showRelatorio();
}

if ($acao == "laboratorio"){
	$report = new Report("laboratorio_mais_emprestado.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));

	$report->showRelatorio();
}

if ($acao == "defeitos"){
	$report = new Report("defeitos.jrxml");
	$report->setParameter(array('dtInicio' => $_GET['dtInicio'],'dtFim' => $_GET['dtFim'],'end' => $end));

	$report->showRelatorio();
}
?>