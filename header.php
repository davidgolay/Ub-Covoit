<?php
if($_SESSION['logged_in'] != 1)
{
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="css/main.css">
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
        <li><a href="index.php">
            <img id="logoAccueuil" src="img/UB.png" alt="logoUB">
            </a>
        </li>
        <li><a class="onglet" href="<?php echo 'profil.php?id='.$_SESSION['id']?>"> 
            <svg width="1.1em" height="1.1em" viewBox="0 0 16 16" class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            </svg> 
            <b class="mobileTexte">Mon Profil </b></a></li>
        <li><a class="onglet" href="trajet.php?partir_ub=1&incoming=1&driver=0">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 16h2V6h5a1 1 0 0 0 .8-.4l.975-1.3a.5.5 0 0 0 0-.6L14.8 2.4A1 1 0 0 0 14 2H9v-.586a1 1 0 0 0-2 0V7H2a1 1 0 0 0-.8.4L.225 8.7a.5.5 0 0 0 0 .6l.975 1.3a1 1 0 0 0 .8.4h5v5z"/>
            </svg> <b class="mobileTexte"> Mes Trajets</b> </a></li>
        <li><a class="onglet" href="logout.php"> 
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2v13h1V2.5a.5.5 0 0 0-.5-.5H11zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
            </svg><b class="mobileTexte"> Deconnexion</b> </a></li>
    </ul>
</div>