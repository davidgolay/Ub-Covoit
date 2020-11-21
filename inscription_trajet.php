<?php
session_start();
include 'config.php';
include 'header.php';


if(isset($_GET['id_trajet']) AND $_GET['id_trajet'] > 0)
{
    $select_id_trajet = intval($_GET['id_trajet']); //conversion en nombre pour sécuriser
    $req_aff_trajet = $bdd->prepare('SELECT * FROM trajet WHERE id_trajet = ?');
    $req_aff_trajet->execute(array($select_id_trajet));
    $trajet = $req_aff_trajet->fetch();
}
if(isset($_POST['choisir_trajet']))
{
    $id_trajet = $trajet['id_trajet'];

    $insert_passager = $bdd->prepare("INSERT INTO participe(id_user, id_trajet) VALUES(?, ?)");
    $insert_passager->execute(array($_SESSION['id'], $id_trajet));
    //$erreur ="vous êtes inscrit au trajet!";
    header('location: my_trajets.php');
}

?>



<div>
    <h2>Réservation du trajet</h2><br/>
            
    <p>id_driver : <?php echo $trajet['id_user'];?></p>
    <p>id_trajet : <?php echo $trajet['id_trajet'];?></p>
    <p>partir_ub : <?php echo $trajet['partir_ub'];?></p>
    <form action="" method="post">
        <input type="submit" name="choisir_trajet" value="S'inscrire au trajet"/>
    </form>

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
