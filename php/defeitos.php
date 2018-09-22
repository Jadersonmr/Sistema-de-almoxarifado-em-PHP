<?php
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Ferramentas quebradas ou perdidas</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>

		<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/jquery.esconde.js" type="text/javascript"></script>
		<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
		<script type="text/javascript">
			function confirmar($id,$cod,$quant){
					var confirmar=confirm("Deseja remover esse cadastro?");
				if (confirmar){
					window.location.href="http://localhost/projeto/php/controller/funcoes.php?codDefeito="+$id+"&codFerramenta="+$cod+"&quant="+$quant+"&acao=removedefeito";
				}
			}
		</script>
	</head>

	<body>

<div id="container">

		<h2>Ferramentas quebradas ou perdidas</h2>

<?php
	function msgbox(){
		// Verifica se existe na sessão uma mensagem, se sim exibir
		if(isset($_SESSION['msg_excluir'])){
			echo $_SESSION['msg_excluir'];
			unset($_SESSION['msg_excluir']);
		}
	}
	msgbox();
?>


<!-- Relatório -->
<span><a href="http://localhost/projeto/relatorio/relatorios.php" class="relatorio" style="text-indent:-999px;overflow:hidden;width:100px;height:30px;display:block;float:right;margin-right:780px;" >Emitir relatório</a></span>

	<div id="box-cadastro" class="tgl" >

<!-- formulário de cadastro -->
<form name="cad_defeito" class="formvalidate" action="" method="post" >

<?php

	//seleciona os dados da tabela
	$SQL="SELECT codFerramenta,nomeFerramenta,quantDisponivel,quantFerramenta FROM ferramentas ORDER BY nomeFerramenta";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));

	$SQLusuario = "SELECT codUsuario,nomeUsuario FROM usuario ORDER BY nomeUsuario";
	$STHusuario = $projeto->prepare($SQLusuario);
	$STHusuario -> execute(array($SQLusuario));
?>

<p class="emlinha">
	<label for="ferramenta">Ferramenta:<br />
		<select name="campoFer" id="ferramenta" tabindex="1" >
			<option selected="selected" value="0" >Escolha uma ferramenta</option>
				<?php while($fer = $STH->fetch(PDO::FETCH_ASSOC)){?>
			<option value= "<?php echo $fer['codFerramenta'];?>">
				<?php echo $fer['nomeFerramenta'];?>
			</option><?php } ?>
		</select>
	</label>

	<label for="quantidade">Quantidade:<br />
		<input type="text" id="quantidade" name="campoQuant" tabindex="2" maxlength="2" size="10" />
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
</p>

<p class="emlinha">
	<label for="observacao">Observações:<br />
		<textarea id="observacao" name="observacao" tabindex="3"></textarea>
	</label>
</p>

	<input type="submit" class="botao" tabindex="4" value="Submit" />

</form>

	</div>
</div>

<?php
if(count($_POST)>0){

	$ferramenta = $_POST["campoFer"];
	$quantidade = $_POST["campoQuant"];
	$obs = $_POST["observacao"];
	$usuario = $_POST["campoUsuario"];
 
	$SQL="SELECT quantDisponivel FROM ferramentas WHERE codFerramenta='$ferramenta'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));

	$fer = $STH->fetch(PDO::FETCH_ASSOC);
	if($quantidade<=$fer['quantDisponivel'] OR $ferramenta<0 OR $usuario<0){

 	$SQL = "INSERT INTO defeitos(
	                                 codFerramenta,
									 quantFerramenta,
									 observacaoDefeito,
									 dataDefeito,
									 codUsuario
									) VALUES(?,?,?,NOW(),?)";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($ferramenta,$quantidade,$obs,$usuario));

	$SQL = "UPDATE ferramentas SET quantDisponivel=quantDisponivel-'$quantidade' WHERE codFerramenta='$ferramenta'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

		echo '<p class="msg_pedido"><span>Dados adicionados com sucesso.</span></p>';
	}else{
		echo '<p class="msg_pedido"><span>Quantidade de ferramentas é maior do que o disponível, ou não foi escolhido uma ferramenta.</span></p>';
	}

}

//exibe todas as ferramentas com defeito.
  $SQL="SELECT defeitos.codDefeito, defeitos.codFerramenta, nomeFerramenta, defeitos.quantFerramenta, defeitos.dataDefeito, defeitos.observacaoDefeito
FROM defeitos
INNER JOIN ferramentas ON ( ferramentas.codFerramenta = defeitos.codFerramenta )
ORDER BY nomeFerramenta";
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
			<th>Quantidade</th>
			<th>Data</th>
			<th>Deletar</th>
		</tr>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>
		<tr>
			<td><a href="http://localhost/projeto/php/controller/funcoes.php?codDefeito=<?php echo $row["codDefeito"]?>&acao=infodefeito" ><?php echo $row['nomeFerramenta']?></a></td>
			<td><?php echo $row['quantFerramenta']?></td>
			<td><?php echo date('d/m/Y H:i:s', strtotime($row['dataDefeito']))?></td>
			<td><input type="image" src="../view/images/excluir.png" alt="remover" value="remover" onclick="confirmar(<?php echo $row['codDefeito']?>,<?php echo $row['codFerramenta']?>,<?php echo $row['quantFerramenta']?>)" /></td>
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
?>
	</body>

</html>