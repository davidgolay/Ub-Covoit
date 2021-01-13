<?php
session_start();
include 'config.php';
$fmt_mail1 = '@etu.u-bourgogne.fr';
$fmt_mail2 = '@iut-dijon.u-bourgogne.fr';
$erreur = '';
$erreur2 = '';
$accepteCondition = 0;
$is_driver = 0;?>

<html>
<head>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/main.css">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/favicon.png">
	<title>uB'Covoit</title>	
</head>


<?php
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
/*
    function check_mdp_format($mdp)
    {
	$majuscule = preg_match('@[A-Z]@', $password);
	$minuscule = preg_match('@[a-z]@', $password);
	$chiffre = preg_match('@[0-9]@', $password);
	
	if(!$majuscule || !$minuscule || !$chiffre || strlen($mdp) < 8)
	{
		return false;
	}
	else 
		return true;
    }*/

    if(empty($_POST["is_driver"])) {
        $is_driver = 0;
    } else{
        $is_driver = 1;
    }

    if(empty($_POST["accepteCondition"])) {
        $accepteCondition = 0;
    } else{
        $accepteCondition = 1;
    }

    if(!empty($_POST['email']) AND !empty($_POST['email_recup']) AND !empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['password']))
    {
        $date = date("Y-m-d");;
        list($annee, $mois, $jour) = sscanf($date, "%d-%d-%d"); //%d pour récupérer des entiers mais on peut utiliser & %s pour récupérer comme des chaînes de caractères 
        $aujourdhui = date("Y-m-d"); // on récupère la date d'aujourd'hui
        $diff = date_diff(date_create($dob), date_create($aujourdhui)); //on calcule l'écart entre la date d'aujourdhui et la date de naissance entré par l'utilisateur
        $age = $diff->format('%y'); // l'écart calculé précedement correspond à l'age de l'utilisateur, on recupère l'age au format année

        if (($dob < $date) AND ($age >=18)){ // la date de naissance est inférieure à la date d'aujourd'hui (la fonction diff bugue pour les année dépassant la date actuelle) et l'utilisateur à plus de 18 ans 
        
            if($age <= 64)
            {
                //echo "c'est okai";
                if(preg_match("#[0][6][- \.?]?([0-9][0-9][- \.?]?){4}$#", $tel)){ // verification du format du num de telephone  
                    if(filter_var($email, FILTER_VALIDATE_EMAIL) AND (endsWith($email, $fmt_mail1) OR endsWith($email, $fmt_mail2))){ // verification du format du mail 
                        $reqmail = $bdd->prepare("SELECT * FROM users WHERE email=?");
                        $reqmail->execute(array($email));
                        $mailexist = $reqmail->rowCount();
                        if($mailexist == 0){
                            if ($accepteCondition == 1){
                                if($password == $password_2){
                                    if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $_POST['password']) AND strlen($_POST['password']) >= 8) {                                                                              
                                        $insertUser = $bdd->prepare("INSERT INTO users(nom, prenom, email, email_recup, tel, dob, password, is_driver) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                                        $insertUser->execute(array($nom,$prenom,$email,$email_recup,$tel,$dob,$password,$is_driver));
                                        //$erreur ="Votre compte a bien été créé!";
                                        header('location: login.php?email='.$email);
                                    }
                                    else {
                                        $erreur = 'Mot de passe non conforme. Il doit contenir au moins 8 caractères ';
                                        $erreur2 = 'dont 1 majuscule, 1 minuscule, 1 chiffre et un caractère spécial';
                                    }
                                }
                                else
                                {   
                                    $_POST['password'] = '';
                                    $_POST['password_confirm'] = '';
                                    $erreur = "Vos mots de passe sont différents";
                                }
                            }
                            else{
                                $erreur = "Vous devez accepter les conditions générales d'utilisation'";
                            }
                        }
                        else{
                            $erreur ="Cet email est déjà utilisé";
                        }
                    }
                    else{
                        $erreur = "Votre email n'a pas été reconnu";
                    }
                }
                else{
                    $erreur = "Entrez un numéro de téléphone correct";    
                }
            }
            else{
                $erreur = "vous êtes un peu vieux pour être étudiant à l'Université";
            }
        }
        else{
            $erreur = "Vous n'avez pas 18 ans";
        }
    }
    else{
        $erreur = "Tous les champs doivent être complétés";
    }
}          
?>




<body>
    <div class="flexColonne">
        <div class="animBasHaut"></div>
        <fieldset>
            <form action="" method="post">
                <h2>Inscription</h2>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <div class="label">Nom</div>  
                        <input class="center-right-left" type="text" name="nom" value="<?php if(isset($nom)) {echo $nom; }?>" /> 
                    </div>
                    <div class="flexColonne">
                        <div class="label">Prénom</div>
                        <input  class="center-right-left" type="text" name="prenom" value="<?php if(isset($prenom)) {echo $prenom; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <div class="label">Date de Naissance</div>  
                        <input  class="center-right-left" type="date" name="dob" value="<?php if(isset($dob)) {echo $dob; }?>" /> 
                    </div>
                    <div class="flexColonne">
                        <div class="label">Téléphone</div>
                        <input  class="center-right-left" type="text" name="tel" value="<?php if(isset($tel)) {echo $tel; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <div class="label">Adresse éléctronique universitaire</div>  
                        <input  class="center-right-left" type="text" name="email" value="<?php if(isset($email)) {echo $email; }?>" placeholder="@etu.u-bourgogne.fr" /> 
                    </div>
                    <div class="flexColonne">
                        <div class="label">Adresse éléctronique de récupération</div>
                        <input  class="center-right-left" type="text" name="email_recup" value="<?php if(isset($email_recup)) {echo $email_recup; }?>" />
                    </div>    
                </div>
                <div class="flexLigne"> 
                    <div class="flexColonne"> 
                        <div class="label">Mot de passe</div>  
                        <input  class="center-right-left" type="password" name="password" value="<?php if(isset($_POST['password'])) {echo $_POST['password']; }?>" placeholder="8 caractères minimum" title="8 caractères minimum, au moins 1 majuscule, 1 minuscule 1 chiffre et 1 caractère spécial"/> 
                    </div>
                    <div class="flexColonne">
                        <div class="label">Confirmation mot de passe</div>
                        <input  class="center-right-left" type="password" name="password_confirm" value="<?php if(isset($_POST['password_confirm'])) {echo $_POST['password_confirm']; }?>"/>
                    </div>    
                </div>
                <p  class="label">Etes-vous conducteur ?
                <input type="checkbox" name="is_driver" value="1" <?php if($is_driver == 1) echo 'checked' ?>/>
                </p>
                <div class="flexColonne">
                    <p  class="label"> Accepter les conditions générales d'utilisation
                    <input type="checkbox" name="accepteCondition" value="1" <?php if($accepteCondition == 1) echo 'checked' ?>/>
                    </p>
                    <p>
                    <a class="CGU center-right-left" href="politique.php">politique de confidentialité</a>
                    </p>
                    <p>
                    <a class="CGU center-right-left" href="conditions.php">Conditions générales d'utilisation</a>
                    </p>
                </div>
                <?php
                    if(isset($erreur))
                    {
                        echo '<div class="error">'. $erreur . '</div>';
                        echo '<div class="error">'. $erreur2 . '</div>';
                    }
                ?>
                <div><input class="bouton" type="submit" name="register" value="S'inscrire"/></div>
                <div class="label">Déjà un compte ?</div>
                <div><br/> <a class="bouton" href="login.php">Se connecter</a></div>
            </form>
        </fieldset>
    </div>

</body>
</html>

<?php
include 'footer.php';
?>