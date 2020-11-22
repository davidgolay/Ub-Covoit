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
        $vehicule = '<a href="my_vehicule.php">Mon vehicule</a>'; 
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
                <div>
                    <table>
                        <tr>
                            <td>Prenom :</td>
                            <td><?php echo $userinfo['prenom'];?></td>
                        </tr>
                        <tr>
                            <td>Nom :</td>
                            <td><?php echo $userinfo['nom'];?></td>
                        </tr>
                        <tr>
                            <td>Email étudiant :</td>
                            <td><?php echo $userinfo['email'];?></td>
                        </tr>
                        <tr>
                            <td>conducteur :</td>
                            <td><?php echo $user_conducteur?></td>
                        </tr>
                        <tr>    
                            <td>biographie :</td>
                            <td><?php echo $userinfo['bio'];?></td>
                        </tr>
                    </table>
                    
                    <?php 
                    if($userinfo['is_driver'] == 1) 
                    {
                        echo 'Accès';
                        echo $vehicule;
                    }
                    else 
                    {
                        echo '';
                    }
                    ?> 
                </div>
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
