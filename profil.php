<?php
session_start();
include 'config.php';
include 'header.php';

//$vehicule = '<a href="edit_vehicule.php">modifier mon vehicule</a>';
$modify_profil_2btn = '<a href="editprofil.php">Modifier mon profil</a>';
$modify_profil_1btn = '<a href="editprofil.php">Modifier mon profil</a>';
$add_vehicule = '<a class ="bouton" id="modifVoiture" href="add_vehicule.php"> Ajouter un vehicule</a>';  // alors il peut accéder la page d'ajout de vehicule
$edit_vehicule = '<a href="edit_vehicule.php">modifier mon vehicule</a>';

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
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

<style>
<?php include 'css/profile.css'; ?>
</style>

<div id="page">
    <h2>Profil de <?php echo $userinfo['prenom']." ".$userinfo['nom']; ?></h2><br/>
    <div>
        <table>
            <tr>
                <td>Prenom :</td>
                <td><?php echo $userinfo['prenom'];?></td>
            </tr>
            <tr>
                <td>Nom :</td>
                <td><?php echo $userinfo['nom'];?></td>
            </tr>
            <tr>
                <td>Email étudiant :</td>
                <td><?php echo $userinfo['email'];?></td>
            </tr>
            <tr>
                <td>conducteur :</td>
                <td><?php echo $user_conducteur?></td>
            </tr>
            <tr>    
                <td>biographie :</td>
                <td><?php echo $userinfo['bio'];?></td>
            </tr>
        </table>
    </div>
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
        
        // on rentre dans la boucle si le véhicule existe
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
                    <div>
                        <table>
                            <tr>    
                                <td>Marque :</td>
                                <td><?php echo $vehicule_info['marque'];?></td>   
                            </tr>
                            <tr>       
                                <td>Modèle :</td> 
                                <td><?php echo $vehicule_info['model'];?></td>  
                            </tr>    
                            <tr>
                                <td>Place :</td>
                                <td><?php echo $vehicule_info['place'];?></td>
                            </tr>
                            <tr>
                                <td>Commentaire :</td>
                                <td><?php echo $vehicule_info['commentaire'];?></td>
                            </tr>
                        </table>
                    </div>           
                </div>
            <?php  

            // l'utilisateur visitant ce profil est sur son profil personnel
            if($_GET['id'] == $_SESSION['id'])
            {
                echo '<div class="2-btn">' . $modify_profil_2btn . $edit_vehicule . '</div>'; // on affiche les deux boutons: modifier mon profil & MODIFIER mon véhicule   
            }
        }
        //le profil visité n'a pas de vehicule renseigné
        else
        {   
            // l'utilisateur visitant ce profil est sur son profil personnel
            if($_GET['id'] == $_SESSION['id'])
            {  
            echo '<div class="2-btn">' . $modify_profil_2btn . $add_vehicule . '</div>'; // on affiche les deux boutons: modifier mon profil & AJOUTER un véhicule
            }
        }
    }
    // le profil n'est pas conducteur
    else
    {   
        //le profil visité est le profil de la personne connectée 
        if($_GET['id'] == $_SESSION['id'])
        {
            echo '<div class="1-btn">' . $modify_profil_1btn . '</div>'; // on affiche le bouton modifier mon profil TOUT SEUL
        }
    }  
?>     
</div>  

       
<?php
include 'footer.php';
?>