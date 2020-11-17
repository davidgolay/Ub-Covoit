<?php
include 'config.php';

if(isset($_POST['search']))
{
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']);
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time; 

    $lg_cp = strlen ( $ville_code_postal);
    echo "longueur code postal" . $lg_cp . "\n";
    

    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        
        
        // Si la requete echoue, on racourci de 3 nombres le code postal entré pour reverifier
        $sht_cp = substr($ville_code_postal, 0, -3);
        //echo "code postale shortened" . $sht_cp . "\n";

        $reqville = $bdd->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?");
        $reqville->execute(array($ville_nom_reel, "%$sht_cp%"));
        $ville_exist = $reqville->rowCount();

        if($ville_exist > 0) 
        {
        $id_ville = $reqville->fetch();
        echo "id ville = " . $id_ville['id_ville'] . "\n";            
        }
        else
        {
            $erreur ="Ville inconnue pour ce code postal";
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
    }
}

?>



<?php
if(isset($_POST['search']))
{
    $insertTrajet = $bdd->prepare("SELECT id_trajet, id_ville, id_user, 
    date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, 
    nom, prenom, tel, email from trajet 
    NATURAL JOIN users WHERE id_ville=? 
    AND datetime_trajet >=? 
    GROUP BY id_trajet 
    ORDER BY datetime_trajet;");
    $insertTrajet->execute(array($id_ville['id_ville'], $datetime));

        echo '<h3>Liste des trajets trouvés</h3>
                <table width="60%" border="1%" cellpadding="5">
                    <tr>
                        <th>Trajet</th>
                        <th>Ville</th>
                        <th colspan="3">Détail sur les conducteurs</th>
                        <th>Date du trajet</th>
                        <th>Heure du trajet</th>

                    </tr>';

    foreach($insertTrajet as $row)
    {
        echo '<tr>
                <td>' . $row["id_trajet"] . '</td>
                <td>' . $ville_nom_reel . '</td>
                <td>' . $row["nom"] . '</td>
                <td>' . $row["prenom"] . '</td>
                <td>' . $row["tel"] . '</td>
                <td>' . $row["email"] . '</td>
                <td>' . $row["date"] . '</td>
                <td>' . $row["hour"] . '</td>
            </tr>';
                                    
    }

    echo '</table>';        
}
?>
<p>
<a href="createTrajet.php">Proposer un trajet</a>
</p>