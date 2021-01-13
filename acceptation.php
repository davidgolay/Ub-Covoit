<?php
session_start();
include 'header.php';
include 'config.php';
if($_SESSION['logged_in'] != 1)
{
    header('location: index.php');
}
?>
<link rel="stylesheet" href="css/trajet.css">
<link rel="stylesheet" href="css/main.css">

<div id="bandeau">
    <h1>Acceptation des passagers</h1>
    <p>En validant l'inscription de passagers qui n'ont encore jamais voyagé avec vous,
    vous aurez accès à des informations de contact supplémentaires en visitant leur profil.
    En validant une inscription, vous permettez également à ces passagers d'acceder à vos informations de contact.
    Retrouvez facilement leur profil en passant par l'onglet "Mes Trajets" puis "Conducteur"
    </p>
</div>
<div id="corps">
<div  id="page">
    <div id="resultats">

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
date_format(datetime_trajet, '%H:%i') as hour FROM trajet INNER JOIN participe ON trajet.id_trajet=participe.id_trajet WHERE trajet.id_user = ? AND trajet.statut_trajet = 0 AND participe.is_accepted = 0 ORDER BY datetime_trajet;");
$passagersNonAccepted->execute(array($_SESSION['id']));

foreach($passagersNonAccepted as $row){
    //requete pour afficher les utlisateurs a accepter
    $heure = substr($row['hour'], 0, 2);
    $minute = substr($row['hour'], -2, 2);

    $id_trajet_info = $row['id_trajet'];
    $trajet = $bdd->prepare("SELECT trajet.partir_ub, ville.ville_nom FROM trajet INNER JOIN ville ON trajet.id_ville=ville.id_ville WHERE trajet.id_trajet = ?;");
    $trajet->execute(array($id_trajet_info));
    foreach ($trajet as $rowTrajet) {
        if($rowTrajet['partir_ub'] == 1){
            $ville = "De l'UB à ".$rowTrajet['ville_nom'];
        }
        else{
            $ville = "De ".$rowTrajet['ville_nom']." à l'UB";
        }
    }

    $profil_passager = $bdd->prepare("SELECT * FROM users WHERE id = ?");
    $profil_passager->execute(array($row['id_user']));
    $passager_row = $profil_passager->rowCount();

    // si il y a au moins 1 passager qui veut s'inscire
    if($passager_row > 0){?>
            <?php
        foreach($profil_passager as $row2){
            //recupération des infos du trajet
            
            
            ?>
            <div class="normal-trajet flexColonne">
                <h3><?php echo $row['date'].' à '.$heure.'h'.$minute. ' - '.$ville?> </h3>
                <div class="infoTrajet">
                    <div>Demande de <a class="profil" href="profil.php?id=<?php echo $row2['id'];?>"> <?php echo $row2['prenom'].' '.$row2['nom'];?></a></div>
                    <div>Message d'inscription : <?php if(!empty($row['com_passager'])){echo $row['com_passager'];}else{echo 'pas de message';};?></div>
                    <div>Email : <?php echo $row2['email'];?></div>
                    <div>Téléphone : <?php echo $row2['tel'];?></div>
                </div>
                <?php            
        }?>
                <div class="bouton"><a class="TexteBouton" href="acceptation.php?idPass=<?php echo $row2['id'].'&idTraj='.$row['id_trajet'].'&idSess='.$_SESSION['id'];?>">Accepter ce passager</a></div>   
            </div>
        <?php
    }
    // si il n'y aucun passager
    else{?>
        <div>Aucun passager inscrit à vos trajets</div><?php
    }
}?>

    </div>
</div>
</div>

<?php
include 'footer.php';
?>