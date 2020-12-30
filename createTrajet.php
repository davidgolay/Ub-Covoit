<?php
session_start();
include 'header.php';
include 'config.php';


if($_SESSION['is_driver'] != 1){
header("location: index.php");
}

$date_now = date_create('now')->format('Y-m-d');
$hour_now = date_create('now')->format('H:i');
$date = $date_now;
$time = $hour_now;


if($_GET['partir_ub']<=1 AND $_GET['partir_ub']>=0)
{
    if($_GET['partir_ub'] == 0)
    {
        $partir_ub = 0;
        $switch_dest = 'createTrajet.php?partir_ub=1';
        // affichage de texte différents selon la valeur d u boolean partir_ub
        // ici cas trajet arrivant à l'UB
        $txt_main = 'Proposer un trajet arrivant à l'."'". 'UB';
        $txt_ville = 'Ville de départ : ';
        $txt_adresse = 'Adresse de départ : ';
        $txt_placeholder_ville = 'ville de départ';
        $txt_placeholder_adresse = 'adresse de départ';
            
    }
    else
    {
        $partir_ub = 1;
        $switch_dest = 'createTrajet.php?partir_ub=0';
        // affichage de texte différents selon la valeur d u boolean partir_ub
        // ici : cas trajet partant de l'UB
        $txt_main = 'Proposer un trajet partant de l'."'". 'UB';
        $txt_ville = 'Ville d'."'".'arrivée : ';
        $txt_adresse = 'Adresse d'."'".'arrivée :';
        $txt_placeholder_ville = 'ville d'."'".'arrivée';
        $txt_placeholder_adresse = 'adresse d'."'".'arrivée';  
          
    }
    echo 'valeur du boolean partir_ub : '. $partir_ub; 
}
else
{
    header('location: index.php');
}

if(isset($_POST['proposer']))
{
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']);
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $com = htmlspecialchars($_POST['com']);
    $rayon = intval($_POST['rayon']);
    $place_dispo = intval($_POST['place_dispo']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time; 

    $lg_cp = strlen ( $ville_code_postal);
    //echo "longueur code postal" . $lg_cp . "\n";
    $rayon = $_POST['rayon'];
    

    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        $reqville = $bdd->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=?");
        $reqville->execute(array($ville_nom_reel));
        $ville_exist = $reqville->rowCount();

        if($ville_exist > 0) 
        {
            $id_ville = $reqville->fetch();
            echo "id ville = " . $id_ville['id_ville'];
        }    
        else
        {   // Si la requete echoue, on racourci de 3 nombres le code postal entré pour reverifier
            $sht_cp = substr($ville_code_postal, 0, -3);
            //echo "code postale shortened" . $sht_cp . "\n";

            $reqville = $bdd->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?");
            $reqville->execute(array($ville_nom_reel, "%$sht_cp%"));
            $ville_exist = $reqville->rowCount();

            if($ville_exist > 0) 
            {
                $id_ville = $reqville->fetch();
                echo "id ville = " . $id_ville['id_ville'];            
            }
            else
            {
                $erreur ="Ville inconnue pour ce code postal";
            }
        }

        $insertTrajet = $bdd->prepare("INSERT INTO trajet(id_user, datetime_trajet, partir_ub, id_ville, adresse, place_dispo, rayon_detour, com) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $insertTrajet->execute(array($_SESSION['id'], $datetime, $partir_ub, $id_ville['id_ville'], $adresse, $place_dispo, $rayon, $com));
        $erreur ="trajet ajouté!";
        header('location: trajet.php?partir_ub='.$partir_ub.'&incoming=1&driver=1');
        
    }
    else
    {
        $erreur = "Les champs obligatoires doivent être complétés";
    }
}


?>
<div class="animBasHaut"></div>
<div id="page">
<form action="" method="post">
    <h2><?php echo $txt_main; ?></h2>
    <br/><div><a class="bouton" href="<?php echo $switch_dest;?>">Inverser la destination</a></div><br/>
    <div>
    <p>
        <label><?php echo $txt_ville;?></label></br>
        <input type="text" name="ville_nom" placeholder="<?php echo $txt_placeholder_ville; ?>" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; }?>"/></br>
    </p>
    <p>
        <label>Code postal :</label></br>
        <input type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/></br>
    </p>
    <p>
        <label><?php echo $txt_adresse;?></label></br>
        <input type="text" name="adresse" placeholder="<?php echo $txt_placeholder_adresse; ?>" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/></br>
    </p>
    <p>
        <label>Date :</label></br>
        <input type="date" name="date" value="<?php if(isset($date)) {echo $date; }?>" min="<?php echo $date_now ?>"/></br>
    </p>
    <p>
        <label>Heure :</label></br>
        <input type="time" name="time" value="<?php if(isset($time)) {echo $time; }?>"/></br>
    </p>
    <p>
        <label>Nombre de place(s) disponible(s) :</label></br>
        <input type="number" name="place_dispo" value="<?php if(isset($place_dispo)) {echo $place_dispo; } else{echo 4;}?>"/></br>
    </p>
    <p>
        <label>distance maximale de détour (en km):</label></br>
        <input type="number" name="rayon" value="<?php if(isset($rayon)) {echo $rayon; } else{echo 0;}?>"/></br>
    </p>
    <p>
        <label>Commentaire sur le trajet :</label></br>
        <input type="text" name="com" value="<?php if(isset($com)) {echo $com; }?>"/></br>
    </p>
    </div> 

    <?php
        if(isset($erreur))
        {
            echo '<div class="error">'. $erreur . '</div>';
        }
    ?>
    <p><input type="submit" name="proposer" value="Créer le trajet"/></p>
    <p><a class="bouton" href="index.php">Rechercher un trajet</a></p>
    
</form>
</div>

<style>
<?php include 'css/recherche.css'; ?>
</style>

<?php
include 'footer.php';
?>