<?php
session_start();
include 'header.php';
?>

<div>
<div><a href="searchTrajet.php?partir_ub=0"> Aller à l'UB </a></div>
<div><a href="searchTrajet.php?partir_ub=1"> Partir de l'UB </a></div>
</div>

<div>
<a href="createTrajet.php?partir_ub=1"> Proposer un trajet </a>
</div>


<?php
include 'footer.php';
?>