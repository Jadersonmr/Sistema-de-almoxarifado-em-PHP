<?php
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Cadastro de requisitante</title>

		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>

		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.esconde.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.maskedinput.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
		<script type="text/javascript">
			function confirmar($id){
					var confirmar=confirm("Deseja remover esse cadastro?");
				if (confirmar){
					window.location.href="http://localhost/projeto/php/controller/funcoes.php?codUsuario="+$id+"&acao=removeusuario";
				}
			}
			
		//script para o mostrar o campo senha
			function mostra(){
				document.getElementById('senha').style.display="block";
			}

			function esconde(){
				document.getElementById('senha').style.display="none";
			}

		//validação do telefone
			jQuery(function($){
				$("#telefone").mask("(99)9999-9999");
			});
		</script>

	</head>

	<body>

<div id="container">

		<h2 class="title-cadastro">Requisitantes</h2>

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

<!-- formulario de busca ---------------------->
<div id="busca" >

	<form class="busca" method="get" >
		<label for="consulta">Buscar:</label>
			<input type="text" id="consulta" name="consulta" maxlength="255" />
			<input type="submit" value="OK" />
	</form>

</div>

<!-- Relatório -->
<span><a href="http://localhost/projeto/relatorio/relatorios.php" class="relatorio" style="text-indent:-999px;overflow:hidden;width:100px;height:30px;display:block;float:right;margin-right: 511px;" >Emitir relatï¿½rio</a></span>

	<div id="box-cadastro" class="tgl" >

<!-- formulario ------------------------------->
<form name="usuario" class="formvalidate" id="formUsuario" method="post" >


<p class="emlinha">

	<label for="nome">Nome:<br />
		<input type="text" id="nome" name="campoNome" tabindex="1" maxlength="255" size="30" />
	</label>

	<label for="endereco">Endereço:<br />
		<input type="text" id="endereco" name="campoEndereco" tabindex="2" maxlength="255" size="30" />
	</label>

	<label for="email">E-mail:<br />
		<input type="text" id="email" name="campoEmail" tabindex="3" maxlength="255" size="30" />
	</label>

	<label for="telefone">Telefone:<br />
		<input type="text" id="telefone" name="telefone" tabindex="4" onkeypress="mascara(this,telefone)" maxlength="14" />
	</label>

</p>

<p class="emlinha">
	<label for="observacao">Observação:<br />
		<textarea id="observacao" name="observacao" tabindex="5"></textarea>
	</label>

	<label for="permissao">Permissão:<br />
	    <input type="radio" value="Requisitante" name="permissao" tabindex="5" onclick="esconde()" checked="checked" />Usuário<br />
	    <input type="radio" value="Admin" name="permissao" tabindex="6" onclick="mostra()" />Administrador
	</label>

	<label id="senha" for="senha" style="display:none" >Senha:<br />
		<input type="password" id="senha" name="campoSenha" />
	</label>
</p>

	<input type="submit" class="botao" tabindex="7" value="Submit" />

</form>

    </div>

<?php 

//insere os dados no banco

if(count($_POST)>0){

	$palavra = strtolower($_POST['campoNome']);
	$nome = ucwords($palavra);
	$endereco = $_POST['campoEndereco'];
	$email = $_POST['campoEmail'];
	$telefone = $_POST['telefone'];
	$permissao = $_POST['permissao'];
	$senha = $_POST['campoSenha'];
	$senha_cod = md5($senha); //criptografa a senha
	$observacao = $_POST['observacao'];

	$SQL = "INSERT INTO usuario(nomeUsuario,enderecoUsuario,emailUsuario,telefoneUsuario,permissaoUsuario,senha,observacaoUsuario) VALUES(?,?,?,?,?,?,?)";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($nome,$endereco,$email,$telefone,$permissao,$senha_cod,$observacao));

	echo '<p class="msg_pedido" ><span>Dados adicionados com sucesso.</span></p>';
}


// Verifica se foi feita alguma busca
// Caso contrario, exibe todos os itens cadastrados
if (!isset($_GET['consulta'])) {

	$SQL="SELECT codUsuario,nomeUsuario,emailUsuario,permissaoUsuario FROM usuario ORDER BY nomeUsuario";
 	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));

//exibe os dados da tabela
$linhas = $STH->rowCount();
               
// Se tiver resultado na query monta o bloco dinï¿½mico
if ($linhas > 0 || !empty($linhas)){
?>
		<div id="tabela">
			<table>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Permissão</th>
					<th>Editar</th>
					<th>Deletar</th>
				</tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>
	<tr>
		<td><a href="http://localhost/projeto/php/controller/funcoes.php?codUsuario=<?php echo $row["codUsuario"]?>&acao=infousuario" ><?php echo $row['nomeUsuario']?></a></td>
		<td><?php echo $row['emailUsuario']?></td>
		<td><?php echo $row['permissaoUsuario']?></td>
		<td><a href="http://localhost/projeto/php/controller/funcoes.php?codUsuario=<?php echo $row["codUsuario"]?>&acao=editausuario" ><img src="../view/images/editar.png" alt="editar" title="Editar cadastro" /></a></td>
		<td><input type="image" src="http://localhost/projeto/view/images/excluir.png" alt="remover" value="remover" onclick="confirmar(<?php echo $row['codUsuario']?>)" /></td>
	</tr>

<?php } ?>
			</table>
		</div>

<?php
}else{
	echo '<p class="msg_pedido">Nenhum cadastro encontrado.</p>';
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
	$SQL = "SELECT count(*) FROM usuario WHERE ((nomeUsuario LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeUsuario DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
	$numero_linhas = $STH->fetchColumn();  

	$SQL = "SELECT * FROM usuario WHERE ((nomeUsuario LIKE '%".$busca."%') OR ('%".$busca."%')) ORDER BY nomeUsuario DESC";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
// ============================================
	if($numero_linhas > 0){

// Começa a exibição dos resultados
echo "<ul>";
	while ($resultado = $STH->fetch(PDO::FETCH_ASSOC)) {
		$nome_usuario = $resultado['nomeUsuario'];
		$telefone = $resultado['telefoneUsuario'];
		$endereço = $resultado['enderecoUsuario'];

	echo "<li>";
?>
		<p><a href="http://localhost/projeto/php/controller/funcoes.php?codUsuario=<?php echo $resultado["codUsuario"]?>&acao=infousuario" ><?php echo $nome_usuario ?></a></p>
<?php
		echo "<p>Telefone: ".$telefone."</p>";
		echo "<p>Endereço: ".$endereço."</p>";
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