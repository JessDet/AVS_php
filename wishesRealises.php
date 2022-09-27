<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}

$pdo = require './database.php';

$stateRead = $pdo->prepare("SELECT idSouhait,titre,categorie,descriptif, dateAjout, dateRealisation, user.pseudo, user.image 
    FROM souhait JOIN user 
    WHERE souhait.idUser = user.idUser and souhait.status=1");
$stateRead->execute();
$allWishes = $stateRead->fetchAll();

$categories = [];

$selectedCat = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';



if (count($allWishes)) {
    $categories = array_map(fn ($a) => $a['categorie'], $allWishes);


    $cat = array_reduce($categories, function ($acc, $c) {               //categories musique/voyage/sport ..., une fonction et [ le stockage] // pour virer les doublons 
        if (isset($acc[$c])) {
            $acc[$c]++;
        } else {
            $acc[$c] = 1;
        }
        return $acc;
    }, []);



    $artPerCat = array_reduce($allWishes, function ($acc, $art) {
        if (isset($acc[$art['categorie']])) {
            $acc[$art['categorie']] = [...$acc[$art['categorie']], $art];
        } else {
            $acc[$art['categorie']] = [$art];
        }
        return $acc;
    }, []);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [
        'titre' => FILTER_SANITIZE_SPECIAL_CHARS,
        'categorie' => FILTER_SANITIZE_SPECIAL_CHARS,
        'descriptif' => FILTER_SANITIZE_SPECIAL_CHARS,
        'zoneGeo' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);

    $titre = $_input['titre'] ?? "";
    $categorie = $_input['categorie'] ?? "";
    $descriptif = $_input['descriptif'] ?? "";
    $zoneGeo = $_input['zoneGeo'] ?? '';



    $ajoutSouhait = $pdo->prepare('INSERT INTO souhait (
         
            titre,
            categorie,
            descriptif,
            zoneGeo,
            idUser
        ) 
        VALUES (
        
            :titre,
            :categorie,
            :descriptif,
            :zoneGeo,
            :idUser)');

    $ajoutSouhait->bindValue(':titre',  $titre);
    $ajoutSouhait->bindValue(':categorie',  $categorie);
    $ajoutSouhait->bindValue(':descriptif',  $descriptif);
    $ajoutSouhait->bindValue(':zoneGeo',  $zoneGeo);
    $ajoutSouhait->bindValue(':idUser',  $user['idUser']);
    $ajoutSouhait->execute();

    header('Location: /wishesRealises.php');
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <script src="./vendor/jquery/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="/public/css/wishesEncours.css">

    <title>AVS</title>
</head>

<body>

<main class="container">

            <div class="header">
                <?php require_once 'includes/header.php' ?>
            </div>
       

        <div class="avslogo">
            <img src="/assets/img/AVS.png" alt="" class="logo" width="150px">
        </div>

        <div class="encours">
            <H1 class="title">SOUHAITS REALISES</H1>
        </div>

        <div id="categories">
            <ul class="category-container">
               
                <li class="fz">
                    <a class="cat" href="/wishesRealises.php" style="color:white;">Tous les Souhaits<span class="small">(<?= count($allWishes) ?>)</span></a>
                </li>
                <?php foreach ($cat ?? [] as $cKey => $cNum) : ?>
                    <li class="fz"> 
                        <a class="cat" style="color:white;" href="/wishesRealises.php?cat=<?= $cKey ?>"><?= $cKey ?><span class="small">(<?= $cNum ?>)</span></a>
                    </li>
                <?php endforeach; ?>
                    
            </ul>
        </div>
        
        <article class=" content">
            <?php if (!$selectedCat) : ?>
            <?php foreach ($cat ?? [] as $c => $num) : ?>
            <?php foreach ($artPerCat[$c] as $a) : ?>

                <a href="/wishesContenuRealises.php?id=<?= $a['idSouhait'] ?>" class="blocsouhait"><button class="btnContenu">
                <div class="wish_container">
                    <section class="high">
                        <div class="avatar_pseudo">
                            <img class="avatar" src="./upload/<?= $a['image'] ?>" alt="">
                            <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                        </div>
                        <div>
                            <h3 class="date">posté le :<?= $a['dateAjout'] ?? '' ?></h3>
                            <h3 class="date">réalisé le :<?= $a['dateRealisation'] ?? '' ?></h3>
                        </div>
                    </section>

                    <section class="low">
                        <div class="title_description">
                            <h2><?= $a['titre'] ?? '' ?></h2>
                            <p><?= $a['descriptif'] ?? '' ?></p>
                        </div>
                        <div class="comment_category">
                            <span><?= $a['categorie'] ?? '' ?></span>
                        </div>
                    </section>
                </div>
            </button></a>

            <?php endforeach; ?>
            <?php endforeach; ?>
            <?php else : ?>

    <h2 style="color:orange;
    display:flex;
    flex-direction: column;
    align-items: center;
    "><?= $selectedCat ?></h2>

    <div>
        <?php foreach ($artPerCat[$selectedCat] as $a) : ?>

            <a href="/wishesContenuRealises.php?id=<?= $a['idSouhait'] ?>" class="blocsouhait"><button class="btnContenu">
                    <div class="wish_container">
                        <section class="high">
                            <div class="avatar_pseudo">
                                <img class="avatar" src="./upload/<?= $a['image'] ?>" alt=""></span>
                                <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                            </div>
                            <div>
                                <h3 class="date"><?= $a['dateAjout'] ?? '' ?></h3>
                            </div>
                        </section>
                        <section class="low">
                            <div class="title_description">
                                <h2><?= $a['titre'] ?? '' ?></h2>
                                <p><?= $a['descriptif'] ?? '' ?></p>
                            </div>
                            <div class="comment_category">
                                <span><?= $a['categorie'] ?? '' ?></span>
                            </div>
                        </section>
                    </div>
                </button></a>


        <?php endforeach; ?>

    </div>

<?php endif; ?>


</article>


</div>


    </main>


</body>

</html>