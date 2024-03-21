<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/mid_pannel.css">
    <title>Login - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <main>
        <form id="login_pannel" class="mid_pannel" action="#" method="post">
            <div class="mid_titre">
                <h1>Login</h1>
                <hr>
            </div>
            <img src="../svg/croix.svg" class="croix">
            <div id="login_mid" class="mid_content">
                <div>
                    <label for="input-login">Login:</label>
                    <input type="text" id="input-login" maxlength="8" name="login" placeholder="Veuillez entrer votre login ENT" >
                </div>
                <div>
                    <label for="input-nom">Nom:</label>
                    <input type="text" id="input-nom" name="nom" placeholder="Veuillez entrer votre nom" >
                </div>
                <div>
                    <label for="input-prenom">Prénom:</label>
                    <input type="text" id="input-prenom" name="prenom" placeholder="Veuillez entrer votre prénom" >
                </div>
            </div>
            <div class="mid_button">
                <hr>
                <button id="swap_button_retour">Envoyer</button>
            </div>
        </form>
    </main>
    <script src="../js/login.js"></script>
</body>
</html>
