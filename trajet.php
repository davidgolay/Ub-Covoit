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
        $txt_title_type_trajet = ' passagers'; // utile pour l'affichage de texte
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_type_trajet = 'driver=1'; // utile pour le switch de passager à conducteur
        $link_type_trajet = 'Conducteur'; // utile pour le switch de passager à conducteur
        $selection_driver = '';
        $selection_passager = 'class="selectionne droite"';
        
    }
    else{
        $affichage_trajet_driver = 1;
        $txt_title_type_trajet = ' conducteurs';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_type_trajet = 'driver=0'; // utile pour le switch de passager à PASSAGER
        $link_type_trajet = 'Passager'; // utile pour le switch de passager à PASSAGER
        $selection_passager = '';
        $selection_driver = 'class="selectionne gauche"';
          
    }
}
if($_GET['incoming']<=1 AND $_GET['incoming']>=0){
    // quand le paramètre incoming passé en URL est 0
    if($_GET['incoming'] == 0){
        $incoming = 0;
        $txt_title_dated = ' effectués ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_date = 'incoming=1';
        $text_incoming = 'A venir';
        $selection_effectue = 'class="selectionne droite"';
        $selection_avenir = '';
    }
    else{
        $incoming = 1;
        $txt_title_dated = ' à venir ';
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_date = 'incoming=0';
        $text_incoming = 'Effectués';
        $selection_effectue = '';
        $selection_avenir = 'class="selectionne gauche"'; 
    }
}
if($_GET['partir_ub']<=1 AND $_GET['partir_ub']>=0){
    if($_GET['partir_ub'] == 0){
        $partir_ub = 0;
        $txt_title_destination = "allant à l'UB";
        $text_destination = " à l'UB de ";
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_dest = 'partir_ub=1';
        $text_selection = " Partant de l'UB";
        $selection_aller = 'class="selectionne gauche"';
        $selection_partir = '';
    }
    else{
        $partir_ub = 1;
        $txt_title_destination = "partant de l'UB";
        $text_destination = " de l'UB à ";
        // UTILE POUR LE BOUTON DE SWITCH
        $switch_dest = 'partir_ub=0';
        $text_selection = " Allant à l'UB";
        $selection_aller = '';
        $selection_partir = 'class="selectionne droite"';
          
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
            date_format(datetime_trajet, '%H:%i') as hour, nom, prenom, is_driver 
            FROM trajet INNER JOIN users ON users.id = trajet.id_user 
            INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
            WHERE participe.id_user = ? AND participe.annulation_passager = 0 AND trajet.partir_ub = ? AND trajet.datetime_trajet > ? ORDER BY datetime_trajet ASC LIMIT 50;");
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
            date_format(datetime_trajet, '%H:%i') as hour, nom, prenom, is_driver 
            FROM trajet INNER JOIN users ON users.id = trajet.id_user 
            INNER JOIN participe ON trajet.id_trajet = participe.id_trajet 
            WHERE participe.id_user = ? AND participe.annulation_passager = 0 AND trajet.partir_ub = ? AND trajet.datetime_trajet < ? ORDER BY datetime_trajet ASC LIMIT 50;");
            $trajet->execute(array($_SESSION['id'], $partir_ub, $date_now));
        }

    }
    ?>
    <div id="bandeau">
    <h1>Liste de tout vos trajets</h1>
    <p> Retrouver tous vos trajet en changeant les filtres. Retrouvez par exemple vos trajets en tant que passager 
    (filtre "passager") partant de l'université de Bourgogne (filtre "Partant de l'UB") 
    qui sont prévus dans le futur (filtre "à venir").
    </p>
    <p>
    Selon les filtres selectionnés, vous pouvez vous désincrire d'un trajet à venir et vous pouvez supprimer un trajet à venir dont vous êtes le conducteur.
    </p>
    </div>
    
    <?php
    echo // les liens boutons permettant de switcher entre partir_ub, trajets effectués/à venir et la destination ub ou non
    '<div id="corps">
    <div id="page">
        <h1>Mes trajets '. $txt_title_type_trajet.'</h1>
        <div class="flexLigne">
            <div class="switch">
                <a '.$selection_driver .'href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&incoming='.$_GET['incoming'] . '&driver=1">Conducteur</a>
                <a '.$selection_passager .'href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&incoming='.$_GET['incoming'] . '&driver=0">Passager</a>
            </div>
            <div class="switch">
                <a '.$selection_aller .'href="trajet.php?partir_ub=0&incoming='.$_GET['incoming'] . '&driver='.$_GET['driver'].'">Allant à l\'UB</a>
                <a '.$selection_partir .'href="trajet.php?partir_ub=1&incoming='.$_GET['incoming'] . '&driver='.$_GET['driver'].'">Partant de l\'UB</a>
            </div>
            <div class="switch">
                <a '.$selection_avenir .'href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&incoming=1&driver='.$_GET['driver'].'">A venir</a>
                <a '.$selection_effectue .'href="trajet.php?partir_ub='. $_GET['partir_ub'] . '&incoming=0&driver='.$_GET['driver'].'">Effectués</a>
            </div>
        </div>';
    
    echo // balises de titre de liste des trajets à afficher
    '<div class="espace"></div><h1>Trajets'. $txt_title_dated . $txt_title_destination.'</h1></br>';

    echo 
    '<div id="resultats">';
    /*if ($_SESSION['is_driver'] == 0 AND $_GET['driver'] == 1){
        $non_conducteur = '<div class="error">Vous n\'êtes pas conducteur</div>';
        //$classTrajet = 'normal-trajet';
    }
    echo $non_conducteur;*/

    foreach($trajet as $row){
        $classTrajet = 'normal-trajet';
        $status_trajet = '';
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
            $div_conducteur = '<div> Conducteur : <a class="profil" href="profil.php?id=' . $id_driver_row.'">'. $driver_result['prenom'] . ' ' . $driver_result['nom'] . '</a></div>';       
        }
        else{
            $div_conducteur = '';
        }

        if ($row['statut_trajet'] == 1){
            $status_trajet = '<div class="error">Ce trajet à été annulé</div>';
            $classTrajet = 'normal-trajet';
        }
        
        echo '<div class="flexColonne '.$classTrajet.'">';
        echo $status_trajet;
        
        echo '<h2>'.$row['date'] . ' à ' . $heure . 'h' . $minute . $text_destination . $nom_ville['ville_nom_reel'] . '</h2>
        <div class="infoTrajet">' . $div_conducteur;

        //requete pour afficher les passagers du trajet
        $trajet_passager = $bdd->prepare("SELECT id, nom, prenom, trajet.id_trajet, trajet.id_ville, participe.is_accepted FROM users 
        INNER JOIN participe ON users.id=participe.id_user 
        INNER JOIN trajet ON participe.id_trajet=trajet.id_trajet
        WHERE trajet.partir_ub = 1 AND trajet.id_trajet=? AND participe.annulation_passager = 0;");
        $trajet_passager->execute(array($row['id_trajet']));
        $passager_row = $trajet_passager->rowCount();

        // si il y a au moins 1 passager
        if($passager_row > 0){
            echo 
                '<div class="flexColonne">
                    <div>Passagers inscrits :</div>      
                        <table>';
            foreach($trajet_passager as $row2){
                if ($row2['is_accepted'] == 1){
                echo    
                    '<tr>
                        <td><a class="profil" href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a></td>
                        <td class="accepte">(Accepté)</td>
                    </tr>';
                            
                }
                else{
                    echo 
                        '<tr>
                            <td><a class="profil" href="profil.php?id=' . $row2['id'].'">'. $row2['prenom'] . ' ' . $row2['nom'] . '</a></td>
                            <td class="attenteAccepte">(En attente d\'acceptation)</td>
                        </tr>';
                    
                }           
            }
            echo '
                    </table>
                </div>';
        }
        // si il n'y aucun passager
        else{
            echo 
                '<div class="passager">Aucun passager inscrit à ce trajet</div>';
        }

        echo '</div></br>'; //div qui ferme la div "flexColonne"
        // si le trajet n'a pas été annulé
        if ($row['statut_trajet'] == 0 AND $_GET['incoming'] == 1){
            // dans le cas "en tant que CONDUCTEUR" => Bouton de suppression du trajet
            if($_GET['driver'] == 1 AND $_SESSION['is_driver'] == 1){
                echo '<div class="bouton"><a class="TexteBouton" href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=delete"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
              </svg> Supprimer</a></div>';
            }
            // dans le cas "en tant que PASSAGER" => Bouton de désinscription
            else{
                echo '<div class="bouton desinscrire"><a class="TexteBouton" href="inscription_trajet.php?id_trajet='.$row['id_trajet'] . '&action=desinscription">Se désinscrire de ce trajet</a></div>';        
            }
            
        }
        //echo '</div></br>';
    echo '</div></br>'; // div qui ferme la div de classe "classTrajet" juste avant le h2 Trajet du ...  
    }
}
else{
    header('location: index.php');
}
echo '</div></div>'
?>

<link rel="stylesheet" href="css/trajet.css">
<link rel="stylesheet" href="css/main.css">
</div>
<?php
include 'footer.php';
?>