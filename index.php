<?php
session_start();
include 'header.php';
?>


<div><p><br/></p></div>
<fieldset>
    <div class="animBasHaut"></div>
    <div id="flexColonne">
        <div id="flexLigne">
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=0"> Aller à l'UB </a></div>
            <div class="animBasHautMobile"></div>
            <div class="centrer"><a class="bouton" href="searchTrajet.php?partir_ub=1"> Partir de l'UB </a></div>
        </div>
        <div class="animBasHaut"></div>
        <div id="flexLigne">
            <div><a class="levier" href="createTrajet.php?partir_ub=1"> Proposer un trajet </a></div>
        </div>
    </div>
</fieldset>
<style>
<?php include 'css/index.css'; ?>
</style>

<?php
include 'footer.php';
?>