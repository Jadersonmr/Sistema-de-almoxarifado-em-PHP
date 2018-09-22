<?php
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Cadastro de ferramentas</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>

		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.esconde.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
		<script type="text/javascript">
			function confirmar($id){
					var confirmar=confirm("Deseja remover esse cadastro?");
				if (confirmar){
					window.location.href="http://localhost/projeto/php/controller/funcoes.php?codFerramenta="+$id+"&acao=removeferramenta";
				}
			}
		</script>
	</head>

	<body>

<div id="container">

		<h2>Ferramentas</h2>

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

<!--formulario de busca---------------------->
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

<!-- formulario de cadastro -->
<form name="cadferramenta" class="formvalidate" action="" method="post" >

<?php

	$SQLusuario = "SELECT codUsuario,nomeUsuario FROM usuario ORDER BY nomeUsuario";
	$STHusuario = $projeto->prepare($SQLusuario);
	$STHusuario -> execute(array($SQLusuario));
	
	$SQLlab = "SELECT codLaboratorio,nomeLaboratorio FROM laboratorios ORDER BY nomeLaboratorio";
	$STHlab = $projeto->prepare($SQLlab);
	$STHlab -> execute(array($SQLlab));

?>

<p class="emlinha">

		<label for="ferramenta">Nome da ferramenta:<br />
			<input type="text" value="" id="ferramenta" name="campoNome" tabindex="1" maxlength="255" size="30"/>
		</label>
		
		<label for="usuario">Requisitante:<br />
			<select name="campoUsuario" id="usuario" tabindex="5" >
				<option selected="selected" value="0" >Escolha um requisitante</option>
					<?php while($usuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
						<option value= "<?php echo $usuario['codUsuario'];?>">
							<?php echo $usuario['nomeUsuario'];?>
						</option><?php } ?>
			</select>
		</label>

		<label for="quantidade">Quantidade:<br />
			<input type="text" value="" id="quantidade" name="campoQuant" tabindex="2" maxlength="4" size="10"/>
		</label>
		
		<label for="laboratorio">Laboratório:<br />
			<select name="campoLab" id="laboratorio" tabindex="5" >
				<option selected="selected" >Escolha um laboratório</option>
					<?php while($lab = $STHlab->fetch(PDO::FETCH_ASSOC)){?>
						<option value= "<?php echo $lab['codLaboratorio'];?>">
							<?php echo $lab['nomeLaboratorio'];?>
						</option><?php } ?>
			</select>
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


<!---insere os dados no banco---------------------->
<?php

if(count($_POST)>0){

	$palavra = strtolower($_POST['campoNome']);
	$ferramenta = ucwords($palavra);
	$quantidade = $_POST['campoQuant'];
	$quantdisponivel = $_POST['campoQuant'];
	if(isset($quantidade)){
		$quantidade = 1;
		$quantdisponivel = 1;
	}

	$usuario = $_POST['campoUsuario'];
	$lab = $_POST['campoLab'];
	$observacao = $_POST['observacao'];

	if($usuario>0){
	$SQL = "INSERT INTO ferramentas(
	                                 nomeFerramenta,
									 quantFerramenta,
									 quantDisponivel,
									 dataEntrada,
									 codUsuario,
									 codLaboratorio,
									 obsFerramenta
									) VALUES(?,?,?,NOW(),?,?,?)";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($ferramenta,$quantidade,$quantdisponivel,$usuario,$lab,$observacao));

		echo '<p class="msg_pedido"><span>Dados adicionados com sucesso.</span></p>';
	
	}else{
			echo '<p class="msg_pedido"><span>Escolha um requisitante.</span></p>';
	}
}

// Verifica se foi feita alguma busca
// Caso contrario, exibe todos os itens cadastrados
if (!isset($_GET['consulta'])) {

  $SQL="SELECT codFerramenta,
                nomeUsuario,
				nomeFerramenta,
				quantFerramenta,
				quantDisponivel,
				dataEntrada
			FROM ferramentas
			INNER JOIN usuario on(usuario.codUsuario=ferramentas.codUsuario) ORDER BY nomeFerramenta";
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
			<th>Nome da ferramenta</th>
			<th>Usuario</th>
			<th>Qdte</th>
			<th>Qdte. Disponivel</th>
			<th>Data de cadastro</th>
			<th>Editar</th>
			<th>Deletar</th>
		</tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>
		<tr>
			<td><a href="http://localhost/projeto/php/controller/funcoes.php?codFerramenta=<?php echo $row["codFerramenta"]?>&acao=infoferramenta" ><?php echo $row['nomeFerramenta']?></a></td>
			<td><?php echo $row['nomeUsuario']?></td>
			<td style="color: green;" class="sim_nao"><?php echo $row['quantFerramenta']?></td>
			<?php 
				if($row['quantDisponivel']== $row['quantFerramenta']){
					echo '<td style="color: green;" class="sim_nao" >'.$row["quantDisponivel"].'</td>';
				}else{
					echo '<td style="color: red;" class="sim_nao" >'.$row["quantDisponivel"].'</td>';
				}
			?>
			<td><?php echo date('d/m/Y H:i:s', strtotime($row['dataEntrada']))?></td>
			<td><a href="http://localhost/projeto/php/controller/funcoes.php?codFerramenta=<?php echo $row["codFerramenta"]?>&acao=editaferramenta" ><img src="../view/images/editar.png" alt="editar" title="Editar cadastro" /></a></td>
			<td><input type="image" src="../view/images/excluir.png" alt="remover" value="remover" onclick="confirmar(<?php echo $row['codFerramenta']?>)" /></td>
		</tr>
<?php
  }
?>
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
	$SQL = "SELECT count(*) FROM ferramentas WHERE ((nomeFerramenta LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeFerramenta DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
	$numero_linhas = $STH->fetchColumn();

	$SQL = "SELECT * FROM ferramentas WHERE ((nomeFerramenta LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeFerramenta DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
// ============================================
	if($numero_linhas > 0){


// Começa a exibição dos resultados
echo "<ul>";
while ($resultado = $STH->fetch(PDO::FETCH_ASSOC)) {
$nome_ferramenta = $resultado['nomeFerramenta'];
$quantidade = $resultado['quantFerramenta'];
	echo "<li>";
?>
		<p><a href="http://localhost/projeto/php/controller/funcoes.php?codFerramenta=<?php echo $resultado["codFerramenta"]?>&acao=infoferramenta" ><?php echo $nome_ferramenta?></a></p>
<?php
		echo '<p>Data: '.date('d/m/Y H:i', strtotime($resultado['dataEntrada'])).'</p>';
		echo '<p>Quantidade: '.$quantidade.'</p>';
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