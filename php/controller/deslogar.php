<?php
session_start();

//destroi as sess�es
unset($_SESSION['usuarioID'], $_SESSION['usuarioNome']);
session_destroy();

//redireciona para a tela de login
Header("Location: http://localhost/projeto/php/controller/login.php");

?>