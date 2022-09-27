<?php

$pdo = require './database.php';

$sessionId = $_COOKIE['session'] ?? '';

if ($sessionId) {
    $statement = $pdo->prepare('DELETE FROM session WHERE idSession=:idSession');
    $statement->bindValue(':idSession', $sessionId);
    $statement->execute();
    setcookie('session', '', time() - 1, '/', '');
    header('Location: /index.php');
} else {
    header('Location: /login.php');
}