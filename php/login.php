<?php

function DBCredential2(){
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $dbname = 'ut_swap';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    mysqli_set_charset($connect, 'utf8');
    return $connect;
}
function validateInput2($input, $connect) {
    $input = trim($input); // Supprime les espaces en début et fin de chaîne
    $input = stripslashes($input); // Supprime les antislashs ajoutés par addslashes
    $input = htmlspecialchars($input); // Convertit les caractères spéciaux en entités HTML
    $input = $connect->real_escape_string($input);
    return $input;
}
$connect = DBCredential2();
session_start();
if (isset($_POST['login'], $_POST['nom'], $_POST['prenom']) && !empty($_POST['login']) && !empty($_POST['nom']) && !empty($_POST['prenom'])){
    $loginEtu = validateInput2($_POST['login'],$connect);
    $prenom = validateInput2($_POST['prenom'],$connect);
    $nom = validateInput2($_POST['nom'],$connect);
    if(strlen($loginEtu) == 8){
        $sqlSelectLogin = "SELECT * FROM personne WHERE login = ?";
        $stmtSelectLogin = $connect->prepare($sqlSelectLogin);
        $stmtSelectLogin->bind_param("s", $loginEtu);
        $stmtSelectLogin->execute();
        $stmtSelectLogin->store_result();
        if ($stmtSelectLogin->num_rows === 0) {
            $mail = $prenom.".".$nom."@etu.utc.fr";
            $sqlInsertPersonne = "INSERT INTO `ut_swap`.`personne` (`login`, `nom`, `prenom`, `mail`) VALUES (?,?,?,?);";
            $stmtInsertPersonne = $connect->prepare($sqlInsertPersonne);
            $stmtInsertPersonne->bind_param("ssss", $loginEtu, $nom, $prenom, $mail);
            $stmtInsertPersonne->execute();
            $sqlInsertEtudiant = "INSERT INTO `ut_swap`.`etudiant` (`login`, `alert`, `reception`, `progress`) VALUES (?,1,1,1);";
            $stmtInsertEtudiant = $connect->prepare($sqlInsertEtudiant);
            $stmtInsertEtudiant->bind_param("s", $loginEtu);
            $stmtInsertEtudiant->execute();
        }
        $_SESSION["login"] = $loginEtu;
    }
}

?>
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
