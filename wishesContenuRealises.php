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
$id = $_GET['id'] ?? '';


    $stateRead->bindValue(':id', $id);
    $stateRead->execute();
    $oneWish = $stateRead->fetch();
    // var_dump($oneWish);


$stateReadCom = $pdo->prepare("SELECT * FROM `commentaire` 
JOIN user ON commentaire.idUser=user.idUser
JOIN souhait ON commentaire.idSouhait=souhait.idSouhait
WHERE souhait.idSouhait=:id");


$stateReadCom->bindValue(':id', $id);
$stateReadCom->execute();
$allComByWish = $stateReadCom->fetchAll();
// var_dump($allComByWish);



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
                <h2 class="titlecom">Description :</h2>
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
      <form action="/wishesContenuRealises.php" method="POST" class="form-container2">

            <div class="form-popup" id="popupCom">
            
                <?php foreach ($allComByWish as $a) : ?>

                        <div class="content-hight">
                            <div class="avatar_pseudo">
                                <img class="avatar" src="<?= $a['image'] ?? '' ?>" alt=""></span>
                                <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                            </div>

                            <div class="time">1j</div>

                            <div class="blocCom">
                                <p><?= $a['commentaire'] ?? '' ?></p>
                            </div>
                        </div>

                <?php endforeach ?>
            </div>

        

            <div class="content-low">
                <textarea type="text" id="commentaire" placeholder="Commentaire" name="commentaire" rows="70px"></textarea>
                <button type="submit" class="btnSubmit"><img src="/assets/img/send.png" alt="send" width="30px"></button>
            </div>

        </form>


<a href="/wishesRealises.php">
    <button type="button" class="bn633-hover bn29" >Retour</button>
</a>
</main>
    
</body>
</html>