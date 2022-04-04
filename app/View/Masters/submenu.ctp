<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<!--<script>

    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });
</script>-->

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
            $('#tableMenu').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
    });
</script>

<script>
    function formadd() {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()));
        });

        var usage_sub_catg_desc_en = $('#usage_sub_catg_desc_en').val();
        //var usage_sub_catg_desc_ll = $('#usage_sub_catg_desc_ll').val();

        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (usage_sub_catg_desc_en === '') {

            alert('Please enter usage sub category description!!!');
            $('#usage_sub_catg_desc_en').focus();
            return false;
        }
        //$('#usage_sub_catg_desc_en').val(usage_sub_catg_desc_en.trim());
        //$('#usage_sub_catg_desc_ll').val(usage_sub_catg_desc_ll.trim());
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(id, name_en, name_ll, controller, action, main_menu_id) {
        document.getElementById("actiontype").value = '1';
        $('#name_en').val(name_en);
        $('#name_ll').val(name_ll);
        $('#controller').val(controller);
        $('#action').val(action);
        $('#main_menu_id').val(main_menu_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script> 

<?php echo $this->Form->create('submenu', array('id' => 'submenu', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblsubmenu'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="controller" class="col-sm-2 control-label"><?php echo __('lblselcontroller'); ?> :<span style="color: #ff0000">*</span></label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('controller', array('options' => $controllerdata, 'empty' => '---select---', 'id' => 'controller', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            </div>
                            <label for="action" class="col-sm-2 control-label"><?php echo __('lblselaction'); ?> :<span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('action', array('options' => $actiondata, 'empty' => '---select---', 'id' => 'action', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="main_menu_id" class="col-sm-2 control-label"><?php echo __('lblselmenu'); ?> :<span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('main_menu_id', array('options' => $menudata, 'empty' => '---select---', 'id' => 'main_menu_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="name_en" class="col-sm-2 control-label"><?php echo __('lblsubmenudesc'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('name_en', array('label' => false, 'id' => 'name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <label for="name_en" class="col-sm-2 control-label"><?php echo __('lblsubmenudescloc'); ?><span style="color: #ff0000">*</span></label>  
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('name_ll', array('label' => false, 'id' => 'name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" 
                                    onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                </div>
                            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblsubmenu'); ?></b></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="tableMenu" class="table table-striped table-bordered table-hover">  
                                        <thead >  
                                            <tr>  
                                                <td style="text-align: center; width: 10%;"><?php echo __('lblmenuid'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblmenuname'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblcontroller'); ?></td>
                                                <td style="text-align: center;"><?php echo __('lblaction'); ?></td>
                                                <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php foreach ($submenurecord as $submenurecord1): ?>
                                                <tr>
                                                    <td style="text-align: center"><?php echo $submenurecord1['SubMenu']['id']; ?></td>
                                                    <td style="text-align: center;"><?php echo $submenurecord1['SubMenu']['name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $submenurecord1['SubMenu']['controller']; ?></td>
                                                    <td style="text-align: center;"><?php echo $submenurecord1['SubMenu']['action']; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdate(('<?php echo $submenurecord1['SubMenu']['id']; ?>'), ('<?php echo $submenurecord1['SubMenu']['name_en']; ?>'), ('<?php echo $submenurecord1['SubMenu']['name_ll']; ?>'), ('<?php echo $submenurecord1['SubMenu']['controller']; ?>'), ('<?php echo $submenurecord1['SubMenu']['action']; ?>'), ('<?php echo $submenurecord1['SubMenu']['main_menu_id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                        </button>
                                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formdelete(('<?php echo $submenurecord1['SubMenu']['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php unset($submenurecord1); ?>
                                        </tbody>
                                    </table> 
                                    <?php if (!empty($submenurecord)) { ?>
                                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                                </div>
                            </div>
                       
            
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




