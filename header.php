<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ub'Covoit</title>
</head>
<body>

<div>
    <img id="logoAccueuil" src="img/UB.png" alt="logoUB">
    <a href="index.php"> Index </a>
    <a href="<?php echo 'profil.php?id='.$_SESSION['id']?>"> Mon profil </a>
    <a href="my_trajets.php"> Mes trajets </a>
    <a href="logout.php"> Se deconnecter </a>
</div>