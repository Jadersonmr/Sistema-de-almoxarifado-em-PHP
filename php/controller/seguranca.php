<?php
session_start();

$usuario = (isset($_POST['usuario']))? $_POST['usuario'] : '';
$senha_cod = (isset($_POST['senha']))? $_POST['senha'] : '';
$senha = md5($senha_cod); //criptografa a senha para comparar com a do banco

function validaUsuario($usuario, $senha) {

	include ("conexao.php");

    $SQL = "SELECT codUsuario,nomeUsuario FROM usuario WHERE nomeUsuario = '".$usuario."' AND  senha = '".$senha."' LIMIT 1";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());
		$resultado = $STH->fetch(PDO::FETCH_ASSOC);


if (empty($resultado)){
// Nenhum registro foi encontrado => o usuário é inválido
	return false;
} else {
	// O registro foi encontrado => o usuário é valido
	// Definimos dois valores na sessão com os dados do usuário
		$_SESSION['usuarioID'] = $resultado['codUsuario']; // Pega o valor da coluna codUsuario.
		$_SESSION['usuarioNome'] = $resultado['nomeUsuario']; // Pega o valor da coluna nomeUsuario.
	return true;
	}
}


function protegePagina() {
	if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
//Se não tiver usuário logado, manda pra página de login
		expulsaVisitante();
	}
}


function expulsaVisitante() {
	unset($_SESSION['usuarioID'], $_SESSION['usuarioNome']);

	$_SESSION['msg_login'] = '<p class="msg_pedido" >Nome ou senha inválidos.</p>';
		header("Location: http://localhost/projeto/php/controller/login.php");
}

?>