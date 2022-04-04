<?php
$lang = $this->Session->read('sess_langauge');

$mainresult = $this->requestAction(array('controller' => 'Masters', 'action' => 'language_main_menu'));
$mainmenuid = "";

$cntcnt = $this->requestAction(array('controller' => 'Masters', 'action' => 'select_language_count'));
$configcnt = $this->requestAction(array('controller' => 'Masters', 'action' => 'config_language_count'));


?>

<div class="btn-group btn-group-justified ">


    <?php
    foreach ($mainresult as $menu) {
        // pr($menu);

        if ($cntcnt != '' && $menu['LanguageMainmenu']['action'] == 'select_language') {
            ?>
            <a class="btn btn-success bg-green" href="<?php echo $this->webroot; ?><?php echo $menu['LanguageMainmenu']['controller'] . "/" . $menu['LanguageMainmenu']['action']; ?>" ><?php echo $menu['LanguageMainmenu']['language_mainmenu_desc_' . $lang]; ?></a>

            <?php
            } else if ($configcnt >1 && $menu['LanguageMainmenu']['action'] == 'config_language') {
            ?>
            <a class="btn btn-success bg-green" href="<?php echo $this->webroot; ?><?php echo $menu['LanguageMainmenu']['controller'] . "/" . $menu['LanguageMainmenu']['action']; ?>" ><?php echo $menu['LanguageMainmenu']['language_mainmenu_desc_' . $lang]; ?></a>

            <?php
            }else if ($this->params['action'] == $menu['LanguageMainmenu']['action'] || $mainmenuid == $menu['LanguageMainmenu']['language_mainmenu_id']) {
                ?> 
                <a class="active btn btn-primary bg-maroon" href="<?php echo $this->webroot; ?><?php echo $menu['LanguageMainmenu']['controller'] . "/" . $menu['LanguageMainmenu']['action']; ?>" ><?php echo $menu['LanguageMainmenu']['language_mainmenu_desc_' . $lang]; ?></a>            
            <?php } else { ?>              
                <a class="btn btn-primary" href="<?php echo $this->webroot; ?><?php echo $menu['LanguageMainmenu']['controller'] . "/" . $menu['LanguageMainmenu']['action']; ?>" ><?php echo $menu['LanguageMainmenu']['language_mainmenu_desc_' . $lang]; ?></a>
                <?php
            } 
        }  
     
    ?>  


</div>