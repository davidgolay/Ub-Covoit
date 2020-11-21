<?php
session_start();
include 'config.php';
include 'header.php';

if(isset($_SESSION['id']))
{
    
    // on recupère les données de l'utilisateur à partir de la variable de session id
    $requser = $bdd->prepare("SELECT * FROM users WHERE id=?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_nom']) AND $_POST['new_nom'] != $user['nom'])
    {
        
        $new_nom = htmlspecialchars($_POST['new_nom']);
        $insertnom = $bdd->prepare("UPDATE users SET nom = ? WHERE id = ?");
        $insertnom->execute(array($new_nom, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
        
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_prenom']) AND $_POST['new_prenom'] != $user['prenom'])
    {
        
        $new_prenom = htmlspecialchars($_POST['new_prenom']);
        $insertprenom = $bdd->prepare("UPDATE users SET prenom = ? WHERE id = ?");
        $insertprenom->execute(array($new_prenom, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
        
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_email']) AND $_POST['new_email'] != $user['email'])
    {
        
        $new_email = htmlspecialchars($_POST['new_email']);
        $insertmail = $bdd->prepare("UPDATE users SET email = ? WHERE id = ?");
        $insertmail->execute(array($new_email, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
        
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_bio']) AND $_POST['new_bio'] != $user['bio'])
    {
        
        $new_bio = htmlspecialchars($_POST['new_bio']);
        $insert_bio = $bdd->prepare("UPDATE users SET bio = ? WHERE id = ?");
        $insert_bio->execute(array($new_bio, $_SESSION['bio']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
    }


}
else
{
    header("Location: login.php");
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mon profil</title>
    </head>
    <body>
        <div>
            <h2>Mofication de mon profil</h2><br/>
                <div>
                    <form action="" method="post">
                        <label>Nom</label>
                        <input type="text" name="new_nom" placeholder="Nom" value="<?php echo $user['nom'];?>"/></br>
                        <label>Prénom</label>
                        <input type="text" name="new_prenom" placeholder="Prenom" value="<?php echo $user['prenom'];?>"/></br>
                        <label>Conducteur</label>
                        <input type="checkbox" name="new_driver" <?php if($user['is_driver'] == 1){echo 'checked';}?> /></br>
                        <label>Adresse mail étudiante</label>
                        <input type="email" name="new_email" placeholder="Email" value="<?php echo $user['email'];?>"/></br>
                        <label>Adresse mail de récupération</label>
                        <input type="email" name="new_email_recup" placeholder="Email de récupération" value="<?php echo $user['email_recup'];?>"/></br>
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" placeholder="Mot de passe"/></br>
                        <label>Confirmer nouveau mot de passe</label>
                        <input type="password" name="new_password_confirm" placeholder="Confirmer mot de passe"/></br>
                        <label>Biographie</label>
                        <input type="text" name="new_bio" value="<?php echo $user['bio'];?>"/></br>
                        <input type="submit" name="edit_profil" value="Enregistrer les modifications"/></br>
                        

                    </form>
                </div>      
        </div>  
       
    <?php
    if(isset($erreur))
    {
        echo '<div class="error">'. $erreur . '</div>';   
    };
    ?>
    
    </body>
</html>


