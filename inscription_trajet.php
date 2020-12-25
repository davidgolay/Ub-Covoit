<?php
session_start();
include 'config.php';
include 'header.php';

switch ($_GET['action']) {
    case 'inscription':
        $action = 'inscription';
        $txt_main = "Inscription à un trajet";
        $txt_action = "S'inscrire";
        break;
    case 'desincription':
        $action = 'desinscription';
        $txt_main = 'Se désincrire du trajet';
        $txt_action = "Se désincrire";
        break;
    case 'delete':
        $action = 'delete';
        $txt_main = 'Supprimer le trajet';
        $txt_action = "Supprimer ce trajet";
        break;
}

// on verifie que l'url est transmise un id_trajet et on vérifie qu'il est supérieur à 0
if(isset($_GET['id_trajet']) AND $_GET['id_trajet'] > 0){
    // on récupère les infos du trajet transmis par url
    $select_id_trajet = intval($_GET['id_trajet']); //conversion en nombre pour sécuriser
    $req_aff_trajet = $bdd->prepare("SELECT trajet.id_trajet, trajet.id_user, trajet.partir_ub, trajet.id_ville, trajet.place_dispo, 
    date_format(datetime_trajet, '%d/%m/%Y') as date, 
    date_format(datetime_trajet, '%H:%i') as hour, 
    users.nom, users.prenom FROM trajet 
    INNER JOIN users ON trajet.id_user = users.id 
    WHERE id_trajet = ?");

    $req_aff_trajet->execute(array($select_id_trajet));
    $trajet = $req_aff_trajet->fetch(); // enregistrement des données de la requete qui récupère les infos du trajet

    $nom_driver = $trajet['prenom'] . ' ' . $trajet['nom']; // concatenation du prénom et du nom du conducteur dans une seule variable
    $heure = substr($trajet['hour'], 0, 2);  // on recupère seulement l'HEURE à partir de l'heure complète
    $minute = substr($trajet['hour'], -2, 2); // on recupère seulement les MINUTES à partir de l'heure complète

    // requete pour récupérer le nom de la ville à partir de l'id_ville enregistré dans le trajet
    $ville_query = $bdd->prepare("SELECT ville_nom_reel FROM ville WHERE id_ville = ?;");
    $ville_query->execute(array($trajet['id_ville']));
    $nom_ville = $ville_query->fetch();
    
    ?>
    <div>
        <h1><?php echo $txt_main;?></h1><br/>
        <h2> Détail du trajet</h2>
    
        <?php
        // le trajet part de l'ub
        if($trajet['partir_ub'] == 1){
            echo 
                '<div>
                    Le ' . $trajet['date'] . ' à ' . $heure . 'h' . $minute . ' de uB à '. $nom_ville['ville_nom_reel'] . '
                <div>';
            $txt_delete = 'le ' . $trajet['date'] . ' à ' . $heure . 'h' . $minute . ' de uB à '. $nom_ville['ville_nom_reel'].'.';  //genération du message à envoyer en cas de suppression du trajet
        }
        else{ // le trajet arrive à l'ub
            echo 
                '<div>
                    Le ' . $trajet['date'] . ' à ' . $heure . 'h' . $minute . ' de uB à '. $nom_ville['ville_nom_reel'] . '  
                <div>';
            $txt_delete = 'le ' . $trajet['date'] . ' à ' . $heure . 'h' . $minute . ' de uB à '. $nom_ville['ville_nom_reel'].'.'; //genération du message à envoyer en cas de suppression du trajet
        }

        // on affiche le conducteur du trajet
        echo 
            '<div>Conducteur du trajet :
                <a href="profil.php?id=' . $trajet['id_user'].'">' . $nom_driver . '</a>
            <div>';

        // requete qui récupére les passagers inscrits au trajet
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=?;");

        $trajet_passager->execute(array($trajet['id_trajet']));
        $row_passager = $trajet_passager->rowCount();

        // il y a des passagers inscrits
        if($row_passager > 0){
            // on affiche les passagers inscrits
            echo 
                '<div classe="passager">
                    
                    <table>
                        <tr>
                            <td>
                                Passagers inscrits au trajet :
                            </td>';

            foreach($trajet_passager as $row2){
                echo '
                            <td>
                                <a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a>
                            </td>';
            } 
        }

        // il n'y a pas de passager inscrit
        else{
            echo 
                    '<div classe="passager">
                        Aucun passager inscrit
                    </div>';
        }
        echo '
                        </tr>
                    </table>
                </div>';
}
        //cas de l'inscription à un trajet
        if(isset($_POST['inscription'])){
            $id_trajet = $trajet['id_trajet'];
            $com_trajet = htmlspecialchars($_POST['com_passager']);
            $insert_passager = $bdd->prepare("INSERT INTO participe(id_user, id_trajet, com_passager) VALUES(?, ?, ?);");
            $insert_passager->execute(array($_SESSION['id'], $id_trajet, $com_trajet));
            $enlever_place = $bdd->prepare("UPDATE trajet SET place_dispo = place_dispo - 1 WHERE id_trajet=?;");
            $enlever_place->execute(array($id_trajet));
            //$erreur ="vous êtes inscrit au trajet!";
            header('location: trajet.php?partir_ub='.$trajet['partir_ub'].'&incoming=1&driver=0');
        }
        //cas de desincription de l'utilisateur passager
        if(isset($_POST['desinscription'])){
            $id_trajet = $trajet['id_trajet'];
            $id_passager = $trajet['id_user'];
            $insert_passager = $bdd->prepare("DELETE FROM participe
            WHERE id_trajet = ?
            AND id_user = ?;");
            $insert_passager->execute(array($id_trajet, $id_passager));
            $add_place = $bdd->prepare("UPDATE trajet SET place_dispo = place_dispo + 1 WHERE id_trajet=?;");
            $add_place->execute(array($id_trajet));
            //$erreur ="vous êtes désinscrit du trajet!";
            header('location: trajet.php?partir_ub='.$trajet['partir_ub'].'&incoming=1&driver=0');
        }

        //cas de suppression du trajet par un conducteur
        if(isset($_POST['delete']) AND $trajet['id_user'] == $_SESSION['id']){
            $id_trajet = $trajet['id_trajet'];
            $id_conducteur = $trajet['id_user'];
            $delete_trajet = $bdd->prepare("UPDATE trajet SET statut_trajet = 1 , com = ?  WHERE id_trajet = ?;");
            $delete_trajet->execute(array($com, $id_trajet));
            header('location: trajet.php?partir_ub='.$trajet['partir_ub'].'&incoming=1&driver=1');
        }
        ?>

        <form action="" method="post">
            <!--
            <label>Ajouter un commentaire</label></br>
              <input type="text" name="com_passager"/></br></br>   
            -->
            <label>Message pour les passagers</label></br>
            <input type="text" name="com_driver" value="<?php echo "Je vous informe que je dois annuler mon trajet proposé ".$txt_delete." Merci de votre compréhension."?>"/></br>
            <input type="submit" name="<?php echo $action;?>" value="<?php echo $txt_action;?>"/>
        </form>
    </div>

<?php

include 'footer.php';
?>
