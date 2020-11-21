<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

if($_SESSION['is_driver'] == 1)
{
    echo '<div class="button"><a href="my_trajets_driver.php">Mes trajets en tant que conducteur</a>';
}

$incoming_trajet = $bdd->prepare("SELECT date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
FROM trajet INNER JOIN users ON users.id = trajet.id_user 
INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
WHERE participe.id_user = ? AND trajet.datetime_trajet > ? ;");
$incoming_trajet->execute(array($_SESSION['id'], $date_now));


echo '<h2>Trajets à venir</h2>';

foreach($incoming_trajet as $row)
{
    echo '<div> Trajet de ' . $row['prenom'] . ' ' . $row['nom'] . ', le ' . $row['date'] . ' à ' . $row['hour'] . '</div>';
    
}


$done_trajet = $bdd->prepare("SELECT date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
FROM trajet INNER JOIN users ON users.id = trajet.id_user 
INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
WHERE participe.id_user = ? AND trajet.datetime_trajet < ? ;");
$done_trajet->execute(array($_SESSION['id'], $date_now));

echo '<h2>Trajets effectués</h2>';

foreach($done_trajet as $row2)
{
    echo '<div> Trajet de ' . $row2['prenom'] . ' ' . $row2['nom'] . ', le ' . $row2['date'] . ' à ' . $row2['hour'] . '</div>';
    
}


?>

<?php
include 'footer.php';
?>