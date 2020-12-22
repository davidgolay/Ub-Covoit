<?php
session_cache_limiter('private_no_expire');
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d');
$hour_now = date_create('now')->format('H:i');


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
    //echo 'valeur du boolean partir_ub : '. $partir_ub; 
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
    //$rayon_recherche = (floatval($_POST['rayon_recherche']) / 111);
    //echo $rayon_recherche;
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time; //on concatène les champs formulaire date et time en une seule variable datetime_trajet
    
    // on verifie si les champs suivant sont vides
    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        
        $sht_cp = substr($ville_code_postal, 0, -3); //on racourcit de 3 nombres le code postal entré

        $reqville = $bdd->prepare("SELECT id_ville, ville_latitude_deg, ville_longitude_deg FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?"); // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
        $reqville->execute(array($ville_nom_reel, "%$sht_cp%"));
        $ville_exist = $reqville->rowCount();

        
        if($ville_exist > 0) //on teste si il y a au moins une ville retourné par la database
        {
            $id_ville = $reqville->fetch();
            //echo 'longitude ville saisie'. $id_ville['ville_longitude_deg'] . '\n';
            //echo 'latitude ville saisie'. $id_ville['ville_latitude_deg'] . '\n';
            //echo 'id ville retourné'. $id_ville['id_ville'] . '\n';

            // on prepare la requete de recherche de trajet
            $search_trajet = $bdd->prepare("SELECT id_trajet, partir_ub, id_ville, id_user, place_dispo, date_format(datetime_trajet, '%d/%m/%Y') as date, date_format(datetime_trajet, '%H:%i') as hour, 
            nom, prenom, tel, email FROM trajet INNER JOIN users ON users.id = trajet.id_user
            WHERE id_ville = ?
            AND datetime_trajet >= ?
            AND partir_ub = ?
            AND place_dispo >=1
            AND id_user != ?
            AND statut_trajet = 0
            LIMIT 10");
            // on exectute la requete de recherche de trajet et on affiche les resultats avec une boucle foreach
            $search_trajet->execute(array($id_ville['id_ville'], $datetime, $partir_ub, $_SESSION['id']));
            $trajet_exist = $search_trajet->rowCount();

            if($trajet_exist > 0)
            {               
                echo '               
                <div>
                    <h3>Liste des trajets trouvés</h3>
                <div>
                </br>'; 
        
                foreach($search_trajet as $row)
                {
                    $depart = $row['partir_ub'];
                    $driver = $row['id_user'];
                    $heure = substr($row['hour'], 0, 2);
                    $minute = substr($row['hour'], -2, 2);

                    $ville = $bdd->prepare("SELECT ville_nom_reel FROM ville WHERE id_ville=?;"); // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
                    $ville->execute(array($row['id_ville']));
                    $nom_ville = $ville->fetch();

                    if($depart == 1)
                    {
                        echo ' 
                        <div>
                            <p> Trajet proposé par <a href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a></p>
                            <p> Le ' . $row['date'] . ' à ' . $heure . 'h' . $minute . ' de uB à '. $nom_ville['ville_nom_reel'] .  '</p>
                        </div>
                        <div>
                            <p>nombre places disponibles : ' . $row['place_dispo'] . '</p>
                            <a href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=inscription"> Choisir ce trajet </a>
                        </div>
                        </br>';
                    }
                    else
                    {
                        echo ' 
                        <div>
                            <p> Trajet proposé par <a href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a></p>
                            <p> Le ' . $row['date'] . ' à ' . $heure . 'h' . $minute . ' de '. $nom_ville['ville_nom_reel'] . ' à uB </p>
                        </div>
                        <div>
                            <a href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=inscription"> Choisir ce trajet </a>
                        </div>
                        </br>';    
                    }                              
                }                
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

<div>
    <form action="" method="post">
        <h2><?php echo $txt_main; ?></h2>
        <div>
            <a href="<?php echo $switch_dest;?>">Inverser la destination</a>
        </div>
        
        <div>    
            <div>
                <label><?php echo $txt_ville;?></label></br>
                <input type="text" name="ville_nom" placeholder="<?php echo $txt_placeholder_ville; ?>" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; } ?>"/>
            </div>
            <div>    
                <label>Code postal :</label></br>
                <input type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/>
            </div>
            <!--
            <div>
                <label>Rayon de recherche (km)</label></br>
                <input type="number" name="rayon_recherche" placeholder="Rayon de recherche" value="<?php if(isset($_POST['rayon_recherche'])) {echo $_POST['rayon_recherche'];} else{echo '10';}?>"/>
            </div>
            -->
            <div>
                <label>Date :</label></br>
                <input type="date" name="date" value="<?php if(isset($date)) {echo $date; } else{echo $date_now;}?>" min="<?php echo $date_now ?>"/>
            </div>
            <div>
                <label>Heure :</label></br>
                <input type="time" name="time" value="<?php if(isset($time)) {echo $time; } else{echo $hour_now;}?>"/>
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
</div>


<p>
<a href="createTrajet.php?partir_ub=1">Proposer un trajet</a>
</p>

<?php
include 'footer.php';
?>
