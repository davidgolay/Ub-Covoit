<?php
session_start();
include 'header.php';
?>

<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/main.css">
<div id="bandeau">
    <h1>Bienvenue sur UB'Covoit !</h1>
    <p>UB'Covoit est une plateforme de covoiturage solidaire entre étudiants. La plateforme met en relation des conducteurs voyageant
         avec des places libres et des passagers se rendant dans la même direction pour effectuer les trajets ensembles. Les membres
          pourront alors diviser les frais routiers et créer des liens. 
    </p>
</div>
<fieldset>
    <div class="animBasHaut"></div>
    <div class="flexColonne">
        <div class="flexLigne">
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=0"> Aller à l'UB </a></div>
            <div class="animBasHautMobile"></div>
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=1"> Partir de l'UB </a></div>
        </div>
        <div class="animBasHaut"></div>
        <div class="flexLigne">
            <div><a class="levier" href="createTrajet.php?partir_ub=1"> Proposer un trajet </a></div>
        </div>
    </div>
</fieldset>

<?php
include 'footer.php';
?>