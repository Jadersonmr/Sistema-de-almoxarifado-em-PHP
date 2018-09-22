<?php
  //select para o campo usuario
  include ("controller/conexao.php");
  include ("controller/seguranca.php");
  include("menu.php");
  protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
  <title>Ferramentas devolvidas</title>
  <link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
</head>
	<body>

		<div id="container">
			<h2 class="emp" >Ferramentas devolvidas</h2>

				<a href="http://localhost/projeto/php/listaEmpFerramenta.php" class="bt_voltar" >voltar</a>

<!--formulario de busca---------------------->
<div id="busca" >
	<form class="busca" method="get" action="" >
		<label for="consulta">Buscar:</label>
			<input type="text" id="consulta" name="consulta" maxlength="255" />
			<input type="submit" value="OK" />
	</form>
</div>

<?php
// Verifica se foi feita alguma busca
// Caso contrario, exibe todos os itens cadastrados
if (!isset($_GET['consulta'])) {

			$SQL="SELECT codEmprestimo,
					     nomeUsuario,
			             dataEmprestimo,
						 dataDevolucao
			    FROM emprestimosferramentas
			    INNER JOIN usuario ON(usuario.codUsuario=emprestimosferramentas.codUsuario)
			    WHERE snDevolvido='Sim'";
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
	<th>Data da devolução</th>
  </tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC))
  {
?>
  <tr>
  	<td><a href="http://localhost/projeto/php/controller/funcoes.php?codEmprestimo=<?php echo $row["codEmprestimo"]?>&acao=infoempferramenta" ><?php echo $row['nomeUsuario']?></a></td>
	<td><?php echo date('d/m/Y H:i:s', strtotime($row['dataEmprestimo']));?></td>
	<td><?php echo date('d/m/Y H:i:s', strtotime($row['dataDevolucao']));?></td>
  </tr>
<?php
  }
?>

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
	$SQL = "SELECT codEmprestimo,nomeUsuario,snDevolvido,dataEmprestimo FROM emprestimosferramentas
            INNER JOIN usuario on(usuario.codUsuario=emprestimosferramentas.codUsuario)
            WHERE ((nomeUsuario LIKE '%".$busca."%') OR ('%".$busca."%')) AND (snDevolvido='Sim')
			ORDER BY dataEmprestimo 
			DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));

// ============================================

// Começa a exibição dos resultados
echo "<ul>";
while ($resultado = $STH->fetch(PDO::FETCH_ASSOC)){
$codigo = $resultado["codEmprestimo"];
$usuario = $resultado['nomeUsuario'];
$devolvido = $resultado['snDevolvido'];
$data = $resultado['dataEmprestimo'];
	echo "<li>";
?>
		<p><a href="http://localhost/projeto/php/controller/funcoes.php?codEmprestimo=<?php echo $codigo ?>&acao=infoempferramenta" ><?php echo $usuario ?></a></p>
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