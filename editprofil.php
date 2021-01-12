<?php
session_start();
include 'config.php';
include 'header.php';

if($_SESSION['logged_in'] != 1){
    header('location: index.php');
}

if(isset($_SESSION['id'])){
    // on recupère les données de l'utilisateur à partir de la variable de session id
    $requser = $bdd->prepare("SELECT * FROM users WHERE id=?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_nom']) AND $_POST['new_nom'] != $user['nom']){   
        $new_nom = htmlspecialchars($_POST['new_nom']);
        $insertnom = $bdd->prepare("UPDATE users SET nom = ? WHERE id = ?");
        $insertnom->execute(array($new_nom, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil  
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_prenom']) AND $_POST['new_prenom'] != $user['prenom']){
        $new_prenom = htmlspecialchars($_POST['new_prenom']);
        $insertprenom = $bdd->prepare("UPDATE users SET prenom = ? WHERE id = ?");
        $insertprenom->execute(array($new_prenom, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_email']) AND $_POST['new_email'] != $user['email']){ 
        $new_email = htmlspecialchars($_POST['new_email']);
        $insertmail = $bdd->prepare("UPDATE users SET email = ? WHERE id = ?");
        $insertmail->execute(array($new_email, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil 
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_bio'])){
        $new_bio = htmlspecialchars($_POST['new_bio']);
        $insert_bio = $bdd->prepare("UPDATE users SET bio = ? WHERE id = ?");
        $insert_bio->execute(array($new_bio, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
    }

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_password']) AND !empty($_POST['new_password_confirm']) AND $_POST['new_password'] == $_POST['new_password_confirm']){
        
        $new_password = sha1($_POST['new_password']);
        $insert_pwd = $bdd->prepare("UPDATE users SET password = ? WHERE id = ?");
        $insert_pwd->execute(array($new_password, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
    }

    if(empty($_POST["new_driver"])){
        $new_driver = 0;
        $_SESSION['is_driver'] = 0;
    } 
    else{
        $new_driver = $_POST["new_driver"];
        $_SESSION['is_driver'] = 1;
    }

    if(isset($_POST['edit_profil'])){
        $insert_driver = $bdd->prepare("UPDATE users SET is_driver = ? WHERE id = ?");
        $insert_driver->execute(array($new_driver, $_SESSION['id']));
        header('location: profil.php?id='. $_SESSION['id']); // on redirige vers le profil
    }
}
else{
    header("Location: index.php");
}

?>
<!--<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mon profil</title>-->
        <link rel="stylesheet" href="css/edit.css">
        <link rel="stylesheet" href="css/main.css">
<!--</head>-->
    <body>
        <div id="page">
            <h2>Mofication de mon profil</h2><br/>
                <div>
                    <form action="" method="post">
                        <table class="flexColonne">
                            <tr>
                                <td><label>Nom</label></td>
                                <td><input class="center-right-left" type="text" name="new_nom" placeholder="Nom" value="<?php echo $user['nom'];?>"/></td>
                            </tr>
                            <tr>
                                <td><label>Prénom</label></td>
                                <td><input class="center-right-left" type="text" name="new_prenom" placeholder="Prenom" value="<?php echo $user['prenom'];?>"/></td>
                            </tr>
                            <tr>
                                <td><label>Adresse email étudiante</label></td>
                                <td><input class="center-right-left" type="email" name="new_email" placeholder="Email" value="<?php echo $user['email'];?>"/></td>
                            </tr>
                            <tr>
                                <td><label>Adresse email de récupération</label></td>
                                <td><input class="center-right-left" type="email" name="new_email_recup" placeholder="Email de récupération" value="<?php echo $user['email_recup'];?>"/></td>
                            </tr>
                            <tr>
                                <td><label>Nouveau mot de passe</label></td>
                                <td><input class="center-right-left" type="password" name="new_password" placeholder="Mot de passe"/></td>
                            </tr>
                            <tr>
                                <td><label>Confirmer mot de passe</label></td>
                                <td><input class="center-right-left" type="password" name="new_password_confirm" placeholder="Confirmer mot de passe"/></td>
                            </tr>
                            <tr>
                                <td><label>Biographie</label></td>
                                <td><input class="center-right-left" type="text" name="new_bio" value="<?php echo $user['bio'];?>" maxlength="255"/></td>
                            </tr>
                            <tr>
                                <td><label>Conducteur</label></td>
                                <td><input class="center-right-left" type="checkbox" name="new_driver" value="1" <?php if($user['is_driver'] == 1){echo 'checked';}?> /></td>
                            </tr>
                        </table>
                        <input class="bouton" type="submit" name="edit_profil" value="Enregistrer"/></td>
                    </form>
                </div>      
        </div>  
       
    <?php
    if(isset($erreur))
    {
        echo '<div class="error">'. $erreur . '</div>';   
    };
    ?>
<!--    
    </body>
</html>
-->


