<?php
session_start();
include 'config.php';
include 'header.php';

if(isset($_SESSION['id']) AND $_SESSION['id'] > 0)
{
    $select_id_driver = intval($_SESSION['id']); //conversion en nombre pour sécuriser
    $requser = $bdd->prepare('SELECT nom,prenom,id,id_vehicule,place,marque,model,commentaire FROM vehicule v INNER JOIN users u ON v.id_user=u.id WHERE id_user=?');
    $requser->execute(array($select_id_driver));
    $vehicule_info = $requser->fetch();

}
?>



    
<div>
    <h2>Véhicule de <?php echo $vehicule_info['prenom']." ".$vehicule_info['nom']; ?></h2><br/>
        <div>
            <table>
                <tr>       
                    <td>Model :</td> 
                    <td><?php echo $vehicule_info['model'];?></td>  
                </tr>    
                <tr>    
                    <td>Marque :</td>
                    <td><?php echo $vehicule_info['marque'];?></td>   
                </tr>
                <tr>
                    <td>Place :</td>
                    <td><?php echo $vehicule_info['place'];?></td>
               </tr>
            </table>
        </div>
    
    <?php
        if($vehicule_info['id'] == $_SESSION['id'])
    {
    ?>
    <p><a href="edit_vehicule.php">Modifier mon véhicule</a></p>
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