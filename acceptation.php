<?php
session_start();
include 'header.php';
include 'config.php';
?>

<link rel="stylesheet" href="css/main.css">

<div class="info">
    <h1>Acceptation des passagers</h1>
    <div>En validant l'inscription de passagers qui n'ont encore jamais voyagé avec vous, </br> 
    vous aurez accès à des informations de contact supplémentaires en visitant leur profil.</br>
    En validant une inscription, vous permettez également à ces passagers d'acceder à vos informations de contact.</br>
    Retrouvez facilement leur profil en passant par l'onglet "Mes Trajets" puis "Afficher mes trajets conducteurs"
    </div>
</div>

<?php

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

$passagersNonAccepted = $bdd->prepare("SELECT participe.id_user, participe.com_passager, participe.is_accepted, trajet.id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%h:%i') as hour FROM trajet INNER JOIN participe ON trajet.id_trajet=participe.id_trajet WHERE trajet.id_user = ? AND trajet.statut_trajet = 0 AND participe.is_accepted = 0 ORDER BY datetime_trajet;");
$passagersNonAccepted->execute(array($_SESSION['id']));

foreach($passagersNonAccepted as $row){
    //requete pour afficher les utlisateurs a accepter
    $heure = substr($row['hour'], 0, 2);
    $minute = substr($row['hour'], -2, 2);

    $profil_passager = $bdd->prepare("SELECT * FROM users WHERE id = ?");
    $profil_passager->execute(array($row['id_user']));
    $passager_row = $profil_passager->rowCount();

    // si il y a au moins 1 passager qui veut s'inscire
    if($passager_row > 0){?>
        <div><?php
        foreach($profil_passager as $row2){?>
            <div id="page">
                <h3>Trajet du <?php echo $row['date'].' à '.$heure.'h'.$minute;?> </h3>
                <div>Demande de <a href="profil.php?id=<?php echo $row2['id'];?>"> <?php echo $row2['prenom'].' '.$row2['nom'];?></a></div>
                <div>Commentaire d'inscription: <?php if(!empty($row['com_passager'])){echo $row['com_passager'];}else{echo 'pas de message';};?></div>
                <div>Email: <?php echo $row2['email'];?></div>
                <div>Telephone: <?php echo $row2['tel'];?></div>
                </br>
                <?php            
        }?>
                <div><a class="bouton" href="acceptation.php?idPass=<?php echo $row2['id'].'&idTraj='.$row['id_trajet'].'&idSess='.$_SESSION['id'];?>">Accepter ce passager</a></div>   
            </div>
        </div><?php
    }
    // si il n'y aucun passager
    else{?>
        <div>Aucun passager inscrit à vos trajets</div><?php
    }
}


include 'footer.php';
?>