<?php
session_start();
include 'config.php';
include 'header.php';

//$vehicule = '<a href="edit_vehicule.php">modifier mon vehicule</a>';

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


?>

<style>
<?php include 'css/profile.css'; ?>
</style>

<div>
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
        <div id="voiture">
            <?php
                $id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
                $req_vehicule_exist = $bdd->prepare('SELECT id_vehicule FROM vehicule WHERE id_user=?;');
                $req_vehicule_exist->execute(array($id_driver));
                $vehicule_exist = $req_vehicule_exist->rowCount();


                if($vehicule_exist > 0) // si le vehicule relié a l'utilisateur passé en url existe
                {
                    include 'my_vehicule.php';

                    /*if($_SESSION['is_driver'] == 1 AND $_GET['id'] == $_SESSION['id'])
                    {
                        $edit_vehicule = '<a href="edit_vehicule.php?edit=1"> Modifier mon vehicule</a>';
                        echo $edit_vehicule;
                    }*/

                
                }
                else //le vehicule de l'user passé en url n'existe pas
                {
                    if($_SESSION['is_driver'] == 1 AND $_GET['id'] == $_SESSION['id']) // l'utilisateur connecté est conducteur et est passé en url 
                    {
                        $add_vehicule = '<a class ="bouton" id="modifVoiture" href="add_vehicule.php"> Ajouter un vehicule</a>';  // alors il peut accéder la page d'ajout de vehicule
                        echo $add_vehicule;
                    }
                }
            ?>
        </div>    
    <?php
    if($userinfo['id'] == $_SESSION['id'])
    {
    ?>
    <p><a class="bouton" href="editprofil.php">Modifier mon profil</a></p>
    <?php
    }
    ?>        
</div>  
       
<?php
if(isset($erreur))
{
    echo '<div class="error">'. $erreur . '</div>';   
}
?>




<?php
include 'footer.php';
?>
