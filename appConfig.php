<?php		
    #ini_set('display_errors', 'on');
    #ini_set('output_buffering', 4096);
    #ini_set('session.auto_start', 1);
    #ini_set('SMTP', 'smtp.embracore.com.br');
    #ini_set('smtp_port', 587);
    #error_reporting(0);

    session_start();
    date_default_timezone_set('America/Sao_Paulo');
	$cfg = array();

    if($_SERVER['SERVER_NAME'] != 'localhost') {
        if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }
?>