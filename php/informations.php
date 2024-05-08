<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/informations.css">
    <title>Login - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
<?php include "header.php" ?>
<main>
    <div class="main_info">
        <div id="info_titre">
            <h1 class="info_title">Page d'informations UT'SWAP</h1>
        </div>
        <div id="info_menu">
            <div id="info_menu_nav">
                <span id="span_nav_menu">
                    <h3 class="currentSpan" id="span_RESUME" onclick="changeMenu('RESUME' , event)">UT'SWAP en quelques mots</h3>
                    <h3 class="other" id="span_HOW" onclick="changeMenu('HOW' , event)">Fonctionnement</h3>
                    <h3 class="other" id="span_MAJ" onclick="changeMenu('MAJ' , event)">Mise à jour et nouveautés</h3>
                    <h3 class="other" id="span_FAQ" onclick="changeMenu('FAQ' , event)">FAQ</h3>
                </span>
                <div id="info_menu_content">
                    <div id="RESUME" class="visible">
                        <span class="content">
                            <h4>Qui et pourquoi ?</h4>
                            <h5> Le projet UT'Swap a été réalisé dans le cadre de l’UV TX00 fondé par Silva Lucas et Dompnier Lucas à l’aide du SIMDE . Dès notre entrée à l’UTC, nous avons été confronté à la difficulté d’échanger des créneaux de Cours/TD/TP, nous avons donc eu l’idée de simplifier la tâche de tout le monde aussi bien des étudiants comme des enseignants en regroupant tout le processus au sein d’une même plateforme. </h5>
                        </span>
                        <span class="content">
                            <h4>UT’Swap, en quelques mots...</h4>
                            <h5>Un système simple et efficace d’échanges ! Un gain de temps précieux pour tout le monde... Un système infaillible fait par des étudiants hors pair, ça, c’est notre projet !</h5>
                        </span>
                        <span class="content">
                            <h4>Plus de 1000 échanges effectués</h4>
                            <h5>UT’Swap c’est plus de 1000 étudiants sauvés en leur permettant de trouver l’horaire qui leur convient. N’attendez plus et soyez le prochain à trouver le votre, il n’attend que vous !</h5>
                        </span>
                        <span class="content">
                            <h4>Notre mission</h4>
                            <h5>Afin de réussir, chacun se doit d’optimiser au mieux son temps. Ainsi, nous avons vocation à favoriser le succès de chacun à l’UTC en permettant aux étudiants comme aux enseignants de gagner un temps précieux dans les démarches de changement de créneaux. </h5>
                        </span>
                    </div>
                    <div id="HOW" class="hidden">
                        <span class="content">
                            <h4>Un créneau ne vous convient pas ?</h4>
                            <h5> Vous avez besoin de libérer un créneau afin de pouvoir pratiquer une activité, vous voulez optimiser votre emploi du temps ou simplement éviter de vous réveiller tous les matins à 8h ? Proposez simplement votre horaire sur la page SWAP, vous avez la possibilité d’intéragir avec votre emploi du temps afin de sélectionner le cours à échanger. Ensuite, si un étudiant est intéressé par votre horaire, il pourra vous proposer le sien en échange et il ne restera plus qu’à accepter ou refuser la proposition, c’est simple n’est-ce pas ? </h5>
                        </span>
                        <span class="content">
                            <h4>Un horaire m’intéresse </h4>
                            <h5>Dans la partie demandes, vous pouvez voir toutes les propositions d’échanges effectuées par des étudiants. Si un créneau vous plaît, il suffit de cliquer dessus puis formuler votre requête. Pour cela, vous avez simplement besoin de renseigner les informations liées à votre horaire puis le tour est joué. Enfin, l’étudiant n’aura qu’à décliner ou accepter votre demande.</h5>
                        </span>
                        <span class="content">
                            <h4>Je suis responsable d’UV ? </h4>
                            <h5>Nous pensons aussi à vous, en tant que responsable d’une UV, notre objectif est de vous simplifier la tâche. Terminé les nombreux mails des étudiants afin de vouloir changer un créneau, vous avez maintenant la possibilité de tout gérer depuis une interface simple et pratique. Refuser une demande n’aura jamais été aussi simple !</h5>
                        </span>
                        <span class="content">
                            <h4>Personne ne veut de mon horaire...</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
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
                        <span class="content">
                            <h4>UT’Swap c’est quoi ?</h4>
                            <h5> UT'Swap, c'est une plateforme qui simplifie la vie étudiante en permettant l'échange facile de créneaux de cours entre étudiants. Gagne en flexibilité, optimise ton emploi du temps et personnalise ton expérience universitaire avec UT'Swap ! </h5>
                        </span>
                        <span class="content">
                            <h4>Où accepter ou refuser les demandes ? </h4>
                            <h5>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus aliquid amet consequatur cumque dolores explicabo iste minima praesentium totam. Dolorem inventore ipsam itaque officiis provident quam qui quibusdam veniam voluptatem?</h5>
                        </span>
                        <span class="content">
                            <h4>Comment créer son emploi du temps ?</h4>
                            <h5>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus accusantium aperiam blanditiis consequuntur, deleniti, doloribus dolorum error explicabo facere illum nam nisi odit officia qui, quis quod sequi sint!</h5>
                        </span>
                        <span class="content">
                            <h4>Où voir toutes les demandes ?</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
                        </span>
                        <span class="content">
                            <h4>Comment savoir quand une demande a été acceptée ?</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
                        </span>
                        <span class="content">
                            <h4>Comment faire une demande d’échange ?</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
                        </span>
                        <span class="content">
                            <h4>Où voir l’avancement de mes Swaps et de mes demandes ?</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
                        </span>
                        <span class="content">
                            <h4>Comment personaliser mon emploi du temps ?</h4>
                            <h5>Nous avons pensé à tout, si personne ne souhaite de votre horaire, vous pourrez au moins le savoir de manière simple et efficace :) Plus besoin de nombreux messages sur Messenger, tout le monde n’aura qu’à refuser votre demande.... </h5>
                        </span>
                    </div>
                </div>
            </div>


            <div>

            </div>
        </div>

    </div>

</main>
<script src="../js/informations.js"></script>
</body>
</html>
