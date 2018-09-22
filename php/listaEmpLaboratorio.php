<?php
	//select para o campo usuario
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	//Chama a função que protege a página
	protegePagina();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Empréstimos de laboratório</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
		<script type="text/javascript">
			function confirmar($id){
				var confirmar=confirm("Deseja remover esse cadastro?");
				if (confirmar){
					window.location.href="http://localhost/projeto/php/controller/funcoes.php?codEmpLab="+$id+"&acao=removeEmpLab";
				}
			}
		</script>
	</head>

	<body>

<div id="container">

	<h2 class="emp">Laboratórios emprestados</h2>


<!--formulario de busca---------------------->
<div id="busca" >
	<form class="busca" method="get" >
		<label for="consulta">Buscar:</label>
			<input type="text" id="consulta" name="consulta" maxlength="255" />
			<input type="submit" value="OK" />
	</form>
</div>

	<div id="botoes_emp">
		<a href="http://localhost/projeto/php/empLaboratorio.php" class="novo_emp" ><img src="http://localhost/projeto/view/images/botao_novo.gif" alt="novo" /></a>
		<a href="http://localhost/projeto/php/laboratoriosDevolvidos.php" class="historico" ><img src="http://localhost/projeto/view/images/botao_historico.gif" alt="historico" /></a>
		<a href="http://localhost/projeto/relatorio/relatorios.php" class="relatorio" >relatorio</a>
	</div>

<?php

  	function msgbox(){
		// Verifica se existe na sessão uma mensagem, se sim exibir
		if(isset($_SESSION['msg_emp_lab'])){
			echo $_SESSION['msg_emp_lab'];
			unset($_SESSION['msg_emp_lab']);
		}

		if(isset($_SESSION['msg_edita_emp_lab'])){
			echo $_SESSION['msg_edita_emp_lab'];
			unset($_SESSION['msg_edita_emp_lab']);
		}

		if(isset($_SESSION['msg_excluir_emp_lab'])){
			echo $_SESSION['msg_excluir_emp_lab'];
			unset($_SESSION['msg_excluir_emp_lab']);
		}

		if(isset($_SESSION['msg_devolve_lab'])){
			echo $_SESSION['msg_devolve_lab'];
			unset($_SESSION['msg_devolve_lab']);
		}
	}
	msgbox();


// Verifica se foi feita alguma busca
// Caso contrario, exibe todos os itens cadastrados
if (!isset($_GET['consulta'])) {

  $SQL="SELECT nomeUsuario,
                codEmpLaboratorio,
                dataEmprestimo
			FROM emprestimolaboratorios
			INNER JOIN usuario ON(usuario.codUsuario=emprestimolaboratorios.codUsuario) WHERE snDevolvido='Não'";
  $STH = $projeto->prepare($SQL);
  $STH->execute(array($SQL));

//exibe os dados da tabela
$linhas = $STH->rowCount();

// Se tiver resultado na query monta o bloco dinâmico
if ($linhas > 0 || !empty($linhas)){
?>

<div id="tabela">
	<table>
		<tr> 
			<th>Nome do usuario</th>
			<th>Data do empréstimo</th>
			<th>Editar</th>
			<th>Deletar</th>
			<th>Devolução</th>
		</tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>
	<tr>
		<td><a href="http://localhost/projeto/php/controller/funcoes.php?codEmpLaboratorio=<?php echo $row["codEmpLaboratorio"]?>&acao=infoemplaboratorio" ><?php echo $row['nomeUsuario']?></a></td>
		<td><?php echo date('d/m/Y H:i:s', strtotime($row['dataEmprestimo']))?></td>
		<td><a href="../php/controller/funcoes.php?codEmpLab=<?php echo $row["codEmpLaboratorio"]?>&acao=editaEmpLab" ><img src="../view/images/editar.png" alt="editar" title="Editar cadastro" /></a></td>
		<td><input type="image" src="../view/images/excluir.png" alt="remover" value="remover" onclick="confirmar(<?php echo $row['codEmpLaboratorio']?>)" /></td>
		<td><a href="../php/controller/funcoes.php?codEmpLab=<?php echo $row["codEmpLaboratorio"]?>&acao=devolveEmpLab" ><img src="../view/images/devolve.png" alt="devolver" title="Devolver emprestimo" /></a></td>
	</tr>
<?php } ?>

	</table>
</div>

<?php
}else{
	echo '<p class="msg_emp" >Nenhum cadastro encontrado.</p>';
}

	exit;
}

// Se houve busca, continue o script:

// Salva o que foi buscado em uma variável
$busca = $_GET['consulta'];
// Usa a função mysql_real_escape_string() para evitar erros no MySQL
$busca = mysql_real_escape_string($busca);

// ============================================

// Monta outra consulta MySQL para a busca
	$SQL = "SELECT codEmpLaboratorio,nomeUsuario,snDevolvido,dataEmprestimo FROM emprestimolaboratorios
            INNER JOIN usuario on(usuario.codUsuario=emprestimolaboratorios.codUsuario)
            WHERE ((nomeUsuario LIKE '%".$busca."%') OR ('%".$busca."%')) AND (snDevolvido='Não')
			ORDER BY dataEmprestimo 
			DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));

// ============================================

// Começa a exibição dos resultados
echo "<ul>";
while ($resultado = $STH->fetch(PDO::FETCH_ASSOC)){
$codigo = $resultado['codEmpLaboratorio'];
$usuario = $resultado['nomeUsuario'];
$devolvido = $resultado['snDevolvido'];
$data = $resultado['dataEmprestimo'];
	echo "<li>";
?>
		<p><a href="http://localhost/projeto/php/controller/funcoes.php?codEmpLaboratorio=<?php echo $codigo ?>&acao=infoemplaboratorio" ><?php echo $usuario ?></a></p>
<?php
		echo "<p>Devolvido: ".$devolvido."</p>";
		echo '<p>Data: '.date('d/m/Y H:i', strtotime($data)).'</p>';
	echo "</li><br />";
}
echo "</ul>";
?>

</div>

	</body>
</html>