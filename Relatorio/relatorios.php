<?php
include ("../php/controller/seguranca.php");
protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

	<head>
		<title>Relatórios</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>

		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.maskedinput.js" type="text/javascript"></script>
		<script type="text/javascript">
		//validação do data
			jQuery(function($){
				$("#dtinicio").mask("99/99/9999");
				$("#dtfim").mask("99/99/9999");
			});
		</script>

	</head>

<body>

<div id="box-editar">

	<fieldset>
		<legend>Relatórios</legend>

<form name="form_relatorio" class="" action="" method="post" >

	<label for="relatorio">Relatório:<br />
		<select name="campo_relatorio" id="relatorio" tabindex="1" >
			<option selected="selected" >Escolha um relatorio</option>
			<option value="requisitante" >Requisitante que mais solicita empréstimos</option>
			<option value="ferramentas" >Ferramenta mais emprestada</option>
			<option value="laboratorio" >Laboratório mais emprestado</option>
			<option value="empFerSemana" >Empréstimos de ferramenta por semana</option>
			<option value="empLabSemana" >Empréstimos de laboratório por semana</option>
			<option value="defeitos" >Requisitante que mais danificou ferramentas</option>
		</select>
	</label><br />

	<label for="dtinicio">Data inicial:<br />
		<input type="text" id="dtinicio" name="campo_dtinicio" tabindex="2" maxlength="255" size="15" onkeypress="mascara(this,data)" />
	</label><br />

	<label for="dtfim">Data final:<br />
		<input type="text" id="dtfim" name="campo_dtfim" tabindex="3" maxlength="255" size="15" onkeypress="mascara(this,data)" />
	</label><br />

	<input type="submit" class="" tabindex="4" value="Enviar" />
	<input type="button" value="Cancelar" onclick="history.go(-1)" />

</form>

	</fieldset>
</div>

<?php
if(count($_POST)>0){

$acao = $_POST["campo_relatorio"];
$dtInicio = $_POST["campo_dtinicio"];
$dtFim = $_POST["campo_dtfim"];


/*$dtInicio = implode(preg_match("~\/~", $_POST["campo_dtinicio"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_POST["campo_dtinicio"]) == 0 ? "-" : "/", $_POST["campo_dtinicio"])));
$dtFim = implode(preg_match("~\/~", $_POST["campo_dtfim"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_POST["campo_dtfim"]) == 0 ? "-" : "/", $_POST["campo_dtfim"])));*/

//echo '<a href="http://localhost/projeto/relatorio/index.php?acao='.$acao.'&dtInicio='.$dtInicio.'&dtFim='.$dtFim.'">aaaa</a>';
header("Location: http://localhost/projeto/relatorio/index.php?acao=$acao&dtInicio=$dtInicio&dtFim=$dtFim");

}
?>

</body>

</html>