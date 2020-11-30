<?php
session_start();
include 'config.php';
include 'header.php';

if(isset($_POST['add_vehicule']) AND !empty($_POST['marque']) AND !empty($_POST['model']) AND !empty($_POST['place']))
{
    $id_proprietaire = intval($_SESSION['id']); //conversion en nombre pour sécuriser
    $place = intval($_POST['place']);
    $marque = htmlspecialchars($_POST['marque']);
    $model = htmlspecialchars($_POST['model']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    //requete d'insertion du vehicule
    $create_vehicule = $bdd->prepare('INSERT INTO vehicule(id_user, place, marque, model, commentaire) VALUES(?, ?, ?, ?, ?);');
    $create_vehicule->execute(array($id_proprietaire, $place, $marque, $model, $commentaire));
    $vehicule_info = $create_vehicule->fetch();
    header('Location: profil.php?id=' . $_SESSION['id']);

}


?>



<div id="voiture">
    <h2>Ajouter mon véhicule</h2><br/>
        <div>
            <form action="" method="post">
                <table>
                    <tr>
                        <td><label>Marque du véhicule</label></td>
                        <td><input type="text" name="marque" placeholder="Marque" value="<?php if(isset($_POST['marque']))echo $_POST['marque'];?>"/></td>
                    </tr>
                    <tr>
                        <td><label>Modèle du véhicule</label></td>
                        <td><input type="text" name="model" placeholder="Modele" value="<?php if(isset($_POST['model']))echo $_POST['model'];?>"/></td>
                    </tr>
                
                    <tr>
                        <td><label>Nombre de places</label></td>
                        <td><input type="number" name="place" placeholder="Place(s) passagères" value="<?php if(isset($_POST['place']))echo $_POST['place'];?>"/></td>
                    </tr>
                    <tr>
                        <td><label>Commentaire véhicule</label></td>
                        <td><input type="text" name="commentaire" placeholder="Commentaire sur votre véhicule" value="<?php if(isset($_POST['commentaire']))echo $_POST['commentaire'];?>"/></td>
                    </tr>                        
                </table>
                <input type="submit" name="add_vehicule" value="Enregistrer les modifications"/></td>
            </form>
        </div>      
</div>