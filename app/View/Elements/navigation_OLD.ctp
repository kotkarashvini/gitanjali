<?php
$lang = $this->Session->read("Config.language");

if (is_null($lang)) {
    $lang = 'en';
}
if ($this->Session->check('Auth.User')) {
    ?>

    <?php
    if (!isset($menus) || empty($menus)) :
        $menus = $this->requestAction('/menus/index');

    endif;

    if (!isset($submenus) || empty($submenus)) :
        $submenus = $this->requestAction('/menus/index1');
    endif;

    if (!isset($Subsubmenus) || empty($Subsubmenus)) :
        $Subsubmenus = $this->requestAction('/menus/index2');

    endif;
    ?>

    <ul class="sidebar-menu tree">
        <li class="treeview"><?php
//            $lang = $this->Session->read("sess_langauge");
            if ($lang == 'en') {
                echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' ><i class='fa fa-home text-aqua'></i><span>" . 'Home' . "</span></a>";
                ?>
            </li>
        <?php } else if ($lang == 'll') { ?>
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' >" . 'рдоре?рдЦре?рдп рдкреГрд╖ре?рда' . "</a>"; ?></li>

        <?php } else { ?>
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' >" . 'ркорл?ркЦрл?ркп рккрлГрк╖рл?рка' . "</a>"; ?></li>
        <?php } ?>

        <?php
        foreach ($menus as $menu) :
            $submenuflag = 0;
            foreach ($submenus as $submenu) :
                if ($submenu['SubMenu']['main_menu_id'] == $menu['Menu']['id']) :
                    $submenuflag = 1;
                endif;
            endforeach;

            if ($submenuflag == 0) {
                ?>
                <li><a href="<?php echo $this->webroot . $menu['Menu']['controller'] . "/" . $menu['Menu']['action']; ?>"><i class="fa fa-link text-aqua"></i> <span><?php echo $menu['Menu']['name_' . $lang] ?></span></a></li>
            <?php } else { ?>
                <li class="treeview">
                    <a href="<?php echo $this->webroot . $menu['Menu']['controller'] . "/" . $menu['Menu']['action']; ?>">
                        <i class="fa fa-th-large text-aqua"></i> <span><?php echo $menu['Menu']['name_' . $lang] ?></span>
            <!--            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>-->
                    </a> 
                    <ul class="treeview-menu">
                        <?php
                        foreach ($submenus as $submenu) :
                            if ($submenu['SubMenu']['main_menu_id'] == $menu['Menu']['id']) :

                                $subsubmenuflag = 0;
                                foreach ($Subsubmenus as $Subsubmenu) :
                                    if ($Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) :
                                        $subsubmenuflag = 1;
                                    endif;
                                endforeach;
                                if ($subsubmenuflag == 0) {
                                    ?>
                                    <li><a href="<?php echo $this->webroot . $submenu['SubMenu']['controller'] . "/" . $submenu['SubMenu']['action']; ?>"><i class="fa fa-link text-red"></i> <?php echo $submenu['SubMenu']['name_' . $lang] ?></a></li>
                                <?php } else { ?>
                                    <li class="treeview"><a href="<?php echo $this->webroot . $submenu['SubMenu']['controller'] . "/" . $submenu['SubMenu']['action']; ?>"><i class="fa fa-th text-red"></i> <?php echo $submenu['SubMenu']['name_' . $lang] ?>
                          <!--              <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                          </span>-->
                                        </a>
                                        <ul class="treeview-menu">
                                            <?php
                                            foreach ($Subsubmenus as $Subsubmenu) :
                                                if ($Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) :
                                                    ?>
                                                    <li><a href="<?php echo $this->webroot . $Subsubmenu['Subsubmenu']['controller'] . "/" . $Subsubmenu['Subsubmenu']['action']; ?>"><i class="fa fa-link text-yellow"></i> <?php echo $Subsubmenu['Subsubmenu']['name_' . $lang]; ?></a></li>
                                                    <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </ul>
                                    </li>

                                <?php } ?>

                                <?php
                            endif;
                        endforeach;    //submenu loop
                        ?>
                    </ul>


                </li>  

                <?php
            }
        endforeach; // mainmenu loop
        ?>

    </ul>
<?php } else { ?>


    <?php
    $statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
    ?>

    <ul class="sidebar-menu tree">
        <?php ?>
        <li ><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomenote' . "'><i class='fa fa-home text-aqua'></i><span>" . 'Home' . "</span></a>"; ?></li>
        <?php if ($statename[0][0]['state_id'] == 27): ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration_mh' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
        <?php elseif ($statename[0][0]['state_id'] == 31): ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration_ga' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
        <?php else: ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
            <?php endif ?>
        <!--<li><?php // echo "<a href='" . $this->webroot . 'Demo' . "/" . 'index' . "'><i class='fa fa-link text-aqua'></i><span>" . 'index' . "</span></a>"; ?></li>-->
    </ul>



<?php } ?>

