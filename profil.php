<?php
session_start();
include 'config.php';

if(isset($_SESSION['id']))
{
    $selectId = intval($_SESSION['id']); //conversion en nombre pour sécuriser
    $requser = $bdd->prepare('SELECT * FROM users WHERE id = ?');
    $requser->execute(array($selectId));
    $userinfo = $requser->fetch();
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
            <?php
            if($userinfo['id'] == $_SESSION['id'])
            {
            ?>
            <p><a href="editprofil.php">Modifier mon profil</a></p>
            <p><a href="logout.php">Se déconnecter</a></p>
            <?php
            }
            ?>        
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
?>
