<?php
$host='localhost';
$db='lojinha';
$user='root';
$pass='';
$charset='utf8mb4';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset",$user,$pass,[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false
    ]);
} catch(PDOException $e) {
    die('<div style="font-family:Arial;padding:30px"><h2>Banco de dados não conectado</h2><p>Confira se o MySQL está ligado no XAMPP e se o banco <b>lojinha</b> foi importado.</p><p>Erro: '.htmlspecialchars($e->getMessage()).'</p></div>');
}
