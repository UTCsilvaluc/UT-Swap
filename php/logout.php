<?php
session_start();

// Supprime toutes les variables de session
$_SESSION = array();

// Détruit la session
setcookie('uuid', '' , time() - 3600 , "/");
setcookie('login', '' , time() - 3600 , "/");
session_destroy();

// Redirige vers la page de connexion ou d'accueil
header("Location: home.php");
exit;
?>