
var home_bg = document.getElementById("home_bg");
var path_bg = document.getElementById("path_bg");
var text_intro = document.getElementById("text_intro");
var header = document.getElementsByTagName("header")[0];
var h1_intro = text_intro.getElementsByTagName("h1")[0];
var p_intro = text_intro.getElementsByTagName("p")[0];
var isAlready850 = false;
var isAlready650 = false;
var isAlready450 = false;

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    if(largeurFenetre <= 450){
        h1_intro.style.fontSize = "18px" ;
        p_intro.style.fontSize = "9px" ;
        text_intro.style.width="300px";
        var differenceBg = 550 - largeurFenetre;
        home_bg.setAttribute('width', '550');
        path_bg.setAttribute('d', 'M 0 220 C 100 270 400 170 500 220 L 500 250 L 0 250')
        home_bg.setAttribute('viewBox', '0 0 500 250');
        home_bg.style.backgroundPosition = -differenceBg/2 + 'px 40%';
    }else if(largeurFenetre <= 700){
        h1_intro.style.fontSize = "22px" ;
        p_intro.style.fontSize = "13px" ;
        text_intro.style.width="350px";
        var differenceBg = 700 - largeurFenetre;
        home_bg.setAttribute('width', '700');
        path_bg.setAttribute('d', 'M 0 220 C 100 270 400 170 500 220 L 500 250 L 0 250')
        home_bg.setAttribute('viewBox', '0 0 500 250');
        home_bg.style.backgroundPosition = -differenceBg/2 + 'px 40%';
        
    }else if(largeurFenetre <= 1100){
        h1_intro.style.fontSize = "22px" ;
        p_intro.style.fontSize = "13px" ;
        text_intro.style.width="350px";
        home_bg.setAttribute('width', '1100');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
        home_bg.style.backgroundPosition = '0px 40%';
    }else if(largeurFenetre <= 1500){
        h1_intro.style.fontSize = "2em" ;
        p_intro.style.fontSize = "16px" ;
        text_intro.style.width="500px"
        home_bg.setAttribute('width', '1500');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
        home_bg.style.backgroundPosition = '0px 40%';
    }else{
        h1_intro.style.fontSize = "2em" ;
        p_intro.style.fontSize = "16px" ;
        text_intro.style.width="500px"
        home_bg.setAttribute('width', '100%');
        path_bg.setAttribute('d', 'M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130')
        home_bg.setAttribute('viewBox', '0 0 500 130');
        home_bg.style.backgroundPosition = '0px 50%';
    }

    if (largeurFenetre <= 450 && !isAlready450) {
        isAlready450 = true;
        isAlready650 = false;
        isAlready850 = false;
        generateTreeMap(250, 112.5, 7);
    } else if (largeurFenetre <= 650 && largeurFenetre > 450 && !isAlready650) {
        isAlready450 = false;
        isAlready650 = true;
        isAlready850 = false;
        generateTreeMap(400, 180, 10);
    } else if (largeurFenetre <= 850 && largeurFenetre > 650 && !isAlready850) {
        isAlready850 = true;
        isAlready650 = false;
        isAlready450 = false;
        generateTreeMap(600, 270, 13);
    } else if (largeurFenetre > 850 && (isAlready850 || isAlready650 || isAlready450)) {
        isAlready450 = false;
        isAlready650 = false;
        isAlready850 = false;
        generateTreeMap(800, 360, 13);
    }
    
    text_intro.style.top = header.offsetHeight + (home_bg.clientHeight - path_bg.getBoundingClientRect().height)/2 - text_intro.offsetHeight/2.5 + "px";
    
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
    var data = {
    name: "UVs",
    children: [
        { name: "MT02", value: 20 },
        { name: "MT23", value: 20 },
        { name: "NF16", value: 20 },
        { name: "NF05", value: 20 },
        { name: "NF02", value: 30 },
        { name: "NF93", value: 30 },
        { name: "NF92", value: 30 },
        { name: "SY01", value: 40 },
        { name: "SY02", value: 40 },
        { name: "SA12", value: 40 },
        // Ajoutez plus de données selon vos besoins
    ]
    };

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
            .text(function(d) { return d.data.value; });
}