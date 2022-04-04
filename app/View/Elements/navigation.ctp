<?php
//echo 'sdfsdfsdfsdf';exit;
$lang = $this->Session->read("Config.language");

if (is_null($lang)) {
    $lang = 'en';
}
$currentcontroller = $this->request->params['controller'];
$currentaction = $this->request->params['action'];


if ($this->Session->check('Auth.User')) {
    ?>

    <?php
   // pr($menus);
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
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' ><i class='fa fa-home text-aqua'></i><span>" . 'Home' . "</span></a>"; ?></li>

        <?php } else { ?>
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' ><i class='fa fa-home text-aqua'></i><span>" . 'Home' . "</span></a>"; ?></li>
        <?php } ?>

        <?php 
      //  print_r($menus);//exit;
        foreach ($menus as $menu) :
            $submenuflag = 0;
            foreach ($submenus as $submenu) :
                if ($submenu['SubMenu']['main_menu_id'] == $menu['Menu']['id']) :
                    $submenuflag = 1;
                endif;
            endforeach;

            if ($submenuflag == 0) {
                ?>
                <li><a href="<?php echo $this->webroot . $menu['Menu']['controller'] . "/" . $menu['Menu']['action']; ?>">
                        <i class="fa fa-link text-aqua"></i> <span>
                            <?php 
                            //echo $menu['Menu']['name_' . $lang];
                            if($lang=='ll')
                            {
                                    $menu_ll_activation_flag=$menu['Menu']['menu_ll_activation_flag'];
                                    if($menu_ll_activation_flag=='Y'){
                                            echo $menu['Menu']['name_' . $lang];
                                    }
                                    else{
                                            echo $menu['Menu']['name_en'];
                                    }
                            }
                            else{
                                    echo $menu['Menu']['name_' . $lang];
                            }
                            ?></span></a></li>
                <?php
            } else {

                $activeclass1 = '';
                $activeclass2 = '';
                $activeclass3 = '';
                foreach ($submenus as $submenu) :
                    if (trim($submenu['SubMenu']['controller'] == trim($currentcontroller)) && trim($submenu['SubMenu']['action']) == trim($currentaction) && $menu['Menu']['id'] == $submenu['SubMenu']['main_menu_id']) {
                        $activeclass1 = 'active';
                        $activeclass2 = 'menu-open';
                        $activeclass3 = 'display: block';
                    } else {
                        foreach ($Subsubmenus as $Subsubmenu) :
                            if (trim($Subsubmenu['Subsubmenu']['controller'] == trim($currentcontroller)) && trim($Subsubmenu['Subsubmenu']['action']) == trim($currentaction) && $menu['Menu']['id'] == $submenu['SubMenu']['main_menu_id'] && $Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) {
                                $activeclass1 = 'active';
                                $activeclass2 = 'menu-open';
                                $activeclass3 = 'display: block';
                            }
                        endforeach;
                    }
                endforeach;
                ?>
                <li class="treeview <?php echo $activeclass1; ?>">
                    <a href="<?php echo $this->webroot . $menu['Menu']['controller'] . "/" . $menu['Menu']['action']; ?>">
                        <i class="fa fa-th-large text-aqua"></i> <span>
                            <?php
                            //echo $menu['Menu']['name_' . $lang];
                             if($lang=='ll')
                            {
                                    $menu_ll_activation_flag=$menu['Menu']['menu_ll_activation_flag'];
                                    if($menu_ll_activation_flag=='Y'){
                                            echo $menu['Menu']['name_' . $lang];
                                    }
                                    else{
                                            echo $menu['Menu']['name_en'];
                                    }
                            }
                            else{
                                    echo $menu['Menu']['name_' . $lang];
                            }
                            
                            ?></span>
            <!--            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>-->
                    </a> 

                    <ul class="treeview-menu <?php echo $activeclass2 ?>">
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
                                    <li><a href="<?php echo $this->webroot . $submenu['SubMenu']['controller'] . "/" . $submenu['SubMenu']['action']; ?>"><i class="fa fa-link text-red"></i>
                                 <?php //echo $submenu['SubMenu']['name_' . $lang];
                                 
                                 if($lang=='ll')
                                {
                                    $submenu_ll_activation_flag=$submenu['SubMenu']['submenu_ll_activation_flag'];
                                    if($submenu_ll_activation_flag=='Y'){
                                        echo $submenu['SubMenu']['name_' . $lang];
                                    }
                                    else{
                                        echo $submenu['SubMenu']['name_en'];
                                    }
                                }
                                else {
                                     echo $submenu['SubMenu']['name_' . $lang];
                                }
                                 ?></a></li>
                                <?php
                                } else {


                                    $activeclass11 = '';
                                    $activeclass22 = '';
                                    $activeclass33 = '';

                                    foreach ($Subsubmenus as $Subsubmenu) :
                                        if (trim($Subsubmenu['Subsubmenu']['controller'] == trim($currentcontroller)) && trim($Subsubmenu['Subsubmenu']['action']) == trim($currentaction) && $Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) {
                                            $activeclass11 = 'active';
                                            $activeclass22 = 'menu-open';
                                            $activeclass33 = 'display: block';
                                        }
                                    endforeach;
                                    ?> 
                                    <li class="treeview  <?php echo $activeclass3 . " " . $activeclass11; ?>"><a href="<?php echo $this->webroot . $submenu['SubMenu']['controller'] . "/" . $submenu['SubMenu']['action']; ?>"><i class="fa fa-th text-red"></i>
                                     <?php 
                                     //echo $submenu['SubMenu']['name_' . $lang];
                                     if($lang=='ll')
					{
                                            $submenu_ll_activation_flag=$submenu['SubMenu']['submenu_ll_activation_flag'];
					    if($submenu_ll_activation_flag=='Y'){
                                                echo $submenu['SubMenu']['name_' . $lang];
                                            }
                                            else{
                                                echo $submenu['SubMenu']['name_en'];
                                            }
                                        }
                                        else {
                                             echo $submenu['SubMenu']['name_' . $lang];
                                        }
                                     ?>
                          <!--              <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                          </span>-->
                                        </a>
                                        <ul class="treeview-menu">
                                            <?php
                                            foreach ($Subsubmenus as $Subsubmenu) :
                                                if ($Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) :
                                                    ?>
                                                    <li><a href="<?php echo $this->webroot . $Subsubmenu['Subsubmenu']['controller'] . "/" . $Subsubmenu['Subsubmenu']['action']; ?>"><i class="fa fa-link text-yellow"></i>
                                                 <?php //echo $Subsubmenu['Subsubmenu']['name_' . $lang];
                                                 if($lang=='ll')
                                                {
                                                    $subsubmenu_ll_activation_flag=$Subsubmenu['Subsubmenu']['subsubmenu_ll_activation_flag'];
                                                    if($subsubmenu_ll_activation_flag=='Y'){
                                                        echo $Subsubmenu['Subsubmenu']['name_' . $lang];
                                                    }
                                                    else{
                                                        echo $Subsubmenu['Subsubmenu']['name_en'];
                                                    }
                                                }
                                                else {
                                                     echo $Subsubmenu['Subsubmenu']['name_' . $lang];
                                                }
                                                 ?></a></li>
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
    
    //if (isset($statename) && (!empty($statename))) {
    ?>

    <ul class="sidebar-menu tree">
        <?php ?>
        <li ><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomenote' . "'><i class='fa fa-home text-aqua'></i><span>" . 'Home' . "</span></a>"; ?></li>
        
        <?php 
        if (isset($statename) && (!empty($statename))) {
            if ($statename[0][0]['state_id'] == 27): ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration_mh' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
        <?php elseif ($statename[0][0]['state_id'] == 31): ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration_ga' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
        <?php else: ?>
            <li id="citizenloginlnk"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration' . "'><i class='fa fa-link text-aqua'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>
            <?php endif ?>
        <li><?php //echo "<a href='" . $this->webroot . 'Demo' . "/" . 'index' . "'><i class='fa fa-link text-aqua'></i><span>" . 'index' . "</span></a>";    ?></li>
        <?php 
        }
        ?>
    
    </ul>



<?php
    //}

} ?>

