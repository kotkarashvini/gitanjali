<?php
pr($cntcnt);
?>
<table border="1">
    <tr>
        <td>       
            <?php
            for($i=0;$i<count($cntcnt);$i++){
                $c=$i%2;
                pr($c);
                if($c==0)
                    pr($cntcnt[$i]['LanguageMainmenu']['language_mainmenu_desc_en']);
            }
            ?>
        </td>
        <td>
            <?php
            for($j=0;$j<count($cntcnt);$j++){
                $d=$j%2;
                //pr($d);
                if($d==1)
                    pr($cntcnt[$j]['LanguageMainmenu']['language_mainmenu_desc_en']);
                   
                    
            }
            ?>
        </td>
    </tr>
</table>    