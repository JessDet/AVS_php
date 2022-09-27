<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}

$pdo = require './database.php';

$stateRead = $pdo->prepare("SELECT *
    FROM souhait JOIN user 
    ON souhait.idUser = user.idUser WHERE idSouhait=:id");

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idSouhait = $_GET['idSouhait'] ?? $_POST['hide'] ?? null;


    $stateRead->bindValue(':id', $idSouhait);
    $stateRead->execute();
    $oneWish = $stateRead->fetch();
    // var_dump($oneWish);


$stateReadCom = $pdo->prepare("SELECT * FROM commentaire 
JOIN user ON commentaire.idUser=user.idUser
JOIN souhait ON commentaire.idSouhait=souhait.idSouhait
WHERE souhait.idSouhait=:id");


$stateReadCom->bindValue(':id', $idSouhait);
$stateReadCom->execute();
$allComByWish = $stateReadCom->fetchAll();
// var_dump($allComByWish);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $commentaire = $_POST['commentaire'] ?? '';
    $idUser = $user['idUser'];
    $idSouhait = $_POST['hide'] ?? '';


    $stateInsertCom = $pdo->prepare('INSERT INTO commentaire (commentaire, idSouhait, idUser) VALUES
        (:commentaire, :idSouhait, :idUser) ');
    $stateInsertCom->bindValue(':commentaire', $commentaire);
    $stateInsertCom->bindValue(':idSouhait', $idSouhait);
    $stateInsertCom->bindValue(':idUser', $user['idUser']);
    // var_dump($idSouhait);
    // var_dump($user['idUser']);
    // die;
    $stateInsertCom->execute();

    $stateReadCom = $pdo->prepare("SELECT * FROM commentaire 
JOIN user ON commentaire.idUser=user.idUser
JOIN souhait ON commentaire.idSouhait=souhait.idSouhait
WHERE souhait.idSouhait=:id");


    $stateReadCom->bindValue(':id', $idSouhait);
    $stateReadCom->execute();
    $allComByWish = $stateReadCom->fetchAll();
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/wishesContenu.css">
    <title>AVS</title>
</head>

<body>
<main class="souhaitcontainer">
    <div class="header">
    <?php require_once 'includes/header.php' ?>
    </div>
  
    <div class="contentSouhait">
        <div class="imgSouhait">
        <img class="avatarimg" src="./upload/<?= $oneWish['img'] ?>" alt="">
        </div>

        <div class="infoSouhait">
      
            <div class="titleSouhait">
                <div class="elem1">
                    <h2 class="title">Souhait :</h2>
                    <p class="txtTitle"><?= $oneWish['titre'] ?? '' ?></p>
                </div>

                <div class="elem2"><?= $oneWish['categorie'] ?? '' ?></div>
            </div>

            <div class="descritpifSouhait">
                <h2 class="title">Description :</h2>
                <p class="txtDesc"><?= $oneWish['descriptif'] ?? '' ?></p>
            </div>
        </div>
    
        <div class="calendar">
            <div class="dateSaisie">
                <img src="/assets/img/calendar.png" alt=""  class="iconCalendar"> 
                <p class="date"><?= $oneWish['dateAjout'] ?? '' ?></p>
            </div>
            <div class="dateRea">
                <img src="/assets/img/calendar.png" alt="" class="iconCalendar"> 
                <p class="date"><?= $oneWish['dateRealisation'] ?? '' ?></p>
            </div>
        </div>
</div>

        <div class="resumeSouhait">
                <h2 class="title">Souhait exaucé :</h2>
                <p class="txtresum"><?= $oneWish['resume'] ?? '' ?></p>
        </div>
      <h2 class="titlecom">Commentaires : </h2>

<form action="/WishesContenu.php" method="POST" class="form-container2">
<div class="form-popup" id="popupCom">

    <?php foreach ($allComByWish as $a) : ?>

            <div class="content-hight">
                <div class="avatar_pseudo">
                    <img class="avatar" src="./upload/<?= $a['image'] ?? '' ?>" alt=""></span>
                    <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                </div>

                <div class="time"><?= $a['dateCom'] ?? ''?></div>

                <div class="blocCom">
                    <p><?= $a['commentaire'] ?? '' ?></p>
                </div>
            </div>

    <?php endforeach ?>
</div>



<div class="content-low">

    <input type="hidden" name="hide" value="<?=$idSouhait?>">
    <textarea type="text" id="commentaire" placeholder="Commentaire" name="commentaire" rows="70px"></textarea>
    <button type="submit" class="btnSubmit"><img src="/assets/img/send.png" alt="send" width="30px"></button>


</form>
</div>
<a href="/wishesEncours.php">
    <button type="button" class="bn633-hover bn29" >Retour</button>
</a>
</main>
    
</body>
</html>