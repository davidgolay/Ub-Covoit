<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

if($_SESSION['is_driver'] == 1)
{
    $incoming_trajet_driver = $bdd->prepare("SELECT partir_ub, id_ville, date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
    FROM trajet INNER JOIN users ON users.id = trajet.id_user
    WHERE trajet.id_user = ? AND partir_ub = 1 ORDER BY datetime_trajet DESC;");
    $incoming_trajet_driver->execute(array($_SESSION['id']));

    echo '<h2>Trajets à venir en tant que conducteur</h2>';

    foreach($incoming_trajet_driver as $row)
    {
        $ville = $bdd->prepare("SELECT ville_nom_reel FROM ville WHERE id_ville=?;"); // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
        $ville->execute(array($row['id_ville']));
        $nom_ville = $ville->fetch();
        $heure = substr($row['date'], 0, 2);
        $minute = substr($row['date'], -3, 2);
    echo 
        '<div> 
                <p> Mon trajet du ' . $row['date'] . ' à ' . $heure . 'h' . $minute .  ' de uB à '. $nom_ville['ville_nom_reel'] . '</p>
        </div>';
    }
}

?>