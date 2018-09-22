<?php

  try {
    $projeto=new PDO("mysql:host=localhost;dbname=projeto","root","");
	$projeto->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e){
    print $e->getMessage();
  }
?>
