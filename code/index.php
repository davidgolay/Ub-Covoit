<?php
session_start();?>

<head>
    <title>uB'Covoit</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/main.css">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/favicon.png">
</head>

<?php
include 'header.php';

if(!isset($_SESSION['logged_in']))
{
    header('location: login.php');
}
?>


<div id="bandeau">
    <h1>Bienvenue sur UB'Covoit !</h1>
    <p>UB'Covoit est une plateforme de covoiturage solidaire entre étudiants. La plateforme met en relation des conducteurs voyageant
         avec des places libres et des passagers se rendant dans la même direction pour effectuer les trajets ensemble. Les membres
          pourront alors diviser les frais routiers et créer des liens.</p>
</div>
<fieldset>
    <div class="animBasHaut"></div>
    <div class="flexColonne">
        <div class="flexLigne">
            <div class="centrer bouton"><a class="TexteBouton" href="searchTrajet.php?partir_ub=0" title="Rechercher un covoiturage allant à l'Université de Bourgogne"> Aller à l'UB </a></div>
            <div class="animBasHautMobile"></div>
            <div class="centrer bouton"><a class="TexteBouton" href="searchTrajet.php?partir_ub=1" title="Rechercher un covoiturage partant de l'Université de Bourgogne"> Partir de l'UB </a></div>
        </div>
        <?php if($_SESSION['is_driver'] == 1){?>
        <div class="animBasHaut"></div>
        <div class="flexLigne">
            <div class="formeLevier"><a class="levier" href="createTrajet.php?partir_ub=1" title="Proposer votre trajet aux étudiants inscrits"> Proposer un trajet </a></div>
        </div>
        <?php } 
        else{?>
                    <div class="animBasHaut"></div>
        <div class="flexLigne">
            <div id="pasConducteur"><p id="pasConducteur"> Pour proposer un trajet : Modifiez votre <a href="<?php if(isset($_SESSION['logged_in'])){echo 'profil.php?id='.$_SESSION['id'];}else{echo 'login.php';}?>">votre profil</a> et cochez la case conducteur. </p></div>
        </div>
        <?php } ?>
    </div>
</fieldset>

<?php
include 'footer.php';
?>