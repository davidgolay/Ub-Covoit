<?php
include 'config.php';
//session_start();
/*if($_SESSION['logged_in'] != 1)
{
    header('location: login.php');
}*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ub'Covoit</title>
</head>
<body>

<!-- <div class="navbar">
    <div><a href="index.php"><img id="logo" src="img/UB.png" alt="logoUB"></a></div>
    <div><a href="<?php echo 'profil.php?id='.$_SESSION['id']?>"> Mon profil </a></div>
    <div><a href="my_trajets.php"> Mes trajets </a></div>
    <div><a href="logout.php"> Se deconnecter </a></div>
</div> -->



<div>
    <ul class="navbar">
        <li><a href="<?php if(isset($_SESSION['logged_in'])){echo 'index.php';}else{echo 'login.php';}?>">
            <img id="logoAccueuil" src="img/UB.png" alt="logoUB">
            </a>
        </li>
        <li><a class="onglet" href="<?php if(isset($_SESSION['logged_in'])){echo 'profil.php?id='.$_SESSION['id'];}else{echo 'login.php';}?>"> 

        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="icon" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
        </svg>

            <b class="mobileTexte">Mon Profil </b></a></li>
        <li><a class="onglet" href="<?php if(isset($_SESSION['logged_in'])){echo 'trajet.php?partir_ub=1&incoming=1&driver=0';}else{echo 'login.php';}?>";>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 16h2V6h5a1 1 0 0 0 .8-.4l.975-1.3a.5.5 0 0 0 0-.6L14.8 2.4A1 1 0 0 0 14 2H9v-.586a1 1 0 0 0-2 0V7H2a1 1 0 0 0-.8.4L.225 8.7a.5.5 0 0 0 0 .6l.975 1.3a1 1 0 0 0 .8.4h5v5z"/>
            </svg> <b class="mobileTexte"> Mes Trajets</b> </a></li>

        <?php // ONGLET CONDUCTEUR pour les acceptations de passager
        if(isset($_SESSION['is_driver'])){
            if($_SESSION['is_driver'] == 1){
            $nbDemandes = 0;
            $req_demande_accept = $bdd->prepare("SELECT trajet.id_user, trajet.statut_trajet, trajet.id_trajet FROM participe INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet AND participe.is_accepted = 0 AND trajet.id_user = ? AND trajet.statut_trajet = 0;");
            $req_demande_accept->execute(array($_SESSION['id']));
            $nbDemandes = $req_demande_accept->rowCount();
            
        ?>
        <li><a class="onglet" href="<?php if(isset($_SESSION['logged_in'])){echo 'acceptation.php';}else{echo 'login.php';}?>"> 

        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="icon" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
        </svg>

            <b class="mobileTexte">Demandes passagères<?php echo ' ('.$nbDemandes.')'?> </b></a></li>
        <?php
            }
        } 
        ?>

        <li><a class="onglet" href="<?php if(isset($_SESSION['logged_in'])){echo 'logout.php';}else{echo 'login.php';}?>"> 
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2v13h1V2.5a.5.5 0 0 0-.5-.5H11zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
            </svg><b class="mobileTexte"><?php if(isset($_SESSION['logged_in'])){ echo ' Deconnexion';}else{echo ' Connexion';}?></b> </a></li>
    </ul>
</div>
