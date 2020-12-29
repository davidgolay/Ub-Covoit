<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

if($_SESSION['is_driver'] == 1)
{
    echo '<div id="page">';
    $incoming_trajet_driver = $bdd->prepare("SELECT date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
    FROM trajet INNER JOIN users ON users.id = trajet.id_user
    WHERE trajet.id_user = ? AND trajet.datetime_trajet > ?;");
    $incoming_trajet_driver->execute(array($_SESSION['id'], $date_now));

    echo '<h2>Trajets à venir en tant que conducteur</h2>';

    foreach($incoming_trajet_driver as $row)
    {
    echo '<div> Trajet de ' . $row['prenom'] . ' ' . $row['nom'] . ', le ' . $row['date'] . ' à ' . $row['hour'] . '</div>';
    }

    $incoming_trajet_driver = $bdd->prepare("SELECT date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
    FROM trajet INNER JOIN users ON users.id = trajet.id_user
    WHERE trajet.id_user = ? AND trajet.datetime_trajet < ?;");
    $incoming_trajet_driver->execute(array($_SESSION['id'], $date_now));

    echo '<h2>Trajets effectué en tant que conducteur</h2>';

    foreach($incoming_trajet_driver as $row)
    {
    echo '<div> Trajet de ' . $row['prenom'] . ' ' . $row['nom'] . ', le ' . $row['date'] . ' à ' . $row['hour'] . '</div>';
    }
    echo '</div>';
}

?>

<style>
<?php include 'css/mytrajetsdriver.css'; ?>
</style>

<?php
include 'footer.php';
?>