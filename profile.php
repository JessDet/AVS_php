<?php


require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}


$pdo = require './database.php';

$StateRead = $pdo->prepare("SELECT image,pseudo,ville,dateDeNaissance FROM user WHERE idUser= :idUser"); 
$StateRead->bindValue(':idUser', $user['idUser']);
$StateRead->execute();
$myProfil = $StateRead->fetchAll();
// print_r($myProfil);

$StateCount= $pdo->prepare('SELECT COUNT(idSouhait) FROM `souhait` WHERE idUser=:idUser');
$StateCount->bindValue(':idUser', $user['idUser']);
$StateCount->execute();
$countWishes = $StateCount->fetch();
// print_r($countWishes);

$dateNaissance =  $user['dateDeNaissance'];
$aujourdhui = date("Y-m-d");
$diff = date_diff(date_create($dateNaissance), date_create($aujourdhui));
// echo 'Votre age est '.$diff->format('%y');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
<?php require_once 'includes/head2.php' ?>

    <link rel="stylesheet" href="/public/css/profile.css">
    <title>AVS</title>
</head>

<body>

    
<main class="profilcontainer">
    <div class="header">
        <?php require_once 'includes/header.php' ?>
    </div>

<?php foreach ($myProfil as $a) : ?>
    <div class="titleprofil">
        <p>PROFIL</p>
    </div>

    <div class="infoProfil">
        <div class="avatar">
            <img src="./upload/<?= $a['image']?>" alt="avatar" class="avatarimg">
        </div>

    <div class="info">
        <ul>
            <div class="pseudo">
                <p><?= $a['pseudo'] ?? '' ?></p>
            </div>
            <div class="ville">
                <img src="assets/img/geoloc.png" alt="localisation" width="25px" height="25px" class="iconProf">
                <p class="pInfo"><?= $a['ville'] ?? '' ?></p>
            </div>
            <div class="birthday">
                <img src="assets/img/anniversaire.png" alt="birthday" width="25px" height="25px" class="iconProf">
                <p class="pInfo"><?= $diff->format('%y') ?></p>
            </div>
        </ul>
    </div>


<div class="blocItems">

    <div class="firstItems">
        <div class="archives">
            <p class="numb"><?= implode($countWishes) ?></p>
            <p class="txtItems">archives</p>
            <a href="archives.php"><img src="assets/img/connect.png" alt="enter" width="25px" height="25px" class="iconProf"></a>
        </div>
        <div class="souhaits">
            <p class="numb"><?= implode($countWishes) ?></p>
            <p class="txtItems">souhaits</p>
            <a href="myWishes.php"><img src="assets/img/connect.png" alt="enter" width="25px" height="25px" class="iconProf"></a>
        </div>
    </div>


    <div class="amis">
        <p class="numb">#</p>
        <p class="txtItems">amis</p>
        <a href="amis.php"><img src="assets/img/connect.png" alt="enter" width="25px" height="25px" class="iconProf"></a>
    </div>
</div>
<?php endforeach; ?>

    <div class="button-modif">
        <a href="modifProfile.php"><input type="button" value="modifier le profil" id="btn-modif"></a>
    </div>
</main>
    
</body>

</html>