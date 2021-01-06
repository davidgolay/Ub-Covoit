<?php
$date_now = date_create('now')->format('Y-m-d H:i:s');

$trajet = $bdd->prepare("SELECT trajet.id_user, trajet.partir_ub, trajet.statut_trajet, id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%H:%i') as hour FROM trajet
WHERE trajet.id_user = ? AND trajet.partir_ub = ? AND trajet.datetime_trajet > ? ORDER BY datetime_trajet ASC LIMIT 50;");
$trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));

?>