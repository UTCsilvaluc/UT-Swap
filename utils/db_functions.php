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

function getInformationDemande($connect, $idDemande){
    $sqlSelectDemande = "SELECT codeUV, type, jour, horaireDebut, horaireFin, semaine, login FROM demande WHERE idDemande = ?";
    $stmtSelectDemande = $connect->prepare($sqlSelectDemande);
    $stmtSelectDemande->bind_param("s", $idDemande);
    $stmtSelectDemande->execute();
    $stmtSelectDemande->store_result();
    $stmtSelectDemande->bind_result($codeUV, $type, $jour, $horaireDebut, $horaireFin, $semaine, $login);
    $stmtSelectDemande->fetch();
    return array(
        'codeUV' => $codeUV,
        'type' => $type,
        'jour' => $jour,
        'horaireDebut' => $horaireDebut,
        'horaireFin' => $horaireFin,
        'semaine' => $semaine,
        'login' => $login
    );
}

function getInformationLogin($connect, $login){
    $sqlSelectNomPrénom = "SELECT nom, prenom FROM personne WHERE login = ?";
    $stmtSelectNomPrénom = $connect->prepare($sqlSelectNomPrénom);
    $stmtSelectNomPrénom->bind_param("s", $login);
    $stmtSelectNomPrénom->execute();
    $stmtSelectNomPrénom->store_result();
    $stmtSelectNomPrénom->bind_result($nom, $prénom);
    $stmtSelectNomPrénom->fetch();
    return array(
        'nom' => $nom,
        'prénom' => $prénom
    );
}

function updateSwapInsertNotif($choix, $demandeur, $idDemande, $id_notif, $login, $connect){
    
    // Valider les données
    $choix = validateInput($choix,$connect);
    $demandeur = validateInput($demandeur,$connect);
    $idDemande = validateInput($idDemande,$connect);
    $idNotif = validateInput($id_notif,$connect);

    $informationDemande = getInformationDemande($connect, $idDemande);
    $codeUV = $informationDemande["codeUV"];
    $type = $informationDemande["type"];
    $jour = $informationDemande["jour"];
    $horaireDebut = $informationDemande["horaireDebut"];
    $horaireFin = $informationDemande["horaireFin"];
    $semaine = $informationDemande["semaine"];

    $sqlCheckSwap = "SELECT d.login, n.demandeur, u.responsable FROM notifications n JOIN demande d ON d.idDemande=n.demandeur JOIN uv u ON u.codeUV = d.codeUV WHERE n.idDemande = ? AND n.demandeur = ? AND n.idNotif = ?";
    $stmtCheckSwap = $connect->prepare($sqlCheckSwap);
    $stmtCheckSwap->bind_param("sss", $idDemande, $demandeur, $idNotif);
    $stmtCheckSwap->execute();
    $stmtCheckSwap->store_result();
    $stmtCheckSwap->bind_result($loginPersonne, $demandeur, $loginResponsable);
    $stmtCheckSwap->fetch();

    if($choix === "0"){
        $sqlUpdateSwap = "UPDATE swap SET statut = 1 WHERE idDemande = ? AND demandeur = ?";
        $choixTexte = "refusé";
        $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
        $stmtUpdateSwap->bind_param("ss", $idDemande, $demandeur);            
        $stmtUpdateSwap->execute();
        sendNotifications($loginPersonne, $idDemande, $demandeur, 2, $choix, $connect);

        $informationLogin = getInformationLogin($connect, $loginPersonne);
        $nom = $informationLogin["nom"];
        $prénom = $informationLogin["prénom"];
        $semaine = $semaine !== "null" ? ' en semaine ' . $semaine : null;
        $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap de ".$nom." ".$prénom.".;La demande de swap du ".$type.$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
        $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
        
        $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
        $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $idNotif);
        $stmtUpdateNotif->execute();
    } else if($choix === "1"){
        $sqlUpdateSwapAccept = "UPDATE swap SET statut = 2 WHERE idDemande = ? AND demandeur = ?";
        $choixTexte = "accepté";
        $stmtUpdateSwapAccept = $connect->prepare($sqlUpdateSwapAccept);
        $stmtUpdateSwapAccept->bind_param("ss", $idDemande, $demandeur);         
        $stmtUpdateSwapAccept->execute();

        sendNotifications($loginPersonne, $idDemande, $demandeur, 2, $choix, $connect);
        sendNotifications($loginResponsable, $idDemande, $demandeur, 6, null, $connect);

        $informationLogin = getInformationLogin($connect, $loginPersonne);
        $nom = $informationLogin["nom"];
        $prénom = $informationLogin["prénom"];
        $semaine = $semaine !== "null" ? ' en semaine ' . $semaine : null;
        $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap de ".ucfirst($nom)." ".ucfirst($prénom).".;La demande de swap du ".$type.$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
        $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
        
        $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
        $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $idNotif);
        $stmtUpdateNotif->execute();

        
        //Demandes reçues
        
        $sqlGetInfoDemandeReçue = "SELECT d1.login, idNotif, n1.demandeur FROM notifications n1 JOIN demande d1 ON d1.idDemande=n1.demandeur WHERE n1.idNotif in (SELECT idNotif FROM `notifications` n JOIN swap s ON s.idDemande = n.idDemande AND s.demandeur = n.demandeur JOIN demande d ON d.idDemande = n.idDemande WHERE (d.login = ? OR d.login = ?) AND d.codeUV=? AND d.type=? AND s.statut=0 AND n.typeNotif = 1 AND n.viewed = 0) AND d1.idDemande != ? AND d1.idDemande != ?";
        $stmtGetInfoDemandeReçue = $connect->prepare($sqlGetInfoDemandeReçue);
        $stmtGetInfoDemandeReçue->bind_param("ssssss", $login, $loginPersonne, $codeUV, $type, $idDemande, $demandeur);
        $stmtGetInfoDemandeReçue->execute();

        $result1 = $stmtGetInfoDemandeReçue->get_result();
        $listeLoginReçue = array();
        $listeIdNotifReçue = array();
        $listeDemandeurReçue = array();
        foreach ($result1 as $row) {
            array_push($listeDemandeurReçue, $row["demandeur"]);
            array_push($listeLoginReçue, $row["login"]);
            array_push($listeIdNotifReçue, $row["idNotif"]);
        }

        if (count($listeLoginReçue) !== 0) {
            $sqlUpdateSwap = "UPDATE swap s JOIN demande d ON d.idDemande=s.idDemande JOIN notifications n ON n.idDemande = s.idDemande AND n.demandeur = s.demandeur SET s.statut = 1 WHERE s.demandeur != ? AND s.demandeur != ? AND (d.login = ? OR d.login= ?) AND d.codeUV=? AND d.type=? AND s.statut=0;";
            $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
            $stmtUpdateSwap->bind_param("ssssss", $demandeur, $idDemande, $login, $loginPersonne, $codeUV ,$type);
            $stmtUpdateSwap->execute();

            for($i=0;$i<count($listeLoginReçue);$i++){
                sendNotifications($listeLoginReçue[$i], $idDemande, $listeDemandeurReçue[$i], 2, 0, $connect);
                
                $informationLogin = getInformationLogin($connect, $listeLoginReçue[$i]);
                $nom = $informationLogin["nom"];
                $prénom = $informationLogin["prénom"];

                $semaine = $semaine !== "null" ? ' en semaine ' . $semaine : null;
                $nouveauContenuNotif = "Vous avez refusé la demande de Swap de ".ucfirst($nom)." ".ucfirst($prénom).".;La demande de swap du ".$type.$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été refusée";
                $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
                
                $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
                $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $listeIdNotifReçue[$i]);
                $stmtUpdateNotif->execute();
            }
        }
        //Demandes faites

        $sqlGetInfoDemandeFaite = "SELECT d1.login as login1, d2.login as login2, n1.demandeur, idNotif, n1.idDemande FROM notifications n1 JOIN demande d1 ON d1.idDemande=n1.idDemande JOIN demande d2 ON d2.idDemande=n1.demandeur WHERE n1.idNotif in (SELECT idNotif FROM `notifications` n JOIN swap s ON s.idDemande = n.idDemande AND s.demandeur = n.demandeur JOIN demande d ON d.idDemande = n.demandeur WHERE (d.login = ? OR d.login = ?) AND d.codeUV=? AND d.type=? AND s.statut=0 AND n.typeNotif = 1 AND n.viewed = 0) AND d1.idDemande != ?";
        $stmtGetInfoDemandeFaite = $connect->prepare($sqlGetInfoDemandeFaite);
        $stmtGetInfoDemandeFaite->bind_param("sssss", $login, $loginPersonne, $codeUV, $type, $idDemande);
        $stmtGetInfoDemandeFaite->execute();

        $result2 = $stmtGetInfoDemandeFaite->get_result();
        $listeLoginFaite = array();
        $listeLoginDemandeurFaite = array();
        $listeIdNotifFaite = array();
        $listeDemandeFaite = array();
        foreach ($result2 as $row) {
            array_push($listeDemandeFaite, $row["idDemande"]);
            array_push($listeLoginFaite, $row["login1"]);
            array_push($listeLoginDemandeurFaite, $row["login2"]);
            array_push($listeIdNotifFaite, $row["idNotif"]);
        }

        if (count($listeLoginFaite) !== 0) {
            $sqlDeleteSwap = "DELETE FROM swap WHERE idDemande != ? AND demandeur IN (SELECT idDemande FROM demande WHERE (login = ? OR login = ?) AND codeUV = ? AND type = ?) AND statut = 0";
            $stmtDeleteSwap = $connect->prepare($sqlDeleteSwap);
            $stmtDeleteSwap->bind_param("sssss", $idDemande, $login, $loginPersonne, $codeUV ,$type);
            $stmtDeleteSwap->execute();
            for($i=0;$i<count($listeLoginFaite);$i++){
                $informationLogin = getInformationLogin($connect, $listeLoginDemandeurFaite[$i]);
                $nom = $informationLogin["nom"];
                $prénom = $informationLogin["prénom"];

                $semaine = $semaine !== "null" ? ' en semaine ' . $semaine : null;
                $nouveauContenuNotif = ucfirst($nom)." ".$prénom." a retourné sa veste.;La demande de swap du ".$type.$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été annulée";
                $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
                
                $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
                $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $listeIdNotifFaite[$i]);
                $stmtUpdateNotif->execute();
            }
        }
    }
}

function cancelSwapFait($idDemande, $demandeur, $login, $connect){

    $demandeur = validateInput($demandeur,$connect);
    $idDemande = validateInput($idDemande,$connect);
    $idNotif = validateInput($id_notif,$connect);

    $sqlGetIdNotif = "SELECT idNotif FROM notifications WHERE idDemande = ? AND demandeur = ? AND typeNotif = 1 AND viewed = 0;";
    $stmtGetIdNotif = $connect->prepare($sqlGetIdNotif);
    $stmtGetIdNotif->bind_param("ss", $idDemande, $demandeur);
    $stmtGetIdNotif->execute();
    $stmtGetIdNotif->store_result();
    $stmtGetIdNotif->bind_result($idNotif);
    $stmtGetIdNotif->fetch();
    
    $sqlDeleteSwap = "DELETE FROM swap WHERE idDemande = ? AND demandeur = ? AND statut = 0";
    $stmtDeleteSwap = $connect->prepare($sqlDeleteSwap);
    $stmtDeleteSwap->bind_param("ss", $idDemande, $demandeur);
    $stmtDeleteSwap->execute();

    $informationLogin = getInformationLogin($connect, $login);
    $nom = $informationLogin["nom"];
    $prénom = $informationLogin["prénom"];


    $semaine = $semaine !== "null" ? ' en semaine ' . $semaine : null;
    $nouveauContenuNotif = ucfirst($nom)." ".$prénom." a retourné sa veste.;La demande de swap du ".$type.$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été annulée";
    $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
    
    $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
    $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $idNotif);
    $stmtUpdateNotif->execute();
}

function insert_demande($connect, $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix, $raison, $motivationAutre) {
    try {
        // Préparer la requête SQL
        $insertion = $connect->prepare("INSERT INTO demande (login, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine, raison, motifPerso, demande) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$insertion) {
            throw new Exception("Erreur de préparation de la requête");
        }
        // Vérifier si c'est une demande de swap
        $demande = (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande'])) ? 0 : 1;
        $insertion->bind_param("sssissssssi", $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix, $raison, $motivationAutre, $demande);
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

function checkIfDetailsChange($connect , $idDemande , $type , $uv , $hdebut, $hfin , $salle, $semaineChoix , $jour){
    try {
        // Préparer la requête SQL
        $sqlGetIdDemande = "SELECT idDemande FROM demande WHERE idDemande = ? AND type = ? AND codeUV = ? AND horaireDebut = ? AND horaireFin = ? AND salle = ? AND semaine = ? AND jour = ?";
        $stmtGetIdDemande = $connect->prepare($sqlGetIdDemande);
        $stmtGetIdDemande->bind_param("issssssi", $idDemande, $type, $uv , $hdebut , $hfin , $salle , $semaineChoix , $jour);
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

function fetchDemandeDetails($connect, $currentIDdemande) {
    $sql = "SELECT horaireDebut, horaireFin, jour, salle , semaine FROM demande WHERE idDemande = ?";
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

function getLoginById($connect , $idDemande){
    try {
        // Préparer la requête SQL
        $sqlGetLogin = "SELECT login FROM demande WHERE idDemande = ?";
        $stmtGetLogin = $connect->prepare($sqlGetLogin);
        $stmtGetLogin->bind_param("i", $idDemande);

        // Exécuter la requête
        if ($stmtGetLogin->execute()) {
            // Récupérer le résultat
            $resultId = $stmtGetLogin->get_result();
            $row = $resultId->fetch_assoc();
            $stmtGetLogin->close();

            // Vérifier si une ligne a été retournée et si l'ID de demande de swap est spécifié
            if ($row) {
                return $row['login'];
            } else {
                error_log("Aucun login n'a été trouvé...");
                return null;
            }
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération du login : " . $e->getMessage());
        return null;
    }

}

function checkIfHasRequest($connect, $idDemande){
    try {
        $stmt = $connect->prepare("SELECT * FROM swap WHERE idDemande = ? AND statut = 0");
        $stmt->bind_param("i", $idDemande);
        if ($stmt->execute()){
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupérer toutes les lignes sous forme de tableau associatif
            $stmt->close();
            return $rows;
        }
    } catch (Exception $e){
        error_log("Erreur :" . $e->getMessage());
    }
}


function deleteSwapByDemandeur($connect , $idDemande){
    try {
        $stmt = $connect->prepare("DELETE FROM swap WHERE idDemande = ?");
        $stmt->bind_param("i" ,$idDemande);
        if ($stmt->execute()){
            $stmt->close();
        } else {
            error_log("Erreur lors de la suppression des swaps !");
        }
    } catch (Exception $e){
        error_log("Erreur :" . $e->getMessage());
    }

}

function updateSwapByDemandeur($connect , $idDemande , $statut){
    try {
        $stmt = $connect->prepare("UPDATE swap SET statut = ? WHERE idDemande = ?");
        $stmt->bind_param("ii"  , $statut ,$idDemande);
        if ($stmt->execute()){
            $stmt->close();
        } else {
            error_log("Erreur lors de l'update des swaps !");
        }
    } catch (Exception $e){
        error_log("Erreur :" . $e->getMessage());
    }

}

function hasCreneauAccepted($connect , $idDemande){
    $stmt = $connect->prepare("SELECT * FROM swap WHERE demandeur = ?");
    $stmt->bind_param("i", $idDemande);
    try{
        if ($stmt->execute()){
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            foreach ($rows as $row){
                $statut = $row['statut'];
                if ($statut == 2 || $statut == 4){
                    return false; /* Une demande avec cet ID déjà acceptée par un prof, il ne peut plus formuler de demande. */
                }
            }
            return true;
        }
    } catch (Exception $e){
        error_log("Erreur lors de la récupération des données ". $e  );
    }
}

?>
