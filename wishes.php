<?php
require_once './isLoggedIn.php';
$user = isLoggedIn();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/wishes.css">
    <title>AVS_souhaits</title>
</head>
<body>

    <main class="container">
        <div class="header">
            <?php require_once 'includes/header.php' ?>
        </div>
        <article class="content">
            <div class="avslogo">
            <img src="/assets/img/avs.png" alt="logo AVS" width="40%" class="logo">
            </div>

            <div class="blocChoice">
               
            <div class="bloc1">
                <a href="/wishesEncours.php" class="bloc1"><button class="btn">
                    <section class="bloc_encours">                        
                            <div class="imgEncours">
                                <img src="/assets/img/dreamcatcher.png" alt="souhaits_enCours" width="70px">
                            </div>
                            <div class="enCours">
                                <h2 class="title">Souhaits en cours</h2>
                                <br>
                                <p class="txt">Tout commence par un rêve.
                                    Pour qu'il se réalise partage le avec la communauté ....
                                </p>
                            </div>
                    </section>
                </button></a>
            </div>

            <div class="bloc2">
                <a href="/wishesRealises.php"><button class="btn">
                    <section class="bloc_realise">
                        <div class="imgRealise">
                            <img src="/assets/img/peace.png" alt="souhaits_realise" width="70px">
                        </div>
                        <div class="realise">
                            <h2 class="title">Souhaits réalisés</h2>
                            <br>
                            <p class="txt">comme quoi tout est possible ... regarde
                            </p>
                        </div>
                </section>
                </button></a>
            </div>

            </div>
        </article>
    </main>
</body>
</html>

