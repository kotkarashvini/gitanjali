 


<?php echo $this->Form->create('userpermission', array('id' => 'userpermission', 'autocomplete' => 'off')); ?>

<script>
    $(document).ready(function () {
        $('#role_id').change(function () {
            $('#functionlist').html('');
            $('#btnSave').hide();
        });
        $('#module_id').change(function () {
            $('#functionlist').html('');
            $('#btnSave').hide();
        });
    });
</script>

<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbluserpermission'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/userpermission_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 col-md-offset-2">                         
                        <div class="col-sm-3">
                            <label for="role_id" class="control-label"><?php echo __('lblselectrole'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('role_id', array('options' => array($role), 'empty' => '--select--', 'id' => 'role_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="role_id_error" class="form-error"><?php echo $errarr['role_id_error']; ?></span>
                        </div>            
                        <div class="col-sm-3">
                            <label for="module_id" class="control-label"><?php echo __('lblselectmodule'); ?><span style="color: #ff0000">*</span></label>    
                            <?php 
                            $module[0]="-- ".__('lblselectallmodules')." --";
                            ksort($module);
                            echo $this->Form->input('module_id', array('options' => array($module), 'id' => 'module_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="module_id_error" class="form-error"><?php echo $errarr['module_id_error']; ?></span>
                        </div> 
                        <div class="col-sm-3">
                            <br> 
                            <button id="btnsearch" name="btnsearch" class="btn btn-success" type="submit"><?php echo __('btnsearch'); ?></button> 
                        </div> 
                    </div>
                </div> 

                <?php if (isset($modulefunction)) { ?>
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2">  
                            <div class="col-md-8">
                                <br>   <br> 
                                <div class="panel panel-primary">
                                    <div class="panel-header">
                                        <center><h3 class="panel-title headbolder"><?php echo __('lblmodulefunctions'); ?></h3></center> 
                                    </div>
                                    <div class="panel-body" id="functionlist">

                                        <?php
                                        $block = '';
                                        if (isset($modulefunction)) {
                                            foreach ($modulefunction as $function) {

                                                $result = $this->requestAction(array('controller' => 'Users', 'action' => 'check_in_modulepermission', $module_id, $function['0']['mainmenu_id'], $function['0']['submenu_id'], $function['0']['subsubmenu_id']));
                                                if (!empty($result)) {
//                                                     pr($result);exit;
                                                    $menu = array();
                                                    $menuid = array();
                                                    if (!empty($function['0']['mainmenu_name'])) {
                                                        array_push($menu, $function['0']['mainmenu_name']);
                                                    }

                                                    if (!empty($function['0']['submenu_name'])) {
                                                        array_push($menu, $function['0']['submenu_name']);
                                                    }
                                                    if (!empty($function['0']['subsubmenu_name'])) {
                                                        array_push($menu, $function['0']['subsubmenu_name']);
                                                    }
                                                    $menustr = implode(" => ", $menu);

                                                    array_push($menuid, $function['0']['mainmenu_id']);
                                                    array_push($menuid, $function['0']['submenu_id']);
                                                    array_push($menuid, $function['0']['subsubmenu_id']);
                                                    

                                                    $menuidstr = implode("_", $menuid);

                                                    if (empty($block)) {
                                                        $block = $function['0']['mainmenu_name'];
                                                        echo "</fieldset><fieldset class='scheduler-border'>
                                                <legend class='scheduler-border'>" . $block . "</legend>";
                                                    }
                                                    if ($block != $function['0']['mainmenu_name']) {
                                                        $block = $function['0']['mainmenu_name'];
                                                        echo "</fieldset><fieldset class='scheduler-border'>
                                                <legend class='scheduler-border'>" . $block . "</legend>";
                                                    }

                                                    // $rolepermissions

                                                    $checked = '';
                                                    if (isset($rolepermissions)) {
                                                        foreach ($rolepermissions as $permissions) {
                                                            $menuide = array();
                                                            // pr($permissions);exit;
                                                            array_push($menuide, $permissions['userpermissions']['menu_id']);
                                                            array_push($menuide, $permissions['userpermissions']['submenu_id']);
                                                            array_push($menuide, $permissions['userpermissions']['subsubmenu_id']);
                                                             
                                                            $menuidestr = implode("_", $menuide);
                                                           // echo $menuidestr." | ".$menuidstr;
                                                           // echo
                                                            if ($menuidestr == $menuidstr) {
                                                                $checked = 'checked=checked';
                                                            }
                                                            
                                                             array_push($menuide, $permissions['userpermissions']['modulepermission_id']);
                                                            
                                                            //pr($menuidestr);
                                                        }
                                                    }
                                                     array_push($menuid, $result['ModulePermissions']['modulepermission_id']);
                                                     $menuidstr = implode("_", $menuid);
                                                    // pr($menuidstr);
//                                            exit;
                                                    ?>

                                                    <br><label class="checkboxcontainer"><input type="checkbox" value="<?php echo $menuidstr; ?>" name="menus[]" <?php echo $checked; ?>>  <?php echo @$menustr; ?> <span class="checkmark"></span></label>

                                                    <?php
                                                }//check   
                                            }
                                        }
                                        echo "</fieldset>";
                                        ?>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                <?php } ?>

                <?php if (isset($modulefunction)) { ?>
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2">  
                            <div class="col-md-8">
                                <button id="btnSave" name="btnSave" class="btn btn-success" type="submit"><?php echo __('btnsave'); ?></button> 
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div> 
        </div>
    </div>

</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

