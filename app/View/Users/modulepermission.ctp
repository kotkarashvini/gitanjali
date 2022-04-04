
<script>
    $(document).ready(function () {         
        $('#module_id').change(function () {
            $('#functionlist').html('');
            $('#btnSave').hide();
        });
    });
</script>
<?php echo $this->Form->create('modulepermission', array('id' => 'modulepermission', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblmodulepermission'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Users/modulepermission_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 col-md-offset-2">                         
                        <div class="col-sm-3">
                            <label for="module_id" class="control-label"><?php echo __('lblselectmodule'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('module_id', array('options' => array($module), 'empty' => '--select--', 'id' => 'module_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="module_id_error" class="form-error"><?php echo $errarr['module_id_error']; ?></span>
                        </div> 
                         <div class="col-sm-3">
                            <br> 
                            <button id="btnsearch" name="btnsearch" class="btn btn-success" type="submit"><?php echo __('btnsearch'); ?></button> 
                        </div> 
                    </div>
                </div> 
 <?php if (isset($menulist)) { ?>
                
                
                
                <div class="row">
                    <div class="col-md-12 col-md-offset-2">  
                        <div class="col-md-8">
                            <br>   <br> 
                            <div class="panel panel-primary">
                                <div class="panel-header">
                                    <center><h3 class="panel-title headbolder"><?php echo __('lblmenus'); ?></h3></center> 
                                </div>
                                <div class="panel-body" id="functionlist">

                                    <?php
                                    $block = '';
                                    if (isset($menulist)) {
                                        foreach ($menulist as $function) {
                                            $menu = array();
                                            $menuid = array();
                                           // pr($function);exit;
                                            if($function['0']['submenu_display_flag']=='N' || $function['0']['subsubmenu_display_flag']=='N'){
                                                 continue;
                                            }
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
                                            
                                            
                                            $checked = '';
                                            if (isset($ModulePermissions)) {
                                                foreach ($ModulePermissions as $permissions) {
                                                    $menuide = array();
//                                                    pr($permissions);exit;
                                                    array_push($menuide, $permissions['ModulePermissions']['menu_id']);
                                                    array_push($menuide, $permissions['ModulePermissions']['submenu_id']);
                                                    array_push($menuide, $permissions['ModulePermissions']['subsubmenu_id']);
                                                    $menuidestr = implode("_", $menuide);
                                                    if ($menuidestr == $menuidstr) {
                                                        $checked = 'checked=checked';
                                                    }
                                                    //pr($menuidestr);
                                                }
                                            }
                                            ?>
                                             
                                            <br><label class="checkboxcontainer"><input type="checkbox" value="<?php echo $menuidstr; ?>" name="menus[]" <?php echo $checked; ?>>  <?php echo @$menustr; ?> <span class="checkmark"></span></label>

                                            <?php
                                        }
                                    }
                                    echo "</fieldset>";
                                    ?>
                                    <?php echo $this->Form->input('modulepermission_id', array('label' => false, 'id' => 'modulepermission_id', 'type' => 'hidden')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                  
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnSave" name="btnSave" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                 <?php }  ?>
                
            </div> 
        </div>
    </div>

</div>

