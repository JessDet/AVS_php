<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();
if (!$user) {
    header('Location: /wishesEncours.php');
}


$pdo = require './database.php';
$idUser=$user['idUser'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = $_POST['status'] ?? '';

    $stateUpdate= $pdo -> prepare('UPDATE souhait SET status=:status WHERE idUser=:idUser');
    $stateUpdate->bindValue (':status', $status);
    $stateUpdate->execute();

    header('Location: /wishesEncours.php');
}


?>