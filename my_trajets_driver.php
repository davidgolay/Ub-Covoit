<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

if($_GET['partir_ub']<=1 AND $_GET['partir_ub']>=0)
{
    if($_GET['partir_ub'] == 0)
    {
        $partir_ub = 0;
        $switch_dest = 'my_trajets_driver.php?partir_ub=1';
        $text_destination = ' allant à uB et partant de ';
        $text_selection = ' Afficher mes trajets partant de uB';
        
    
    }
    else
    {
        $partir_ub = 1;
        $switch_dest = 'my_trajets_driver.php?partir_ub=0';
        $text_destination = ' partant de uB et allant à ';
        $text_selection = ' Afficher mes trajets allant à uB';   
    }
    //echo 'valeur du boolean partir_ub : '. $partir_ub; 
} 


if($_SESSION['is_driver'] == 1)
{   
    // requete pour recupérer les trajet du conducteur qui part de l'ub
    $trajet_driver = $bdd->prepare("SELECT partir_ub, ville_nom_reel, id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%H:%i') as hour 
    FROM trajet INNER JOIN ville ON trajet.id_ville = ville.id_ville
    WHERE trajet.id_user = ? AND trajet.partir_ub = ? ORDER BY datetime_trajet ASC;");
    $trajet_driver->execute(array($_SESSION['id'], $partir_ub));

    echo 
        '<h1>Tout mes trajets proposés</h1>
            <a href="' . $switch_dest . '">' . $text_selection . '</a>
            <div classe="trajet-conducteur">'; 

    foreach($trajet_driver as $row)
    {
        //echo $row['hour'];
        //echo $row['id_trajet'];
        $heure = substr($row['hour'], 0, 2);
        $minute = substr($row['hour'], -2, 2);

            echo 
            '<div classe="trajet-conducteur"> 
                <h2>Mon trajet du ' . $row['date'] . ' à ' . $heure . 'h' . $minute . $text_destination . $row['ville_nom_reel'] . '</h2>';
       

        //requete pour afficher les passagers du trajet
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=?;");
        $trajet_passager->execute(array($row['id_trajet']));
        $passager_row = $trajet_passager->rowCount();

        if($passager_row > 0)
        {

            echo 
                '<div classe="passager">
                    <table>
                        <tr>
                            <td>
                                Passagers inscrits à mon trajet :
                            </td>';
        

            foreach($trajet_passager as $row2)
            {
                echo    
                            '<td>
                                <a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a>
                            </td>';            
            }
            echo
                        '</tr>
                    </table>
                </div></br>';
        }
        else
        {
            echo 
                '<div classe="passager">
                    Aucun passager inscrit
                </div></br>
            </div>';
        }
    }
}





?>