<?php
session_cache_limiter('private_no_expire');
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d');
$hour_now = date_create('now')->format('H:i');

if($_SESSION['logged_in'] != 1)
{
    header('location: login.php');
}
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
        $selectionAller = 'class="selectionne gauche"';
        $selectionPartir = '';
    
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
        $selectionPartir = 'class="selectionne droite"';
        $selectionAller = '';    
    }
    //echo 'valeur du boolean partir_ub : '. $partir_ub; 
}
else
{
    header('location: index.php');
}

//début de la page
echo '<div id="corps"><div id="page">';

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

    $length_ville = strlen($ville_code_postal);
    $nb_zero = 5 - strlen($ville_code_postal);
    while($nb_zero > 0){
        $ville_code_postal = $ville_code_postal.'0';
        $nb_zero = $nb_zero - 1;   
    }
    
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
            $search_trajet = $bdd->prepare("SELECT id_trajet, partir_ub, id_ville, id_user, place_dispo, com, date_format(datetime_trajet, '%d/%m/%Y') as date, date_format(datetime_trajet, '%H:%i') as hour, 
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
                        <h2>Liste des trajets trouvés</h2>
                    </div>
                    
                <div id="resultats">'; 
        
                foreach($search_trajet as $row)
                {
                    $depart = $row['partir_ub'];
                    $driver = $row['id_user'];
                    $heure = substr($row['hour'], 0, 2);
                    $minute = substr($row['hour'], -2, 2);
                    /*if(!empty($row['com'])){
                        $commentaire_driver = $row['com'];
                    }
                    else{
                        $commentaire_driver = 'Aucun';
                    }*/

                    $ville = $bdd->prepare("SELECT ville_nom_reel FROM ville WHERE id_ville=?;"); // requete qui permet de trouver l'id correspondant à la ville et son code postal saisis
                    $ville->execute(array($row['id_ville']));
                    $nom_ville = $ville->fetch();

                    if($depart == 1)
                    {
                        echo '
                        <div  class="normal-trajet"> 
                            <div>
                                <h2>' . $row['date'] . ' à ' . $heure . 'h' . $minute . ' - uB à '. $nom_ville['ville_nom_reel'] .  '</h2>
                            </div>
                            <div class="infoTrajet">
                                <p> Conducteur : <a class="profil" href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a> </p>
                                <p> Place(s) disponible(s) : ' . $row['place_dispo'] . '</p>
                            </div>
                            <a class="bouton" href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=inscription"> Choisir ce trajet </a>
                        </div>
                        </br>';
                    }
                    else
                    {
                        echo '
                        <div class="normal-trajet"> 
                            <div class="infoTrajet">
                                <p> Trajet proposé par <a class="profil" href="profil.php?id=' . $driver.'">'. $row['prenom'] . ' ' . $row['nom'] . '</a></p>
                                <p> Le ' . $row['date'] . ' à ' . $heure . 'h' . $minute . ' de '. $nom_ville['ville_nom_reel'] . ' à uB </p>
                            </div>
                            <div>
                                <a class="bouton" href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=inscription"> Choisir ce trajet </a>
                            </div>
                        </div>
                        </br>';    
                    }                              
                }
                echo '</div>';                
            }
            else
            {
                $erreur = "Aucun trajet trouvé.";
            }

        }
        else
        {
            $erreur ="Ville inconnue ou mal saisie.";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent être complétés.";
    }
}

?>
<div class="animBasHaut"></div>

<h2><?php echo $txt_main; ?></h2><br/>
    <div class="flexColonne">
        <form action="" method="post">
            <div class="flexColonne">
                <div class="switch">
                    <a <?php echo $selectionAller;?> href="searchTrajet.php?partir_ub=0">Aller à l'UB</a>
                    <a <?php echo $selectionPartir;?> href="searchTrajet.php?partir_ub=1">Partir de l'UB</a>
                </div>
            </div>
            <div>    
                <div class="flexLigne">
                    <label><?php echo $txt_ville;?></label></br>
                    <input class="center-right-left"  type="text" name="ville_nom" placeholder="<?php echo $txt_placeholder_ville; ?>" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; } ?>"/>
                </div>
                <div class="flexLigne">    
                    <label>Code postal :</label></br>
                    <input class="center-right-left" type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/>
                </div>
                <!--
                <div>
                    <label>Rayon de recherche (km)</label></br>
                    <input type="number" name="rayon_recherche" placeholder="Rayon de recherche" value="<?php if(isset($_POST['rayon_recherche'])) {echo $_POST['rayon_recherche'];} else{echo '10';}?>"/>
                </div>
                -->
                <div class="flexLigne">
                    <label>Date :</label></br>
                    <input class="center-right-left" type="date" name="date" value="<?php if(isset($date)) {echo $date; } else{echo $date_now;}?>" min="<?php echo $date_now ?>"/>
                </div>
                <div class="flexLigne">
                    <label>Heure :</label></br>
                    <input class="center-right-left" type="time" name="time" value="<?php if(isset($time)) {echo $time; } else{echo $hour_now;}?>"/>
                </div>


                <?php // affichage du message d'erreur ou succes 
                if(isset($erreur)){
                    echo '<div class="error">'. $erreur . '</div>';
                    }?>
            <input class="bouton" type="submit" name="search" value="Rechercher le trajet"/>
        </form>
    </div>
    <div class="flexColonne">
        <hr>
        <div class="levier">
        <a class="levier" href="createTrajet.php?partir_ub=1">Proposer un trajet</a>
        </div>
    </div>
    </div>    
</div>
</div>





<link rel="stylesheet" href="css/recherche.css">
<link rel="stylesheet" href="css/main.css">

<?php
include 'footer.php';
?>
