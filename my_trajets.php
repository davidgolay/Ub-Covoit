<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

echo '<div id ="page">';

if($_SESSION['is_driver'] == 1)
{
    echo '<div><a class="bouton" href="my_trajets_driver.php">Mes trajets en tant que conducteur</a>';
}

$incoming_trajet = $bdd->prepare("SELECT trajet.partir_ub, trajet.id_trajet, trajet.id_user, date_format(datetime_trajet, '%d/%m/%Y') as date, 
date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
FROM trajet INNER JOIN users ON users.id = trajet.id_user 
INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
WHERE participe.id_user = ? AND trajet.datetime_trajet > ? ;");
$incoming_trajet->execute(array($_SESSION['id'], $date_now));

//Tu pourrais rajouter un if qui permet de echo soit un h2 normal soit un h2 avec un id driver si l'utilisateur est conducteur s'il te plait ?
echo '<h2 id="driver">Trajets à venir :</h2>';
//

foreach($incoming_trajet as $row)
{
    $depart = $row['partir_ub'];
    $trajet = $row['id_trajet'];
    $driver = $row['id_user'];
    $ville = $bdd->prepare("SELECT ville_nom_reel FROM trajet 
    INNER JOIN ville ON trajet.id_ville = ville.id_ville 
    WHERE id_trajet = ?;");
    $ville->execute(array($trajet));
    $nom_ville = $ville->fetch();

    if($depart == 1)
    {
        echo ' 
        <div>
            <p> Le ' . $row['date'] . ' à ' . $row['hour'] . '</p>
            <p> De uB à '. $nom_ville['ville_nom_reel'] . '</p>
            <p> Conducteur :<a href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a></p>
        </div></br>';
    }
    else
    {
        echo ' 
        <div>
            <p> Le ' . $row['date'] . ' à ' . $row['hour'] . '</p>
            <p> De ' . $nom_ville['ville_nom_reel'] . ' à uB </p>
            <p> Conducteur :<a href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a></p>
        </div></br>';
    }
    
}
echo '</div>';




?>

<style>
<?php include 'css/mytrajetsdriver.css'; ?>
</style>

<?php
include 'footer.php';
?>