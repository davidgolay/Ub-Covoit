<?php
session_start();
include 'header.php';
include 'config.php';

$date_now = date_create('now')->format('Y-m-d H:i:s');

if($_GET['driver']<=1 AND $_GET['driver']>=0){
    // quand le paramètre driver passé en URL est 0
    // correspondra au trajets en tant que passagers
    if($_GET['driver'] == 0){
        $affichage_trajet_driver = 0;
        $txt_title_type_trajet = ' en tant que passager'; // utile pour l'affichage de texte
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_type_trajet = 'driver=1'; // utile pour le switch de passager à conducteur
        $link_type_trajet = 'Afficher mes trajets en tant que conducteur'; // utile pour le switch de passager à conducteur
        
    }
    else{
        $affichage_trajet_driver = 1;
        $txt_title_type_trajet = ' en tant que conducteur';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_type_trajet = 'driver=0'; // utile pour le switch de passager à PASSAGER
        $link_type_trajet = 'Afficher mes trajets en tant que passager'; // utile pour le switch de passager à PASSAGER
          
    }
}
if($_GET['incoming']<=1 AND $_GET['incoming']>=0){
    // quand le paramètre incoming passé en URL est 0
    if($_GET['incoming'] == 0){
        $incoming = 0;
        $txt_title_dated = ' effectués ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_date = 'incoming=1';
        $text_incoming = ' Afficher mes trajets à venir';
        
    }
    else{
        $incoming = 1;
        $txt_title_dated = ' à venir ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_date = 'incoming=0';
        $text_incoming = ' Afficher mes trajets effectués';  
    }
}
if($_GET['partir_ub']<=1 AND $_GET['partir_ub']>=0){
    if($_GET['partir_ub'] == 0){
        $partir_ub = 0;
        $txt_title_destination = "allant à l'Université de Bourgogne";
        $text_destination = ' allant à uB et partant de ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_dest = 'partir_ub=1';
        $text_selection = ' Afficher mes trajets partant de uB';
    }
    else{
        $partir_ub = 1;
        $txt_title_destination = "partant de l'Université de Bourgogne";
        $text_destination = ' partant de uB et allant à ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_dest = 'partir_ub=0';
        $text_selection = ' Afficher mes trajets allant à uB';
          
    }
    //echo 'valeur du boolean partir_ub : '. $partir_ub; 
} 

// si l'utilisateur connecté est conducteur
if(isset($_GET['incoming']) AND isset($_GET['driver']) AND isset($_GET['partir_ub'])) {   

    // si l'utilisateur à cliqué sur afficher mes trajets à venir
    if($_GET['incoming'] == 1){
        // cas de figure pour recuperer les trajets CONDUCTEURS  
        if($_GET['driver'] == 1){

            $trajet = $bdd->prepare("SELECT trajet.id_user, trajet.partir_ub, trajet.statut_trajet, id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
            date_format(datetime_trajet, '%H:%i') as hour FROM trajet
            WHERE trajet.id_user = ? AND trajet.partir_ub = ? AND trajet.datetime_trajet > ? ORDER BY datetime_trajet ASC LIMIT 50;");
            $trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));
        }
        // cas de figure pour recuperer les trajets PASSAGERS
        else{ 
            $trajet = $bdd->prepare("SELECT trajet.partir_ub, trajet.statut_trajet, trajet.id_trajet, trajet.id_user, date_format(datetime_trajet, '%d/%m/%Y') as date, 
            date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
            FROM trajet INNER JOIN users ON users.id = trajet.id_user 
            INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
            WHERE participe.id_user = ? AND trajet.partir_ub = ? AND trajet.datetime_trajet > ? ORDER BY datetime_trajet ASC LIMIT 50;");
            $trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));
        }
    }    
    // si l'utilisateur à cliqué sur afficher mes trajets effectués
    else{ 
        // cas de figure pour recuperer les trajets CONDUCTEURS
        if($_GET['driver'] == 1){ 

            $trajet = $bdd->prepare("SELECT trajet.id_user, trajet.partir_ub, trajet.statut_trajet, id_trajet, date_format(datetime_trajet, '%d/%m/%Y') as date, 
            date_format(datetime_trajet, '%H:%i') as hour FROM trajet
            WHERE trajet.id_user = ? AND trajet.partir_ub = ? AND trajet.datetime_trajet < ? ORDER BY datetime_trajet ASC LIMIT 50;");
            $trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));
        }
        // cas de figure pour recuperer les trajets PASSAGERS
        else{ 
            $trajet = $bdd->prepare("SELECT trajet.partir_ub, trajet.statut_trajet, trajet.id_trajet, trajet.id_user, date_format(datetime_trajet, '%d/%m/%Y') as date, 
            date_format(datetime_trajet, '%h:%i') as hour, nom, prenom, is_driver 
            FROM trajet INNER JOIN users ON users.id = trajet.id_user 
            INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
            WHERE participe.id_user = ? AND trajet.partir_ub = ? AND trajet.datetime_trajet < ? ORDER BY datetime_trajet ASC LIMIT 50;");
            $trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));
        }

    }

    echo // les liens boutons permettant de switcher entre partir_ub, trajets effectués/à venir et la destination ub ou non
    '<div><a href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&incoming='.$_GET['incoming'] . '&' .$switch_type_trajet.'">' . $link_type_trajet . '</a></div>
    <div><a href="trajet.php?'. $switch_dest . '&incoming='.$_GET['incoming'] . '&driver='.$_GET['driver'].'">' . $text_selection . '</a></div>
    <div><a href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&' . $switch_date . '&driver='.$_GET['driver'].'">' . $text_incoming . '</a></div>';

    echo // balises de titre de liste des trajets à afficher
    '<h1>Liste de mes trajets'. $txt_title_dated . $txt_title_destination . $txt_title_type_trajet . '</h1></br>';

    echo 
    '<div classe="trajet-conducteur">';

    foreach($trajet as $row){
        $classTrajet = 'normal-trajet';
        $heure = substr($row['hour'], 0, 2);
        $minute = substr($row['hour'], -2, 2);

        $ville = $bdd->prepare("SELECT ville_nom_reel FROM trajet 
        INNER JOIN ville ON trajet.id_ville = ville.id_ville 
        WHERE id_trajet = ?;");
        $ville->execute(array($row['id_trajet']));
        $nom_ville = $ville->fetch();

        if($_GET['driver'] == 0){
            $id_driver_row = $row['id_user'];
            $driver_info = $bdd->prepare("SELECT nom, prenom, email, tel FROM users WHERE id=?;");
            $driver_info->execute(array($id_driver_row));
            $driver_result = $driver_info->fetch();
            $div_conducteur = '<div> Conducteur :<a href="profil.php?id=' . $id_driver_row.'">'. $driver_result['prenom'] . ' ' . $driver_result['nom'] . '</a></div>';       
        }
        else{
            $div_conducteur = '';
        }

        if ($row['statut_trajet'] == 1){
            echo '<div>Ce trajet à été annulé</div>';
            $classTrajet = 'deleted-trajet';
        }
        
        echo  
            '<div class="'.$classTrajet.'">
            <h2>Trajet du ' . $row['date'] . ' à ' . $heure . 'h' . $minute . $text_destination . $nom_ville['ville_nom_reel'] . '</h2>' . $div_conducteur;

        //requete pour afficher les passagers du trajet
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=?;");
        $trajet_passager->execute(array($row['id_trajet']));
        $passager_row = $trajet_passager->rowCount();

        // si il y a au moins 1 passager
        if($passager_row > 0){
            echo 
                '<div classe="passager">
                    <table>
                        <tr>
                            <td>Passagers inscrits au trajet :</td>';      

            foreach($trajet_passager as $row2){
                echo    
                            '<td><a href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a></td>';            
            }
        echo
                        '</tr>
                    </table>
                </div>';
        }
        // si il n'y aucun passager
        else{
            echo 
                '<div classe="passager">Aucun passager inscrit à ce trajet</div>';
        }
        // si le trajet n'a pas été annulé
        if ($row['statut_trajet'] == 0 AND $_GET['incoming'] == 1){
            // dans le cas "en tant que CONDUCTEUR" => Bouton de suppression du trajet
            if($_GET['driver'] == 1 AND $_SESSION['is_driver'] == 1){
                echo '<div><a href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=delete">Supprimer ce trajet</a></div>';
            }
            // dans le cas "en tant que PASSAGER" => Bouton de désinscription
            else{
                echo '<div><a href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=desinscription">Se désinscrire de ce trajet</a></div>';        
            }
            
        }
    echo '</div>'; // div qui ferme la div de classe "classTrajet" juste avant le h2 Trajet du ...
    echo '</div></br>'; // div qui ferme la div de classe "trajet-conducteur" juste avant le 1er foreach  
    }
}
else{
    header('location: index.php');
}
?>

<?php
include 'footer.php';
?>