<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head2.php' ?>
    <script src="./vendor/jquery/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="/public/css/commentaire.css">

    <title>AVS</title>
</head>

<body>

    <main class="container">

        <div class="header">
            <?php require_once 'includes/header.php' ?>
        </div>
        
        <div class="encours">
            <H1 class="title">COMMENTAIRES</H1>
        </div>


<!--  --------Popup commentaires -------------- -->

<div class="com-popup">
        <div class="form-popup" id="popupCom">
                
            <form action="/wishesEncours.php" method="POST" class="form-container2">
                
                <div class="content-hight">
                    <div class="avatar_pseudo">
                        <img class="avatar" src="<?= $a['image'] ?? '' ?>" alt=""></span>
                        <h1 class="pseudo"><?= $a['pseudo'] ?? '' ?></h1>
                    </div>
                    <div class="time">1j</div>
                </div>
                <div class="blocCom">
                    <p><?= $a['commentaire'] ?? '' ?></p>
                </div>
            <div class="container_submit">
                <div class="content-low">
                    <textarea type="text" id="commentaire" placeholder="Commentaire" name="commentaire" rows="70px"></textarea>
                    <button type="submit" class="btnSubmit"><img src="/assets/img/send.png" alt="send" width="30px"></button>
                </div>
            </div>
            </form>
        </div>
</div>
           
    <script>
        function openCom() {
            document.getElementById("popupCom").style.display = "block";
        }

        function closeCom() {
            document.getElementById("popupCom").style.display = "none";
        }
    </script>

    </main>


</body>

</html>