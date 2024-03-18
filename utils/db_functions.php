<?php
function insert_swap($connect, $offerId, $primaryKeyDemande) {
    try {
        $stmt = $connect->prepare("INSERT INTO swap (idDemande, demandeur, statut) VALUES (?, ?, 0)");
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête.");
        }

        $stmt->bind_param("ii", $offerId, $primaryKeyDemande);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }

        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_envoie_swap.classList.toggle('hidden', false);boutons_confirmation.classList.toggle('hidden', false);</script>";
        $stmt->close();
    } catch (Exception $e) {
        error_log("Erreur dans la création du SWAP : " . $e->getMessage());
        return "Erreur dans la création du SWAP : " . $e->getMessage();
    }
    return true;
}

function update_demande_statut($connect, $idDemande , $state) {
    try {
        $stmt = $connect->prepare("UPDATE demande SET demande = ? WHERE idDemande = ?");
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête.");
        }

        $stmt->bind_param("ii", $state, $idDemande);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Erreur dans la création du SWAP : " . $e->getMessage());
        return "Erreur dans la création du SWAP : " . $e->getMessage();
    }
    return true;
}

function updateSwapInsertNotif($choix, $demandeur, $idDemande, $id_notif, $login, $connect){
    
    
    // Valider les données
    $choix = validateInput($choix,$connect);
    $demandeur = validateInput($demandeur,$connect);
    $idDemande = validateInput($idDemande,$connect);
    $idNotif = validateInput($id_notif,$connect);

    $sqlSelectDemande = "SELECT codeUV, type, jour, horaireDebut, horaireFin, semaine FROM demande WHERE idDemande = ?";
    $stmtSelectDemande = $connect->prepare($sqlSelectDemande);
    $stmtSelectDemande->bind_param("s", $demandeur);
    $stmtSelectDemande->execute();
    $stmtSelectDemande->store_result();
    $stmtSelectDemande->bind_result($codeUV, $type, $jour, $horaireDebut, $horaireFin, $semaine);
    $stmtSelectDemande->fetch();

    if($choix === "0"){
        $sqlCheckSwap = "SELECT d.login, n.idNotif, n.demandeur FROM notifications n JOIN demande d ON d.idDemande=n.demandeur WHERE n.idDemande = ? AND n.demandeur = ? AND n.idNotif = ?";
        $stmtCheckSwap = $connect->prepare($sqlCheckSwap);
        $stmtCheckSwap->bind_param("sss", $idDemande, $demandeur, $idNotif);
    } else if($choix === "1"){
        $sqlCheckSwap = "SELECT d1.login, idNotif, n1.demandeur FROM notifications n1 JOIN demande d1 ON d1.idDemande=n1.demandeur WHERE n1.idNotif in (SELECT idNotif FROM `notifications` n JOIN swap s ON s.idDemande = n.idDemande AND s.demandeur = n.demandeur JOIN demande d ON d.idDemande = n.idDemande WHERE d.login = ? AND d.codeUV=? AND d.type=? AND s.statut=0 AND n.typeNotif = 1 AND n.viewed = 0)";
        $stmtCheckSwap = $connect->prepare($sqlCheckSwap);
        $stmtCheckSwap->bind_param("sss", $login, $codeUV, $type);
    }
    $stmtCheckSwap->execute();
    $result1 = $stmtCheckSwap->get_result();
    $listeLogin = array();
    $listeIdNotif = array();
    $listeDemandeur = array();
    foreach ($result1 as $row) {
        if(($choix === "1" && $row["idNotif"] != $idNotif) || $choix === "0"){
            array_push($listeDemandeur, $row["demandeur"]);
            array_push($listeLogin, $row["login"]);
            array_push($listeIdNotif, $row["idNotif"]);
        }else{
            array_unshift($listeDemandeur, $row["demandeur"]);
            array_unshift($listeLogin, $row["login"]);
            array_unshift($listeIdNotif, $idNotif);
        }
    }
    if (count($listeLogin) !== 0) {

        if($choix === "0"){
            $sqlUpdateSwap = "UPDATE swap SET statut = 1 WHERE idDemande = ? AND demandeur = ?";
            $choixTexte = "refusé";
            $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
            $stmtUpdateSwap->bind_param("ss", $idDemande, $demandeur);            
            $stmtUpdateSwap->execute();
        } else if($choix === "1"){
            $sqlUpdateSwap = "UPDATE swap s JOIN demande d ON d.idDemande=s.idDemande JOIN notifications n ON n.idDemande = s.idDemande AND n.demandeur = s.demandeur SET s.statut = 1 WHERE idNotif != ? AND d.login = ? AND d.codeUV=? AND d.type=? AND s.statut=0;";
            $sqlUpdateSwapAccept = "UPDATE swap SET statut = 2 WHERE idDemande = ? AND demandeur = ?";
            $choixTexte = "accepté";

            $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
            $stmtUpdateSwap->bind_param("ssss", $idNotif, $login, $codeUV ,$type);
            $stmtUpdateSwap->execute();

            $stmtUpdateSwapAccept = $connect->prepare($sqlUpdateSwapAccept);
            $stmtUpdateSwapAccept->bind_param("ss", $idDemande, $demandeur);         
            $stmtUpdateSwapAccept->execute();
        }

        for($i=0;$i<count($listeLogin);$i++){
            if(($choix === "1" && $listeIdNotif[$i] == $idNotif) || $choix === "0"){
                sendNotifications($listeLogin[$i], $idDemande, $listeDemandeur[$i], 2, $choix+1, $connect);
            }else{
                sendNotifications($listeLogin[$i], $idDemande, $listeDemandeur[$i], 2, 1, $connect);
            }
        }

        for($j=0;$j<count($listeIdNotif);$j++){
            $sqlSelectNomPrénom = "SELECT nom, prenom FROM personne WHERE login = ?";
            $stmtSelectNomPrénom = $connect->prepare($sqlSelectNomPrénom);
            $stmtSelectNomPrénom->bind_param("s", $listeLogin[$j]);
            $stmtSelectNomPrénom->execute();
            $stmtSelectNomPrénom->store_result();
            $stmtSelectNomPrénom->bind_result($nom, $prénom);
            $stmtSelectNomPrénom->fetch();

            if(($choix === "1" && $listeIdNotif[$j] == $idNotif) || $choix === "0"){
                if($semaine !== "null"){
                    $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap de ".$nom." ".$prénom.".;La demande de swap du ".$type." en semaine ".$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
                }else{
                    $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap de ".$nom." ".$prénom.".;La demande de swap du ".$type." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
                }
            }else{
                if($semaine !== "null"){
                    $nouveauContenuNotif = "Vous avez refusé la demande de Swap de ".$nom." ".$prénom.".;La demande de swap du ".$type." en semaine ".$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été refusée";
                }else{
                    $nouveauContenuNotif = "Vous avez refusé la demande de Swap de ".$nom." ".$prénom.".;La demande de swap du ".$type." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été refusée";
                }
            }
            $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=0 WHERE idNotif = ?";
            
            $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
            $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $listeIdNotif[$j]);
            $stmtUpdateNotif->execute();
        }

    }
}

function insert_demande($connect, $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix) {
    try {
        echo "<script>console.log($login + $uv + $type + $jour + $hdebut + $hfin + $salle + $semaineChoix)</script>";
        // Préparer la requête SQL
        $insertion = $connect->prepare("INSERT INTO demande (login, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine, raison, demande) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$insertion) {
            throw new Exception("Erreur de préparation de la requête");
        }
        // Valider l'entrée pour la raison
        $raison = validateInput($_POST['motivation'], $connect);
        // Vérifier si c'est une demande de swap
        $demande = (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande'])) ? 0 : 1;
        $insertion->bind_param("sssisssssi", $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix, $raison, $demande);
        // Exécuter la requête
        if ($insertion->execute()) {
            // Succès de l'insertion, retourner l'ID de la nouvelle demande
            return $connect->insert_id;
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $insertion->error);
        }
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion de la demande : " . $e);
        return null;
    } finally {
        // Fermer la requête
        if ($insertion) {
            $insertion->close();
        }
    }
}

function getIdDemandeSwap($connect, $login, $type, $uv) {
    try {
        // Préparer la requête SQL
        $sqlGetIdDemande = "SELECT idDemande , demande FROM demande WHERE login = ? AND type = ? AND codeUV = ?";
        $stmtGetIdDemande = $connect->prepare($sqlGetIdDemande);
        $stmtGetIdDemande->bind_param("sss", $login, $type, $uv);

        // Exécuter la requête
        if ($stmtGetIdDemande->execute()) {
            // Récupérer le résultat
            $resultId = $stmtGetIdDemande->get_result();
            $row = $resultId->fetch_assoc();
            $stmtGetIdDemande->close();

            // Vérifier si une ligne a été retournée et si l'ID de demande de swap est spécifié
            if ($row) {
                return $row;
            } else {
                error_log("Aucune ligne retournée ou swapIdDemande non spécifié");
                return null;
            }
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération de l'ID de demande : " . $e->getMessage());
        return null;
    }
}

function checkIfDetailsChange($connect , $login , $type , $uv , $hdebut, $hfin , $salle, $semaineChoix){
    try {
        // Préparer la requête SQL
        $sqlGetIdDemande = "SELECT idDemande FROM demande WHERE login = ? AND type = ? AND codeUV = ? AND horaireDebut = ? AND horaireFin = ? AND salle = ? AND semaine = ?";
        $stmtGetIdDemande = $connect->prepare($sqlGetIdDemande);
        $stmtGetIdDemande->bind_param("sssssss", $login, $type, $uv , $hdebut , $hfin , $salle , $semaineChoix);

        // Exécuter la requête
        if ($stmtGetIdDemande->execute()) {
            // Récupérer le résultat
            $resultId = $stmtGetIdDemande->get_result();
            $row = $resultId->fetch_assoc();
            $stmtGetIdDemande->close();

            // Vérifier si une ligne a été retournée et si l'ID de demande de swap est spécifié
            if ($row) {
                return $row;
            } else {
                error_log("Aucune ligne retournée ou swapIdDemande non spécifié");
                return null;
            }
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération de l'ID de demande : " . $e->getMessage());
        return null;
    }
}
function getDetailsById($connect , $idDemande){
    try {
        // Préparer la requête SQL
        $sqlGetIdDemande = "SELECT idDemande FROM demande WHERE login = ? AND type = ? AND codeUV = ? AND demande = ?";
        $stmtGetIdDemande = $connect->prepare($sqlGetIdDemande);
        $stmtGetIdDemande->bind_param("sssi", $login, $type, $uv , $demande);

        // Exécuter la requête
        if ($stmtGetIdDemande->execute()) {
            // Récupérer le résultat
            $resultId = $stmtGetIdDemande->get_result();
            $row = $resultId->fetch_assoc();
            $stmtGetIdDemande->close();

            // Vérifier si une ligne a été retournée et si l'ID de demande de swap est spécifié
            if ($row) {
                return $row['idDemande'];
            } else {
                error_log("Aucune ligne retournée ou swapIdDemande non spécifié");
                return null;
            }
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération de l'ID de demande : " . $e->getMessage());
        return null;
    }

}

function fetchDemandeDetails($connect, $currentIDdemande) {
    $sql = "SELECT horaireDebut, horaireFin, jour, salle FROM demande WHERE idDemande = ?";
    $stmt = $connect->prepare($sql);
    // Vérification de la préparation de la requête
    if ($stmt === false) {
        // Gestion de l'erreur si la préparation a échoué
        throw new Exception("Erreur de préparation de la requête: " . $connect->error);
    }

    $stmt->bind_param("i", $currentIDdemande);
    if (!$stmt->execute()) {
        // Gestion de l'erreur si l'exécution de la requête a échoué
        throw new Exception("Erreur lors de l'exécution de la requête: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Vérification si une ligne a été trouvée
    if (!$row) {
        throw new Exception("Aucune ligne trouvée pour l'idDemande: $currentIDdemande");
    }

    // Retourner les détails de la demande
    return $row;
}

?>
