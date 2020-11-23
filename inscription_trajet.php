<?php
session_start();
include 'config.php';
include 'header.php';


if(isset($_GET['id_trajet']) AND $_GET['id_trajet'] > 0)
{
    $select_id_trajet = intval($_GET['id_trajet']); //conversion en nombre pour sécuriser

<<<<<<< Updated upstream
    $req_aff_trajet = $bdd->prepare("SELECT * FROM trajet WHERE id_trajet = ?");
=======
    $req_aff_trajet = $bdd->prepare("SELECT trajet.id_trajet, trajet.partir_ub, trajet.id_ville, trajet.id_user, 
    date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, 
    nom, prenom, tel, email from trajet 
    INNER JOIN users ON users.id = trajet.id_user
    WHERE id_trajet=?;");
>>>>>>> Stashed changes
    $req_aff_trajet->execute(array($select_id_trajet));
    $trajet = $req_aff_trajet->fetch(); // enregistrement des données de la requete affichage du trajet

    $other_passager = $bdd->prepare("SELECT * FROM users INNER JOIN participe ON users.id=participe.id_user WHERE id_trajet = ?;");
    $other_passager->execute(array($select_id_trajet));
<<<<<<< Updated upstream
    //$passager = $other_passager->fetch();
=======
    $trajet = $other_passager->fetch();
>>>>>>> Stashed changes
    
    foreach($other_passager as $row)
    {
        echo $row['nom'] . $row['prenom']; 
    }
}

if(isset($_POST['choisir_trajet']))
{
    $id_trajet = $trajet['id_trajet'];

    $insert_passager = $bdd->prepare("INSERT INTO participe(id_user, id_trajet) VALUES(?, ?);");
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
