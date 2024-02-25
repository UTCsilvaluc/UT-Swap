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
?>