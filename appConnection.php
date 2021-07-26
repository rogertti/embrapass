<?php
	define("DB_TYPE","mysql");
	define("DB_HOST","mysql.embracore.com.br");
	define("DB_USER","embracore22");
	define("DB_PASS","emb3974");
	define("DB_DATA","embracore22");

	$pdo = new PDO("".DB_TYPE.":host=".DB_HOST.";dbname=".DB_DATA."",DB_USER,DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec("SET NAMES utf8");
?>