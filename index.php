<?php
require_once './isLoggedIn.php';
$user = isLoggedIn();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/accueil.css">
    <title>AVS</title>
</head>
<body>
    <main class="container">
        <article class="content">
            <img src="/assets/img/avs.png" alt="logo AVS" width="50%">
            
            <a href="/login.php/"><button class="bn633-hover bn29">Bienvenue</button></a>
            
        </article>
    </main>
</body>
</html>

