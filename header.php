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
        <li><a href="index.php"><img id="logoAccueuil" src="img/UB.png" alt="logoUB"></a></li>
        <li><a class="onglet" href="<?php echo 'profil.php?id='.$_SESSION['id']?>"> Mon profil </a></li>
        <li><a class="onglet" href="my_trajets.php"> Mes trajets </a></li>
        <li><a class="onglet" href="logout.php"> Se deconnecter </a></li>
    </ul>
</div>