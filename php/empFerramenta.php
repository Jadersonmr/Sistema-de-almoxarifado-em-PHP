<?php
	include ("controller/conexao.php");
	include ("controller/seguranca.php");
	include("menu.php");
	protegePagina(); // Chama a função que protege a página
	//session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Empréstimos de ferramentas</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
		<script type="text/javascript" src="http://localhost/projeto/view/js/jquery.js"></script>
		<script type="text/javascript" src="http://localhost/projeto/view/js/jquery-ui-1.7.2.custom.min.js"></script>
	</head>

	<body>

<div id="container">

	<h2 class="title-cadastro">Emprestimos de ferramentas</h2><br />

<div id="box-cadastro" style="margin-top: 5px">

<!-- formulario -->
	<form name="empferramenta" method="post" >

<?php
	$SQLusuario = "SELECT codUsuario,nomeUsuario FROM usuario ORDER BY nomeUsuario;";
	$STHusuario = $projeto->prepare($SQLusuario);
	$STHusuario -> execute(array($SQLusuario));
?>

	<p class="emlinha">
    	<label for="usuario"><span>Usuário:</span>
			<select name="nomeusuario" id="usuario" tabindex="1">
					<option selected="selected" >Escolha um requisitante</option>
				<?php while($nomeusuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
					<option value= "<?php echo $nomeusuario['codUsuario'];?>">
				<?php echo $nomeusuario['nomeUsuario'];?>
					</option>
				<?php } ?>
			</select>
		</label>
	</p>

	<p class="emlinha">
		<label for="observacao"><span>Observação:</span><textarea id="observacao" name="observacao" tabindex="3"></textarea></label>
	</p>

</div>

<?php
//insere os dados no banco
if(count($_POST)>0){

	$usuario = $_POST['nomeusuario'];
	$observacao = $_POST['observacao'];
	$locatario = $_SESSION['usuarioNome'];

	$SQL = "INSERT INTO emprestimosFerramentas(
												codUsuario,
												observacaoEmprestimo,
												dataEmprestimo,
												nomeLocatario,
												snDevolvido
											) VALUES(?,?,NOW(),?,'Não')";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($usuario,$observacao,$locatario));
	$ultimo_id = $projeto->lastInsertId();

}



//verifica quais ferramentas foram selecionadas
if((count($_POST)>0) && isset($_POST['ferramenta'])){

	foreach($_POST['ferramenta'] as $codigo => $quantidade){

		if($quantidade>0){

		//Insere no banco
			$SQL="INSERT INTO itememprestimoferramenta(codFerramenta,quantEmprestada,quantHistorico,codEmprestimo) VALUES(?,?,?,?)";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array($codigo,$quantidade,$quantidade,$ultimo_id));

		//Atualiza a quantidade de ferramentas disponiveis
			$total= $_SESSION['quantidade_disponivel'][$codigo] - $quantidade;
			$SQL = "UPDATE ferramentas 
				SET 
					quantDisponivel = '".$total."'
				WHERE
					codFerramenta = '".$codigo."'
				";
			$STH = $projeto->prepare($SQL);
			$data = array($codigo);
			$STH->execute($data);
		}

	}


	// Redireciona para a página de pedidos dando mensagem de conclusão
	$_SESSION['msg_emp_fer'] = '<p class="msg_pedido" >Pedido concluido com êxito</p>';
	header("Location: http://localhost/projeto/php/listaEmpFerramenta.php");

}


	//seleciona os dados da tabela
	$SQL="SELECT codFerramenta,nomeFerramenta,quantDisponivel,quantFerramenta FROM ferramentas ORDER BY nomeFerramenta";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
?>

	<div id="tabela" style="margin-right: 350px; margin-top: 30px;">
		<table>
			<tr>
				<th class="fer" >Nome ferramenta</th>
				<th class="qtde" >Qtde. disponivel</th>
			</tr>

<?php
	//exibe as ferramentas disponiveis
	//Armazena a quantidade disponível de cada ferramenta para ser abatida no banco
	//session_destroy();
while($row = $STH->fetch(PDO::FETCH_ASSOC)){

	$_SESSION['quantidade_disponivel'][$row['codFerramenta']]= $row['quantDisponivel'];
?>
			<tr>
				<td class="fer"><?php echo $row['nomeFerramenta']; ?></td>

				<td class="qtde">
					<select name="ferramenta[<?php echo $row['codFerramenta']; ?>]">
						<?php
							for ($i = 0; $i <= $row['quantDisponivel']; $i++) {
						?>
								<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
							}
						?>
					</select>
				</td>
			</tr>
<?php
}
?>
		</table>
	</div>

	<div id="bt_fechar_pedido">
		<input type="submit" name="submit" value="Fechar pedido" />
		<input type="button" value="Cancelar" onclick="history.go(-1)" />
	</div>

	</form>

</div>

	</body>
</html>