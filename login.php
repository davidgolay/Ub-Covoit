<?php
session_start();
include 'config.php';?>

<head>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/main.css">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/favicon.png">
	<title>uB'Covoit</title>	
</head>


<?php
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
            $_SESSION['logged_in'] = 1;
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
    <div class="flexColonne">
        <div>
        <img id="logoUB" src="img/UB.png" alt="logo Université Bourgogne">
        </div>
        <div class="animBasHaut"></div>
        <fieldset>
                <h2>Connexion</h2>
                <form action="login.php" method="post">
                    <div class="flexColonne">
                        <div><input class="center-right-left" type="email" name="email_log" placeholder="Email etudiant" value="<?php if(isset($email_log)) {echo $email_log; }?>"/></div>
                        <div><input class="center-right-left" type="password" name="password_log" placeholder="Mot de passe"/></div>
                        <?php if(isset($erreur)){
                            echo '<div class="error">'. $erreur . '</div>';
                        }?>
                        <div><input class="bouton" type="submit" name="login" value="Se connecter"/></div>
                        <div>Pas encore de compte ?</p></div>
                        <a class="bouton" href="register.php">S'inscrire</a>
                </form>
            </div>
        </fieldset>  
    </div>  
</body>
</html>