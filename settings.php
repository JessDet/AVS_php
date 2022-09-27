<?php


$pdo = require_once './database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/settings.css">
    <title>AVS_settings</title>
</head>
<body>
<main class="souhaitcontainer">
    <div class="header">
    <?php require_once 'includes/header.php' ?>
    </div>

    <div class="titleSettings">
        <h1>
            PARAMETRES
        </h1>
    </div>

    <div class="containerSetting">
        <div class="modifProfil">
            <a href="/modifProfile.php">
            <img class="iconSettings" src="/assets/img/modif.png" alt="">
            <p class="txtpart">Modification de profil</p></a>
        </div>
        <div class="modifMdp">
            <a href="/update_mdp.php">
            <img class="iconSettings" src="/assets/img/modifier.png" alt="">
            <p class="txtpart">Modification de mot de passe</p></a>
        </div>
        <div class="supprCpt">
            <a href="/delete_profile.php">
            <img class="iconSettings" src="/assets/img/corbeille.png" alt="">
            <p class="txtpart">Suppression de compte</p></a>
        </div>
        <div class="CondUtil">
            <a href="/cgu.php">
            <img class="iconSettings" src="/assets/img/set.png" alt="">
            <p class="txtpart">Conditions d'utilisation</p></a>
        </div>
    </div>


</main>
</body>
</html>