<?php
	include("conexao.php");
	include ("seguranca.php");
	protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css">
	<script src="http://localhost/projeto/view/js/jquery.js" type="text/javascript"></script>
	<script src="http://localhost/projeto/view/js/jquery.validate.js" type="text/javascript"></script>
	<script src="http://localhost/projeto/view/js/jquery.maskedinput.js" type="text/javascript"></script>
	<script src="http://localhost/projeto/view/js/validacao.js" type="text/javascript"></script>
	<script type="text/javascript">
		//validação do telefone
		jQuery(function($){
			$("#telefone").mask("(99)9999-9999");
		});
	</script>
</head>

	<body>
<?php

$acao = $_GET["acao"];




//Remover
//remove usuario-----------------------------
if ($acao == "removeusuario"){
	$codUsuario = (int)$_REQUEST['codUsuario'];

		$SQL = "SELECT codFerramenta FROM ferramentas WHERE codUsuario = '".$codUsuario."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
			$resultado = $STH->fetch(PDO::FETCH_ASSOC);

if (empty($resultado)){

	$SQL = "DELETE FROM usuario WHERE codUsuario = '".$codUsuario."'"; //sql para deletar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_excluir'] = '<p class="msg_pedido" >Cadastro removido com sucesso</p>';
	header("Location: http://localhost/projeto/php/cadastroUsuario.php");

	return false;
} else {
?>
	<div id="exclusao" >
		<fieldset>

		<h2>Existem ferramentas associadas com esse requisitante.Deseja removê-las também?</h2>

		<p><a href="exclusao.php?codFerramenta=<?php echo $resultado['codFerramenta'] ?>&codUsuario=<?php echo $codUsuario; ?>&acao=removeusuario" >Sim</a>
			<a href="http://localhost/projeto/php/cadastroUsuario.php" >Não</a></p>

		</fieldset>
	</div>
<?php

	return true;
	}

}





//remove ferramenta-----------------------
if ($acao == "removeferramenta"){
	$codFerramenta = (int)$_REQUEST['codFerramenta'];

		$SQL = "SELECT codEmprestimo FROM itememprestimoferramenta WHERE codFerramenta = '".$codFerramenta."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
			$resultado = $STH->fetch(PDO::FETCH_ASSOC);

	if (empty($resultado)){

		$SQL = "DELETE FROM ferramentas WHERE codFerramenta = '".$codFerramenta."'"; #sql para deletar cadastro no banco
 		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

			$_SESSION['msg_excluir'] = '<p class="msg_pedido" >Cadastro removido com sucesso</p>';
			header("Location: http://localhost/projeto/php/cadastroFerramenta.php");

	}else{
		$_SESSION['msg_excluir'] =  '<p class="msg_pedido" >Existem emprestimos associados com essa ferramenta.<br/>Exclua este antes de excluir a ferramenta.</p>';
		header("Location: http://localhost/projeto/php/cadastroFerramenta.php");
	}

}





//remove laboratorio--------------------------
if ($acao == "removelab"){
	$codLaboratorio = (int)$_REQUEST['codLab'];

	$SQL = "SELECT codEmpLaboratorio FROM itensemplaboratorio WHERE 	codLaboratorio = '".$codLaboratorio."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());
		$resultado = $STH->fetch(PDO::FETCH_ASSOC);

	if (empty($resultado)){

		$SQL = "DELETE FROM laboratorios WHERE codLaboratorio = '".$codLaboratorio."'"; #sql para deletar cadastro no banco
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

			$_SESSION['msg_excluir'] = '<p class="msg_pedido" >Cadastro removido com sucesso</p>';
			header("Location: http://localhost/projeto/php/cadastroLab.php");

	}else{
		$_SESSION['msg_excluir'] =  '<p class="msg_pedido" >Existem emprestimos associados com esse laboratório.<br/>Exclua este antes de excluir a ferramenta.</p>';
		header("Location: http://localhost/projeto/php/cadastroLab.php");
	}

}





//remove empréstimos de ferramentas--------------------------
if ($acao == "removeEmpFerramenta"){
  $codEmprestimo = (int)$_REQUEST['codEmpFerramenta'];

	//deleta o emprestimo
	$SQL = "DELETE FROM emprestimosferramentas WHERE codEmprestimo = '".$codEmprestimo."'"; #sql para deletar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	//seleciona o codigo e a quantidade emprestada da tabela itemEmprestimoFerramenta
	$SQLfer = "SELECT codFerramenta,quantEmprestada FROM itememprestimoferramenta WHERE codEmprestimo='".$codEmprestimo."'";
	$STHfer = $projeto->prepare($SQLfer);
	$STHfer->execute(array());

	while($row = $STHfer->fetch(PDO::FETCH_ASSOC)){
		$SQL = "UPDATE ferramentas SET quantDisponivel=quantDisponivel+".$row['quantEmprestada']." WHERE codFerramenta='".$row['codFerramenta']."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

		//deleta os itens do emprestimo
		$SQL = "DELETE FROM itememprestimoferramenta WHERE codEmprestimo = '".$codEmprestimo."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
	}

	$_SESSION['msg_excluir_emp_fer'] = '<p class="msg_pedido" >Empréstimo removido com sucesso</p>';
	header("Location: http://localhost/projeto/php/listaEmpFerramenta.php");
}





//remove empréstimos de laboratórios--------------------------
if ($acao == "removeEmpLab"){
	$codEmpLab = (int)$_REQUEST['codEmpLab'];

		//deleta o emprestimo
		$SQL = "DELETE FROM emprestimolaboratorios WHERE codEmpLaboratorio = '".$codEmpLab."'"; #sql para deletar cadastro no banco
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

		//seleciona o codigo da tabela itensemplaboratorio
		$SQLfer = "SELECT codLaboratorio FROM itensemplaboratorio WHERE codEmpLaboratorio='".$codEmpLab."'";
		$STHfer = $projeto->prepare($SQLfer);
		$STHfer->execute(array());

	while($row = $STHfer->fetch(PDO::FETCH_ASSOC)){
		$SQL = "UPDATE laboratorios SET sn_emprestado='Não' WHERE codLaboratorio='".$row['codLaboratorio']."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());

		//deleta os itens do emprestimo
		$SQL = "DELETE FROM itensemplaboratorio WHERE codEmpLaboratorio = '".$codEmpLab."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
	}

	$_SESSION['msg_excluir_emp_lab'] = '<p class="msg_pedido" >Empréstimo removido com sucesso</p>';
	header("Location: http://localhost/projeto/php/listaEmpLaboratorio.php");
}





//remove ferramentas quebradas--------------------------------------------
if ($acao == "removedefeito"){
	$codDefeito = (int)$_REQUEST['codDefeito'];
	$codFerramenta = (int)$_REQUEST['codFerramenta'];
	$quantidade = (int)$_REQUEST['quant'];

	$SQL = "DELETE FROM defeitos WHERE codDefeito = '".$codDefeito."'"; //sql para deletar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$SQL = "UPDATE ferramentas SET quantDisponivel=quantDisponivel+".$quantidade." WHERE codFerramenta='".$codFerramenta."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_excluir'] = '<p class="msg_pedido" >Cadastro removido com sucesso</p>';
	header("Location: http://localhost/projeto/php/defeitos.php");
}





//Editar

//editar laboratorio------------------------------------------------
//se ação é igual a editalab seleciona os dados da tabela
if ($acao == "editalab"){
 	$codLaboratorio = (int)$_REQUEST['codLab'];

	$SQL="SELECT nomeLaboratorio,numeroLaboratorio,observacaoLaboratorio FROM laboratorios WHERE codLaboratorio='".$codLaboratorio."'";
	$STH = $projeto->prepare($SQL);
 	$STH->execute(array($SQL));
		$row = $STH->fetch(PDO::FETCH_ASSOC);

?>

<div id="box-editar">
	<fieldset>
		<legend>Editar laboratório</legend>

	<form name="editaLab" class="formvalidate" method="POST" >

		<p class="emlinha">
			<label for="nomelab"><span>laboratorio:</span><input type="text" value="<?php echo $row['nomeLaboratorio'] ?>" id="nomelab" name="campoNome" tabindex="1" maxlength="255" size="30"></label>
			<label for="numero"><span>Numero:</span><input type="text" value="<?php echo $row['numeroLaboratorio'] ?>" id="numero" name="numero" tabindex="2" maxlength="4" size="10"></label>
		</p>

		<p class="emlinha">
			<label for="observacao"><span>Observação:</span><textarea id="observacao" name="observacao" tabindex="3"><?php echo $row['observacaoLaboratorio'] ?></textarea></label>
		</p>

		<div id="botao_editar">
			<input type="submit" class="botao_editar"  value="Enviar" tabindex="4" />
			<a href="/projeto/php/cadastroLab.php" class="voltar_editar" >Cancelar</a>
		</div>

	</form>

	</fieldset>
</div>

<?php
//altera os dados no banco
if(count($_POST)>0){
	$nomelab = $_POST['campoNome'];
	$numeroLab = $_POST['numero'];
	$obsLab = $_POST['observacao'];

	$SQL = "UPDATE laboratorios SET nomeLaboratorio='".$nomelab."',
                 numeroLaboratorio='".$numeroLab."',
				 observacaoLaboratorio='".$obsLab."' 
				 WHERE codLaboratorio = '".$codLaboratorio."'"; #sql para deletar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_editar'] = '<p class="msg_pedido" >Cadastro atualizado com sucesso</p>';
	header("Location: http://localhost/projeto/php/cadastroLab.php");
	}
}





//editar usuario------------------------------------------------
//se ação é igual a editausuario seleciona os dados da tabela
if ($acao == "editausuario"){
	$codusuario = (int)$_REQUEST['codUsuario'];
  
	$SQL="SELECT nomeUsuario,
               enderecoUsuario,
			   telefoneUsuario,
			   emailUsuario,
			   permissaoUsuario,
			   observacaoUsuario 
			   FROM usuario WHERE codUsuario='".$codusuario."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
	$row = $STH->fetch(PDO::FETCH_ASSOC);

?>
<div id="box-editar">
	<fieldset>
		<legend>Editar requisitante</legend>

	<form name="editaUser" class="formvalidate" action="" method="POST" >

		<p class="emlinha">
			<label for="nome"><span>Nome:</span><input type="text" value="<?php echo $row['nomeUsuario']?>" id="nome" name="campoNome" tabindex="1" maxlength="255" size="30"></label>
	 		<label for="endereco"><span>Endereço:</span><input type="text" value="<?php echo $row['enderecoUsuario']?>" id="endereco" name="campoEndereco" tabindex="2" maxlength="255" size="30"></label>
	 	</p>

	 	<p class="emlinha">
	 		<label for="email"><span>E-mail:</span><input type="text" value="<?php echo $row['emailUsuario']?>" id="email" name="campoEmail" tabindex="3" maxlength="255" size="30"></label>
	 		<label for="telefone"><span>Telefone:</span><input type="text" value="<?php echo $row['telefoneUsuario']?>" id="telefone" name="telefone" tabindex="4" onkeypress="mascara(this,telefone)" maxlength="14"></label>
		</p>

		<p class="emlinha">
			<label for="observacao"><span>Observação:</span><textarea id="observacao" name="observacao" tabindex="5"><?php echo $row['observacaoUsuario']?></textarea></label>
		</p>

		<div id="botao_editar">
			<input type="submit" class="botao_editar" value="Enviar" tabindex="4" />
			<a href="/projeto/php/cadastroUsuario.php" class="voltar_editar" >Cancelar</a>
		</div>

	</form>

	</fieldset>
</div>

<?php
//altera os dados no banco
if(count($_POST)>0){
	$nomeUser = $_POST['campoNome'];
	$endereco = $_POST['campoEndereco'];
	$email = $_POST['campoEmail'];
	$telefone = $_POST['telefone'];
	$obsUser = $_POST['observacao'];

	$SQL = "UPDATE usuario SET nomeUsuario='".$nomeUser."',
							 enderecoUsuario='".$endereco."',
							 emailUsuario='".$email."',
							 telefoneUsuario='".$telefone."',
							 observacaoUsuario='".$obsUser."' 
							 WHERE codUsuario = '".$codusuario."'"; #sql para deletar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_editar'] = '<p class="msg_pedido" >Cadastro atualizado com sucesso</p>';
	header("Location: http://localhost/projeto/php/cadastroUsuario.php");
	}
}





//editar ferramenta------------------------------------------------
//se ação é igual a editaferramenta seleciona os dados da tabela
if ($acao == "editaferramenta"){
	$codFerramenta = (int)$_REQUEST['codFerramenta'];

	$SQL="SELECT nomeFerramenta,
			    quantFerramenta,
				quantDisponivel,
				dataEntrada,
				codUsuario,
				codLaboratorio
				obsFerramenta FROM ferramentas WHERE codFerramenta='".$codFerramenta."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
		$row = $STH->fetch(PDO::FETCH_ASSOC);

	$quantTotal = $row['quantFerramenta'];
	$quantDisponivel = $row['quantDisponivel'];
	
?>
<div id="box-editar">
	<fieldset>
		<legend>Editar ferramenta</legend>

	<form name="editaFer" class="formvalidate" action="" method="POST" >

		<p class="emlinha">
			<label for="ferramenta"><span>Nome da ferramenta:</span>
				<input type="text" value="<?php echo $row['nomeFerramenta']?>" id="ferramenta" name="campoNome" tabindex="1" maxlength="255" size="30"/>
			</label>

			<?php 
			//select para mostrar os usuarios
			$SQLusuario = "SELECT codUsuario,
	                      nomeUsuario 
				          FROM usuario ORDER BY nomeUsuario";
			$STHusuario = $projeto->prepare($SQLusuario);
			$STHusuario -> execute(array($SQLusuario));
			
			//select para mostrar os laboratórios
			$SQLlab = "SELECT codLaboratorio,
	                      nomeLaboratorio 
				          FROM laboratorios ORDER BY nomeLaboratorio";
			$STHlab = $projeto->prepare($SQLlab);
			$STHlab -> execute(array($SQLlab));
			?>

			<label for="usuario"><span>Usuario:</span>
				<select name="campoUsuario" id="usuario" tabindex="5" />
					<option selected="selected" >Escolha um requisitante</option>
						<?php while($usuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
							<option <?php if($usuario['codUsuario'] == $row['codUsuario']) echo 'selected="selected"';?> value= "<?php echo $usuario['codUsuario'];?>">
								<?php echo $usuario['nomeUsuario'];?>
					</option><?php } ?>
				</select>
			</label>
			
			<label for="laboratorio">Laboratório:<br />
				<select name="campoLab" id="laboratorio" tabindex="5" />
					<option selected="selected" >Escolha um laboratório</option>
						<?php while($lab = $STHlab->fetch(PDO::FETCH_ASSOC)){?>
							<option <?php if($lab['codLaboratorio'] == $lab['codLaboratorio']) echo 'selected="selected"';?> value= "<?php echo $lab['codLaboratorio'];?>">
								<?php echo $lab['nomeLaboratorio'];?>
					</option><?php } ?>
				</select>
			</label>
		</p>

		<p class="emlinha">
			<label for="observacao"><span>Observação:</span>
				<textarea id="observacao" name="observacao" tabindex="3"><?php echo $row['obsFerramenta']?></textarea>
			</label>

			<label for="quantidade"><span>Quantidade:</span>
				<input type="text" value="<?php echo $row['quantFerramenta']?>" id="quantidade" name="quantidade" tabindex="2" maxlength="4" size="10"/>
			</label>

		</p>

		<div id="botao_editar">
			<input type="submit" class="botao_editar" value="Enviar" tabindex="4" />
			<a href="/projeto/php/cadastroFerramenta.php" class="voltar_editar" >Cancelar</a>
		</div>

	</form>

	</fieldset>
</div>

<?php
//altera os dados no banco
	if(count($_POST)>0){
		$nomeFer = $_POST['campoNome'];
		$quantidadeEditada = $_POST['quantidade'];
		$usuario = $_POST['campoUsuario'];
		$lab = $_POST['campoLab'];
		$observacao = $_POST['observacao'];

//altera a quantidade disponivel de acordo com a quantidade nova
	$quantEmprestada = $quantTotal - $quantDisponivel;
	$novaQuantDisponivel = $quantidadeEditada - $quantEmprestada;

	$SQL = "UPDATE ferramentas SET nomeFerramenta='".$nomeFer."',
                 quantFerramenta='".$quantidadeEditada."',
				 quantDisponivel='".$novaQuantDisponivel."',
				 codUsuario='".$usuario."',
				 codLaboratorio='".$lab."',
				 obsFerramenta='".$observacao."' 
				 WHERE codFerramenta = '".$codFerramenta."'"; #sql para atualizar cadastro no banco
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_editar'] = '<p class="msg_pedido" >Cadastro atualizado com sucesso</p>';
	header("Location: http://localhost/projeto/php/cadastroFerramenta.php");
	}
}





//editar emprestimo ferramenta------------------------------------------------
//se ação é igual a editaEmpFerramenta seleciona os dados da tabela
if ($acao == "editaEmpFerramenta"){
	$codEmp = (int)$_REQUEST['codEmpFerramenta'];

	$SQL="SELECT codUsuario,
				observacaoEmprestimo
				FROM emprestimosferramentas WHERE codEmprestimo='".$codEmp."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
	$row = $STH->fetch(PDO::FETCH_ASSOC);

?>

<div id="box-editar">
	<fieldset>
		<legend>Editar empréstimo</legend>

	<form name="editaEmpFer" class="formvalidate" method="POST" >

<?php 
	//select para mostrar usuarios
       $SQLusuario = "SELECT codUsuario,
	                         nomeUsuario 
							 FROM usuario ORDER BY nomeUsuario";
       $STHusuario = $projeto->prepare($SQLusuario);
       $STHusuario -> execute(array($SQLusuario));
?>

	<p class="emlinha">
		<label for="usuario"><span>Requisitante:</span>
			<select name="usuario" id="usuario" tabindex="1" />
				<?php while($usuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
					<option <?php if($usuario['codUsuario'] == $row['codUsuario']) echo 'selected="selected"';?> value= "<?php echo $usuario['codUsuario'];?>" >
				<?php echo $usuario['nomeUsuario'];?>
					</option><?php } ?>
			</select>
		</label>
	</p>

	<p class="emlinha">
		<label for="observacao"><span>Observação:</span>
			<textarea id="observacao" name="observacao" tabindex="2"><?php echo $row['observacaoEmprestimo'];?></textarea>
		</label>
	</p>

	<div id="botao_editar">
		<input type="submit" class="botao_editar" value="Enviar" tabindex="3" />
		<a href="/projeto/php/listaEmpFerramenta.php" class="voltar_editar" >Cancelar</a>
	</div>

	</form>
	
	</fieldset>
</div>

<?php
//altera os dados no banco
if(count($_POST)>0){
	$usuario = $_POST['usuario'];
	$observacao = $_POST['observacao'];

	$SQL = "UPDATE emprestimosferramentas SET
                   codUsuario='".$usuario."',
				   observacaoEmprestimo='".$observacao."'
				  WHERE codEmprestimo = '".$codEmp."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_edita_emp_fer'] = '<p class="msg_pedido" >Empréstimo atualizado com sucesso</p>';
	header("Location: http://localhost/projeto/php/listaEmpFerramenta.php");
	}
}





//editar emprestimo laboratórios------------------------------------------------
//se ação é igual a editaEmpLab seleciona os dados da tabela
if ($acao == "editaEmpLab"){
	$codEmpLab = (int)$_REQUEST['codEmpLab'];

	$SQL="SELECT codUsuario,
				observacaoEmprestimo
				FROM emprestimolaboratorios WHERE codEmpLaboratorio='".$codEmpLab."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array($SQL));
		$row = $STH->fetch(PDO::FETCH_ASSOC);

?>

<div id="box-editar">
	<fieldset>
		<legend>Editar empréstimo</legend>

	<form name="editaEmpLab" class="formvalidate" action="" method="POST" >

	<?php
	//select para mostrar usuarios
	$SQLusuario = "SELECT codUsuario,
	                         nomeUsuario 
							 FROM usuario ORDER BY nomeUsuario";
	$STHusuario = $projeto->prepare($SQLusuario);
 	$STHusuario -> execute(array($SQLusuario));
	?>

	<p class="emlinha">
		<label for="usuario"><span>Requisitante:</span>
			<select name="usuario" id="usuario" tabindex="1" />
				<?php while($usuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
					<option <?php if($usuario['codUsuario'] == $row['codUsuario']) echo 'selected="selected"';?> value= "<?php echo $usuario['codUsuario'];?>" >
		  		<?php echo $usuario['nomeUsuario'];?>
					</option><?php } ?>
			</select>
		</label>
	</p>

	<p class="emlinha">
		<label for="observacao"><span>Observação:</span><textarea id="observacao" name="observacao" tabindex="2"><?php echo $row['observacaoEmprestimo'];?></textarea></label>
	</p>

	<div id="botao_editar">
		<input type="submit" class="botao_editar" value="Enviar" tabindex="3" />
		<a href="/projeto/php/listaEmpLaboratorio.php" class="voltar_editar" >Cancelar</a>
	</div>

	</form>

	</fieldset>
</div>
<?php

//altera os dados no banco
if(count($_POST)>0){
	$usuario = $_POST['usuario'];
	$observacao = $_POST['observacao'];

	$SQL = "UPDATE emprestimolaboratorios SET
                 codUsuario='".$usuario."',
				 observacaoEmprestimo='".$observacao."'
				 WHERE codEmpLaboratorio = '".$codEmpLab."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	$_SESSION['msg_edita_emp_lab'] = '<p class="msg_pedido" >Empréstimo atualizado com sucesso</p>';
	header("Location: http://localhost/projeto/php/listaEmpLaboratorio.php");
	}
}






//informações do cadastro
//Mostrar usuarios
if ($acao == "infousuario"){
  $codUsuario = (int)$_REQUEST['codUsuario'];

  $SQL = "SELECT * FROM usuario WHERE codUsuario = '".$codUsuario."'"; #sql para selecionar os dados cadastro do banco
  $STH = $projeto->prepare($SQL);
  $STH->execute(array());

?>
	<div id="detalhes" >
		<a href="http://localhost/projeto/php/cadastroUsuario.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Informações do requisitante:</legend>
<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Código: </span><?php echo $row['codUsuario']; ?></label><br />
  <label><span>Nome: </span><?php echo $row['nomeUsuario']; ?></label><br />
  <label><span>Telefone: </span><?php echo $row['telefoneUsuario']; ?></label><br />
  <label><span>Endereço: </span><?php echo $row['enderecoUsuario']; ?></label><br />
  <label><span>E-mail: </span><?php echo $row['emailUsuario']; ?></label><br />
  <label><span>Permissão: </span><?php echo $row['permissaoUsuario']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['observacaoUsuario'];?></label><br />

<?php
  }
?>
		</fieldset>
	</div>
<?php
}





//Mostrar ferramentas
if ($acao == "infoferramenta"){
  $codFer = (int)$_REQUEST['codFerramenta'];

  $SQL = "SELECT codFerramenta,
                nomeUsuario,
				nomeLaboratorio,
				nomeFerramenta,
				quantFerramenta,
				quantDisponivel,
				dataEntrada,
				obsFerramenta
			FROM ferramentas
			INNER JOIN usuario on(usuario.codUsuario=ferramentas.codUsuario)
			LEFT JOIN laboratorios on(laboratorios.codlaboratorio=ferramentas.codlaboratorio)
			WHERE codFerramenta = '".$codFer."'"; #sql para selecionar os dados cadastrados no banco

  $STH = $projeto->prepare($SQL);
  $STH->execute(array());
?>
  	<div id="detalhes" >
		<a href="http://localhost/projeto/php/cadastroFerramenta.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Informações da ferramenta:</legend>
<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Código: </span><?php echo $row['codFerramenta']; ?></label><br />
  <label><span>Nome da ferramenta: </span><?php echo $row['nomeFerramenta']; ?></label><br />
  <label><span>Quantidade total: </span><?php echo $row['quantFerramenta']; ?></label><br />
  <label><span>Quantidade disponivel: </span><?php echo $row['quantDisponivel']; ?></label><br />
  <label><span>Data do cadastro: </span><?php echo $row['dataEntrada']; ?></label><br />
  <label><span>Requisitante: </span><?php echo $row['nomeUsuario']; ?></label><br />
  <label><span>Laboratório: </span><?php echo $row['nomeLaboratorio']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['obsFerramenta']; ?></label><br />

<?php
  }
?>
		</fieldset>
	</div>
<?php
}




//Mostrar laboratório
if ($acao == "infolab"){
  $codLab = (int)$_REQUEST['codLaboratorio'];

	#sql para selecionar os dados cadastrados do banco
	$SQL = "SELECT codLaboratorio,
                   nomeLaboratorio,
				   numeroLaboratorio,
				   observacaoLaboratorio
				   FROM laboratorios WHERE codLaboratorio = '".$codLab."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

	#sql para selecionar as ferramentas
	$SQLfer="SELECT nomeFerramenta FROM ferramentas WHERE codLaboratorio = '".$codLab."'";
	$STHfer = $projeto->prepare($SQLfer);
	$STHfer->execute(array());

?>

  	<div id="detalhes">
		<a href="http://localhost/projeto/php/cadastroLab.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Informações do laboratório:</legend>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Código: </span><?php echo $row['codLaboratorio']; ?></label><br />
  <label><span>Nome do laboratorio: </span><?php echo $row['nomeLaboratorio']; ?></label><br />
  <label><span>Numero: </span><?php echo $row['numeroLaboratorio']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['observacaoLaboratorio']; ?></label><br />
  <label><span>Ferramentas: </span></label><br />
<?php
}
	while($linha = $STHfer->fetch(PDO::FETCH_ASSOC)){
?>
		<label><?php echo $linha['nomeFerramenta']; ?></label><br />
<?php
	}
?>
		</fieldset>
	</div>
<?php
}





//exibe os empréstimos de ferramentas
if ($acao == "infoempferramenta"){
  $infoEmpFer = (int)$_REQUEST['codEmprestimo'];

	#sql para selecionar os dados cadastrados do banco
	$SQL = "SELECT codEmprestimo,
                   dataEmprestimo,
				   observacaoEmprestimo,
				   nomeUsuario,
				   nomeLocatario
				   FROM emprestimosferramentas
				   INNER JOIN usuario on(usuario.codUsuario=emprestimosferramentas.codUsuario)
				   WHERE codEmprestimo = '".$infoEmpFer."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());


	$SQLitem = "SELECT codItemEmprestimo,
	                   quantHistorico,
					   nomeFerramenta,
					   codEmprestimo
				    FROM itememprestimoferramenta
					INNER JOIN ferramentas ON(ferramentas.codFerramenta=itememprestimoferramenta.codFerramenta)
					WHERE codEmprestimo='".$infoEmpFer."'";
	$STHitem = $projeto->prepare($SQLitem);
	$STHitem->execute(array());
?>

  	<div id="detalhes" >
		<a href="http://localhost/projeto/php/listaEmpFerramenta.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Emprestimo de ferramentas:</legend>
<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Código: </span><?php echo $row['codEmprestimo']; ?></label><br />
  <label><span>Data do empréstimo: </span><?php echo date('d/m/Y H:i:s', strtotime($row['dataEmprestimo']));?></label><br />
  <label><span>Requisitante: </span><?php echo $row['nomeUsuario']; ?></label><br />
  <label><span>Locatario: </span><?php echo $row['nomeLocatario']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['observacaoEmprestimo']; ?></label><br />

<?php
}
?>

		</fieldset>
	</div>

<!-- lista as ferramentas emprestadas -->
	<div id="item">
		<fieldset>
			<legend>Ferramentas emprestadas:</legend>

<table>
  <tr>
	<th>Ferramenta</th>
	<th>Qtde. Emprestada</th>
  </tr>

<?php
while($row = $STHitem->fetch(PDO::FETCH_ASSOC)){
?>
  <tr>
	<td><?php echo $row['nomeFerramenta']?></td>
	<td><?php echo $row['quantHistorico']?></td>
  </tr>
<?php
}
?>
			</table>
		</fieldset>
	</div>
<?php
}





//exibe os empréstimos de laboratórios
if ($acao == "infoemplaboratorio"){
  $infoEmpLab = (int)$_REQUEST['codEmpLaboratorio'];

	#sql para selecionar os dados cadastrados do banco
	$SQL = "SELECT codEmpLaboratorio,
                   dataEmprestimo,
				   observacaoEmprestimo,
				   nomeUsuario,
				   nomeLocatario
				   FROM emprestimolaboratorios
				   INNER JOIN usuario on(usuario.codUsuario=emprestimolaboratorios.codUsuario)
				   WHERE codEmpLaboratorio = '".$infoEmpLab."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());
	
	$SQLitem = "SELECT codEmpLaboratorio, 
	                   nomeLaboratorio
				    FROM itensemplaboratorio
					INNER JOIN laboratorios ON(laboratorios.codlaboratorio=itensemplaboratorio.codlaboratorio)
					WHERE codEmpLaboratorio='".$infoEmpLab."'";
	$STHitem = $projeto->prepare($SQLitem);
	$STHitem->execute(array());
?>

  	<div id="detalhes">
		<a href="http://localhost/projeto/php/listaEmpLaboratorio.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Empréstimos de laboratórios:</legend>
<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Código: </span><?php echo $row['codEmpLaboratorio']; ?></label><br />
  <label><span>Data do empréstimo: </span><?php echo date('d/m/Y H:i:s', strtotime($row['dataEmprestimo']));?></label><br />
  <label><span>Requisitante: </span><?php echo $row['nomeUsuario']; ?></label><br />
  <label><span>Locatario: </span><?php echo $row['nomeLocatario']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['observacaoEmprestimo']; ?></label><br />

<?php
  }
?>
		</fieldset>
	</div>

<!-- lista as ferramentas emprestadas -->
	<div id="item">
		<fieldset>
			<legend>Laboratórios emprestados:</legend>
				<ul>
<?php
while($row = $STHitem->fetch(PDO::FETCH_ASSOC)){
?>

<li><?php echo $row['nomeLaboratorio']?></li>

<?php
}
?>
				</ul>
		</fieldset>
	</div>
<?php
}





//Mostrar ferramentas quebradas
if ($acao == "infodefeito"){
  $codDefeito = (int)$_REQUEST['codDefeito'];

	#sql para selecionar os dados cadastrados do banco
	$SQL = "SELECT defeitos.codDefeito, 
					defeitos.codFerramenta, 
					nomeFerramenta, 
					defeitos.quantFerramenta, 
					defeitos.dataDefeito, 
					defeitos.observacaoDefeito,
					nomeUsuario
			FROM defeitos
			INNER JOIN ferramentas ON ( ferramentas.codFerramenta = defeitos.codFerramenta )
			INNER JOIN usuario ON ( usuario.codUsuario = defeitos.codUsuario )
			WHERE codDefeito = '".$codDefeito."'
			ORDER BY nomeFerramenta";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

?>

  	<div id="detalhes">
		<a href="http://localhost/projeto/php/defeitos.php" class="bt_voltar" >Voltar</a>

		<fieldset>
			<legend>Informações da ferramenta:</legend>

<?php
while($row = $STH->fetch(PDO::FETCH_ASSOC)){
?>

  <label><span>Nome da ferramenta: </span><?php echo $row['nomeFerramenta']; ?></label><br />
  <label><span>Requisitante: </span><?php echo $row['nomeUsuario']; ?></label><br />
  <label><span>Quantidade: </span><?php echo $row['quantFerramenta']; ?></label><br />
  <label><span>Data: </span><?php echo $row['dataDefeito']; ?></label><br />
  <label><span>Observações: </span><?php echo $row['observacaoDefeito']; ?></label><br />
<?php
}
?>
		</fieldset>
	</div>
<?php
}





//-----------------------------Devolução---------------------------

//devolve Emprestimos de laboratório
if ($acao == "devolveEmpLab"){
	$codEmpLab = (int)$_REQUEST['codEmpLab'];

	$SQLemplab = "SELECT i.codlaboratorio,
						i.codlaboratorio,
				   l.nomelaboratorio,
				   l.sn_emprestado
				FROM itensemplaboratorio i, laboratorios l
			WHERE 
				i.codemplaboratorio='".$codEmpLab."' AND
				l.codlaboratorio = i.codlaboratorio AND
				sn_emprestado = 'Sim'";
	$STHemplab = $projeto->prepare($SQLemplab);
	$STHemplab->execute(array($SQLemplab));


function get_post_action($name){
	$params = func_get_args();
    
	foreach ($params as $name) {
        if (isset($_POST[$name])){
            return $name;
		}
	}
}

switch (get_post_action('selecionados', 'todos')){

case 'todos';

	$SQLemp = "UPDATE emprestimolaboratorios SET snDevolvido='Sim',dataDevolucao=now() WHERE codEmpLaboratorio = '".$codEmpLab."'";
	$STHemp = $projeto->prepare($SQLemp);
	$STHemp->execute(array());

	while($row = $STHemplab->fetch(PDO::FETCH_ASSOC)){
		$SQL = "UPDATE laboratorios SET sn_emprestado='Não' WHERE codLaboratorio='".$row['codlaboratorio']."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
	}

		$_SESSION['msg_devolve_lab'] = '<p class="msg_pedido" >Devolução realizada com sucesso</p>';
		header("Location: http://localhost/projeto/php/listaEmpLaboratorio.php");
	break;


case 'selecionados';

/*	$SQLemp = "UPDATE emprestimolaboratorios SET snDevolvido='Sim',dataDevolucao=now() WHERE codEmpLaboratorio = '".$codEmpLab."'";
	$STHemp = $projeto->prepare($SQLemp);
	$STHemp->execute(array());*/

	$input = $_POST['lab_devolvido'];

	foreach($input as $lab_devolvido){
		$SQL = "UPDATE laboratorios SET sn_emprestado='Não' WHERE codLaboratorio='".$lab_devolvido."'";
		$STH = $projeto->prepare($SQL);
		$STH->execute(array());
	}

		$_SESSION['msg_devolve_lab'] = '<p class="msg_pedido" >Devolução realizada com sucesso</p>';
		header("Location: http://localhost/projeto/php/listaEmpLaboratorio.php");
	break;

default:
}
?>


<div id="devolve" >
	<fieldset>

	<form name="devolveLaboratorio" class="formvalidate" action="" method="POST">

	<table>
		<tr>
			<th></th>
			<th>Laboratorio</th>
		</tr>

<?php while($lab = $STHemplab->fetch(PDO::FETCH_ASSOC)){?>

		<tr>
			<td><input type="checkbox" name="lab_devolvido[]" value="<?php echo $lab['codlaboratorio'];?>" onclick="if (this.checked) { selecionados.disabled = false; } else if (! this.checked) { selecionados.disabled = true; }" /></td>
			<td><?php echo $lab['nomelaboratorio']?></td>
		</tr>

<?php } ?>
	</table>

		<input type="submit" name="selecionados" value="Devolver selecionados" disabled >

		<input type="submit" name="todos" value="Devolver todos">

		<input type="button" value="Cancelar" tabindex="5" onclick="history.go(-1)" />
</form>

	</fieldset>
</div>

<?php
}






//devolve Emprestimos de ferramentas
if ($acao == "devolveEmpFerramenta"){
	$codEmpFerramenta = (int)$_REQUEST['codEmpFerramenta'];

	$SQLempfer = "SELECT i.codFerramenta,
						i.codFerramenta,
					i.quantEmprestada,
				   l.nomeFerramenta
				FROM itememprestimoferramenta i, ferramentas l
			WHERE 
				i.codEmprestimo='".$codEmpFerramenta."' AND
				l.codFerramenta = i.codFerramenta";
	$STHempfer = $projeto->prepare($SQLempfer);
	$STHempfer->execute(array($SQLempfer));

//verifica qual botão foi clicado
function get_post_action($name){
	$params = func_get_args();

	foreach ($params as $name) {
        if (isset($_POST[$name])){
            return $name;
		}
	}
}

switch (get_post_action('selecionados', 'todos')){

case 'todos';

	$SQL = "UPDATE emprestimosferramentas SET snDevolvido='Sim',dataDevolucao=now() WHERE codEmprestimo = '".$codEmpFerramenta."'";
	$STH = $projeto->prepare($SQL);
	$STH->execute(array());

		while($row = $STHempfer->fetch(PDO::FETCH_ASSOC)){
			$SQL = "UPDATE ferramentas SET quantDisponivel=quantDisponivel+".$row['quantEmprestada']." WHERE codFerramenta='".$row['codFerramenta']."'";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array());
		}

		$_SESSION['msg_devolve_fer'] = '<p class="msg_pedido" >Devolução realizada com sucesso</p>';
		header("Location: http://localhost/projeto/php/listaEmpFerramenta.php");
break;


case 'selecionados';

	$quantidade = $_POST['quantidade'];

		foreach($quantidade as $codigo=>$valor){
			$SQL = "UPDATE ferramentas SET quantDisponivel=quantDisponivel+'".$valor."' WHERE codFerramenta='".$codigo."'";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array());

			$SQL = "UPDATE itememprestimoferramenta SET quantEmprestada=quantEmprestada-'".$valor."' WHERE codFerramenta='".$codigo."'";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array());
		}

		$_SESSION['msg_devolve_fer'] = '<p class="msg_pedido" >Devolução realizada com sucesso</p>';
		header("Location: http://localhost/projeto/php/listaEmpFerramenta.php");
break;

default:
}
?>

<div id="devolve" >
	<fieldset>

<form name="devolveFerramenta" class="formvalidate" action="" method="POST">

	<table>
		<tr>
			<th></th>
			<th>Ferramenta</th>
			<th>Quantidade</th>
		</tr>

<?php while($fer= $STHempfer->fetch(PDO::FETCH_ASSOC)){

	if($fer['quantEmprestada']>0){?>

		<tr>
			<td><input type="checkbox" name="ferramenta_devolvida[]" value="<?php echo $fer['codFerramenta'];?>" onclick="if (this.checked){selecionados.disabled = false;} else if (! this.checked){selecionados.disabled = true;}" /></td>
			<td><?php echo $fer['nomeFerramenta']?></td>
			<td><select name="quantidade[<?php echo $fer['codFerramenta'];?>]" >
				<?php for ($i = 0; $i <= $fer['quantEmprestada']; $i++){ ?>
					<option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>

	<?php } 

 } ?>
	</table>

		<input type="submit" name="selecionados" value="Devolver selecionados" disabled />

		<input type="submit" name="todos" value="Devolver tudo" />

		<input type="button" value="Cancelar" tabindex="5" onclick="history.go(-1)"/>

</form>

	</fieldset>
</div>

<?php } ?>

	</body>
</html>