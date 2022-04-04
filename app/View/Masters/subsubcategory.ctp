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
        if ($('#hfhidden1').val() == 'Y')
        {
            $('#tablesubsubcategory').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        } else {
            $('#tablesubsubcategory').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }

        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#village_name_en').focus();
        }

        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag == 'Y')
        {
            $('#btnadd').html('Save');
        }

    });
</script>
<script>
    function formadd() {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()));
        });

        var usage_sub_sub_catg_desc_en = $('#usage_sub_sub_catg_desc_en').val();
        //var usage_sub_sub_catg_desc_ll = $('#usage_sub_sub_catg_desc_ll').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (usage_sub_sub_catg_desc_en === '') {
            alert('Please enter usage sub sub category description!!!');
            $('#usage_sub_sub_catg_desc_en').focus();
            return false;
        }
        //$('#usage_sub_sub_catg_desc_en').val(usage_sub_sub_catg_desc_en.trim());
        //$('#usage_sub_sub_catg_desc_ll').val(usage_sub_sub_catg_desc_ll.trim());
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(usage_sub_sub_catg_desc_en, usage_sub_sub_catg_desc_ll, dolr_usage_code,
            contsruction_type_flag, depreciation_flag, road_vicinity_flag, user_defined_dependency1_flag, user_defined_dependency2_flag, id) {

        document.getElementById("actiontype").value = '1';
        $('#usage_sub_sub_catg_desc_en').val(usage_sub_sub_catg_desc_en);
        $('#usage_sub_sub_catg_desc_ll').val(usage_sub_sub_catg_desc_ll);
        $('#dolr_usage_code').val(dolr_usage_code);
        $('#contsruction_type_flag').val(contsruction_type_flag);
        $('#depreciation_flag').val(depreciation_flag);
        $('#road_vicinity_flag').val(road_vicinity_flag);
        $('#user_defined_dependency1_flag').val(user_defined_dependency1_flag);
        $('#user_defined_dependency2_flag').val(user_defined_dependency2_flag);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
        $('input:radio[name="data[subsubcategory][contsruction_type_flag]"][value=' + contsruction_type_flag + ']').attr('checked', true);
        $('input:radio[name="data[subsubcategory][depreciation_flag]"][value=' + depreciation_flag + ']').attr('checked', true);
        $('input:radio[name="data[subsubcategory][road_vicinity_flag]"][value=' + road_vicinity_flag + ']').attr('checked', true);
        $('input:radio[name="data[subsubcategory][user_defined_dependency1_flag]"][value=' + user_defined_dependency1_flag + ']').attr('checked', true);
        $('input:radio[name="data[subsubcategory][user_defined_dependency2_flag]"][value=' + user_defined_dependency2_flag + ']').attr('checked', true);
        return false;
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script> 

<?php echo $this->Form->create('subsubcategory', array('id' => 'subsubcategory', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblsubsubcategoryhead'); ?></b></div>
            <div class="panel-body">
                <div class="row" id="selectsubsubcategory">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1">&nbsp;</div>
                            <label for="construction_type_desc_en" class="col-sm-3 control-label"><?php echo __('lblsubsubcategorydesc'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('usage_sub_sub_catg_desc_en', array('label' => false, 'id' => 'usage_sub_sub_catg_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('usage_sub_sub_catg_desc_ll', array('label' => false, 'id' => 'usage_sub_sub_catg_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="dolr_usage_code" class="col-sm-3 control-label"><?php echo __('lbldlrusagecode'); ?>:<span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('dolr_usage_code', array('label' => false, 'id' => 'dolr_usage_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <div class="col-sm-1 tdselect">
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="contsruction_type_flag" class="control-label col-sm-3"><?php echo __('lblconstuctiontypehead'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('contsruction_type_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'constructionflag')); ?></div>                        
                            <label for="depreciation_flag" class="control-label col-sm-3"><?php echo __('lbldepreciation'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('depreciation_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'depreciationflag')); ?></div> 
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="road_vicinity_flag" class="control-label col-sm-3"><?php echo __('lblroadvicinity'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('road_vicinity_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'roadvicinityflag')); ?></div> 
                            <label for="user_defined_dependency1_flag" class="control-label col-sm-3"><?php echo __('lbluserdependencyflag1'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency1_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'userdefinedependency1flag')); ?></div> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="user_defined_dependency2_flag" class="control-label col-sm-3"><?php echo __('lbluserdependencyflag2'); ?></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('user_defined_dependency2_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'userdefinedependency2flag')); ?></div> 
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                <div class="row" >
                    <div class="col-sm-12 tdselect" style="text-align: center">
                        <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                    <div class="col-sm-12 tdsave" hidden="true" style="text-align: center">
                        <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formsave();">
                            <span class="glyphicon glyphicon-floppy-saved"></span><?php echo __('btnsave'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblsubsubcategoryhead'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="tablesubsubcategory" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <td style="text-align: center;"><b><?php echo __('lbladmstate'); ?></b></td>
                                <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                    <td style="text-align: center;"><b><?php echo __('lblsubsubcategorydesc'); ?></b></td>
                                    <td style="text-align: center;"><b>DOLR UsageCode</b></td>
                                    <td style="text-align: center;"><b><?php echo __('lblsubsubcategorydesc_ll'); ?></b></td>
                                <?php } else { ?>
                                    <td style="text-align: center;"><b><?php echo __('lblsubsubcategorydesc'); ?></b></td>
                                    <td style="text-align: center;"><b>DOLR UsageCode</b></td>
                                    <td style="text-align: center;"><b><?php echo __('lblsubsubcategorydesc_ll'); ?></b></td>
                                <?php } ?>
                                <td style="text-align: center;"><b><?php echo __('lblaction'); ?></b></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($subsubcategoryrecord as $subsubcategoryrecord1): ?>
                                    <td style="text-align: center;"><?php echo $state; ?></td>
                                    <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['dolr_usage_code']; ?></td>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_ll']; ?></td>
                                        <td style="text-align: center; width: 15%">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formupdate(('<?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_en']; ?>'), ('<?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_ll']; ?>'), ('<?php echo $subsubcategoryrecord1['subsubcategory']['dolr_usage_code']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['contsruction_type_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['depreciation_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['road_vicinity_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['user_defined_dependency1_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['user_defined_dependency2_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>

                                            <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formdelete(('<?php echo $subsubcategoryrecord1['subsubcategory']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </td>
                                    <?php } else { ?>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_ll']; ?></td>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['dolr_usage_code']; ?></td>
                                        <td style="text-align: center;"><?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_en']; ?></td>
                                        <td style="text-align: center;">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formupdate(('<?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_ll']; ?>'), ('<?php echo $subsubcategoryrecord1['subsubcategory']['usage_sub_sub_catg_desc_en']; ?>'), ('<?php echo $subsubcategoryrecord1['subsubcategory']['dolr_usage_code']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['contsruction_type_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['depreciation_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['road_vicinity_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['user_defined_dependency1_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['user_defined_dependency2_flag']; ?>'),
                                                                                ('<?php echo $subsubcategoryrecord1['subsubcategory']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formdelete(('<?php echo $subsubcategoryrecord1['subsubcategory']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($subsubcategoryrecord1); ?>
                        </tbody>
                    </table> 
                    <?php
                    if (!empty($subsubcategoryrecord1)) {
                        ?>
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
