<?php
session_start();
include 'config.php';
include 'header.php';


if(isset($_GET['id']) AND $_GET['id'] > 0)
{
    $selectId = intval($_GET['id']); //conversion en nombre pour sécuriser
    $requser = $bdd->prepare('SELECT * FROM users WHERE id = ?');
    $requser->execute(array($selectId));
    $userinfo = $requser->fetch();

    if($userinfo['is_driver'] == 1) 
    {
        $user_conducteur = 'OUI';
    }
    else
    {
        $user_conducteur = 'NON';
    }
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
            <h2>Profil de <?php echo $userinfo['prenom']." ".$userinfo['nom']; ?></h2><br/>
            
            <p>Prenom : <?php echo $userinfo['prenom'];?></p>
            <p>Nom : <?php echo $userinfo['nom'];?></p>
            <p>Email étudiant : <?php echo $userinfo['email'];?></p>
            <p>conducteur : <?php echo $user_conducteur?></p>
            <p>biographie : <?php echo $userinfo['bio'];?></p>
            <?php
            if($userinfo['id'] == $_SESSION['id'])
            {
            ?>
            <p><a href="editprofil.php">Modifier mon profil</a></p>
            <?php
            }
            ?>        
        </div>  
       
    <?php
    if(isset($erreur))
    {
        echo '<div class="error">'. $erreur . '</div>';   
    };
    ?>
    
    </body>
</html>

<?php
include 'footer.php';
?>
