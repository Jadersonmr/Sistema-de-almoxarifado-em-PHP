<?php
// Inclui o arquivo com o sistema de seguran�a
include("seguranca.php");

if (validaUsuario($usuario, $senha) == true) {
// O usu�rio e a senha digitados foram validados, manda pra p�gina interna
	header("Location: http://localhost/projeto/index.php");
} else {
// O usu�rio e/ou a senha s�o inv�lidos, manda de volta pro form de login
// Para alterar o endere�o da p�gina de login, verifique o arquivo seguranca.php
	expulsaVisitante();
}

?>