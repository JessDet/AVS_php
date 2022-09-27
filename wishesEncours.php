<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}

$pdo = require './database.php';

$stateRead = $pdo->prepare("SELECT idSouhait, titre,categorie,descriptif, dateAjout, user.pseudo, user.image 
    FROM souhait JOIN user 
    WHERE souhait.idUser = user.idUser and souhait.status=0");
$stateRead->execute();
$allWishes = $stateRead->fetchAll();

$categories = [];

$selectedCat = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';



if (count($allWishes)) {
    $categories = array_map(fn ($a) => $a['categorie'], $allWishes);


    $cat = array_reduce($categories, function ($acc, $c) {               //categories music/serie/film, une fonction et [ le stockage] // pour virer les doublons 
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

    header('Location: /wishesEncours.php');
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

        <div class="open-btn">
            <button class="open-button" onclick="openForm()"><img src="/assets/img/add.png" alt=""></button>
        </div>

        <div class="avslogo">
            <img src="/assets/img/AVS.png" alt="" class="logo" width="150px">
        </div>



        <div class="encours">
            <H1 class="title">SOUHAITS EN COURS</H1>
        </div>

        <div id="categories">
            <ul class="category-container">

                <li class="fz">
                    <a class="cat" href="/wishesEncours.php" style="color:white;">Tous les Souhaits<span class="small">(<?= count($allWishes) ?>)</span></a>
                </li>
                <?php foreach ($cat ?? [] as $cKey => $cNum) : ?>
                    <li class="fz">
                        <a class="cat" style="color:white;" href="/wishesEnCours.php?cat=<?= $cKey ?>"><?= $cKey ?><span class="small">(<?= $cNum ?>)</span></a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>

        <article class=" content">
            <?php if (!$selectedCat) : ?>
                <?php foreach ($cat ?? [] as $c => $num) : ?>
                    <?php foreach ($artPerCat[$c] as $a) : ?>

                        <a href="/wishesContenu.php?idSouhait=<?= $a['idSouhait'] ?>" class="blocsouhait"><button class="btnContenu">
                                <div class="wish_container">
                                    <section class="high">
                                        <div class="avatar_pseudo">
                                            <img class="avatar" src="./upload/<?= $a['image'] ?>" alt="">
                                            <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                                        </div>
                                        <div>
                                            <h3 class="date"><?= $a['dateAjout'] ?? '' ?></h3>
                                        </div>
                                    </section>

                                    <section class="low">
                                        <div class="title_description">
                                            <h2><?= $a['titre'] ?? '' ?></h2>
                                            <textarea class="descriptif" name="descriptif" rows="10px"><?= $a['descriptif'] ?? '' ?></textarea>
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

                        <a href="/wishesContenu.php?id=<?= $a['idSouhait'] ?>" class="blocsouhait"><button class="btnContenu">
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

        <div class="login-popup">
            <div class="form-popup" id="popupForm">
                <form action="/wishesEncours.php" method="POST" class="form-container">

                    <h2 class="titlepopup">Nouveau souhait</h2>

                    <label for="category"></label>
                    <select type="text" name="categorie" id="category">
                        <option value="">Choisis une catégorie</option>
                        <option value="amour">Amour</option>
                        <option value="loisir">Loisir</option>
                        <option value="rencontre">Rencontre</option>
                        <option value="voyage">Voyage</option>
                        <option value="sport">Sport</option>
                        <option value="autre">Autre</option>
                    </select>

                    <label for="title"></label>
                    <input type="text" id="title" placeholder="Titre" name="titre">

                    <label for="description"></label>
                    <textarea type="text" id="description" placeholder="Description" name="descriptif" rows="70px"></textarea>

                    <label for="zoneGeo"></label>
                    <select type="text" id="zoneGeo" placeholder="Description" name="zoneGeo" value="">
                        <option value="Zone Geographique">Zone Geographique</option>
                        <option value="1 - Ain">1 - Ain</option>
                        <option value="2 - Aisne">2 - Aisne</option>
                        <option value="3 - Allier">3 - Allier</option>
                        <option value="4 - Alpes-de-Haute-Provence">4 - Alpes-de-Haute-Provence</option>
                        <option value="5 - Hautes-Alpes">5 - Hautes-Alpes</option>
                        <option value="6 - Alpes-Maritimes">6 - Alpes-Maritimes</option>
                        <option value="7 - Ardèche">7 - Ardèche</option>
                        <option value="8 - Ardennes">8 - Ardennes</option>
                        <option value="9 - Ariège">9 - Ariège</option>
                        <option value="10 - Aube">10 - Aube</option>
                        <option value="11 - Aude">11 - Aude</option>
                        <option value="12 - Aveyron">12 - Aveyron</option>
                        <option value="13 - Bouches-du-Rhône">13 - Bouches-du-Rhône</option>
                        <option value="14 - Calvados">14 - Calvados</option>
                        <option value="15 - Cantal">15 - Cantal</option>
                        <option value="16 - Charente">16 - Charente</option>
                        <option value="17 - Charente-Maritime">17 - Charente-Maritime</option>
                        <option value="18 - Cher">18 - Cher</option>
                        <option value="19 - Corrèze">19 - Corrèze</option>
                        <option value="21 - Côte-d'Or">21 - Côte-d'Or</option>
                        <option value="22 - Côtes-d'Armor">22 - Côtes-d'Armor</option>
                        <option value="23 - Creuse">23 - Creuse</option>
                        <option value="24 - Dordogne">24 - Dordogne</option>
                        <option value="25 - Doubs">25 - Doubs</option>
                        <option value="26 - Drôme">26 - Drôme</option>
                        <option value="27 - Eure">27 - Eure</option>
                        <option value="28 - Eure-et-Loir">28 - Eure-et-Loir</option>
                        <option value="29 - Finistère">29 - Finistère</option>
                        <option value="2A - Corse-du-Sud">2A - Corse-du-Sud</option>
                        <option value="2B - Haute-Corse">2B - Haute-Corse</option>
                        <option value="30 - Gard">30 - Gard</option>
                        <option value="31 - Haute-Garonne">31 - Haute-Garonne</option>
                        <option value="32 - Gers">32 - Gers</option>
                        <option value="33 - Gironde">33 - Gironde</option>
                        <option value="34 - Hérault">34 - Hérault</option>
                        <option value="35 - Ille-et-Vilaine">35 - Ille-et-Vilaine</option>
                        <option value="36 - Indre">36 - Indre</option>
                        <option value="37 - Indre-et-Loire">37 - Indre-et-Loire</option>
                        <option value="38 - Isère">38 - Isère</option>
                        <option value="39 - Jura">39 - Jura</option>
                        <option value="40 - Landes">40 - Landes</option>
                        <option value="41 - Loir-et-Cher">41 - Loir-et-Cher</option>
                        <option value="42 - Loire">42 - Loire</option>
                        <option value="43 - Haute-Loire">43 - Haute-Loire</option>
                        <option value="44 - Loire-Atlantique">44 - Loire-Atlantique</option>
                        <option value="45 - Loiret">45 - Loiret</option>
                        <option value="46 - Lot">46 - Lot</option>
                        <option value="47 - Lot-et-Garonne">47 - Lot-et-Garonne</option>
                        <option value="48 - Lozère">48 - Lozère</option>
                        <option value="49 - Maine-et-Loire">49 - Maine-et-Loire</option>
                        <option value="50 - Manche">50 - Manche</option>
                        <option value="51 - Marne">51 - Marne</option>
                        <option value="52 - Haute-Marne">52 - Haute-Marne</option>
                        <option value="53 - Mayenne">53 - Mayenne</option>
                        <option value="54 - Meurthe-et-Moselle">54 - Meurthe-et-Moselle</option>
                        <option value="55 - Meuse">55 - Meuse</option>
                        <option value="56 - Morbihan">56 - Morbihan</option>
                        <option value="57 - Moselle">57 - Moselle</option>
                        <option value="58 - Nièvre">58 - Nièvre</option>
                        <option value="59 - Nord">59 - Nord</option>
                        <option value="60 - Oise">60 - Oise</option>
                        <option value="61 - Orne">61 - Orne</option>
                        <option value="62 - Pas-de-Calais">62 - Pas-de-Calais</option>
                        <option value="63 - Puy-de-Dôme">63 - Puy-de-Dôme</option>
                        <option value="64 - Pyrénées-Atlantiques">64 - Pyrénées-Atlantiques</option>
                        <option value="65 - Hautes-Pyrénées">65 - Hautes-Pyrénées</option>
                        <option value="66 - Pyrénées-Orientales">66 - Pyrénées-Orientales</option>
                        <option value="67 - Bas-Rhin">67 - Bas-Rhin</option>
                        <option value="68 - Haut-Rhin">68 - Haut-Rhin</option>
                        <option value="69 - Rhône">69 - Rhône</option>
                        <option value="70 - Haute-Saône">70 - Haute-Saône</option>
                        <option value="71 - Saône-et-Loire">71 - Saône-et-Loire</option>
                        <option value="72 - Sarthe">72 - Sarthe</option>
                        <option value="73 - Savoie">73 - Savoie</option>
                        <option value="74 - Haute-Savoie">74 - Haute-Savoie</option>
                        <option value="75 - Paris">75 - Paris</option>
                        <option value="76 - Seine-Maritime">76 - Seine-Maritime</option>
                        <option value="77 - Seine-et-Marne">77 - Seine-et-Marne</option>
                        <option value="78 - Yvelines">78 - Yvelines</option>
                        <option value="79 - Deux-Sèvres">79 - Deux-Sèvres</option>
                        <option value="80 - Somme">80 - Somme</option>
                        <option value="81 - Tarn">81 - Tarn</option>
                        <option value="82 - Tarn-et-Garonne">82 - Tarn-et-Garonne</option>
                        <option value="83 - Var">83 - Var</option>
                        <option value="84 - Vaucluse">84 - Vaucluse</option>
                        <option value="85 - Vendée">85 - Vendée</option>
                        <option value="86 - Vienne">86 - Vienne</option>
                        <option value="87 - Haute-Vienne">87 - Haute-Vienne</option>
                        <option value="88 - Vosges">88 - Vosges</option>
                        <option value="89 - Yonne">89 - Yonne</option>
                        <option value="90 - Territoire de Belfort">90 - Territoire de Belfort</option>
                        <option value="91 - Essonne">91 - Essonne</option>
                        <option value="92 - Hauts-de-Seine">92 - Hauts-de-Seine</option>
                        <option value="93 - Seine-Saint-Denis">93 - Seine-Saint-Denis</option>
                        <option value="94 - Val-de-Marne">94 - Val-de-Marne</option>
                        <option value="95 - Val-d'Oise">95 - Val-d'Oise</option>
                        <option value="971 - Guadeloupe">971 - Guadeloupe</option>
                        <option value="972 - Martinique">972 - Martinique</option>
                        <option value="973 - Guyane">973 - Guyane</option>
                        <option value="974 - La Réunion">974 - La Réunion</option>
                        <option value="976 - Mayotte">976 - Mayotte</option>
                    </select>

                    <button type="submit" class="bn632-hover bn28">Valider</button>
                    <button type="button" class="bn633-hover bn29" onclick="closeForm()">Annuler</button>

                </form>

            </div>

        </div>

        <script>
            function openForm() {
                document.getElementById("popupForm").style.display = "block";
            }

            function closeForm() {
                document.getElementById("popupForm").style.display = "none";
            }
        </script>

    </main>

</body>

</html>