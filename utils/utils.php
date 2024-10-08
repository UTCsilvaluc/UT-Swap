<?php
function numeroVersJour($numeroJour) {
    // Conversion du numéro de jour en entier
    $numeroJour = (int)$numeroJour;

    // Tableau associatif pour mapper les numéros de jour aux noms de jour
    $jours = array(
        1 => "Lundi",
        2 => "Mardi",
        3 => "Mercredi",
        4 => "Jeudi",
        5 => "Vendredi",
        6 => "Samedi",
        7 => "Dimanche"
    );

    // Vérifier si le numéro de jour existe dans le tableau
    if (array_key_exists($numeroJour, $jours)) {
        // Retourner le nom du jour correspondant
        return $jours[$numeroJour];
    } else {
        // Retourner un message d'erreur si le numéro de jour est invalide
        return "Numéro de jour invalide";
    }
}

function tempsDecimalEntreDeuxHeures($heureDebut, $heureFin) {
    // Convertir les heures en minutes
    $heuresDebut = explode(":", $heureDebut);
    $heuresFin = explode(":", $heureFin);

    $minutesDebut = $heuresDebut[0] * 60 + $heuresDebut[1];
    $minutesFin = $heuresFin[0] * 60 + $heuresFin[1];

    // Calculer la différence en minutes
    $differenceMinutes = $minutesFin - $minutesDebut;

    // Convertir la différence en heures décimales
    $tempsDecimal = $differenceMinutes / 60;

    return $tempsDecimal;
}

?>
