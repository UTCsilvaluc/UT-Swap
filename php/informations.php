<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/informations.css">
    <title>Informations - UT'Swap</title>
    <link rel="icon" href="../img/logo_mini.png" type="image/x-icon">
</head>
<body>
<?php include "header.php" ?>
    <main>
        <div class="main_info">
            <div id="info_titre">
                <h1 class="info_title">Page d'informations UT'SWAP</h1>
            </div>
            <div id="info_menu">
                <span id="span_nav_menu">
                    <a class="currentSpan" id="span_RESUME" href="#RESUME" onclick="changeMenu('RESUME' , event)">UT'SWAP en quelques mots</a>
                    <a class="other" id="span_HOW" href="#HOW" onclick="changeMenu('HOW' , event)">Fonctionnement</a>
                    <a class="other" id="span_MAJ" href="#MAJ" onclick="changeMenu('MAJ' , event)">Mise à jour et nouveautés</a>
                    <a class="other" id="span_FAQ" href="#FAQ" onclick="changeMenu('FAQ' , event)">FAQ</a>
                </span>
                <div id="info_menu_content">
                    <div id="RESUME" class="visible">
                        <span class="content">
                            <h4>Qui et pourquoi ?</h4>
                            <h5>Le projet UT'Swap a été réalisé dans le cadre de l’UV TX00, fondé par Silva Lucas et Dompnier Lucas, avec l’aide du SIMDE. Dès notre entrée à l’UTC, nous avons été confrontés à la difficulté d’échanger des créneaux de cours/TD/TP. Nous avons donc eu l’idée de simplifier la tâche de tout le monde, aussi bien des étudiants que des enseignants, en regroupant tout le processus au sein d’une même plateforme.</h5>
                        </span>
                        <span class="content">
                            <h4>UT’Swap, en quelques mots...</h4>
                            <h5>Un système simple et efficace d’échanges ! Un gain de temps précieux pour tout le monde... Un système infaillible fait par des étudiants hors pair, ça, c’est notre projet !</h5>
                        </span>
                        <span class="content">
                            <h4>Plus de 1000 échanges effectués</h4>
                            <h5>UT’Swap, c’est plus de 1000 étudiants sauvés en leur permettant de trouver l’horaire qui leur convient. N’attendez plus et soyez le prochain à trouver le vôtre, il n’attend que vous !</h5>
                        </span>
                        <span class="content">
                            <h4>Notre mission</h4>
                            <h5>Afin de réussir, chacun se doit d’optimiser au mieux son temps. Ainsi, nous avons pour vocation de favoriser le succès de chacun à l’UTC en permettant aux étudiants comme aux enseignants de gagner un temps précieux dans les démarches de changement de créneaux.</h5>
                        </span>
                    </div>
                    <div id="HOW" class="hidden">
                        <span class="content">
                            <h4>Un créneau ne vous convient pas ?</h4>
                            <h5>Vous avez besoin de libérer un créneau afin de pouvoir pratiquer une activité, vous voulez optimiser votre emploi du temps ou simplement éviter de vous réveiller tous les matins à 8h ? Proposez simplement votre horaire sur la page SWAP. Vous avez la possibilité d'interagir avec votre emploi du temps afin de sélectionner le cours à échanger. Ensuite, si un étudiant est intéressé par votre horaire, il pourra vous proposer le sien en échange et il ne restera plus qu’à accepter ou refuser la proposition. C’est simple, n'est-ce pas ? </h5>
                        </span>
                        <span class="content">
                            <h4>Un horaire m’intéresse </h4>
                            <h5>Dans la partie demandes, vous pouvez voir toutes les propositions d'échanges effectuées par des étudiants. Si un créneau vous plaît, il suffit de cliquer dessus puis de formuler votre requête. Pour cela, vous avez simplement besoin de renseigner les informations liées à votre horaire, puis le tour est joué. Enfin, l'étudiant n'aura qu’à décliner ou accepter votre demande.</h5>
                        </span>
                        <span class="content">
                            <h4>Je suis responsable d’UV ? </h4>
                            <h5>Nous pensons aussi à vous, en tant que responsable d’une UV. Notre objectif est de vous simplifier la tâche. Terminé les nombreux e-mails des étudiants voulant changer un créneau, vous avez maintenant la possibilité de tout gérer depuis une interface simple et pratique. Refuser une demande n'aura jamais été aussi simple !</h5>
                        </span>
                        <span class="content">
                            <h4>Personne ne veut de mon horaire...</h4>
                            <h5>Nous avons pensé à tout. Si personne ne souhaite votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n'aura qu’à refuser votre demande... </h5>
                        </span>
                    </div>
                    <div id="MAJ" class="hidden">
                        <span class="content">
                                <h4>Mise à jour du 06/06/2024</h4>
                                <h5>
                                    <li>
                                        cc
                                    </li>
                                    <li>
                                        cc
                                    </li>
                                </h5>
                        </span>
                        <span class="content">
                            <h4>Mise à jour du 06/06/2024</h4>
                            <h5>
                                <li>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur deleniti dignissimos distinctio dolorum ea, et eum fugiat hic id ipsum iusto laborum laudantium, modi provident quasi sed unde velit.
                                </li>
                                <li>
                                    cc
                                </li>
                            </h5>
                        </span>
                        <span class="content">
                                <h4>Mise à jour du 06/06/2024</h4>
                                <h5>
                                    <li>
                                        cc
                                    </li>
                                    <li>
                                        cc
                                    </li>
                                </h5>
                        </span>
                    </div>
                    <div id="FAQ" class="hidden">
                        <span class="faq_content">
                            <span class="faq_openContent">
                                <h4>UT’Swap c’est quoi ?</h4>
                                <img class="fleche_svg" src="../svg/right_arrow.svg" alt="">
                            </span>
                            <h5 class="hidden">UT'Swap, c'est une plateforme qui simplifie la vie étudiante en permettant l'échange facile de créneaux de cours entre étudiants. Gagne en flexibilité, optimise ton emploi du temps et personnalise ton expérience universitaire avec UT'Swap !</h5>
                        </span>
                        <span class="faq_content">
                            <span class="faq_openContent">
                                <h4>Où accepter ou refuser les demandes de swap ?</h4>
                                <img class="fleche_svg" src="../svg/right_arrow.svg" alt="">
                            </span>
                            <h5 class="hidden">Pour accepter ou refuser une demande de swap, vous avez deux options: soit vous ouvrez l'onglet des notifications en appuyant sur la cloche dans la barre de navigation. Soit vous vous rendez sur la page profil où vous pourrez y retrouver tes demandes faites et celles reçues, vous n'aurez qu'à survoler la demande pour pouvoir l'accepter ou la refuser !</h5>
                        </span>
                        <span class="faq_content">
                            <span class="faq_openContent">
                                <h4>Comment créer son emploi du temps ?</h4>
                                <img class="fleche_svg" src="../svg/right_arrow.svg" alt="">
                            </span>
                            <h5 class="hidden">Pour créer votre emploi du temps, rien de plus simple, vous n'avez qu'à vous rendre sur la page swap puis vous avez deux options: Soit vous créez chacun de vos créneaux un par un en appuyant directement sur la fenêtre de l'emploi du temps. Soit vous appuyez sur la petite icône d'importation (celle la plus à gauche) et vous copiez collez le mail "vos inscriptions" que vous avez du recevoir en début de semestre !</h5>
                        </span>
                        <span class="faq_content">
                            <span class="faq_openContent">
                                <h4>Où voir l'avancement de mes swaps et de mes demandes ?</h4>
                                <img class="fleche_svg" src="../svg/right_arrow.svg" alt="">
                            </span>
                            <h5 class="hidden">Pour voir l'avancement de vos swaps et de vos demandes vous pouvez regarder dans l'onglet des notifications en cliquant sur la cloche dans la barre de navigation ou alors vous pouvez vous rendre dû la page profil. Sur cette page vous pourrez retrouver toutes les informations concernant vos demandes et vos swaps.</h5>
                        </span>
                        <span class="faq_content">
                            <span class="faq_openContent">
                                <h4>Comment faire une demande d'échange de créneau ?</h4>
                                <img class="fleche_svg" src="../svg/right_arrow.svg" alt="">
                            </span>
                            <h5 class="hidden">Pour réaliser une demande d'échange de créneau, vous avez juste à cliquer sur la demande de swap dont vous avez besoin sur la page "demandes" accessible via la barre de navigation, puis à rentrer les informations concernant votre propre créneau. À savoir que cette dernière étape ne sera à faire que dans le cas où vous n'avez pas encore créé votre emploi du temps.</h5>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        </main>
        <?php include "footer.php" ?>
        <script src="../js/informations.js"></script>
    </body>
</html>
