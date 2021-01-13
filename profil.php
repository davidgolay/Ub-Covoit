<?php
session_start();
include 'config.php';
include 'header.php';

//$vehicule = '<a href="edit_vehicule.php">modifier mon vehicule</a>';
$modify_profil_2btn = '<div><a class="bouton" href="editprofil.php">Modifier mon profil</a></div>';
$modify_profil_1btn = '<div><a class="bouton" href="editprofil.php">Modifier mon profil</a></div>';
$add_vehicule = '<div><a class="bouton" href="add_vehicule.php"> Ajouter un vehicule</a></div>';  // alors il peut accéder la page d'ajout de vehicule
$edit_vehicule = '<div><a class="bouton" href="edit_vehicule.php">Modifier mon vehicule</a><d/iv>';

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
    $id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
    $participation = $bdd->prepare('SELECT trajet.id_user, trajet.statut_trajet FROM participe INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet WHERE participe.id_user = ? AND participe.is_accepted = 1 AND trajet.id_user = ?;');
    $participation->execute(array($_SESSION['id'], $id_driver));
    $participation_trajet_exist = $participation->rowCount();

    $id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
    $been_drived_by = $bdd->prepare('SELECT trajet.id_user, trajet.statut_trajet FROM participe INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet WHERE participe.id_user = ? AND participe.is_accepted = 1 AND trajet.id_user = ?;');
    $been_drived_by->execute(array($id_driver, $_SESSION['id']));
    $conducteur_trajet_exist = $been_drived_by->rowCount();

    if($participation_trajet_exist > 0 OR $conducteur_trajet_exist > 0){
        $usersAccepted = 1;
        //echo 'oui accepté';
    }
    else{
        $usersAccepted = 0;
        //echo 'non accepté';
    }

    $selectId = intval($_GET['id']); //conversion en nombre pour sécuriser
    $requser = $bdd->prepare('SELECT * FROM users WHERE id = ?');
    $requser->execute(array($selectId));
    $userinfo = $requser->fetch();

    if($userinfo['is_driver'] == 1) 
    {
        $user_conducteur = 'OUI'; 
    }
    else
    {
        $user_conducteur = 'NON';
    }
}
else
{
    header('location: index.php');
}
?>
<link rel="stylesheet" href="css/profile.css">
<link rel="stylesheet" href="css/main.css">

<div class="animBasHaut"></div>
<div id="corps">
<div id="page">
    <h2>Profil de <?php echo $userinfo['prenom']." ".$userinfo['nom']; ?></h2><br/>
    <div class="flexLigne">
        <div class="flexColonneDroite" id="aligneDroite">
                <div class="flexLigne">
                    <div class="etiquette">Prenom : </div>
                    <div class="info"><?php echo $userinfo['prenom'];?></div>
                </div>
                <div class="flexLigne">
                    <div class="etiquette">Nom : </div>
                    <div class="info"><?php echo $userinfo['nom'];?></div>
                </div>

                <?php if (($_GET['id'] == $_SESSION['id']) OR ($usersAccepted == 1)){?>
                    <div class="flexLigne">
                        <div class="etiquette">Email étudiant : </div>
                        <div class="info"> <?php echo $userinfo['email'];?></div>
                    </div><?php
                }?>

                <?php if ($_GET['id'] == $_SESSION['id'] OR $usersAccepted == 1){?>
                    <div class="flexLigne">
                        <div class="etiquette">Téléphone : </div>
                        <div class="info"> <?php echo $userinfo['tel'];?></div>
                    </div><?php
                }?>              


                <div class="flexLigne"> 
                    <div class="etiquette">Conducteur : </div>
                    <div class="info"><?php echo $user_conducteur?></div>
                </div>
        </div>
        <div class="flexColonne">    
        <div class="etiquetteTexte">Biographie : </div>
        <div class="textArea"><?php if(!empty($userinfo['bio'])){echo $userinfo['bio'];}else{echo 'Non renseignée';}?></div>
        </div>
    </div>
    <div><p></br></p></div>

    <?php

    //requete pour savoir si le profil visité est conducteur
    $id_profil = intval($_GET['id']);
    $profil_is_driver = $bdd->prepare('SELECT is_driver FROM users WHERE id=?;');
    $profil_is_driver->execute(array($id_profil));
    $is_driver_info = $profil_is_driver->fetch();
    

    // le profil que l'on visite est conducteur 
    if($is_driver_info['is_driver'] == 1)  
    {   
        //echo 'Le profil est conducteur';
        $id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
        $req_vehicule_exist = $bdd->prepare('SELECT id_vehicule FROM vehicule WHERE id_user=?;');
        $req_vehicule_exist->execute(array($id_driver));
        $vehicule_exist = $req_vehicule_exist->rowCount();
        
        // on rendive dans la boucle si le véhicule existe
        if($vehicule_exist > 0)
        {   
            // le véhicule du profil visité existe, alors on realise la requete qui recupère les données du vehicule correspondant à ce profil
            $select_id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
            $requser = $bdd->prepare('SELECT nom,prenom,id,id_vehicule,place,marque,model,commentaire FROM vehicule v INNER JOIN users u ON v.id_user=u.id WHERE id_user=?');
            $requser->execute(array($select_id_driver));
            $vehicule_info = $requser->fetch();
            // on affiche les champs du vehicule en html
            ?>
                <div>
                    <h2>Véhicule de <?php echo $vehicule_info['prenom']." ".$vehicule_info['nom']; ?></h2><br/>
                    <div class="flexLigne">
                        <div class="flexColonneDroite"  id="aligneDroite">
                                <div class="flexLigne">    
                                    <div class="etiquette">Marque : </div>
                                    <div class="info"><?php echo $vehicule_info['marque'];?></div>   
                                </div>
                                <div class="flexLigne">       
                                    <div class="etiquette">Modèle : </div> 
                                    <div class="info"><?php echo $vehicule_info['model'];?></div>  
                                </div>    
                                <div class="flexLigne">
                                    <div class="etiquette">Place : </div>
                                    <div class="info"><?php echo $vehicule_info['place'];?></div>
                                </div>
                        </div>
                        <div class="flexColonne" id="commentaire">
                                    <div class="etiquetteTexte">Commentaire : </div>
                                    <div class="textArea"><?php if(!empty($vehicule_info['commentaire'])){echo $vehicule_info['commentaire'];}else{echo 'Non renseigné';}?></div>
                        </div>
                    </div>               
                </div>
                <div id="espace"></div>
            <?php  

            // l'utilisateur visitant ce profil est sur son profil personnel
            if($_GET['id'] == $_SESSION['id'])
            {
                echo '<div class="DeuxBtn">' . $modify_profil_2btn .  '<div id="espace"></div>' . $edit_vehicule . '</div>'; // on affiche les deux boutons: modifier mon profil & MODIFIER mon véhicule   
            }
        }
        //le profil visité n'a pas de vehicule renseigné
        else
        {   
            // l'utilisateur visitant ce profil est sur son profil personnel
            if($_GET['id'] == $_SESSION['id'])
            {  
            echo '<div class="DeuxBtn">' . $modify_profil_2btn .  '<div id="espace"></div>' . $add_vehicule . '</div>'; // on affiche les deux boutons: modifier mon profil & AJOUTER un véhicule
            }
        }
    }
    // le profil n'est pas conducteur
    else
    {   
        //le profil visité est le profil de la personne connectée 
        if($_GET['id'] == $_SESSION['id'])
        {
            echo '<div class="UnBtn">' . $modify_profil_1btn . '</div>'; // on affiche le bouton modifier mon profil TOUT SEUL
        }
    }  
?>     
</div>
</div>
</div>
   
<?php
include 'footer.php';
?>
       
