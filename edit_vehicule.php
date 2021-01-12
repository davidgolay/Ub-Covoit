<?php
session_start();
include 'config.php';
include 'header.php';

if($_SESSION['logged_in'] != 1){
    header('location: index.php');
}

if(isset($_SESSION['id']))
{
    $var_session_id = $_SESSION['id'];
    // on recupère les données de l'utilisateur et véhicule à partir de la variable de session id
    $select_id_driver = intval($_SESSION['id']); //conversion en nombre pour sécuriser
    $requser = $bdd->prepare('SELECT nom,prenom,id,id_vehicule,place,marque,model,commentaire FROM vehicule v INNER JOIN users u ON v.id_user=u.id WHERE id_user=?');
    $requser->execute(array($select_id_driver));
    $vehicule_info = $requser->fetch();

    if(isset($_POST['edit_vehicule']) AND !empty($_POST['new_model']) AND $_POST['new_model'] != $vehicule_info['model'])
    {
        
        $new_model = htmlspecialchars($_POST['new_model']);
        $insert_model = $bdd->prepare("UPDATE vehicule SET model = ? WHERE id_user = ?");
        $insert_model->execute(array($new_model, $_SESSION['id']));
        header('location: profil.php?id='. $var_session_id); // on redirige vers le véhicule
        
    }

    if(isset($_POST['edit_vehicule']) AND !empty($_POST['new_marque']) AND $_POST['new_marque'] != $vehicule_info['marque'])
    {
        
        $new_marque = htmlspecialchars($_POST['new_marque']);
        $insert_marque = $bdd->prepare("UPDATE vehicule SET marque = ? WHERE id_user = ?");
        $insert_marque->execute(array($new_marque, $_SESSION['id']));
        header('location: profil.php?id='. $var_session_id); // on redirige vers le véhicule
        
    }

    if(isset($_POST['edit_vehicule']) AND !empty($_POST['new_place']) AND $_POST['new_place'] != $vehicule_info['place'])
    {
        $new_place = intval($_POST['new_place']);
        $insert_place = $bdd->prepare("UPDATE vehicule SET place = ? WHERE id_user = ?");
        $insert_place->execute(array($new_place, $_SESSION['id']));
        header('location: profil.php?id='. $var_session_id); 
    }

    if(isset($_POST['edit_vehicule']) AND !empty($_POST['new_commentaire']) AND $_POST['new_commentaire'] != $vehicule_info['commentaire'])
    {
        $new_com = htmlspecialchars($_POST['new_commentaire']);
        $insert_com = $bdd->prepare("UPDATE vehicule SET commentaire = ? WHERE id_user = ?");
        $insert_com->execute(array($new_com, $_SESSION['id']));
        header('location: profil.php?id='. $var_session_id); 
    }


    if(isset($_POST['edit_vehicule']))
    {
        header('location: profil.php?id='. $var_session_id); 
    }
}
else
{
    header("Location: index.php");
}



?>

<link rel="stylesheet" href="css/edit.css">
<link rel="stylesheet" href="css/main.css">

<div id="page">
    <h2>Mofication de mon véhicule</h2><br/>
        <div>
            <form action="" method="post">
                <table class="flexColonne">
                    <tr>
                        <td><label>Model</label></td>
                        <td><input class="center-right-left" type="text" name="new_model" placeholder="Model" value="<?php echo $vehicule_info['model'];?>"/></td>
                    </tr>
                    <tr>
                        <td><label>Marque</label></td>
                        <td><input class="center-right-left" type="text" name="new_marque" placeholder="Marque" value="<?php echo $vehicule_info['marque'];?>"/></td>
                    </tr>
                    <tr>
                        <td><label>Place</label></td>
                        <td><input class="center-right-left" type="text" name="new_place" placeholder="Place(s)" value="<?php echo $vehicule_info['place'];?>"/></td>
                    </tr>
                    <tr>
                        <td><label>Commentaire</label></td>
                        <td><input class="center-right-left" type="text" name="new_commentaire" placeholder="Commentaire" value="<?php echo $vehicule_info['commentaire'];?>"/></td>
                    </tr>                        
                </table>
                <input class="bouton" type="submit" name="edit_vehicule" value="Enregistrer"/></td>
            </form>
        </div>      
</div>  
       
<?php
if(isset($erreur))
{
    echo '<div class="error">'. $erreur . '</div>';   
}
include 'footer.php';
?>