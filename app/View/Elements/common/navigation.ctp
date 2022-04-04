<?php if ($this->Session->check('Auth.User')) { ?>

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

    <ul class="sidebar-menu">
        <!--<li class="header" style="color:#849da8;">MAIN NAVIGATION</li>-->
        <li class="treeview"><?php
            $lang = $this->Session->read("sess_langauge");
            if ($lang == 'en') {
                echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' ><i class='fa fa-dashboard'></i><span>" . 'Home' . "</span></a>";
                
                ?>
            </li>
        <?php } else if ($lang == 'll') { ?>
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' >" . 'मुख्य पृष्ठ' . "</a>"; ?></li>

        <?php } else { ?>
            <li class="header"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomemodel' . "' >" . 'મુખ્ય પૃષ્ઠ' . "</a>"; ?></li>
        <?php } ?>


        <?php
        foreach ($menus as $menu) :
            ?>
            <li class="treeview">
                <?php echo "<a href='" . $this->webroot . $menu['Menu']['controller'] . "/" . $menu['Menu']['action'] . "' ><i class='fa fa-share'></i>" . $menu['Menu']['name_' . $lang] . " <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span></a>"; ?>

                <?php
                $l2flag = 0;
                foreach ($submenus as $submenu) :
                    if ($submenu['SubMenu']['main_menu_id'] == $menu['Menu']['id']) :

                        if ($l2flag == 0) {
                            echo "<ul class='treeview-menu'>";
                            $l2flag = 1;
                        }
                        ?>
                    <li class="treeview">
                        <?php echo "<a href='" . $this->webroot . $submenu['SubMenu']['controller'] . "/" . $submenu['SubMenu']['action'] . "'><i class='fa fa-circle-o'></i><span>" . $submenu['SubMenu']['name_' . $lang] . "</span></a>"; ?>
                        <?php // Fill Subsub Menu Here ?>


                        <?php
                        $l3flag = 0;
                        foreach ($Subsubmenus as $Subsubmenu) :


                            if ($Subsubmenu['Subsubmenu']['sub_menu_id'] == $submenu['SubMenu']['id']) :
                                if ($l3flag == 0) {
                                    echo "<ul class='treeview-menu'>";
                                    $l3flag = 1;
                                }
                                ?>

                            <li>
                                <?php echo "<a href='" . $this->webroot . $Subsubmenu['Subsubmenu']['controller'] . "/" . $Subsubmenu['Subsubmenu']['action'] . "'><i class='fa fa-circle-o'></i><span>" . $Subsubmenu['Subsubmenu']['name_' . $lang] . "</span></a>"; ?>
                            </li>
                                <?php
                            endif;
                        endforeach;
                        if ($l3flag == 1) {
                            echo "</ul>";
                        }
                        ?>


                </li>
                    <?php
                endif;
            endforeach;
            if ($l2flag == 1) {
                echo "</ul>";
            }
            ?>

        </li>
    <?php endforeach; ?>

    </ul>


<?php } else { ?>


   <ul class="sidebar-menu">
        <?php
        ?>
        <li class="treeview"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomenote' . "'><i class='fa fa-dashboard'></i><span>" . 'Home' . "</span></a>"; ?></li>
        <li><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration' . "'><i class='fa fa-share'></i><span>" . 'Citizen Registration' . "</span></a>"; ?></li>

        <?php //} else {     ?>
        <li><?php //echo "<a href='" . $this->webroot . 'Users' . "/" . 'welcomenote' . "'><i class='fa fa-dashboard'></i>" . 'मुख्य' . "</a>";         ?></li>
        <li><?php //echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenregistration' . "'><i class='fa fa-share'></i>" . 'नागरिक नोंदणी' . "</a>";           ?></li>
        <?php //}      ?>

          <li><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'appointment' . "'><i class='fa fa-share'></i><span>" . 'Appointment' . "</span></a>"; ?>
                   </li>

    </ul>


    <?php } ?>



<script>
    $('document').ready(function () {

        $('.treeview').click(function () {
            $(this).find("span>i").toggleClass('fa-angle-down');
        });

    })</script>