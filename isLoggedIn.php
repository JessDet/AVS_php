<?php 

function isLoggedIn()

{
    $pdo = require './database.php';
    $sessionId = $_COOKIE['session'] ?? '';

if ($sessionId) {
    $statementSession = $pdo->prepare('SELECT * FROM session WHERE idSession=:idSession');
    $statementSession->bindValue(':idSession', $sessionId);
    $statementSession->execute();
    $session = $statementSession->fetch();
    
    $userStatement = $pdo->prepare('SELECT * FROM user WHERE idUser=:idSession');
    $userStatement->bindValue(':idSession', $session['idUser']);
    $userStatement->execute();
    $user = $userStatement->fetch();
}
return $user ?? false;

}
