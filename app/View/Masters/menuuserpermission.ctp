<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }

        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tableminorfunction').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
    });
</script>
<script>
    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
    }
</script> 

<?php echo $this->Form->create('menuuserpermission', array('id' => 'menuuserpermission', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblmenuuserpermission'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="role_id" class="col-sm-2 control-label"><?php echo __('lblselectrole'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('role_id', array('label' => false, 'id' => 'role_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $roledata))); ?></div>
                            <label for="id" class="col-sm-2 control-label"><?php echo __('lblselectmainmenu'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('menu_id', array('label' => false, 'id' => 'menu_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $menudata))); ?></div>
                            <label for="submenu_id" class="col-sm-2 control-label"><?php echo __('lblselectsubmenu'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"><?php echo $this->Form->input('submenu_id', array('label' => false, 'id' => 'submenu_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $submenudata))); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 25px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" 
                                    onclick="javascript: return formsave();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




