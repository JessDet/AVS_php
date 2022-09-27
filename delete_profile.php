<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();
if (!$user) {
    header('Location: /index.php');
}


$pdo = require './database.php';
$idUser=$user['idUser'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $statusUser = true;

    $stateUpdate= $pdo -> prepare('UPDATE user SET statusUser=:statusUser WHERE idUser=:idUser');
    $stateUpdate->bindValue (':statusUser', $statusUser);
    $stateUpdate->bindValue (':idUser', $idUser);

    $stateUpdate->execute();

    header('Location: /login.php');
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/delete_profile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>AVS</title>
</head>

<body>
<main class="souhaitcontainer">
    <div class="header">
    <?php require_once 'includes/header.php' ?>
    </div>

    <div class="titlesuppr">
        <h1>SUPPRESSION</h1>
    </div>

    <div class="justifDelete">
        <p>Etes-vous sûre de vouloir supprimer votre compte ?</p>          
    </div>

    <div class="btn_ok"> 
        <form action="/delete_profile.php" method="POST">
            <button id="btn_ok" class="bn632-hover bn28">Supprimer</button>
        </form> 
        <a href="/settings.php"><button type="button" class="bn633-hover bn29">Annuler</button></a>
    </div>

        <!-- <div class="login-popup">
            <div class="form-popup" id="popupForm">
                 <form action="/index.php" method="POST" class="form-container">
            
                    <div class="validsuppr">
                        <p>Votre compte est bien supprimé !</p>
                    </div>
                    <div class="btn">
                        <button type="button" class="bn633-hover bn29" onclick="closeForm()">ok</button>
                    </div>
                    </form>
                    
            </div>
        </div> -->
        <!-- <script>
            function openForm() {
                document.getElementById("popupForm").style.display = "block";
            }

            function closeForm() {
                document.getElementById("popupForm").style.display = "none";
            }
        </script> -->
 
        </main>
</body>
</html>