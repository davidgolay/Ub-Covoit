<?php

    function get_buttons()
    {
        $str='';
        $btns=array(
            1=>'Aller à UB',
            2=> 'Partir de UB',
            );
        while(list($k,$v)=each($btns))
        {
            $str.='<td><input type="submit" value="'.$v.'" name="btn_'.$k.'" id="btn_'.$k.'"/></td>';
        }
        return $str;
    }


 //include 'searchTrajet.php';

  // check si le bouton à ete cliqué
    if(isset($_POST['btn_1']))
    {
        $_SESSION['partir_ub'] = 0;
        header("location: searchTrajet.php");        
    }

    if(isset($_POST['btn_2']))
    {
        $_SESSION['partir_ub'] = 1;
        header("location: searchTrajet.php");
    }

    if(isset($_POST['proposer_trajet']))
    {
        header("location: createTrajet.php");
    }


?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <div id="buttons_panel">
        <table>
            <?php echo get_buttons(); ?>
        </table>
    </div>
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <div id="buttons_panel">
        <input type="submit" name="proposer_trajet" value="Proposer un trajet">
    </div>
</form>

