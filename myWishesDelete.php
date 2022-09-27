<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}

$pdo = require './database.php';

$statement = $pdo->prepare('DELETE FROM souhait WHERE idSouhait=:id');


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id){
    $statement->bindValue('id',$id);
    $statement->execute();
}


header('Location: /myWishes.php');

?>