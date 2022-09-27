<?php


require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}


$pdo = require './database.php';

$idUser = $user['idUser'];

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//     $motDePasse = $_POST['motDePasse'] ?? '';


//     $stateUpdate = $pdo->prepare('
// UPDATE user
// SET
// motDePasse=:motDePasse,
// WHERE idUser=:idUser'
//     );

//     $stateUpdate->bindValue(':motDePasse',  $motDePasse);
//     $stateUpdate->bindValue(':idUser', $idUser);
//     $stateUpdate->execute();

//     header('Location: /update_mdp.php');
// }
 // Comparaison mot de passe
 $pdo = $bdd->prepare("SELECT * FROM user WHERE idUser = :id");
 $pdo->bindValue(':id',$_SESSION['user']['idUser'], PDO::PARAM_INT);
 $pdo->execute();
 $row = $pdo->fetch(PDO::FETCH_ASSOC);
 $ancien_motDePasse = $row['motDePasse'];

 echo '<br>'.$ancien_password;
   

// Si on propose le formulaire
if(!empty($_POST)) {

   // Si les mots de passe sont les mêmes
    if($ancien_motDePasse  == password_hash($_POST['motDePasse'])) {

           // On Change le mot de pqsse
            $stmt = $bdd->prepare('UPDATE user SET motDePasse = :motDePasse WHERE email = :email AND idUser = :idUser');
            $stmt->bindValue('email', $_SESSION['user']['email']);
            $stmt->bindValue('idUser', $_SESSION['user']['idUser']);
            $stmt->bindValue('motDePasse', password_hash($_POST['motDePasse']));

}
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/update_mdp.css">
    <title>AVS</title>
</head>

<body>
<main class="upmdp_container">
    <div class="header">
        <?php require_once 'includes/header.php' ?>
    </div>

    

    <div class="titleupdate">
            <h1>MODIFICATION</h1>
            <h2>MOT DE PASSE</h2>
    </div>

    <div class="container_upmdp">
        <form class="form_upmdp" action="/update_mdp.php" method="post"  enctype="multipart/form-data">
   
                <label for="ancien_mdp">ancien mot de passe</label>
                <input type="text" id="ancien_mdp" placeholder="" name="motDePasse">
               
                <label for="nouveau_mdp">nouveau mot de passe</label>
                <input type="text" id="nouveau_mdp" placeholder="" name="motDePasse">

                <label for="confirm_nouveau_mdp">confirmation mot de passe</label>
                <input type="text" id="confirm_nouveau_mdp" placeholder="" name="motDePasse">

        </form>

        <button class="bn632-hover bn28" onclick="openForm()">Enregistrer</button>
        <a href="/settings.php"><button type="submit" class="bn633-hover bn29">Annuler</button></a>
        


    </div>



    <div class="login-popup">
            <div class="form-popup" id="popupForm">
                 <form action="/wishesEncours.php" method="POST" class="form-container">
 

                    <h2 class="validation">Mot de passe modifié avec succés !</h2>


    <button type="button" class="bn632-hover bn28" onclick="closeForm()">X</button>
   </form> </div></div>

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