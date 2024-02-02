
var lundi = document.getElementById("lundi").getElementsByClassName("endroit_cours")[0];
var mardi = document.getElementById("mardi").getElementsByClassName("endroit_cours")[0];
var mercredi = document.getElementById("mercredi").getElementsByClassName("endroit_cours")[0];
var jeudi = document.getElementById("jeudi").getElementsByClassName("endroit_cours")[0];
var vendredi = document.getElementById("vendredi").getElementsByClassName("endroit_cours")[0];
var samedi = document.getElementById("samedi").getElementsByClassName("endroit_cours")[0];



var debutEDT = 8;
var finEDT = 20;

let conteneurHours = document.getElementsByClassName("conteneurHours")[0];
conteneurHours.className = "conteneurHours";

for (var heure = debutEDT ; heure <= finEDT ; heure++){

    let textHour = document.createElement("h4");
    textHour.innerHTML = `${heure}h00`;
    textHour.style.height = "5.55vh";
    textHour.id = `h${heure}`;

    conteneurHours.appendChild(textHour);
}

class Cours {
    constructor(codeUV, horaireDebut, horaireFin, jour, salle, semaine = null, couleur = null) {
        this.codeUV = codeUV;
        this.horaireDebut = horaireDebut;
        this.horaireFin = horaireFin;
        this.jour = jour;
        this.salle = salle;
        this.semaine = semaine;
        this.couleur = couleur;
    }

    afficherDetails() {
        console.log(`Code UV: ${this.codeUV}`);
        console.log(`Horaire début: ${this.horaireDebut}`);
        console.log(`Horaire fin: ${this.horaireFin}`);
        console.log(`Jour: ${this.jour}`);
        console.log(`Salle: ${this.salle}`);
    }
}

let cours1 = new Cours("MT23", "10h15", "12h15", "lundi", "FA104" , "B");
let cours2 = new Cours("IC05", "12h30", "13h00", "mardi", "FA104");
let cours3 = new Cours("MT02", "10h15", "11h15", "mardi", "FA104");
let cours4 = new Cours("PS21", "14h15", "16h15", "vendredi", "FA104");
let cours5 = new Cours("CACA", "08h00", "10h00", "mercredi", "FA104");
let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A");
var colorList = [
    "#A7C4BC", "#D0C4B0", "#B0C4C4", "#C4A4B0", "#C4B8A4",
    "#B0C4B3", "#B0A4C4", "#C4B0A4", "#A4B0C4", "#C4B0B0",
    "#A4C4B0", "#C4B0C1", "#B0C1C4", "#C1C4A4", "#C4B0A7",
    "#A4B0C4", "#C4A790", "#A7C4B0", "#C4A4A4", "#B0A7C4"
];
var coursColors = {};
var liste = [cours1, cours2, cours3, cours4, cours5 , cours6]
var endroit_cours;
var cours;

var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi];
for (var i of listeJour) {
    for(var j=0; j<=48 ; j++){
        if(j%4 === 0){
            if (j === 48){
                i.innerHTML+="<div style='height:10px'></div>";
            } else {
                i.innerHTML+="<div class='dash' style='height:10px'></div>";
            }

        }else{
            i.innerHTML+="<div class='creneau' style='height:1.5vh' onclick='addCreneau(event)'/div>";
        }
    }
    i.removeChild(i.getElementsByClassName('dash')[0]);
}

function addCreneau(event){
    console.log(event);
}

function calculDecimal(nombre) {
    var heuresMinutesDebut = nombre.split('h');
    var heuresDebut = parseInt(heuresMinutesDebut[0], 10);
    var minutesDebut = parseInt(heuresMinutesDebut[1], 10);

    return heuresDebut + minutesDebut / 60;
}

function calculPourcentage(nombre) {

    var pixel = nombre * tailleEDT / nbHeureEDT;
    return pixel * 100 / tailleEDT;
}

function getRandomColor(listOfColors) {
    if (listOfColors.length === 0) {
        console.log("La liste de couleurs est vide !");
        return null;
    }
    // Générer un index aléatoire
    const randomIndex = Math.floor(Math.random() * listOfColors.length);

    // Récupérer la couleur à l'index aléatoire
    const randomColor = listOfColors[randomIndex];

    // Supprimer la couleur de la liste
    listOfColors.splice(randomIndex, 1);

    // Retourner la couleur sélectionnée
    return randomColor;
}

var compteur=0;
var lundiListe = [];
var mardiListe = [];
var mercrediListe = [];
var jeudiListe = [];
var vendrediListe = [];
for (var i = 0; i < liste.length; i++) {

    if(liste[i].jour == "lundi"){
        endroit_cours = lundi;
    }
    if(liste[i].jour == "mardi"){
        endroit_cours = mardi;
    }
    if(liste[i].jour == "mercredi"){
        endroit_cours = mercredi;
    }
    if(liste[i].jour == "jeudi"){
        endroit_cours = jeudi;
    }
    if(liste[i].jour == "vendredi"){
        endroit_cours = vendredi;
    }
    if(liste[i].jour == "samedi"){
        endroit_cours = samedi;
    }

    if (!(liste[i].codeUV in coursColors)) {
        coursColors[liste[i].codeUV] = getRandomColor(colorList);
    }
    liste[i].couleur = coursColors[liste[i].codeUV];

    endroit_cours.innerHTML += '<div class="cours"><h2 class="UV">' + '</h2><p>' + liste[i].horaireDebut + '-' + liste[i].horaireFin + '</p><p>' + liste[i].salle + '</p></div>'
    cours = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length -1];

    var tailleEDT = endroit_cours.offsetHeight;
    var nbHeureEDT = 12;
    var heureDebutEDT = 8;
    var heuresDecimalesDebut = calculDecimal(liste[i].horaireDebut);

    var heuresDecimalesFin = calculDecimal(liste[i].horaireFin);

    var tempsCours = heuresDecimalesFin - heuresDecimalesDebut;

    var pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT);

    var pourcentageHeight = calculPourcentage(tempsCours);

    document.getElementById("emploi_du_temps").addEventListener("mousemove" , function (event) {
        if (event.target.className == "cours"){
            console.log("test");
            hoverCours = document.getElementsByClassName("hoverCours")[0];
            hoverCours.style.display = "flex";
            //hoverCours.getElementById("goswap").style.height = event.target.style.height;
            hoverCours.style.left = `${event.target.getBoundingClientRect().x }px`
            hoverCours.style.top = `${event.target.getBoundingClientRect().y + 5}px`
        }

    });

    document.getElementById("emploi_du_temps").addEventListener("mouseout" , function (event) {
        if (event.target.className !== "cours" && event.target.className !== "hoverCours" && event.target.parentElement.className !== "cours") {
            hoverCours = document.getElementsByClassName("hoverCours")[0];
            hoverCours.style.display = "none";
        }
    });
    cours.style.height = pourcentageHeight + "%";
    cours.style.overflow = "hidden";

    cours.style.top = pourcentageTop + "%";
    if (pourcentageHeight < 10){
        cours.style.flexDirection = "row";
        cours.style.fontSize = pourcentageHeight * 8 + '%';
        cours.style.gap = "4px";
        for (element of cours.children){
            element.style.fontSize = "12px";
        }
    } else {
        cours.style.fontSize = pourcentageHeight * 5 + '%';
    }

    cours.style.background = liste[i].couleur;

    if (liste[i].semaine == null){
        cours.getElementsByClassName("UV")[0].innerHTML = liste[i].codeUV;
    } else {
        if (liste[i].semaine === "B"){
            cours.style.left = "50%";
        }
        cours.style.width = "50%";
        cours.getElementsByClassName("UV")[0].innerHTML = liste[i].codeUV + ' - ' + liste[i].semaine;
    }

};

