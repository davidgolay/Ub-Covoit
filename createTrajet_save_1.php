<?php
session_start();
include 'config.php';

if(isset($_POST['proposer']))
{
    $ville_nom_reel = htmlspecialchars($_POST['ville_nom']);
    $ville_code_postal = htmlspecialchars($_POST['code_postal']);
    $date = $_POST['date'];
    $time = $_POST['time']; 

    $lg_cp = strlen ( $ville_code_postal);
    echo "longueur code postal" . $lg_cp . "\n";


    if(!empty($_POST['ville_nom']) AND !empty($_POST['date']) AND !empty($_POST['time']))
    {   
        $reqville = $bdd2->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?");
        $reqville->execute(array($ville_nom_reel, "%$ville_code_postal%"));
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

            $reqville = $bdd2->prepare("SELECT id_ville FROM ville WHERE ville_nom_reel=? AND ville_code_postal LIKE ?");
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


        $insertTrajet = $bdd3->prepare("INSERT INTO trajet(id_user, date_trajet, time_trajet, id_ville) VALUES(?, ?, ?, ?)");
        $insertTrajet->execute(array($_SESSION['id'], $date, $time, $id_ville['id_ville']));
        $erreur ="trajet ajouté!";

        
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
<form action="" method="post">
    <h2>Proposer un trajet</h2>
    <div>
    <label>Ville :</label>
    <input type="text" name="ville_nom" placeholder="Entrez la ville de départ ou d'arrivée"/>
    <label>Code postal :</label>
    <input type="text" name="code_postal" placeholder="Entrez le code postal de cette ville"/>
    <label>Date :</label>
    <input type="date" name="date" />
    <label>Heure :</label>
    <input type="time" name="time" />
    </div> 

    <?php
        if(isset($erreur))
        {
            echo '<font color="red">'. $erreur;
        };
    ?>
    <p><input type="submit" name="proposer" value="Proposer le trajet"/>
    <a href="login.php">Déja un compte ?</a>
    </p>
</form>

</body>
</html>