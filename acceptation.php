<?php
session_start();
include 'header.php';
include 'config.php';

if(isset($_GET['idPass']) AND isset($_GET['idTraj'])){

    if(isset($_GET['idSess']) AND ($_GET['idSess'] == $_SESSION['id'])){
    // echo 'lancement script is_accepted';
    $delete_trajet = $bdd->prepare("UPDATE participe SET is_accepted = 1  WHERE id_trajet = ? AND id_user = ?;");
    $delete_trajet->execute(array($_GET['idTraj'], $_GET['idPass']));
    }
    else{
        header('location: index.php');
    }
}

$passagersNonAccepted = $bdd->prepare("SELECT participe.id_user, trajet.id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%h:%i') as hour FROM trajet INNER JOIN participe ON trajet.id_trajet=participe.id_trajet WHERE trajet.id_user = ? AND participe.is_accepted = 0 ORDER BY datetime_trajet;");
$passagersNonAccepted->execute(array($_SESSION['id']));

foreach($passagersNonAccepted as $row){
    //requete pour afficher les utlisateurs a accepter
    $heure = substr($row['hour'], 0, 2);
    $minute = substr($row['hour'], -2, 2);

    $profil_passager = $bdd->prepare("SELECT * FROM users WHERE id = ?");
    $profil_passager->execute(array($row['id_user']));
    $passager_row = $profil_passager->rowCount();

    // si il y a au moins 1 passager
    if($passager_row > 0){
        echo 
            '<div classe="passager">';                          
        foreach($profil_passager as $row2){
            echo    
                '<div>
                    <div>Trajet du '.$row['date'] . ' à ' . $heure . 'h' . $minute.' </div>
                    <div>Demande de <a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a></div>';            
        }
    echo
                    '<div><a href="acceptation.php?idPass=' . $row2['id'].'&idTraj='.$row['id_trajet'].'&idSess='.$_SESSION['id'].'">Valider ce passager</a></div>   
                </div></br>
            </div>';
    }
    // si il n'y aucun passager
    else{
        echo 
            '<div classe="passager">Aucun passager inscrit à vos trajets</div>';
    }



}


include 'footer.php';
?>