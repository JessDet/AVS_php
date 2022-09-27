<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();
if (!$user) {
    header('Location: /index.php');
}


$pdo = require './database.php';
$idUser=$user['idUser'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $statusUser = $_POST['statusUser'] ?? '';

    $stateUpdate= $pdo -> prepare('UPDATE user SET statusUser=:statusUser WHERE idUser=:idUser');
    $stateUpdate->bindValue (':statusUser', $statusUser);
    $stateUpdate->execute();

    header('Location: /login.php');
}


?>