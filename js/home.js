
var home_bg = document.getElementById("home_bg");
var path_bg = document.getElementById("path_bg");
var text_intro = document.getElementById("text_intro");
var header = document.getElementsByTagName("header")[0];
var h1_intro = text_intro.getElementsByTagName("h1")[0];
var p_intro = text_intro.getElementsByTagName("p")[0];
var input_home = document.getElementById("input_home");
var isAlready1000 = false;
var isAlready700 = false;
var isAlready450 = false;

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    home_bg.style.backgroundPosition = '0px 40%';
    if(largeurFenetre <= 450){
        h1_intro.style.fontSize = "18px" ;
        p_intro.style.fontSize = "9px" ;
        text_intro.style.width="300px";
        home_bg.setAttribute('width', '550');
        path_bg.setAttribute('d', 'M 0 220 C 100 270 400 170 500 220 L 500 250 L 0 250')
        home_bg.setAttribute('viewBox', '0 0 500 250');
    }else if(largeurFenetre <= 700){
        h1_intro.style.fontSize = "22px" ;
        p_intro.style.fontSize = "13px" ;
        text_intro.style.width="350px";
        home_bg.setAttribute('width', '700');
        path_bg.setAttribute('d', 'M 0 220 C 100 270 400 170 500 220 L 500 250 L 0 250')
        home_bg.setAttribute('viewBox', '0 0 500 250');
    }else if(largeurFenetre <= 1100){
        h1_intro.style.fontSize = "22px" ;
        p_intro.style.fontSize = "13px" ;
        text_intro.style.width="350px";
        home_bg.setAttribute('width', '1100');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
    }else if(largeurFenetre <= 1500){
        h1_intro.style.fontSize = "2em" ;
        p_intro.style.fontSize = "16px" ;
        text_intro.style.width="500px"
        home_bg.setAttribute('width', '1500');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
    }else{
        h1_intro.style.fontSize = "2em" ;
        p_intro.style.fontSize = "16px" ;
        text_intro.style.width="500px"
        home_bg.setAttribute('width', '100%');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
    }

    if (largeurFenetre <= 450 && !isAlready450) {
        isAlready450 = true;
        isAlready700 = false;
        isAlready1000 = false;
        generateTreeMap(300, 135, 7);
    } else if (largeurFenetre <= 700 && largeurFenetre > 450 && !isAlready700) {
        isAlready450 = false;
        isAlready700 = true;
        isAlready1000 = false;
        generateTreeMap(400, 180, 10);
    } else if (largeurFenetre <= 1000 && largeurFenetre > 700 && !isAlready1000) {
        isAlready1000 = true;
        isAlready700 = false;
        isAlready450 = false;
        generateTreeMap(600, 270, 13);
    } else if (largeurFenetre > 1000 && (isAlready1000 || isAlready700 || isAlready450)) {
        isAlready450 = false;
        isAlready700 = false;
        isAlready1000 = false;
        generateTreeMap(800, 360, 13);
    }

    if (largeurFenetre <= 300) {
        var differenceBg = 700 - largeurFenetre;
        home_bg.style.backgroundPosition = -differenceBg/4 + 'px 40%';
        
    }
    text_intro.style.top = (home_bg.clientHeight - path_bg.getBoundingClientRect().height)/2 - text_intro.offsetHeight/2.5 + "px";
    
}



var codeUV_input_home = document.getElementById("codeUV_input_home");
var type_input_home = document.getElementById("type_input_home");

type_input_home.addEventListener("change", function() {
    type_input_home.style.color = "black";
});

codeUV_input_home.addEventListener("change", function() {
    codeUV_input_home.style.color = "black";
});

function searchSwap(){
    var type_input_home_value = type_input_home.value;
    var codeUV_input_home_value = codeUV_input_home.value;
    if(codeUV_input_home_value.length == 4){
        if(["all", "TD", "TP", "CM"].includes(type_input_home_value)){
            if(type_input_home_value == "all"){
                window.location.href ='../php/demandes.php?codeUV=' + codeUV_input_home_value;
            }else{
                window.location.href ="../php/demandes.php?type="+ type_input_home_value +"&codeUV=" + codeUV_input_home_value;
            }
        }else{
            shakeElement(type_input_home);
        }
    }else{
        shakeElement(codeUV_input_home);
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);
requestAnimationFrame(function() {
    mettreAJourContenuProfil();
});
generateTreeMap(800, 360, 13);
window.addEventListener("load", function() {
    mettreAJourContenuProfil();
});

function randomNudeColor() {
    // Plage de valeurs pour la teinte (0-360)
    var hue = Math.random() * 360;
    // Plage de valeurs pour la saturation (20-70)
    var saturation = Math.random() * 50 + 50;
    // Plage de valeurs pour la luminosité (75-90)
    var lightness = Math.random() * 15 + 75;

    // Convertir les valeurs en format HSL (Teinte, Saturation, Luminosité)
    return 'hsl(' + hue + ',' + saturation + '%,' + lightness + '%)';
}

function generateTreeMap(width, height, fontSize){
    
    d3.select("#treemap svg").remove();
    // Données JSON exemple

    // Création de la treemap avec D3.js
    var treemap = d3.treemap()
    .size([width, height]) // Taille de la treemap
    .padding(5) // Espacement entre les cellules
    .round(true) // Arrondir les dimensions des cellules


    // Sélection de l'élément conteneur
    var svg = d3.select("#treemap")
    .append("svg")
    .attr("width", width)
    .attr("height", height)
    .style("background", "none");

    // Conversion des données en hiérarchie
    var root = d3.hierarchy(data)
    .sum(d => d.value)
    .sort((a, b) => b.value - a.value);


    // Génération de la treemap
    treemap(root);

    // Génération des couleurs aléatoires pour chaque élément de la treemap
    data.children.forEach(function(d) {
        d.color = randomNudeColor();
    });

    // Création des rectangles pour représenter les données
    svg.selectAll("rect")
    .data(root.leaves())
    .enter().append("rect")
        .attr("x", d => d.x0)
        .attr("y", d => d.y0)
        .attr("width", d => d.x1 - d.x0)
        .attr("height", d => d.y1 - d.y0)
        .attr("rx", 15*width/800) // Rayon horizontal
        .attr("ry", 15*width/800) // Rayon vertical
        .attr('fill', function(d) { return d.data.color; });

    svg.selectAll(".name-text")
        .data(root.leaves())
        .enter().append("text")
            .attr("class", "name-text")
            .attr("x", d => (d.x0 + d.x1) / 2)
            .attr("y", d => (d.y0 + d.y1) / 2 - fontSize/2 - 1)
            .attr("text-anchor", "middle")
            .attr("alignment-baseline", "middle")
            .attr("font-size", fontSize + "px")
            .text(d => d.data.name);

    svg.selectAll(".value-text")
        .data(root.leaves())
        .enter().append("text")
            .attr("class", "value-text")
            .attr("x", d => (d.x0 + d.x1) / 2)
            .attr("y", d => (d.y0 + d.y1) / 2 + fontSize/2 + 1)
            .attr("text-anchor", "middle")
            .attr("alignment-baseline", "middle")
            .attr("font-size", fontSize - 2 + "px")
            .text(function(d) { return d.data.nbSwap; });
}

const searchInput = document.getElementById('codeUV_input_swap');
const resultsContainer = document.querySelector('.results');


searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const items = resultsContainer.querySelectorAll('.item_uv');
    items.forEach(item => {
        const itemName = item.getElementsByTagName("h2")[0].textContent.toLowerCase();
        if (itemName.includes(query)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});