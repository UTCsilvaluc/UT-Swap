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

function insert_demande($connect, $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix) {
    try {
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
        error_log("Erreur lors de l'insertion de la demande : " . $e->getMessage());
        return null;
    } finally {
        // Fermer la requête
        if ($insertion) {
            $insertion->close();
        }
    }
}

function getIdDemandeSwap($connect, $login, $type, $uv , $demande = 0) {
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


?>
