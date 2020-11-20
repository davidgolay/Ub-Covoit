<?php
session_start();
include 'header.php';
include 'config.php';

$now = date_create('now')->format('Y-m-d H:i:s');

$incoming_trajet = $bdd->prepare("SELECT datetime_trajet, nom, prenom, is_driver 
FROM trajet INNER JOIN users ON users.id = trajet.id_user 
INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
WHERE participe.id_user = ? AND datetime_trajet > ?;");
$incoming_trajet->execute(array($_SESSION['id'], $now));


echo '<h2>Trajets à venir</h2>';

foreach($incoming_trajet as $row)
{
    echo '<div>'. $row['nom'] . $row['prenom'] . $row['datetime_trajet'] . '</div>';
}


$incoming_trajet = $bdd->prepare("SELECT datetime_trajet, nom, prenom, is_driver 
FROM trajet INNER JOIN users ON users.id = trajet.id_user 
INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
WHERE participe.id_user = ? AND datetime_trajet < ?;");
$incoming_trajet->execute(array($_SESSION['id'], $now));

echo '<h2>Trajets effectué</h2>';

foreach($incoming_trajet as $row2)
{
    echo '<div>'. $row2['nom'] . $row2['prenom'] . $row2['datetime_trajet'] . '</div>';
}


?>

<?php
include 'footer.php';
?>