<?php
include('conexao.php');
include ("seguranca.php");
protegePagina(); // Chama a função que protege a página

$acao = mysql_real_escape_string($_GET["acao"]);


if ($acao == "removeusuario"){
		$codUsuario = (int)$_REQUEST['codUsuario'];
		$codFerramenta = (int)$_REQUEST['codFerramenta'];

			$SQL = "SELECT codEmprestimo FROM itememprestimoferramenta WHERE codFerramenta = '".$codFerramenta."'";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array());
				$resultado = $STH->fetch(PDO::FETCH_ASSOC);

			$SQL = "SELECT codEmprestimo FROM emprestimosferramentas WHERE codUsuario = '".$codUsuario."'";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array());
				$resultUser = $STH->fetch(PDO::FETCH_ASSOC);

	if(empty($resultado) and empty($resultUser)){

		$SQL = "DELETE FROM usuario WHERE codUsuario = '".$codUsuario."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

		$SQL = "DELETE FROM ferramentas WHERE codUsuario = '".$codUsuario."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

			$_SESSION['msg_excluir'] = '<p class="msg_pedido" >Cadastro removido com sucesso</p>';
			header("Location: http://localhost/projeto/php/cadastroUsuario.php");

	}else{
		$_SESSION['msg_excluir'] =  '<p class="msg_pedido" >Existem emprestimos associados com essa ferramenta.<br/>Exclua este antes de excluir a ferramenta.</p>';
		header("Location: http://localhost/projeto/php/cadastroUsuario.php");
	}

}
?>