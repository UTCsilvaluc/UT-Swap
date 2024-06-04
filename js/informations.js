
function changeMenu(name, event) {
    if (event) {
        event.preventDefault();
    }
    const currentSpan = document.querySelector(".currentSpan");
    if (currentSpan) {
        currentSpan.className = "other";
    }
    const targetLink = document.querySelector(`a[href="#${name}"]`);
    if (targetLink) {
        targetLink.className = "currentSpan";
    }
    const visibleSection = document.querySelector(".visible");
    if (visibleSection) {
        visibleSection.className = "hidden";
    }
    const targetSection = document.getElementById(name);
    if (targetSection) {
        targetSection.className = "visible";
    }
    window.location.hash = name;
}

const mainInfo = document.querySelector('div.main_info');
const headerHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--taille-header'));
const maxHeight = window.innerHeight - headerHeight;

function adjustHeight() {
    // Réinitialise la hauteur pour obtenir la hauteur du contenu réel
    mainInfo.style.height = 'auto';

    // Vérifie si la hauteur du contenu dépasse la hauteur maximale
    if (mainInfo.scrollHeight > maxHeight) {
        mainInfo.style.height = 'fit-content'; // S'adapte au contenu
    } else {
        mainInfo.style.height = `${maxHeight + 50}px`; // Utilise la hauteur maximale disponible
    }
}

const faq_contents = document.querySelectorAll('.faq_content');
faq_contents.forEach(faq_content => {
    faq_content.addEventListener('click', () => {
        const parent = faq_content;
        const h5 = parent.querySelector('h5');
        const svg = parent.querySelector("img");
        svg.classList.toggle('fleche_svg_rotate');
        adjustHeight();
        if (h5.classList.contains('hidden')) {
            h5.classList.remove('hidden');
            h5.style.maxHeight = h5.scrollHeight + 'px';
            h5.style.margin="20px 0px";
            parent.style.height="fit-content";
            parent.style.marginTop = "10px";
        } else {
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

window.addEventListener('resize', mettreAJourContenu);
mettreAJourContenu();

function handleHashChange() {
    const hash = window.location.hash.substring(1);
    if (hash) {
        const targetLink = document.querySelector(`a[href="#${hash}"]`);
        if (targetLink) {
            targetLink.click(); // Simule un clic sur le lien correspondant au fragment d'ancrage
        }
    }
}

window.addEventListener('load', handleHashChange);
window.addEventListener('hashchange', handleHashChange);