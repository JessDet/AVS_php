<?php
require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}


$pdo = require './database.php';

$idUser = $user['idUser'];

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

        $idUser = $user['idUser'];
        $image = json_encode($file);

        $req = $pdo->prepare('UPDATE user
        SET
        image=:image
        WHERE idUser=:idUser');
        $req->bindValue(':image', json_decode($image));
        $req->bindValue(':idUser', $idUser);
        $req->execute();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo = $_POST['pseudo'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $email = $_POST['email'] ?? '';
    $idUser = $user['idUser'];

    $stateUpdate = $pdo->prepare('
UPDATE user
SET
pseudo=:pseudo,
ville=:ville,
email=:email
WHERE idUser=:idUser'
    );

    $stateUpdate->bindValue(':pseudo',  $pseudo);
    $stateUpdate->bindValue(':ville', $ville);
    $stateUpdate->bindValue(':email', $email);
    $stateUpdate->bindValue(':idUser', $idUser);

    $stateUpdate->execute();

    header('Location: /profile.php');
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>

    <link rel="stylesheet" href="/public/css/modifprofile.css">
    <title>AVS</title>
</head>

<body>


    <main class="profilcontainer">
        <div class="header">
            <?php require_once 'includes/header.php' ?>
        </div>
        <div class="titleprofil">
            <h1> MODIFICATION DE PROFIL</h1>
        </div>
        <div class="infoProfil">
            <div class="avatar">
                <img src="./upload/<?= $user['image'] ?>" alt="avatar" class="avatarimg">
            </div>

            <div class="info">
                <div class="wrapper">
                    <form class="form-container" action="/modifProfile.php" method="POST" enctype="multipart/form-data">

                        <input class="input-file" type="file" name="file">

                        <div  class="input-data">
                            <input type="text" name="pseudo" id="pseudo" value="<?= $user['pseudo'] ?? '' ?>">

                        </div>

                        <div class="input-data">
                            <input type="text" name="ville" id="ville" value="<?= $user['ville'] ?? '' ?>">
                        </div>

                        <div class="input-data">
                            <input type="text" name="email" id="email" value="<?= $user['email'] ?? '' ?>">
                        </div>

                        <button type="submit" class="bn632-hover bn28">Enregistrer</button>
                        <a href="/profile.php"><button type="" class="bn633-hover bn29">Annuler</button></a>
                        
                    </form>
                </div>
            </div>

        </div>
    </main>
</body>
</html>