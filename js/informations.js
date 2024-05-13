function changeMenu(name , event){
    document.getElementsByClassName("currentSpan")[0].className = "other";
    event.target.className = "currentSpan";
    document.getElementsByClassName("visible")[0].className = "hidden";
    document.getElementById(name).className = "visible";
}

// Sélectionner tous les titres h4 dans la div FAQ
const faq_contents = document.querySelectorAll('.faq_content');

// Pour chaque titre h4
faq_contents.forEach(faq_content => {
    // Ajouter un écouteur d'événement de clic
    faq_content.addEventListener('click', () => {
        // Récupérer le h5 associé
        const parent = faq_content;
        const h5 = parent.querySelector('h5');
        const svg = parent.querySelector("img");

        svg.classList.toggle('fleche_svg_rotate');

        // Vérifier si le h5 est caché ou non
        if (h5.classList.contains('hidden')) {
            // Retire la classe hidden pour afficher l'élément
            h5.classList.remove('hidden');
            // Retire la propriété max-height pour afficher l'élément
            h5.style.maxHeight = h5.scrollHeight + 'px';
            h5.style.margin="20px 0px";
            parent.style.height="fit-content";
            parent.style.marginTop = "10px";
        } else {
            // Cache l'élément
            h5.style.maxHeight = 0;
            h5.style.margin="5px 0px";

            setTimeout(() => {
                h5.classList.add('hidden');
                parent.style.height="50px";
                parent.style.marginTop = "0px";
            }, 500);
        }
    });
});

function mettreAJourContenu() {
    var largeurFenetre = window.innerWidth;
    var span_RESUME = document.getElementById("span_RESUME");
    var span_HOW = document.getElementById("span_HOW");
    var span_MAJ = document.getElementById("span_MAJ");
    var span_FAQ = document.getElementById("span_FAQ");
    if(largeurFenetre <= 900){
        span_RESUME.textContent = "UT'Swap";
        span_MAJ.textContent = "MAJ";
        span_HOW.textContent = "Fonct.";
    }else{
        span_HOW.textContent = "Fonctionnement";
        span_RESUME.textContent = "UT'Swap en quelques mots";
        span_MAJ.textContent = "Mise à jour et nouveautés";
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenu);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenu();
