<?php
$lang = $this->Session->read('sess_langauge');

if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {

    $mainresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'main_menu'));
    $subresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'sub_menu'));
    $subsubresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'subsub_menu'));


    $mainmenuflag = "";
    $submenuflag = "";
    
    $submenuid = "";
    $mainmenuid = "";
    $pagetitle = "";
    foreach ($subsubresult as $subsubmenu) {
        if ($this->params['action'] == $subsubmenu['RegistrationSubsubmenu']['action']) {
            $submenuflag = 'Y';
            $submenuid = $subsubmenu['RegistrationSubsubmenu']['submenu_id'];
        }
    }
// $submenuflag = 'Y';
    foreach ($subresult as $submenu) {
        if ($submenu['RegistrationSubmenu']['submenu_id'] == $submenuid) {
            $mainmenuflag = 'Y';
            $mainmenuid = $submenu['RegistrationSubmenu']['mainmenu_id'];
        }
    }
   // $mainmenuflag = 'Y';
    ?>
 
    <div class="btn-group btn-group-justified ">
        
            
                <?php
                foreach ($mainresult as $menu) {
                    if ($this->params['action'] == $menu['RegistrationMainmenu']['action'] || $mainmenuid == $menu['RegistrationMainmenu']['mainmenu_id']) {
                        ?> 
                          <a class="active btn btn-primary bg-maroon" href="<?php echo $this->webroot; ?><?php echo $menu['RegistrationMainmenu']['controller'] . "/" . $menu['RegistrationMainmenu']['action']; ?>" ><?php echo $menu['RegistrationMainmenu']['mainmenu_desc_' . $lang]; ?></a>            
                    <?php } else { ?>              
                          <a class="btn btn-primary" href="<?php echo $this->webroot; ?><?php echo $menu['RegistrationMainmenu']['controller'] . "/" . $menu['RegistrationMainmenu']['action']; ?>" ><?php echo $menu['RegistrationMainmenu']['mainmenu_desc_' . $lang]; ?></a>
                        <?php
                    }
                }
                ?>  
           
       
    </div>
    <div  class="rowht">&nbsp;</div>


    <?php
    if ($submenuflag == 'Y') {
        ?>

        <div class="row">  

            <div class="col-sm-12">
      <div class="btn-arrow">
    
                
                            <?php
                            foreach ($subresult as $submenu) {
                                $nextlink = "";
                                
                                foreach ($subsubresult as $subsubmenu) {
                                    if ($submenu['RegistrationSubmenu']['submenu_id'] == $subsubmenu['RegistrationSubsubmenu']['submenu_id']) {
                                       if (empty($nextlink)) { 
                                        $nextlink = $subsubmenu['RegistrationSubsubmenu']['controller'] . "/" . $subsubmenu['RegistrationSubsubmenu']['action'];
                                       }
                                        
                                    }
                                }
                                if (!empty($nextlink)) {
                                    if ($submenu['RegistrationSubmenu']['submenu_id'] == $submenuid) {
                                        $pagetitle = $submenu['RegistrationSubmenu']['submenu_desc_' . $lang];
                                        ?> 
                                          <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot; ?><?php echo $nextlink; ?>" class=""><?php echo $submenu['RegistrationSubmenu']['submenu_desc_' . $lang]; ?></a>            
                                    <?php } else { ?>              
                                              <a class="btn btn-success btn-arrow-right" href="<?php echo $this->webroot; ?><?php echo $nextlink; ?>" class=""><?php echo $submenu['RegistrationSubmenu']['submenu_desc_' . $lang]; ?></a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        
                </div>
            </div>

        </div> 
    <?php } ?> 

    
    <?php if (!empty($submenuid)) {
        ?>
        <div class="row">  

            <div class="col-sm-12">

 <div class="btn-arrow">

      <?php
                            foreach ($subsubresult as $subsubmenu) {
                                if ($subsubmenu['RegistrationSubsubmenu']['submenu_id'] == $submenuid) {

                                    if ($this->params['action'] == $subsubmenu['RegistrationSubsubmenu']['action']) {
                                        ?> 
                                          <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot; ?><?php echo $subsubmenu['RegistrationSubsubmenu']['controller'] . "/" . $subsubmenu['RegistrationSubsubmenu']['action']; ?>" ><?php echo $subsubmenu['RegistrationSubsubmenu']['subsubmenu_desc_' . $lang]; ?></a>  

                                    <?php } else { ?>              
                                          <a class="btn btn-success btn-arrow-right" href="<?php echo $this->webroot; ?><?php echo $subsubmenu['RegistrationSubsubmenu']['controller'] . "/" . $subsubmenu['RegistrationSubsubmenu']['action']; ?>" ><?php echo $subsubmenu['RegistrationSubsubmenu']['subsubmenu_desc_' . $lang]; ?></a> 
                                        <?php
                                    }
                                }
                            }
                            ?>
  
    
</div>

               

            </div>
        </div>
    <?php } ?> 


<?php } ?>
 
<?php if (isset($stampconfig)) { ?>
    <div class="back-to-top">
        <ul>
            <?php 
                foreach ($stampconfig as $stamprec) { //pr($stamprec['stamp_desc']);
                    if ($documents[0][0][$stamprec['stamp_flag']] == 'Y') {
                        ?>
            <li><span class="glyphicon glyphicon-check text-success"></span> <?php echo $stamprec['stamp_desc'];?></li>
                        <?php } else {
                            ?>
                        <li><span class="fa fa-times  text-danger"></span> <?php echo $stamprec['stamp_desc'];?></li>
                        <?php }
                    ?>

        <?php } ?>
            </ul>
        </div>
    <?php } ?>