<?php
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Cadastro de laboratórios</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.esconde.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
		<script type="text/javascript">
			function confirmar($id){
					var confirmar=confirm("Deseja remover esse cadastro?");
				if (confirmar){
					window.location.href="http://localhost/projeto/php/controller/funcoes.php?codLab="+$id+"&acao=removelab";
				}
			}
		</script>
	</head>

	<body>

<div id="container">

	<h2 class="title-cadastro">Laboratórios</h2>

<?php
	function msgbox(){
		// Verifica se existe na sessão uma mensagem, se sim exibir
		if(isset($_SESSION['msg_excluir'])){
			echo $_SESSION['msg_excluir'];
			unset($_SESSION['msg_excluir']);
		}

		if(isset($_SESSION['msg_editar'])){
			echo $_SESSION['msg_editar'];
			unset($_SESSION['msg_editar']);
		}
	}
	msgbox();
?>


<div id="busca" >
	<form class="busca" method="get" action="" >
		<label for="consulta">Buscar:</label>
			<input type="text" id="consulta" name="consulta" maxlength="255" />
			<input type="submit" value="OK" />
	</form>
</div>

<!-- Relatório -->
<span><a href="http://localhost/projeto/relatorio/relatorios.php" class="relatorio" style="text-indent:-999px;overflow:hidden;width:100px;height:30px;display:block;float:right;margin-right: 500px;" >Emitir relatório</a></span>

	<div id="box-cadastro" class="tgl" >


<form name="laboratorio" class="formvalidate" action="" method="post" >


	<p class="emlinha">

		<label for="nomelab">laboratorio:<br />
			<input type="text" value="" id="nomelab" name="campoNome" tabindex="1" maxlength="255" size="30" />
		</label>

		<label for="numero">Numero:<br />
			<input type="text" value="" id="numero" name="campoQuant" tabindex="2" maxlength="4" size="10" />
		</label>

	</p>

	<p class="emlinha">

		<label for="observacao">Observação:<br />
			<textarea id="observacao" name="observacao" tabindex="3"></textarea>
		</label>

	</p>

	<input type="submit" class="botao" tabindex="7" value="Submit" />

</form>

	</div>


<!-- insere os dados no banco -->
<?php

if(count($_POST)>0){

	$palavra = strtolower($_POST['campoNome']);
	$nomelab = ucwords($palavra);
	$numero = $_POST['campoQuant'];
	$observacao = $_POST['observacao'];

	$SQL = "INSERT INTO laboratorios(nomeLaboratorio,numeroLaboratorio,observacaoLaboratorio,sn_emprestado) VALUES(?,?,?,'Não')";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($nomelab,$numero,$observacao));

	echo '<p class="msg_pedido"><span>Dados adicionados com sucesso.</span></p>';
}

// Verifica se foi feita alguma busca
// Caso contrario, exibe todos os itens cadastrados
if (!isset($_GET['consulta'])) {

  $SQL="SELECT codLaboratorio,nomeLaboratorio,numeroLaboratorio,sn_emprestado FROM laboratorios";
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
			<th>Numero</th>
			<th>Nome</th>
			<th>Emprestado</th>
			<th>Editar</th>
			<th>Deletar</th>
		</tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

		<tr>
			<td><?php echo $row['numeroLaboratorio'];?></td>
			<td><a href="http://localhost/projeto/php/controller/funcoes.php?codLaboratorio=<?php echo $row["codLaboratorio"]?>&acao=infolab" ><?php echo $row['nomeLaboratorio']?></a></td>
			<?php if($row['sn_emprestado']=="Sim"){
					echo '<td style="color: red;" class="sim_nao" >'.$row["sn_emprestado"].'</td>';
				}else{
					echo '<td style="color: green;" class="sim_nao" >'.$row["sn_emprestado"].'</td>';
				} ?>
			<td><a href="http://localhost/projeto/php/controller/funcoes.php?codLab=<?php echo $row["codLaboratorio"]?>&acao=editalab" ><img src="http://localhost/projeto/view/images/editar.png" alt="editar" title="Editar cadastro" /></a></td>
			<td><input type="image" src="../view/images/excluir.png" alt="remover" value="remover" onclick="confirmar(<?php echo $row['codLaboratorio']?>)" /></td>
		</tr>

<?php } ?>

	</table>
</div>

<?php
}else{
	echo '<p class="msg_pedido"><span>Nenhum cadastro encontrado.</span></p>';
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
	$SQL = "SELECT count(*) FROM laboratorios WHERE ((nomeLaboratorio LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeLaboratorio DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
	$numero_linhas = $STH->fetchColumn();

	$SQL = "SELECT * FROM laboratorios WHERE ((nomeLaboratorio LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeLaboratorio DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
// ============================================
	if($numero_linhas > 0){

// Começa a exibição dos resultados
echo "<ul>";
while ($resultado = $STH->fetch(PDO::FETCH_ASSOC)) {
$nome_laboratorio = $resultado['nomeLaboratorio'];
$numero = $resultado['numeroLaboratorio'];
	echo "<li>";
?>
		<p><a href="http://localhost/projeto/php/controller/funcoes.php?codLaboratorio=<?php echo $resultado["codLaboratorio"]?>&acao=infolab" ><?php echo $nome_laboratorio?></a></p>
<?php
		echo '<p>Numero: '.$numero.'</p>';
	echo "</li><br />";
}
echo "</ul>";
}else{
	echo "Nenhum resultado encontrado.";
}
?>

</div>

	</body>
</html>