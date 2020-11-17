<?php
session_start();
include 'header.php';
include 'config.php';

//echo "boolean: partir_ub = ". $_SESSION['partir_ub'];

/*on teste la variable session envoyé depuis le formulaire de ub_trajet.php
selon, la valeur posté par ce formulaire, on defini quelle affichage on donnera
entre ville d'arrivée et ville de départ
*/
if($_SESSION['partir_ub'] == 1)
{
    $txt_destination = 'Ville d'."'".'arrivée : ';
    $td_debut = 'Trajets arrivant à ';
    $td_fin = ' et partant de l'."'".'UB';
}

else
{
    $txt_destination = 'Ville départ : ';
    $td_debut = 'Trajets partant de ';
    $td_fin = ' et arrivant à l'."'".'UB';
}

// on teste si le submit "rechercher le trajet" =name"search" a été cliqué
if(isset($_POST['search']))
{
    //on affecte les champs du form postés à des variables pour les manipuler plus facilement
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']);
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    //on concatène les champs formulaire date et time en une seule variable datetime_trajet
    $datetime = $date . ' ' . $time; 

    $lg_cp = strlen ( $ville_code_postal);
    //echo "longueur code postal" . $lg_cp . "\n";
    
    // on verifie si les champs suivant sont vides
    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        //on racourcit de 3 nombres le code postal entré pour faire le teste dans la bdd
        $sht_cp = substr($ville_code_postal, 0, -3);
        //echo "code postale shortened" . $sht_cp . "\n";

        // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
        $reqville = $bdd->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?");
        $reqville->execute(array($ville_nom_reel, "%$sht_cp%"));
        $ville_exist = $reqville->rowCount();

        //on teste si il y a au moins une ville retourné par la database
        if($ville_exist > 0) 
        {
            $id_ville = $reqville->fetch();
        /*echo "id ville = " . $id_ville['id_ville'] . "\n"; */           
        }
        else
        {
            $erreur ="Ville inconnue... Essayez à nouveau";
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
    }
}

?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h2>Rechercher un trajet</h2>
    <div>    
        <p>
            <label><?php echo $txt_destination;?></label>
            <input type="text" name="ville_nom" placeholder="Ville de départ ou d'arrivée" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; }?>"/>
            <label>Code postal :</label>
            <input type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/>
        </p>
        <p>
            <label>Date :</label>
            <input type="date" name="date" value="<?php if(isset($date)) {echo $date; }?>"/>
            <label>Heure :</label>
            <input type="time" name="time" value="<?php if(isset($time)) {echo $time; }?>"/>
        </p>
    

        <?php // affichage du message d'erreur ou succes 
        if(isset($erreur)){echo '<font color="red">'. $erreur;};?>
        <p>
            <input type="submit" name="search" value="Rechercher le trajet"/>
        </p>
    </div>
</form>

<?php
if(isset($_POST['search']))
{
    $insertTrajet = $bdd->prepare("SELECT id_trajet, id_ville, id_user, 
    date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%h:%i') as hour, 
    nom, prenom, tel, email from trajet 
    NATURAL JOIN users WHERE id_ville=? 
    AND datetime_trajet >=?
    AND partir_ub =? 
    GROUP BY id_trajet 
    ORDER BY datetime_trajet;");
    $insertTrajet->execute(array($id_ville['id_ville'], $datetime, $_SESSION['partir_ub']));

        echo '<h3>Liste des trajets trouvés</h3>
                <table width="70%" border="1%" cellpadding="5">
                    <tr>
                        <th colspan="3">' . $td_debut . $ville_nom_reel . $td_fin . '</th>
                        <th colspan="4">Détails conducteur</th>
                    </tr>';

    foreach($insertTrajet as $row)
    {
        echo '<tr>

                <td>' . $txt_destination . ' ' . $ville_nom_reel . '</td>
                <td>' . $row["date"] . '</td>
                <td>' . $row["hour"] . '</td>
                <td>' . $row["nom"] . '</td>
                <td>' . $row["prenom"] . '</td>
                <td>' . $row["tel"] . '</td>
                <td>' . $row["email"] . '</td>
                
            </tr>';
                                    
    }

    echo '</table>';        
}
?>
<p>
<a href="createTrajet.php">Proposer un trajet</a>
</p>

<?php
include 'footer.php';
?>
