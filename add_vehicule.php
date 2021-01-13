<?php
session_start();
include 'config.php';
include 'header.php';

if($_SESSION['logged_in'] != 1){
    header('location: index.php');
}

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

<link rel="stylesheet" href="css/edit.css">
<link rel="stylesheet" href="css/main.css">

<div id="corps">
<div id="page">
    <h2>Ajouter mon véhicule</h2><br/>
        <div>
            <form action="" method="post">
                <table class="flexColonne">
                    <tr class="flexColonneMobile">
                        <td><label>Marque du véhicule</label></td>
                        <td><input class="center-right-left" type="text" name="marque" placeholder="Marque" value="<?php if(isset($_POST['marque']))echo $_POST['marque'];?>"/></td>
                    </tr>
                    <tr class="flexColonneMobile">
                        <td><label>Modèle du véhicule</label></td>
                        <td><input class="center-right-left" type="text" name="model" placeholder="Modele" value="<?php if(isset($_POST['model']))echo $_POST['model'];?>"/></td>
                    </tr>
                
                    <tr class="flexColonneMobile">
                        <td><label>Nombre de places</label></td>
                        <td><input class="center-right-left" type="number" name="place" placeholder="Place(s) passagères" value="<?php if(isset($_POST['place']))echo $_POST['place'];?>"/></td>
                    </tr>
                    <tr class="flexColonneMobile">
                        <td><label>Commentaire véhicule</label></td>
                        <td><input class="center-right-left" type="text" name="commentaire" placeholder="Commentaire sur votre véhicule" value="<?php if(isset($_POST['commentaire']))echo $_POST['commentaire'];?>"/></td>
                    </tr>                        
                </table>
                <div >
                <input class="bouton" type="submit" name="add_vehicule" value="Enregistrer"/></td>
                </div>
            </form>
        </div>      
</div>
</div>