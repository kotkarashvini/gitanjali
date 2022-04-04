<script>
    $(document).ready(function () {
        if (!navigator.onLine) {
          //  window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward();
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };

        //calander code
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

    });

    function dropdownchange(ddlid) {
        var ddlval = $("#" + ddlid + " option:selected").val();
        //alert(ddlval);
        $.getJSON("<?php echo $this->webroot; ?>Property/dropdowndependency", {ddlid: ddlid, ddlval: ddlval}, function (data)
        {
            var sc = '<option value="empty">--Select Option--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            if (ddlid == 'fldarticle') {
                $("#flddocumenttitle option").remove();
                $("#flddocumenttitle").append(sc);
            }
        });
    }
</script>

<?php echo $this->Form->create('frmparty'); ?>
<div class="container-fluid">
    <div class="panel panel-warning">
        <div class="panel-heading"><h2><?php echo __('lblpartydetails'); ?></h2></div>
        <div class="panel-body">

            <div class="row" style="text-align: center;">

                <?php  echo $majorfunction ;foreach ($majorfunction as $mf) {
                    ?>
                    <input type="button" id="btn<?php echo $mf['majorfunction']['function_desc']; ?>" class="btn btn-primary " value="<?php echo __($mf['formlabels']['labelname']); ?>"  onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => $mf['mf_forms']['form_name'])); ?>';" />
                <?php }
                ?>
            </div>
            <br>
            <?php foreach ($formbehaviour as $fb) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php echo __($fb['formlabels']['labelname']); ?> 
                            <?php // echo $fb['formbehaviour']['behaviour_desc']; ?>
                            <!--<label for="" class="col-sm-3 control-label"><?php echo __('lblName_en'); ?></label>-->
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php // foreach($validationrule as $valid) 
//                            $valid['validationrule']['validationrule_desc'];
                            
//                            pr($formfields);exit;
                            
//                            eval("var x = 'numeric';");
//                            console.log(x);
                            foreach ($formfields as $ff) {
//                                pr($ff['fieldformlinkage']['validationrule_id']);exit;
                                if ($fb['formbehaviour']['id'] == $ff['fieldformlinkage']['behaviour_id']) {
                                    $lbls = explode('_ll', $ff['formlabels']['labelname']);
                                    ?>
                                    <div class="row">
                                        <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                            <!--<div class="col-sm-2"></div>-->

                                                                                                                            <!--<label for="<?php //echo $ff['fieldlist']['fieldname'];       ?>" class="col-sm-3 control-label"><?php //echo $ff['formlabels']['label_desc_en'];       ?></label>-->    
                                            <label for="<?php echo $ff['fieldlist']['fieldname']; ?>" class="col-sm-3 control-label"><?php echo __($lbls[0]); ?></label>    
                                            <div class="col-sm-3">
                                                <?php
                                                if ($ff['fieldlist']['fieldtype'] == 'Text') {
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm','onblur'=>"validate(".$ff['fieldformlinkage']['validationrule_id'].",this.value,".$ff['fieldformlinkage']['min_lenght'].",".$ff['fieldformlinkage']['max_lenght'].")"));
//                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm','onkeyup'=>"validate(2,this.value)",'onblur'=>"minLength(this.value,2)"));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Number') {
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm','onblur'=>"validate(".$ff['fieldformlinkage']['validationrule_id'].",this.value,".$ff['fieldformlinkage']['min_lenght'].",".$ff['fieldformlinkage']['max_lenght'].")"));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Dropdown') {
                                                    $options = ClassRegistry::init('fillDropdown')->getdropdown($ff['fieldlist']['fieldname']);
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'select', 'error' => false, 'options' => array(0 => '--Select Option--', $options), 'id' => $ff['fieldlist']['fieldname'], 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'dropdownchange("' . $ff['fieldlist']['fieldname'] . '");'));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Date') {
                                                    ?>
                                                    <div class="input-group date">
                                                        <?php echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly')); ?>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                    <?php
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Checkbox') {
                                                    //echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'checkbox', 'label' => $ff['formlabels']['label_desc_en'], 'format' => array('before', 'input', 'between', 'label', 'after', 'error')));
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'checkbox', 'label' => __($lbls[0]), 'format' => array('before', 'input', 'between', 'label', 'after', 'error')));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Radio') {
                                                    $options = ClassRegistry::init('fillDropdown')->getradiobuttonlist($ff['fieldlist']['fieldname']);
                                                    $attributes = array('legend' => false);
                                                    echo $this->Form->radio($ff['fieldlist']['fieldname'], $options, $attributes);
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($ff['fieldlist']['fieldtype'] == 'Text') {
                                                ?>
                                                                    <!--<label for="<?php //echo $ff['fieldlist']['fieldname'] . '_ll';      ?>" class="col-sm-3 control-label"><?php //echo $ff['formlabels']['label_desc_' . $this->Session->read("local_langauge")];      ?></label>-->    
                                                <label for="<?php echo $ff['fieldlist']['fieldname'] . '_ll'; ?>" class="col-sm-3 control-label"><?php echo __($lbls[0] . '_ll'); ?></label>    
                                                <div class="col-sm-3">
                                                    <?php
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'] . '_ll', array('label' => false, 'id' => $ff['fieldlist']['fieldname'] . '_ll', 'type' => 'text', 'class' => 'form-control input-sm','onblur'=>"validate(".$ff['fieldformlinkage']['validationrule_id'].",this.value,".$ff['fieldformlinkage']['min_lenght'].",".$ff['fieldformlinkage']['max_lenght'].")"));
                                                    ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-3"></div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <!--<div class="col-sm-2"></div>-->
                                            <?php if ($ff['fieldlist']['fieldtype'] == 'Text') { ?>
                                                                    <!--<label for = "<?php //echo $ff['fieldlist']['fieldname'] . '_ll';      ?>" class = "col-sm-3 control-label"><?php //echo $ff['formlabels']['label_desc_' . $this->Session->read("local_langauge")];      ?></label>-->
                                                <label for = "<?php echo $ff['fieldlist']['fieldname'] . '_ll'; ?>" class = "col-sm-3 control-label"><?php echo __($lbls[0]); ?></label>
                                            <?php } else {
                                                ?>
                                                <label for = "<?php echo $ff['fieldlist']['fieldname']; ?>" class = "col-sm-3 control-label"><?php echo __($lbls[0]); ?></label>
                                            <?php }
                                            ?>

                                            <div class="col-sm-3">
                                                <?php
                                                if ($ff['fieldlist']['fieldtype'] == 'Text') {
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'] . '_ll', array('label' => false, 'id' => $ff['fieldlist']['fieldname'] . '_ll', 'type' => 'text', 'class' => 'form-control input-sm'));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Number') {
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm'));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Dropdown') {
                                                    $options = ClassRegistry::init('fillDropdown')->getdropdown($ff['fieldlist']['fieldname']);
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'select', 'error' => false, 'options' => array(0 => '--Select Option--', $options), 'id' => $ff['fieldlist']['fieldname'], 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'dropdownchange("' . $ff['fieldlist']['fieldname'] . '");'));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Date') {
                                                    ?>
                                                    <div class="input-group date">
                                                        <?php echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly')); ?>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                    <?php
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Checkbox') {
                                                    //echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'checkbox', 'label' => $ff['formlabels']['label_desc_' . $this->Session->read("local_langauge")], 'format' => array('before', 'input', 'between', 'label', 'after', 'error')));
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'checkbox', 'label' => __($lbls[0]), 'format' => array('before', 'input', 'between', 'label', 'after', 'error')));
                                                } else if ($ff['fieldlist']['fieldtype'] == 'Radio') {
                                                    $options = ClassRegistry::init('fillDropdown')->getradiobuttonlist($ff['fieldlist']['fieldname']);
                                                    $attributes = array('legend' => false);
                                                    echo $this->Form->radio($ff['fieldlist']['fieldname'], $options, $attributes);
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($ff['fieldlist']['fieldtype'] == 'Text') {
                                                ?>
                                                                    <!--<label for="<?php //echo $ff['fieldlist']['fieldname'];      ?>" class="col-sm-3 control-label"><?php //echo $ff['formlabels']['label_desc_en'];      ?></label>-->
                                                <label for="<?php echo $ff['fieldlist']['fieldname']; ?>" class="col-sm-3 control-label"><?php echo __($lbls[0] . '_ll'); ?></label>
                                                <div class="col-sm-3">
                                                    <?php
                                                    echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm'));
                                                    ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-3"></div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                            
                            ?>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm-12">
                    <input type="button" id="btnCancel" class="btn btn-primary " value="<?php echo __('btncancel'); ?>" />
                    <button type="submit" id="btnNext" name="btnNext" class="btn btn-primary"><?php echo __('btnnext'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>