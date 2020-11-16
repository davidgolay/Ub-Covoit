<?php
include 'config.php';

if(isset($_POST['register']))
{
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $dob = htmlspecialchars($_POST['dob']);
    $tel = htmlspecialchars($_POST['tel']);
    $email = htmlspecialchars($_POST['email']);
    $email_recup = htmlspecialchars($_POST['email_recup']);
    $password = sha1($_POST['password']);
    $password_2 = sha1($_POST['password_confirm']);

    if(empty($_POST["is_driver"])) {
        $is_driver = 0;
    } else{
        $is_driver = $_POST["is_driver"];
    } 



    if(!empty($_POST['email']) AND !empty($_POST['email_recup']) AND !empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['password']))
    {   
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $reqmail = $bdd->prepare("SELECT * FROM users WHERE email=?");
            $reqmail->execute(array($email));
            $mailexist = $reqmail->rowCount();
            if($mailexist == 0) 
            {
                if($password == $password_2)
                {   
                    
                    $insertUser = $bdd->prepare("INSERT INTO users(nom, prenom, email, email_recup, tel, dob, password, is_driver) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertUser->execute(array($nom,$prenom,$email,$email_recup,$tel,$dob,$password,$is_driver));
                    $erreur ="Votre compte a bien été créé!";
                }
                else
                {
                    $erreur = "Vos mots de passe sont différents!";
                }
            }
            else
            {
                $erreur ="Ce mail est déjà utilisé";
            }
        }
        else
        {
            $erreur = "ceci n'est pas une adresse mail!";
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés!";
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
<form action="" method="post">
    <h2>Inscription</h2>
    <p><input type="text" name="nom" placeholder="Nom" value="<?php if(isset($nom)) {echo $nom; }?>" /></p>
    <p><input type="text" name="prenom" placeholder="Prenom" value="<?php if(isset($prenom)) {echo $prenom; }?>" /></p>
    <p><input type="date" name="dob" placeholder="Date de naissance" value="<?php if(isset($dob)) {echo $dob; }?>" /></p>
    <p><input type="text" name="tel" placeholder="Téléphone" value="<?php if(isset($tel)) {echo $tel; }?>" /></p>
    <p><input type="text" name="email" placeholder="Email etudiant" value="<?php if(isset($email)) {echo $email; }?>" /></p>
    <p><input type="text" name="email_recup" placeholder="Email recup" value="<?php if(isset($email_recup)) {echo $email_recup; }?>" /></p>
    <p><input type="password" name="password" placeholder="Mot de passe"/></p>
    <p><input type="password" name="password_confirm" placeholder="Confirmer mot de passe"/></p>
    <p><label>Etes-vous conducteur ?</label>
    <input type="checkbox" name="is_driver" value="1"/>
    </p>
    <?php
        if(isset($erreur))
        {
            echo '<font color="red">'. $erreur;
        };
    ?>
    <p><input type="submit" name="register" value="S'inscire"/>
    <a href="login.php">Déja un compte ?</a>
    </p>
</form>

</body>
</html>