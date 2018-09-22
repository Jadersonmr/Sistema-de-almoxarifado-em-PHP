<?php
// Inclui o arquivo com o sistema de segurança
include("seguranca.php");

if (validaUsuario($usuario, $senha) == true) {
// O usuário e a senha digitados foram validados, manda pra página interna
	header("Location: http://localhost/projeto/index.php");
} else {
// O usuário e/ou a senha são inválidos, manda de volta pro form de login
// Para alterar o endereço da página de login, verifique o arquivo seguranca.php
	expulsaVisitante();
}

?>