<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d');
$hour_now = date_create('now')->format('H:i');
$date = $date_now;
$time = $hour_now;


//switch_destination
if($_GET['partir_ub']<=1 AND $_GET['partir_ub']>=0)
{
    if($_GET['partir_ub'] == 0)
    {
        $partir_ub = 0;
        $switch_dest = 'searchTrajet.php?partir_ub=1';
        // affichage de texte différents selon la valeur d u boolean partir_ub
        // ici cas trajet arrivant à l'UB
        $txt_main = 'Rechercher un trajet arrivant à l'."'". 'UB';
        $txt_ville = 'Ville de départ : ';
        $txt_placeholder_ville = 'ville de départ';
        $td_debut = 'Trajets partant de ';
        $td_fin = ' et arrivant à l'."'".'UB';    
    }
    else
    {
        $partir_ub = 1;
        $switch_dest = 'searchTrajet.php?partir_ub=0';
        // affichage de texte différents selon la valeur d u boolean partir_ub
        // ici : cas trajet partant de l'UB
        $txt_main = 'Rechercher un trajet partant de l'."'". 'UB';
        $txt_ville = 'Ville d'."'".'arrivée : ';
        $txt_placeholder_ville = 'ville d'."'".'arrivée';
        $td_debut = 'Trajets arrivant à ';
        $td_fin = ' et partant de l'."'".'UB';    
    }
    echo 'valeur du boolean partir_ub : '. $partir_ub; 
}
else
{
    header('location: index.php');
}


// on teste si le submit "rechercher le trajet" =name"search" a été cliqué
// on verifie que la ville est okai

if(isset($_POST['search']))
{
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']); //on affecte les champs du form postés à des variables pour les manipuler plus facilement
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time; //on concatène les champs formulaire date et time en une seule variable datetime_trajet
    
    // on verifie si les champs suivant sont vides
    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        
        $sht_cp = substr($ville_code_postal, 0, -3); //on racourcit de 3 nombres le code postal entré

        $reqville = $bdd->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?"); // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
        $reqville->execute(array($ville_nom_reel, "%$sht_cp%"));
        $ville_exist = $reqville->rowCount();

        
        if($ville_exist > 0) //on teste si il y a au moins une ville retourné par la database
        {
            $id_ville = $reqville->fetch();

            // on prepare la requete de recherche de trajet
            $search_trajet = $bdd->prepare("SELECT id_trajet, id_ville, id_user, 
            date_format(datetime_trajet, '%d/%m/%Y') as date, 
            date_format(datetime_trajet, '%h:%i') as hour, 
            nom, prenom, tel, email from trajet 
            INNER JOIN users ON users.id = trajet.id_user 
            WHERE id_ville=?
            AND datetime_trajet >=?
            AND partir_ub =?  
            ORDER BY datetime_trajet;");
            // on exectute la requete de recherche de trajet et on affiche les resultats avec une boucle foreach
            $search_trajet->execute(array($id_ville['id_ville'], $datetime, $partir_ub));
            $trajet_exist = $search_trajet->rowCount();

            if($trajet_exist > 1)
            {
                echo 
                '<div class="affichage_trajet">
                <h3>Liste des trajets trouvés</h3>
                <table width="70%" border="1%" cellpadding="5">
                <tr>
                    <th colspan="3">' . $td_debut . $ville_nom_reel . $td_fin . '</th>
                    <th colspan="4">Détails conducteur</th>
                </tr>';
        
                foreach($search_trajet as $row)
                {
                echo 
                '<tr>
        
                    <td>' . $txt_ville . ' ' . $ville_nom_reel . '</td>
                    <td>' . $row["date"] . '</td>
                    <td>' . $row["hour"] . '</td>
                    <td>' . $row["nom"] . '</td>
                    <td>' . $row["prenom"] . '</td>
                    <td>' . $row["tel"] . '</td>
                    <td>' . $row["email"] . '</td>
                    <td><a href="inscription_trajet.php?id_trajet='.$row['id_trajet'].'"> Choisir trajet </a></td>
                        
                </tr>';                                   
                }                
                echo 
                '</table>
                </div>';
            }
            else
            {
                $erreur = "Aucun trajet trouvé";
            }

        }
        else
        {
            $erreur ="ville inconnue ou mal saisie";
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
    }
}

?>


<form action="" method="post">
    <h2><?php echo $txt_main; ?></h2>
    <div><a href="<?php echo $switch_dest;?>">Inverser la destination</a></div>
    <div>    
        <div>
            <label><?php echo $txt_ville;?></label></br>
            <input type="text" name="ville_nom" placeholder="<?php echo $txt_placeholder_ville; ?>" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; } ?>"/>
        </div>
        <div>    
            <label>Code postal :</label></br>
            <input type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/>
        </div>
        <div>
            <label>Date :</label></br>
            <input type="date" name="date" value="<?php if(isset($date)) {echo $date; }?>" min="<?php echo $date_now ?>"/>
        </div>
        <div>
            <label>Heure :</label></br>
            <input type="time" name="time" value="<?php if(isset($time)) {echo $time; }?>"/>
        </div>
    

        <?php // affichage du message d'erreur ou succes 
        if(isset($erreur)){
            echo '<div class="error">'. $erreur . '</div>';
            }?>
        <p>
            <input type="submit" name="search" value="Rechercher le trajet"/>
        </p>
    </div>
</form>


<p>
<a href="createTrajet.php?partir_ub=1">Proposer un trajet</a>
</p>

<?php
include 'footer.php';
?>
