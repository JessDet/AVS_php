<?php

require_once './isLoggedIn.php';
$user = isLoggedIn();


$pdo = require './database.php';
$error = '';
$errors = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [

        'email' => FILTER_SANITIZE_EMAIL
    ]);

    $email = $_input['email'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

    if (!$email || !$motDePasse) {
        $error = "TOUS LES CHAMPS DOIVENT ETRE REMPLIS";
    } else {
        $statementUser = $pdo->prepare('SELECT * FROM user WHERE email=:email and statusUser=0');
        $statementUser->bindValue(':email', $email);
        $statementUser->execute();
        $user = $statementUser->fetch();


        if ($user && password_verify($motDePasse, $user['motDePasse'])) {
            $statementSession = $pdo->prepare('INSERT INTO session (idUser) VALUES (:idUser)');
            $statementSession->bindValue(':idUser', $user['idUser']);
            $statementSession->execute();
            $sessionId = $pdo->lastInsertId();
            setcookie('session', $sessionId, time() + 60 * 60, '', '', false, true);
            header('Location: /wishes.php');
        } else {
            $errors = "PSEUDO ET/OU MOT DE PASSE INCORRECT(S)";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <link rel="stylesheet" href="/public/css/login.css">
    <title>AVS</title>
</head>

<body>

    <main class="container">
        <article class="contentlog">
                
                <div class="titleCo">
                    <h1>CONNEXION</h1>
                </div>
                
                <form class="login" action="/login.php" method="POST" id="login_form">

                    <div class="wrapper">
                        <div class="input-data">
                            <input type="email" placeholder="Email" name="email" id="email_input" required>
                            <label for="email"></label><br>
                        </div>
                    </div>
                    <div class="wrapper">
                        <div class="input-data">
                            <input type="password" placeholder="Mot de Passe" name="motDePasse" id="mdp_input" required>
                            <label for="mot de passe"></label><br>
                        </div>
                    </div>

                    
                    <?php if ($error) : ?>

                        <h1 style="color:red; font-size:medium; text-transform:lowercase"><?= $error ?></h1>

                    <?php endif; ?>

                    <?php if ($errors) : ?>

                        <h1 style="color:red; font-size:medium; text-transform:lowercase"><?= $errors ?></h1>

                    <?php endif; ?>
                    <br>
                    <div class="mdpoublie"><a class="mdpoublie" href="">Mot de passe oublié ?</a></div>
                        <div>
                    <button class="bn632-hover bn28">SE CONNECTER</button>
</div>
                </form>
                        <p class="txtsubs">Pas encore membre ?</p>
                <a href="/register.php"><button class="bn633-hover bn29">S'INSCRIRE</button></a>

        </article>

    </main>

    </div>

</body>

</html>