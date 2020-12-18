<?php
include 'config.php';
$fmt_mail1 = '@etu.u-bourgogne.fr';
$fmt_mail2 = '@iut-dijon.u-bourgogne.fr';

// Function to check the string is ends  
// with given substring or not 
function endsWith($string, $endString) 
{ 
    $len = strlen($endString); 
    if ($len == 0) { 
        return true; 
    } 
    return (substr($string, -$len) === $endString); 
} 

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
        if(preg_match("#[0][6][- \.?]?([0-9][0-9][- \.?]?){4}$#", $tel))
        {   
            if(filter_var($email, FILTER_VALIDATE_EMAIL) AND (endsWith($email, $fmt_mail1) OR endsWith($email, $fmt_mail2)))
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
                        //$erreur ="Votre compte a bien été créé!";
                        header('location: login.php?email='.$email);
                    }
                    else
                    {
                        $erreur = "Vos mots de passe sont différents";
                    }
                }
                else
                {
                    $erreur ="Cet email est déjà utilisé";
                }
            }
            else
            {
                $erreur = "Votre email n'a pas été reconnu";
            }
        }
        else
        {
            $erreur = "Entrez un numéro de téléphone correct";    
        }
    }
    else
    {
        $erreur = "Tout les champs doivent être complétés";
    }
}
?>

<style>
<?php include 'css/register.css'; ?>
</style>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <div class="flexColonne">
        <div class="animBasHaut"></div>
        <fieldset>
            <form action="" method="post">
                <h2>Inscription</h2>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <p class="label">Nom</p>  
                        <input class="center-right-left" type="text" name="nom" value="<?php if(isset($nom)) {echo $nom; }?>" /> 
                    </div>
                    <div class="flexColonne">
                        <p class="label">Prénom</p>
                        <input  class="center-right-left" type="text" name="prenom" value="<?php if(isset($prenom)) {echo $prenom; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <p class="label">Date de Naissance</p>  
                        <input  class="center-right-left" type="date" name="dob" value="<?php if(isset($dob)) {echo $dob; }?>" /> 
                    </div>
                    <div class="flexColonne">
                        <p class="label">Téléphone</p>
                        <input  class="center-right-left" type="text" name="tel" value="<?php if(isset($tel)) {echo $tel; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <p class="label">Adresse email UB</p>  
                        <input  class="center-right-left" type="text" name="email" value="<?php if(isset($email)) {echo $email; }?>" /> 
                    </div>
                    <div class="flexColonne">
                        <p class="label">Adresse email récupération</p>
                        <input  class="center-right-left" type="text" name="email_recup" value="<?php if(isset($email_recup)) {echo $email_recup; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <p class="label">Mot de passe</p>  
                        <input  class="center-right-left" type="password" name="password" /> 
                    </div>
                    <div class="flexColonne">
                        <p class="label">Confirmation mot de passe</p>
                        <input  class="center-right-left" type="password" name="password_confirm" />
                    </div>    
                </div>
                <p  class="label">Etes-vous conducteur ?
                <input type="checkbox" name="is_driver" value="1"/>
                </p>
                <p  class="label"> Accepter les conditions générales d'utilisation
                <input type="checkbox" name="accepteCondition" value="1"/>
                </p>
                <p>
                <a href="conditions.php">Conditions générales d'utilisation</a>
                </p>
                <p>
                <a href="politique.php">politique de confidentialité</a>
                </p>
                <?php
                    if(isset($erreur))
                    {
                        echo '<div class="error">'. $erreur . '</div>';
                    }
                ?>
                <p><input type="submit" name="register" value="S'inscrire"/></p>
                <p class="label">Déjà un compte ?</p>
                <p><br/> <a class="bouton" href="login.php">Se connecter</a></p>
            </form>
        </fieldset>
    </div>

</body>
</html>
<?php
include 'footer.php';
?>