<?php

require_once './isLoggedIn.php';
$user = isLoggedIn();

$pdo = require './database.php';
$error = '';

const ERROR_REQUIRED = "Veuillez renseigner ce champ";
const ERROR_LENGTH = 'Le mot de passe doit contenir <br> 6 caractères  minimum';
const ERROR_EMAIL = "L'email n'est pas valide";
const ERROR_PSEUDO = "Le pseudo doit contenir <br> entre 3 et 12 caractères";

$errors = [
    'motDePasse' => '',
    'email' => '',
    'pseudo' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [
      
        'nom' => FILTER_SANITIZE_SPECIAL_CHARS,
        'prenom' => FILTER_SANITIZE_SPECIAL_CHARS,
        'pseudo' => FILTER_SANITIZE_SPECIAL_CHARS,
        'dateDeNaissance' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL,
        'ville' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);

   
    $nom = $_input['nom'] ?? "";
    $prenom = $_input['prenom'] ?? "";
    $pseudo = $_input['pseudo'] ?? '';
    $dateDeNaissance = $_input['dateDeNaissance'] ?? '';
    $email = $_input['email'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';
    $ville = $_input['ville'] ?? '';


    if (!$nom || !$prenom || !$pseudo || !$dateDeNaissance || !$email || !$motDePasse || !$ville ) {
        $error = "TOUS LES CHAMPS DOIVENT ETRE REMPLIS";
    }

    if (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL;
    }

    if (!$pseudo) {
        $errors['pseudo'] = ERROR_REQUIRED;
    } else if (mb_strlen($pseudo) < 3 || mb_strlen($pseudo) > 12) {
        $errors['pseudo'] = ERROR_PSEUDO;
    }

    if (!$motDePasse) {
        $errors['motDePasse'] = ERROR_REQUIRED;
    } else if (mb_strlen($motDePasse) < 6) {
        $errors['motDePasse'] = ERROR_LENGTH;
    } else {
        $hashPassword = password_hash($motDePasse, PASSWORD_ARGON2I);
        $statement = $pdo->prepare('INSERT INTO user (nom, prenom, pseudo, dateDeNaissance, email, motdePasse, ville)VALUES (
            :nom,
            :prenom,
            :pseudo,
            :dateDeNaissance,
            :email,
            :motDePasse,
            :ville
        )');
   
        $statement->bindvalue(':nom', $nom);
        $statement->bindvalue(':prenom', $prenom);
        $statement->bindvalue(':pseudo', $pseudo);
        $statement->bindvalue(':dateDeNaissance', $dateDeNaissance);
        $statement->bindvalue(':email', $email);
        $statement->bindvalue(':motDePasse', $hashPassword);
        $statement->bindvalue(':ville', $ville);
        $statement->execute();

        header('Location: /login.php');
    }
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/register.css">
    <title>AVS</title>
</head>


<body>
    <main class="container">
      
        <article class="content">
                <div class="titleIns">
                    <h1>INSCRIPTION</h1>
                </div>
            
                <form class="register" action="/register.php" method="POST" id="register_form">
       
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="nom" placeholder="Nom" name="nom" id="nom_input" >
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="text" placeholder="Prenom" name="prenom" id="prenom_input" >
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="text" placeholder="Pseudo (entre 3 et 12 caractères)" name="pseudo" id="pseudo_input" required><br>
                            <?= $errors['pseudo'] ? "<p>" . $errors['pseudo'] . "</p>" : '' ?>
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="date" placeholder="Date de naissance" name="dateDeNaissance" id="dateNaissance_input" required><br>
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="email" placeholder="Email" name="email" id="email_input" required>
                            <?= $errors['email'] ? "<p>" . $errors['email'] . "</p>" : '' ?>
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="password" placeholder="Mot de Passe" name="motDePasse" id="mdp_input" required>
                             <?= $errors['motDePasse'] ? "<p>" . $errors['motDePasse'] . "</p>" : '' ?>
                        </div>
                    </div>
       
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="text" placeholder="Votre ville" name="ville" id="ville_input"><br>
                        </div>
                    </div>
                    <?php if ($error) : ?>
                        <h1 style="color:red; font-size:medium"><?= $error ?></h1>
                    <?php endif; ?>
                    <a href="/"><button class="bn632-hover bn28 inscription">S'inscrire</button></a>
           
                </form>
<div class="annuler">
                <a href="/login.php"><button class="bn632-hover bn28" >Annuler</button></a>
                </div>
            </section>
        </article>
    </main>
    </div>
</body>

</html>