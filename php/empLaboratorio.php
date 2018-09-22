<?php
  include ("controller/conexao.php");
  include ("controller/seguranca.php");
  include("menu.php");
  protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Empréstimo de laboratórios</title>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css"></link>
	</head>

	<body>

	<div id="container">

		<h2 class="title-cadastro">Emprestimos de Laboratórios</h2><br />


<div id="box-cadastro" style="margin-top: 5px" >

<!-- formulario -->
<form name="emplaboratorio" method="post" >

	  
<?php
  //select para o campo usuario

  $SQLusuario = "SELECT codUsuario,nomeUsuario FROM usuario ORDER BY nomeUsuario;";
  $STHusuario = $projeto->prepare($SQLusuario);
  $STHusuario -> execute(array($SQLusuario));	
?>	

<p class="emlinha">
    
	<label for="usuario"><span>Usuário:</span>
		<select name="campousuario" id="usuario" tabindex="1">
			<option selected="selected" >Escolha um requisitante</option>
				<?php while($nomeusuario = $STHusuario->fetch(PDO::FETCH_ASSOC)){?>
			<option value= "<?php echo $nomeusuario['codUsuario'];?>">
				<?php echo $nomeusuario['nomeUsuario'];?>
			</option><?php } ?>
		</select></label>

</p>
    <p class="emlinha">
	  <label for="observacao"><span>Observação:</span><textarea id="observacao" name="observacao" tabindex="3"></textarea></label>
	</p>
		</div>

<!-- insere os dados no banco -->
<?php

if(count($_POST)>0){

	$usuario = $_POST['campousuario'];
	$observacao = $_POST['observacao'];
	$locatario = $_SESSION['usuarioNome'];

	$SQL = "INSERT INTO emprestimolaboratorios(
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



//verifica quais laboratórios foram selecionadas
if((count($_POST)>0) && isset($_POST['laboratorio'])){

	foreach($_POST['laboratorio'] as $codigolaboratorio){

			//Insere no banco
			$SQL="INSERT INTO itensemplaboratorio(codEmpLaboratorio,codLaboratorio) VALUES(?,?)";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array($ultimo_id,$codigolaboratorio));

			$SQL = "UPDATE laboratorios SET sn_emprestado='Sim' WHERE codLaboratorio=?";
			$STH = $projeto->prepare($SQL);
			$STH->execute(array($codigolaboratorio));
	}


	// Redireciona para a página de pedidos dando mensagem de conclusão
	$_SESSION['msg_emp_lab'] = '<p class="msg_pedido" >Pedido concluido com êxito</p>';
	header("Location: http://localhost/projeto/php/listaEmpLaboratorio.php");
}


//seleciona os dados da tabela
$SQL="SELECT codLaboratorio,nomeLaboratorio,numeroLaboratorio,sn_emprestado FROM laboratorios ORDER BY nomeLaboratorio";
$STH = $projeto->prepare($SQL);
$STH->execute(array($SQL));
?>

			<div id="tabela" style="margin-right: 350px; margin-top: 30px;">
			<table>
				<tr>
					<th class="fer" >Laboratório</th>
					<th class="fer" >Numero do laboratório</th>
					<th class="fer" >Emprestado</th>
				</tr>

<?php
//exibe os laboratórios disponiveis
	while($row = $STH->fetch(PDO::FETCH_ASSOC)){ ?>
				<tr>
					<td class="fer"><?php echo $row['nomeLaboratorio']; ?></td>
					<td class="fer"><?php echo $row['numeroLaboratorio']; ?></td>
					<td class="fer"><?php echo $row['sn_emprestado']; ?></td>
				<?php 
				if($row['sn_emprestado']=='Não'){
				?>
					<td class="fer"><input type="checkbox" name="laboratorio[]" value="<?php echo $row["codLaboratorio"];?>" /></td>
				<?php	
				}else{
				?>
					<td class="fer"><input type="checkbox" name="laboratorio[]" value="<?php echo $row["codLaboratorio"];?>" disabled="disabled" /></td>
				<?php 					
				}
				?>
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