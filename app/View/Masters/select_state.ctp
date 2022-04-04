<script>
    function formadd() {

        var r = confirm("Do you want to Lock this State/ UT");
        if (r == true) {
            // submit form
            var r2 = confirm("Saving this State/ UT will create database level configuration, are you sure you want to lock this State/ UT for further configuration ?");
            if (r2 == true) {
                $("#config_state").submit();
            }
            else {
                return false;
            }
        } else {
            return false;
        }

    }
</script>
<?php
echo $this->element("Master/language_main_menu");
echo $this->Form->create('config_state', array('id' => 'config_language', 'type' => 'file'));
?>
<div class="row">
    <div class="col-lg-12">

        <div class="pull-left"> <b style="color:red">Note 1 : After locking the State/ UT, further change in State/ UT Setup is not allowed.</b></div><br>
        <div class="pull-left"> <b style="color:blue">Note 2 : After State/ UT Setup current user automatically get Sign out, for further configuration Please Login using given user.</b></div><br>
        <div class="pull-left"> <b style="color:red">Note 3 : '*' indicates mandatory fields. </b></div><br>
        <div class="pull-left"> <b style="color:blue">Note 4 : Upload Logo Format - 50 X 50 px, .PNG, .JPEG, .JPG format with 1 MB.</b></div><br>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo 'State/ UT Setup'; ?></h3></center>
                <!--<div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ConfigLanguage/config_language_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>--> 
            </div>

            <?php
            if ($szcntstate <= 0) {
                ?>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectstate'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php
                                //  pr($state_list);
                                echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($state_list)));
                                ?>
                                <span id="state_id_error" class="form-error"><?php //echo $errarr['state_id_error'];    ?></span>
                            </div>
                            <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectimage'); ?><br>(Upload State Logo here)<span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('logo_path', array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'placeholder' => 'Upload Image', 'id' => 'logo_path', 'class' => 'Cntrl1')); ?>
                                <span id="logo_path_error" class="form-error"><?php //echo $errarr['logo_path_error'];    ?></span> 
                            </div>

                            <div class="col-sm-1"></div>

                        </div>
                    </div>



                    <div class="row">
                        <div class="form-group">


                            <label for="dept_name" class="col-sm-2 control-label"><?php echo 'Enter Department Name'; ?></label>    
                            <div class="col-sm-2">
                                <?php
                                echo $this->Form->input('dept_name', array('label' => false, 'id' => 'dept_name','placeholder'=>'Department name', 'class' => 'form-control input-sm'));
                                ?>
                            </div>    
                            <div class="col-sm-1"></div>

                        </div>
                    </div>

                    <div class="rowht"></div><div class="rowht"></div>
                    <!--<br>
                    <div class="row center"><b><font color="red">You can not again change the state/ UT, after selection of state/ Ut here.</font></b></div>
                    <br>-->
                    <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <button id="btnadd"  class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo 'Lock State/ UT'; ?></button>

                            </div>
                        </div>
                    </div>
                    <!--<br>
                    <div class="row center"><b><font color="blue">After State/ UT selection current user automatically get Sign out, for further language configuration Please Login using </font><font color="red">"configmanager" </font><font color="blue"> user.</font></b></div>
                    <br>-->
                </div>
                <?php
            } else {
                ?>
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo 'You Have Already Created instance for <font color=red>' . $statecount[0]['currentstate']['state_name'] . ' </font>State. If you want to delete this setup Please Use "Reinitiate State/ UT Setup" button, But this will delete previous information'; ?></h3></center>

                </div>



                <?php
            }
            ?>
        </div>
    </div>
</div>