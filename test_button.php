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
            $str.='<input type="submit" value="'.$v.'" name="btn_'.$k.'" id="btn_'.$k.'"/>';
        }
        return $str;
    }


 //include 'searchTrajet.php';
?>

<div id="buttons_panel">
<?php echo get_buttons(); ?>
</div>