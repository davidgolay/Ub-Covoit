<?php
session_start();
include 'config.php';
if(isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM users WHERE id=?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['edit_profil']) AND !empty($_POST['new_nom']) AND $_POST['new_nom'] != $user['nom']);
    {
        echo "lol"."\n";
        $new_nom = htmlspecialchars($_POST['new_nom']);
        echo "leeeel"."\n";
        echo $new_nom;
        /*
        $insertnom = $bdd->prepare("UPDATE users SET nom = ? AND id = ?");
        $insertnom->execute(array($new_nom, $_SESSION['nom']));
        */
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
            <h2>Profil de mon profil</h2><br/>
                <div>
                    <form action="" method="post">
                        <label>Nom</label>
                        <input type="text" name="new_nom" placeholder="Nom" value="<?php echo $user['nom'];?>"/></br>
                        <label>Prénom</label>
                        <input type="text" name="new_prenom" placeholder="Prenom" value="<?php echo $user['prenom'];?>"/></br>
                        <label>Adresse mail étudiante</label>
                        <input type="email" name="new_email" placeholder="Email" value="<?php echo $user['email'];?>"/></br>
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" placeholder="Mot de passe"/></br>
                        <label>Confirmer nouveau mot de passe</label>
                        <input type="password" name="new_password_confirm" placeholder="Confirmer mot de passe"/></br>
                        <input type="submit" name="edit_profil" value="Enregistrer les modifications"/></br>
                    </form>
                </div>

            
                
        </div>  
       
    <?php
    if(isset($erreur))
    {
        echo '<font color="red">'. $erreur;   
    };
    ?>
    
    </body>
</html>

<?php
}
else
{
    header("Location: login.php");
}
?>
