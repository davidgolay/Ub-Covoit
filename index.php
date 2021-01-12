<?php
session_start();
include 'header.php';

if($_SESSION['logged_in'] != 1)
{
    header('location: login.php');
}
?>

<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/main.css">
<div id="bandeau">
    <h1>Bienvenue sur UB'Covoit !</h1>
    <p>UB'Covoit est une plateforme de covoiturage solidaire entre étudiants. La plateforme met en relation des conducteurs voyageant
         avec des places libres et des passagers se rendant dans la même direction pour effectuer les trajets ensembles. Les membres
          pourront alors diviser les frais routiers et créer des liens. 
    </p>
    <?php if($_SESSION['is_driver'] != 1) {?>
        <p>Si vous êtes devenu conducteur et que vous souhaitez proposer des trajets, modifier<a class="onglet" href="<?php if(isset($_SESSION['logged_in'])){echo 'profil.php?id='.$_SESSION['id'];}else{echo 'login.php';}?>"> votre profil </a>
    et revenez sur la page d'acceuil pour accéder à cette fonctionnalité.
    </p>
    <?php } ?>
</div>
<fieldset>
    <div class="animBasHaut"></div>
    <div class="flexColonne">
        <div class="flexLigne">
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=0" title="Rechercher un covoiturage allant à l'Université de Bourgogne"> Aller à l'UB </a></div>
            <div class="animBasHautMobile"></div>
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=1" title="Rechercher un covoiturage partant de l'Université de Bourgogne"> Partir de l'UB </a></div>
        </div>
        <?php if($_SESSION['is_driver'] == 1){?>
        <div class="animBasHaut"></div>
        <div class="flexLigne">
            <div><a class="levier" href="createTrajet.php?partir_ub=1" title="Proposer votre trajet aux étudiants inscrits"> Proposer un trajet </a></div>
        </div>
        <?php } ?>
    </div>
</fieldset>

<?php
include 'footer.php';
?>