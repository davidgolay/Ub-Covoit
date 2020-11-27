<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s'); 
/*
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
*/


if($_SESSION['is_driver'] == 1)
{   
    // requete pour recupérer les trajet du conducteur qui part de l'ub
    $trajet_driver = $bdd->prepare("SELECT partir_ub, ville_nom_reel, id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%H:%i') as hour 
    FROM trajet INNER JOIN ville ON trajet.id_ville = ville.id_ville
    WHERE trajet.id_user = ? AND partir_ub = 1 ORDER BY datetime_trajet DESC;");
    $trajet_driver->execute(array($_SESSION['id']));

    echo '<h1>Tout mes trajets proposés</h1>';

    foreach($trajet_driver as $row)
    {
        //echo $row['hour'];
        //echo $row['id_trajet'];
        $heure = substr($row['hour'], 0, 2);
        $minute = substr($row['hour'], -2, 2);

        echo 
            '<div classe="trajet-conducteur"> 
                <h2>
                    Mon trajet du ' . $row['date'] . ' à ' . $heure . 'h' . $minute .  ' de uB à '. $row['ville_nom_reel'] . 
                '</h2>';
        //requete pour afficher les passagers du trajet
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=?;");
        $trajet_passager->execute(array($row['id_trajet']));

            echo 
                '<div classe="passager">Passagers';

        foreach($trajet_passager as $row2)
        {
            echo    
                '<a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a>';            
        }
            echo
                '</div>
            </div>
            </br>';
        
    }
}





?>