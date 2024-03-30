<?php
function create_swap($connect, $primaryKeyDemande, $offerId, $uv, $type, $login) {
    try {
        // Vérifier les données de l'offre
        $sqlCheckData = "SELECT codeUV, type, login FROM demande WHERE idDemande = ?";
        $stmtCheckData = $connect->prepare($sqlCheckData);
        $stmtCheckData->bind_param("i", $offerId);
        $stmtCheckData->execute();
        $stmtCheckData->bind_result($offerCodeUV, $offerType, $offerLogin);
        $stmtCheckData->fetch();
        $stmtCheckData->close();

        // Vérifier la cohérence des données
        if ($offerCodeUV != $uv || $offerType != $type) {
            throw new Exception("L'UV et / ou le type sont différents !");
        } elseif ($offerLogin === $login) {
            throw new Exception("Impossible de se faire une demande à soi-même !");
        } else if (checkIfSwapExist($connect , $offerId , $primaryKeyDemande)){
            throw new Exception("Cette demande de SWAP existe déjà !!!!");
        } else if (!(checkCreneauIsEqual($connect , $primaryKeyDemande , $offerId))){
            throw new Exception("La durée du créneau proposé et demandé ne sont pas équivalents...");
        } else {
            // Insérer le swap dans la base de données
            $result = insert_swap($connect, $offerId, $primaryKeyDemande);
            if ($result) {
                echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_envoie_swap.classList.toggle('hidden', false);boutons_confirmation.classList.toggle('hidden', false);</script>";
            } else {
                throw new Exception("Erreur dans la création du SWAP...");
            }
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la création du swap : " . $e->getMessage());
        echo "Erreur lors de la création du swap : " . $e->getMessage();
    }
}

function checkCreneauIsEqual($connect , $primaryKeyDemande, $offerId){
    try {
        $sqlGetCreneau = "SELECT horaireDebut, horaireFin FROM demande WHERE idDemande = ?";

        $stmtCheckData = $connect->prepare($sqlGetCreneau);
        $stmtCheckData->bind_param("i", $primaryKeyDemande);
        if ($stmtCheckData->execute()) {
            // Récupérer le résultat
            $result = $stmtCheckData->get_result();
            $row = $result->fetch_assoc();
            $stmtCheckData->close();
            $heureDebut1 = $row['horaireDebut'];
            $heureFin1 = $row['horaireFin'];
        }

        $stmtCheckData = $connect->prepare($sqlGetCreneau);
        $stmtCheckData->bind_param("i", $offerId);
        if ($stmtCheckData->execute()) {
            // Récupérer le résultat
            $result = $stmtCheckData->get_result();
            $row = $result->fetch_assoc();
            $stmtCheckData->close();
            $heureDebut2 = $row['horaireDebut'];
            $heureFin2 = $row['horaireFin'];
        }

        if ($heureDebut1 == null || $heureDebut2 == null || $heureFin1 == null || $heureFin2 == null){
            throw new Exception("Erreur dans la récupération des données, valeur null...");
        }
        $tempsDecimal1 = tempsDecimalEntreDeuxHeures($heureDebut1 , $heureFin1);
        $tempsDecimal2 = tempsDecimalEntreDeuxHeures($heureDebut2 , $heureFin2);
        if ($tempsDecimal1 == $tempsDecimal2){
            return true;
        }
        return false;
    } catch (Exception $e){
        error_log("Erreur lors de la récupération des données du créneau : " . $e->getMessage());
        echo "Erreur lors de la récupération des données du créneau : " . $e->getMessage();
    }
}
function checkIfSwapExist($connect, $offerId, $demandeur) {
    try {
        // Préparer la requête SQL
        $stmt = $connect->prepare("SELECT idDemande FROM swap WHERE idDemande = ? AND demandeur = ?");
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête");
        }
        $stmt->bind_param("ii", $offerId, $demandeur);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
        // Lier le résultat
        $stmt->store_result();
        // Vérifier si des lignes sont retournées
        $rows = $stmt->num_rows;
        // Fermer la requête
        $stmt->close();
        // Retourner True si des lignes sont retournées, sinon False
        return ($rows > 0);
    } catch (Exception $e) {
        error_log("Erreur lors de la vérification de l'existence du swap : " . $e->getMessage());
        return false;
    }
}

function afficherChangementCreneau($connect , $currentIDdemande , $jour, $salle, $hdebut, $hfin) {
    $demandeDetails = fetchDemandeDetails($connect, $currentIDdemande);
    // Extraction des détails dans des variables individuelles
    $initHoraireDebut = $demandeDetails['horaireDebut'];
    $initHoraireDebut = substr($initHoraireDebut, 0, 5);
    $initHoraireFin = $demandeDetails['horaireFin'];
    $initHoraireFin = substr($initHoraireFin, 0, 5);
    $initJour = $demandeDetails['jour'];
    $initSalle = $demandeDetails['salle'];
    // Conversion des numéros de jour en texte
    $textJour = numeroVersJour($jour);
    $textInitJour = numeroVersJour($initJour);
    $hasRequest = checkIfHasRequest($connect , $currentIDdemande);
    echo "<script> document.getElementById('swapJour1').innerHTML = '$textJour'; document.getElementById('swapSalle1').innerHTML = '$salle'; document.getElementById('swapCreneau1').innerHTML = `$hdebut - $hfin` ; </script>";
    echo "<script> document.getElementById('swapJour2').innerHTML = '$textInitJour'; document.getElementById('swapSalle2').innerHTML = '$initSalle'; document.getElementById('swapCreneau2').innerHTML = `$initHoraireDebut - $initHoraireFin` ; </script>";
    if (!$hasRequest){
        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_changement_creneau.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);bouton_remplacer.classList.toggle('hidden', false);</script>";
        echo "<script> document.getElementById('sendSwap').classList.toggle('hidden' , false);document.getElementById('ancienCreneauSwap1').classList.toggle('hidden' , false);document.getElementById('ancienCreneauSwap2').classList.toggle('hidden' , false);</script>";
    } else {
        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_demande_recu_changement_creneau.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);bouton_continuer.classList.toggle('hidden', false);</script>";
        $_SESSION["hasRequest"] = "hasRequest";
    }
}
?>