<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://localhost/projeto/view/estilo.css">
	</head>

	<body>
  
		<div id="container">

			<div id="boxlogin">

				<form name="login" action="valida.php" method="POST" >
	  
					<h1>Login</h1><br /><br />
		
					<label for="usuario">Nome: </label>
						<input type="text" name="usuario" id="usuario" tabindex="1" /><br /><br />
		  
					<label for="senha">Senha:</label>
						<input type="password" name="senha" id="senha" tabindex="2" /><br /><br />
	 
					<input type="submit" value="Enviar" tabindex="3" />
		
				</form>
	  
			</div>

<?php
function msgbox(){
		// Verifica se existe na sessão uma mensagem, se sim exibir
		if(isset($_SESSION['msg_login'])){
			echo $_SESSION['msg_login'];
			unset($_SESSION['msg_login']);
		}
	}
	msgbox();
?>

		</div>

	</body>
</html>