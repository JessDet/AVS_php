<?php
require_once './isLoggedIn.php';
$user = isLoggedIn();

$pdo = require './database.php';

$stateRead = $pdo->prepare("SELECT *
FROM souhait JOIN user 
ON souhait.idUser=:idUser WHERE idSouhait=:id");

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? $_POST['hide'] ??  null;
$idUser = $user['idUser'];

$stateRead->bindValue(':id', $id);
$stateRead->bindValue(':idUser', $idUser);
$stateRead->execute();
$oneWish = $stateRead->fetch();

$idSouhait = $oneWish['idSouhait'] ??  null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categorie = $_POST['categorie'] ?? '';
    $hide = $_POST['hide'] ?? '';
    $titre = $_POST['titre'] ?? '';
    $descriptif = $_POST['descriptif'] ?? '';
    $zoneGeo = $_POST['zoneGeo'] ?? '';
    $resume = $_POST['resume'] ?? '';
    $dateRealisation = $_POST['dateRealisation'] ?? '';
   
    // $idSouhait = $oneWish['idSouhait'];

    $stateUpdate = $pdo->prepare(
        'UPDATE souhait
            SET
                categorie=:categorie,
                titre=:titre,
                descriptif=:descriptif,
                zoneGeo=:zoneGeo,
                resume=:resume,
                dateRealisation=:dateRealisation

                WHERE idSouhait=:id AND idUser=:idUser'
    );

    $stateUpdate->bindValue(':categorie',  $categorie);
    $stateUpdate->bindValue(':titre', $titre);
    $stateUpdate->bindValue(':descriptif', $descriptif);
    $stateUpdate->bindValue(':zoneGeo', $zoneGeo);
    $stateUpdate->bindValue(':resume', $resume);
    $stateUpdate->bindValue(':dateRealisation', $dateRealisation);
 
    $stateUpdate->bindValue(':id', $hide);
    $stateUpdate->bindValue(':idUser', $user['idUser']);
    $stateUpdate->execute();

    if (isset($_POST['status']) && $_POST['status'] == 'true') 
{
    $status = 1;

    $stateUpdate = $pdo->prepare(
        'UPDATE souhait
            SET
                status=:status
                WHERE idSouhait=:id AND idUser=:idUser'
    );
    $stateUpdate->bindValue(':status', $status);
    $stateUpdate->bindValue(':id', $hide);
    $stateUpdate->bindValue(':idUser', $user['idUser']);
    $stateUpdate->execute();

}

    if (isset($_FILES['file'])) {
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $maxSize = 400000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            $file = $uniqueName . "." . $extension;

            move_uploaded_file($tmpName, './upload/' . $file);

            $img = json_encode($file);

            $stateRead = $pdo->prepare("SELECT * FROM souhait WHERE idSouhait=:id");

            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_GET['id'] ?? '';


            $stateRead->bindValue(':id', $id);
            $stateRead->execute();
            $oneWish = $stateRead->fetch();

            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_GET['id'] ?? '';

            $req = $pdo->prepare('UPDATE souhait
            SET
            img=:img
            WHERE idSouhait=:id AND idUser=:idUser');
            $req->bindValue(':img', json_decode($img));
            $req->bindValue(':id', $hide);
            $req->bindValue(':idUser', $user['idUser']);
            $req->execute();

        }

    }

    header('Location: /myWishesContenu.php?id=' . $_POST['hide'] . '');

}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/wishesModif.css">
    <title>AVS</title>
</head>

<body>
<main class="souhaitcontainer">
    <div class="header">
    <?php require_once 'includes/header.php' ?>
    </div>

<div class="form-update" id="updateForm">
        <form action="/myWishesModif.php" method="POST" class="form-container" enctype="multipart/form-data">
 


    <div class="titleModif">
        <h1>MODIFICATION</h1>
    </div>

    <label for="categories"></label>
            <select type="text" name="categorie" id="categories" value="">
                <option value="<?= $oneWish['categorie'] ?? '' ?>"><?= $oneWish['categorie'] ?? '' ?></option>
                <option value="amour">Amour</option>
                <option value="loisir">Loisir</option>
                <option value="rencontre">Rencontre</option>
                <option value="voyage">Voyage</option>
                <option value="sport">Sport</option>
                <option value="autre">Autre</option>
            </select>

            <input type="hidden" name="hide" value="<?= $idSouhait ?>">

    <label for="title"></label>
    <input type="text" id="title" placeholder="Titre" name="titre" value="<?= $oneWish['titre'] ?? '' ?>">                                          

    <label for="description"></label>
    <textarea type="text" id="description" placeholder="Description" name="descriptif" rows="10px" value=""><?= $oneWish['descriptif'] ?? '' ?></textarea>
   
    <label for="zoneGeo"></label>
                    <select type="text" id="zoneGeo" placeholder="Description" name="zoneGeo" value="">
                        <option value="<?= $oneWish['zoneGeo'] ?? '' ?>"><?= $oneWish['zoneGeo'] ?? '' ?></option>
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



    <label for="resume"></label>
<textarea type="text" id="resume" placeholder="Résumé" name="resume"  rows="10px"><?= $oneWish['resume'] ?? '' ?></textarea>

    <div class="imgSouhait">
        <img class="avatarimg" src="./upload/<?= $oneWish['img'] ?? '' ?>" alt="">
        <input type="file" class="input-file" name="file">
    </div>

    <div class="date_realise">
        <label style="color:white" class="dateReal">Date de réalisation:</label>
        <input type="date"  id="dateReal" placeholder="Date de realisation" name="dateRealisation" value="<?= $oneWish['dateRealisation'] ?? '' ?>">
    </div>

    <div class="slideTwo">
        <!-- <h3>réalisé ?</h3> -->
        <input type="checkbox" value="true" id="slideTwo" name="status" />
        <label for="slideTwo"></label>
    </div>



<div class="iconsfinpage">
    
    <a class="modifier" href="/myWishesContenu.php?id=<?= $idSouhait ?>">
        <button class="btnValider">Valider
        <img class="iconmodif" src="/assets/img/check.png" alt="modifier"></button>
    </a>

    <a class="supprimer" href="/myWishesDelete.php?id=<?= $oneWish['idSouhait'] ?>">
                <p class="txtpart">Supprimer</p>
                <img class="iconSuppr" src="/assets/img/corbeille.png" alt="supprimer">
            </a>


</div>
</form>
<a href="/myWishesContenu.php?id=<?=$oneWish ['idSouhait']?>">
    <button type="button" class="bn633-hover bn29" >Retour</button>
</a>

</div>

</main>
    
</body>
</html>