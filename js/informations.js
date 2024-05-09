function changeMenu(name , event){
    document.getElementsByClassName("currentSpan")[0].className = "other";
    event.target.className = "currentSpan";
    document.getElementsByClassName("visible")[0].className = "hidden";
    document.getElementById(name).className = "visible";
}

// Sélectionner tous les titres h4 dans la div FAQ
const faqH4s = document.querySelectorAll('#FAQ h4');

// Pour chaque titre h4
faqH4s.forEach(h4 => {
    // Ajouter un écouteur d'événement de clic
    h4.addEventListener('click', () => {
        // Récupérer le h5 associé
        const parent = h4.closest(".content");
        const h5 = parent.querySelector('h5');
        const svg = h4.nextElementSibling;

        // Vérifier si le h5 est caché ou non
        if (h5.classList.contains('hidden')) {
            // Afficher le h5 en faisant un défilement vers le bas avec une animation
            h5.classList.remove('hidden');
            h5.style.maxHeight = h5.scrollHeight + 'px';
            svg.className = "fleche_svg_rotate";
        } else {
            // Cacher le h5 en faisant un défilement vers le haut avec une animation
            h5.style.maxHeight = null;
            h5.classList.add('hidden');
            svg.className = "fleche_svg"
        }
    });
});
