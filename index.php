<?php
include("php/controller/seguranca.php");
protegePagina(); // Chama a função que protege a página
echo '<div id="mensagem_boas_vindas" >Olá, ' . $_SESSION['usuarioNome'] . '</div>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
  <link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
</head>
  <body>

    <div id="container">

	  <div id="index">
        <a href="http://localhost/projeto/php/cadastroUsuario.php"><img src="http://localhost/projeto/view/images/usuario.gif" alt="usuario" title="usuario" /></a>
		<a href="http://localhost/projeto/php/cadastroFerramenta.php"><img src="http://localhost/projeto/view/images/ferramenta.gif" alt="ferramenta" title="ferramenta" /></a>
		<a href="http://localhost/projeto/php/cadastroLab.php"><img src="http://localhost/projeto/view/images/laboratorio.gif" /></a><br /><br />
		<a href="http://localhost/projeto/php/listaEmpLaboratorio.php"><img src="http://localhost/projeto/view/images/emplaboratorio.gif" /></a>
		<a href="http://localhost/projeto/php/listaEmpFerramenta.php"><img src="http://localhost/projeto/view/images/empFerramenta.gif" /></a>
      </div>

	  <div id="footer"><a href="http://localhost/projeto/php/controller/deslogar.php"><img src="http://localhost/projeto/view/images/sair.png" alt="sair" title="sair" /></a></div>

	</div>

  </body>
</html>