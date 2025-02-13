<?php
//local
//$HOSTNAME = 'localhost';
//$USERNAME = 'root';
//$PASSWORD = '';
//$DATABASE = 'db_proiect';

$HOSTNAME = 'sql104.infinityfree.com';
$USERNAME = 'if0_38311003';
$PASSWORD = 'PF1kjWX4NL';
$DATABASE = 'if0_38311003_db_proiect';

$connect = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

//conexiune database
if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

//Cross-Site Request Forgery (CSRF) - atacuri care pot fi evitate prin folosirea unui token CSRF
//SQL Injection - atacuri care pot fi evitate prin folosirea parametrilor interogarii - bind_param()
//Cross-Site Scripting (XSS) - atacuri care pot fi evitate prin folosirea functiei htmlspecialchars()

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>