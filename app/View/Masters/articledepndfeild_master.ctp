<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#fee_type_row").hide();
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

        $("#fee_param_type_id").change(function () {
            fee_type_change($(this).val());
        });

    });
    function fee_type_change(feetype) {
        if (feetype == 2 || feetype == 6) {
            $("#fee_type_row").show();
        }
        else {
            $("#fee_type_row").hide();
            $("#fee_type_id").val('');
        }
    }
    function formadd() {

        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, fee_item_desc_en, fee_item_desc_ll, fee_item_desc_ll1, fee_item_desc_ll2, fee_item_desc_ll3, fee_item_desc_ll4, gen_dis_flag, list_flag) {
        $('#hfid').val(id);
        $('#fee_item_desc_en').val(fee_item_desc_en);
        $('#fee_item_desc_ll').val(fee_item_desc_ll);
        $('#fee_item_desc_ll1').val(fee_item_desc_ll1);
        $('#fee_item_desc_ll2').val(fee_item_desc_ll2);
        $('#fee_item_desc_ll3').val(fee_item_desc_ll3);
        $('#fee_item_desc_ll4').val(fee_item_desc_ll4);

        $('input:radio[name="data[fees_items][list_flag]"][value=' + list_flag + ']').prop('checked', true);
        $('input:radio[name="data[fees_items][gen_dis_flag]"][value=' + gen_dis_flag + ']').attr('checked', true);
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

<?php echo $this->Form->create('fees_items', array('id' => 'fees_items', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblarticledepfield'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/articledepndfeild_master_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row" >
                    <div class="form-group">
                        <label for="fee_item_desc_en" class="col-sm-2 control-label"><?php echo __('lblfeesitem'); ?><span style="color: #ff0000">*</span></label>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $language1) {

                            if ($i % 6 == 0) {

                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('fee_item_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'fee_item_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'maxlength' => "255")) ?>
                                <span id="<?php echo 'fee_item_desc_' . $language1['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['fee_item_desc_' . $language1['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
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

                <div  class="rowht"></div>
                <div class="row" >
                    <div class="form-group ">
                        <label for="gen_dis_flag" class="col-sm-4 control-label"><?php echo __('lbldisplayongeninfocitizenentry'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('gen_dis_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'gen_dis_flag')); ?>
                        </div>  
                    </div>
                </div>
                <div class="row" id="list_flag_div">
                    <div class="form-group">
                        <label for="list flag" class="control-label col-sm-2"><?php echo __('lbllistflag'); ?></label>            
                        <div class="col-sm-3"><?php echo $this->Form->input('list_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'listflag')); ?></div> 
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group center">
                        <button id="btnadd" type="submit"name="btnadd" class="btn btn-info "    onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                        <button id="btnadd" name="btncancel" class="btn btn-info "    onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;<?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body" id="divfees_items">
                <table id="tablefees_items" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblfieldcode'); ?></th>
                            <th class="center"><?php echo __('lblfieldname'); ?></th>
                            <th class="width10 center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($fees_items); $i++) { ?>
                        <tr>
                            <td ><?php echo $fees_items[$i][0]['fee_param_code']; ?></td>
                            <td ><?php echo $fees_items[$i][0]['fee_item_desc_' . $laug]; ?></td>
                            <td >
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                ('<?php echo $fees_items[$i][0]['fee_item_id']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_en']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_ll']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_ll1']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_ll2']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_ll3']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['fee_item_desc_ll4']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['gen_dis_flag']; ?>'),
                                                ('<?php echo $fees_items[$i][0]['list_flag']; ?>')
                                                );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>

                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'articledepndfeild_delete', $fees_items[$i][0]['fee_item_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($fees_items)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




