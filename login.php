<style type="text/css">
<?php include 'css/login.css'; ?>
</style>
<?php
session_start();
include 'header.php';
include 'config.php';

if(isset($_POST['login']))
{
    $email_log = htmlspecialchars($_POST['email_log']);
    $password_log = sha1($_POST['password_log']);
    

    if(!empty($email_log) AND !empty($password_log))
    {
        /* echo "email". $email_log; echo "password".$password_log;*/
        $requser = $bdd->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $requser->execute(array($email_log, $password_log));
        $userexist = $requser->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['prenom'] = $userinfo['prenom'];
            $_SESSION['email'] = $userinfo['email'];
            $_SESSION['is_driver'] = $userinfo['is_driver'];
            //header("location: profil.php?id=".$_SESSION['id']);
            header("location: index.php");
        }
        else
        {
            $erreur = "Mauvais email ou mot de passe";
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés";
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
<form action="login.php" method="post">
    <h2 class="bordsRonds">Connexion</h2>
    <p><input type="email" name="email_log" placeholder="Email etudiant" value="<?php if(isset($email_log)) {echo $email_log; }?>"/></p>
    <p><input type="password" name="password_log" placeholder="Mot de passe"/></p>
    <?php if(isset($erreur)){echo '<font color="red">'. $erreur;};?>
    <p><input type="submit" name="login" value="Se connecter"/>
    <a href="register.php">Pas encore de compte ?</a>
    </p>
</form>   
</body>
</html>