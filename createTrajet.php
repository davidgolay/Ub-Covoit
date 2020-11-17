<?php
include 'header.php';
include 'config.php';
session_start();



if(isset($_POST['proposer']))
{
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']);
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time; 

    $lg_cp = strlen ( $ville_code_postal);
    echo "longueur code postal" . $lg_cp . "\n";
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

        $insertTrajet = $bdd->prepare("INSERT INTO trajet(id_user, datetime_trajet, id_ville) VALUES(?, ?, ?)");
        $insertTrajet->execute(array($_SESSION['id'], $datetime, $id_ville['id_ville']));
        $erreur ="trajet ajouté!";
        
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h2>Proposer un trajet</h2>
    <div>
    <p>
        <label>Départ de l'UB :</label></br>
        <input type="radio" name="depart" value="1"/></br>
        <label>Arrivée à l'UB :</label></br>
        <input type="radio" name="depart" value="0"/></br>
    </p>
    <p>
        <label>Ville :</label></br>
        <input type="text" name="ville_nom" placeholder="Ville de départ ou d'arrivée" value="<?php if(isset($ville_nom_reel)) {echo $ville_nom_reel; }?>"/></br>
    </p>
    <p>
        <label>Code postal :</label></br>
        <input type="text" name="code_postal" placeholder="Code postal de cette ville" value="<?php if(isset($ville_code_postal)) {echo $ville_code_postal; }?>"/></br>
    </p>
    <p>
        <label>Date :</label></br>
        <input type="date" name="date" value="<?php if(isset($date)) {echo $date; }?>"/></br>
    </p>
    <p>
        <label>Heure :</label></br>
        <input type="time" name="time" value="<?php if(isset($time)) {echo $time; }?>"/></br>
    </p>
    <p>
        <label>distance maximal de détour :</label></br>
        <input type="number" name="rayon" value="<?php if(isset($rayon)) {echo $rayon; } else{echo 0;}?>"/></br>
    </p>
    </div> 

    <?php
        if(isset($erreur))
        {
            echo '<font color="red">'. $erreur;
        };
    ?>
    <p><input type="submit" name="proposer" value="Proposer le trajet"/></p>
    <p><a href="index.php">Rechercher un trajet</a></p>
    
</form>

<?php
include 'footer.php';
?>