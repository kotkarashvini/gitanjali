<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {
        
        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divfees_items').slideDown(1000);
        }
        else {
            $('#divfees_items').hide();
        }
         $('#tablefees_items').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        
        $('input[type=radio][name=is_boolean]').change(function() {
        if (this.value == 'Y') {
            $('#boolyes').show();
             $('#boolno').hide();
             $('#info_value').val('');
        }
        else if (this.value == 'N') {
             $('#boolno').show();
             $('#boolyes').hide();
              $('input[name="conf_bool_value"]').prop('checked', false);
        }
        else {
            $('#boolyes').hide();
             $('#boolno').hide();
        }
    });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
//        var boolval=$("input[name=conf_bool_value]:checked").val();
//        var infoval=$('#info_value').val();
//        alert(boolval); alert(infoval);
//        if($('#hfupdateflag').val=='Y' && boolval!=''){
//            $('#info_value').val('');
//        }
//        if($('#hfupdateflag').val=='Y' && infoval!=''){
//            $('input[name="conf_bool_value"]').prop('checked', false);
//            alert($("input[name=conf_bool_value]:checked").val());
//        }
    }
    
    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id,conf_desc_en,is_boolean,conf_bool_value,info_value,conf_desc_ll,conf_desc_ll1,conf_desc_ll2,conf_desc_ll3,conf_desc_ll4) {
       $("input:radio").attr("checked", false);
//       $('input:radio[name=is_boolean]').attr('checked',false);
       $('input[name=is_boolean][value="' + is_boolean + '"]').prop('checked', 'checked');
        $('#hfid').val(id);
        $('#conf_desc_en').val(conf_desc_en);
       
         if (is_boolean == 'Y') {
            $('#boolyes').show();
             $('#boolno').hide();
        }else {
            $('#boolyes').hide();
             $('#boolno').show();
        }
         $('input[name=is_boolean][value="' + is_boolean + '"]').prop('checked', 'checked');
         $('input[name=conf_bool_value][value="' + conf_bool_value + '"]').prop('checked', 'checked');
        $('#info_value').val(info_value);
        $('#conf_desc_ll').val(conf_desc_ll);
        $('#conf_desc_ll1').val(conf_desc_ll1);
        $('#conf_desc_ll2').val(conf_desc_ll2);
        $('#conf_desc_ll3').val(conf_desc_ll3);
         $('#conf_desc_ll4').val(conf_desc_ll4);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
</script>

<?php echo $this->Form->create('regconfig', array('id' => 'regconfig', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblregconfig'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group"><br>
                            <div class="row">
                                <label for="conf_desc_en" class="col-sm-4 control-label"><?php echo __('lblregconfig'); ?><span style="color: #ff0000">*</span></label>
                            </div>
                             <?php 
                                                    $i = 1;
                                                    foreach ($language2 as $language1) {

                                                        if ($i % 6 == 0) {

                                                            echo "<div class=row>";
                                                        }
                                                        ?>
                                                    <div class="col-sm-2">
                                                    <?php echo $this->Form->input('conf_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'conf_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text','placeholder'=>$language1['mainlanguage']['language_name'], 'onkeyup' => "validate(2,this.value,1,8)")) ?>
                                                    </div>
                                                    <?php
                                                    if ($i % 6 == 0) {
                                                        if ($i > 1) {
                                                            echo "</div><br>";
                                                        }
                                                    }
                                                    $i++;
                                                }
                                                ?> 
                            
                        </div>
                       
                             
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="is_boolean" class="control-label col-sm-2"><?php echo __('lblisboolean'); ?><span style="color: #ff0000">*</span></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('is_boolean', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_boolean','name' => 'is_boolean')); ?></div>                        
                            <div id="boolyes" hidden="true">
                             <label for="conf_bool_value" class="control-label col-sm-2"><?php echo __('lblselbooleanval'); ?><span style="color: #ff0000">*</span></label>            
                            <div class="col-sm-2"> <?php echo $this->Form->input('conf_bool_value', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'conf_bool_value','name' => 'conf_bool_value')); ?></div>    
                            </div>
                            <div id="boolno" hidden="true">
                             <label for="info_value" class="col-sm-2 control-label"><?php echo __('lblinfoval'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('info_value', array('label' => false, 'id' => 'info_value', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12" style="text-align: center">
                        <div class="form-group" style="text-align: center">
                                <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"   onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp; <?php echo __('lblbtnAdd'); ?></button> &nbsp;&nbsp;&nbsp;
                                <button id="btnadd" name="btncancel" class="btn btn-primary " style="text-align: center;"   onclick="javascript: return forcancel();">
                                    <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info" id="divfees_items">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="tablefees_items" class="table table-striped table-bordered table-hover">  
                                        <thead >  
                                            <tr>  
                                                <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lblconfigdesc'); ?></td>
                                                <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lblisboolean'); ?></td>
                                                <td style="text-align: center; font-weight:bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                       
                                            <?php for ($i = 0; $i < count($regconfig); $i++) { ?>
                                         <tr>
                                                <td style="text-align: center;"><?php echo $regconfig[$i][0]['conf_desc_'.$language]; ?></td>
                                                <td style="text-align: center;"><?php echo $regconfig[$i][0]['is_boolean']; ?></td>
                                                <td style="text-align: center;">
                                                    <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdate(
                                                                    ('<?php echo $regconfig[$i][0]['id']; ?>'),
                                                                    ('<?php echo $regconfig[$i][0]['conf_desc_en']; ?>'),
                                                                    ('<?php echo $regconfig[$i][0]['is_boolean']; ?>'),
                                                                      ('<?php echo $regconfig[$i][0]['conf_bool_value']; ?>'),
                                                                      ('<?php echo $regconfig[$i][0]['info_value']; ?>'),
                                                                      ('<?php echo $regconfig[$i][0]['conf_desc_ll']; ?>'),
                                                                        ('<?php echo $regconfig[$i][0]['conf_desc_ll1']; ?>'),
                                                                          ('<?php echo $regconfig[$i][0]['conf_desc_ll2']; ?>'),
                                                                            ('<?php echo $regconfig[$i][0]['conf_desc_ll3']; ?>'),
                                                                              ('<?php echo $regconfig[$i][0]['conf_desc_ll4']; ?>'));">
                                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                                    <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" onclick="javascript: return formdelete(('<?php echo $regconfig[$i][0]['id']; ?>'));">
                                                        <span class="glyphicon glyphicon-remove"></span></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table> 
                                    <?php if (!empty($regconfig)) { ?>
                                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                            
                        
            
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




