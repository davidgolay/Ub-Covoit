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

<div>
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
    if($userinfo['id'] == $_SESSION['id'])
    {
    ?>
    <p><a href="editprofil.php">Modifier mon profil</a></p>
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
$id_driver = intval($_GET['id']); //conversion en nombre pour sécuriser
$req_vehicule_exist = $bdd->prepare('SELECT id_vehicule FROM vehicule v INNER JOIN users u ON v.id_vehicule=u.id WHERE id=?');
$req_vehicule_exist->execute(array($id_driver));
$vehicule_exist = $req_vehicule_exist->rowCount();

if($_SESSION['is_driver'] == 1)
{
    if($vehicule_exist > 0)
    {
        include 'my_vehicule.php';   
    }
    else{
        $add_vehicule = '<a href="edit_vehicule.php">Ajouter un vehicule</a>';
        echo $add_vehicule;
    }
}
?>    


<?php
include 'footer.php';
?>
