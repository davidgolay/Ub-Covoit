<?php
session_start();
include 'config.php';
include 'header.php';


if(isset($_GET['id_trajet']) AND $_GET['id_trajet'] > 0)
{
    $select_id_trajet = intval($_GET['id_trajet']); //conversion en nombre pour sécuriser

    $req_aff_trajet = $bdd->prepare("SELECT trajet.id_trajet, trajet.id_user, trajet.partir_ub, users.nom, users.prenom FROM trajet INNER JOIN users ON trajet.id_user = users.id WHERE id_trajet = ?");
    $req_aff_trajet->execute(array($select_id_trajet));
    $trajet = $req_aff_trajet->fetch(); // enregistrement des données de la requete affichage du trajet
    /*
    $other_passager = $bdd->prepare("SELECT nom, prenom FROM users INNER JOIN participe ON users.id=participe.id_user WHERE id_trajet = ?;");
    $other_passager->execute(array($select_id_trajet));
    //$passager = $other_passager->fetch();
    
    foreach($other_passager as $row)
    {
        echo $row['nom'] . $row['prenom'];
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=?;");
        $trajet_passager->execute(array($trajet['id_trajet']));

            echo 
                '<div classe="passager">Passagers';

        foreach($trajet_passager as $row2)
        {
            echo    
                '<a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a>
                </div>
            </div>';            
        } 
    }*/
}

if(isset($_POST['choisir_trajet']))
{
    $id_trajet = $trajet['id_trajet'];

    $insert_passager = $bdd->prepare("INSERT INTO participe(id_user, id_trajet) VALUES(?, ?);");
    $insert_passager->execute(array($_SESSION['id'], $id_trajet));

    $enlever_place = $bdd->prepare("UPDATE trajet SET place_dispo = place_dispo - 1 WHERE id_trajet=?;");
    $enlever_place->execute(array($id_trajet));


    //$erreur ="vous êtes inscrit au trajet!";
    header('location: my_trajets.php');
}

?>



<div>
    <h2>Réservation du trajet</h2><br/>
            
        <p>nom conducteur : <?php echo $trajet['nom'];?></p>
        <p>prenom conducteur : <?php echo $trajet['prenom'];?></p>
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
