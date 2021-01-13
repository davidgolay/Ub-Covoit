<?php
session_start();
include 'config.php';?>

<head>
    <link rel="stylesheet" href="css/trajet.css">
    <link rel="stylesheet" href="css/main.css">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/favicon.png">
	<title>uB'Covoit</title>	
</head>

<?php
include 'header.php';
if($_SESSION['logged_in'] != 1)
{
    header('location: index.php');
}
?>


<div id="bandeau">
    <h1>Acceptation des passagers</h1>
    <p>En validant l'inscription de passagers qui n'ont encore jamais voyagé avec vous,
    vous aurez accès à des informations de contact supplémentaires en visitant leur profil.
    En validant une inscription, vous permettez également à ces passagers d'acceder à vos informations de contact.
    Retrouvez facilement leur profil en passant par l'onglet <a href="trajet.php?partir_ub=1&incoming=1&driver=1">Mes Trajets Conducteur</a> 
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
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-right-text-fill" viewBox="0 0 16 16">
                        <path d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353V2zM3.5 3h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1zm0 2.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1zm0 2.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1z"/>
                        </svg> : <?php if(!empty($row['com_passager'])){echo $row['com_passager'];}else{echo 'pas de message';};?></div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                        </svg> : 
                        <?php echo $row2['email'];?>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon trajet" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                        </svg> : <?php echo $row2['tel'];?>
                </div>
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