<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divoperator').slideDown(1000);
        }
        else {
            $('#divoperator').hide();
        }
        $('#tableoperator').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(operator_id, operatorsign, operator_name_en, operator_name_ll, operator_name_ll1, operator_name_ll2, operator_name_ll3, operator_name_ll4) {
        $('#hfid').val(operator_id);
        $('#operatorsign').val(operatorsign);
        $('#operator_name_en').val(operator_name_en);
        $('#operator_name_ll').val(operator_name_ll);
        $('#operator_name_ll1').val(operator_name_ll1);
        $('#operator_name_ll2').val(operator_name_ll2);
        $('#operator_name_ll3').val(operator_name_ll3);
        $('#operator_name_ll4').val(operator_name_ll4);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(operator_id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(operator_id);
        } else {
            return false;
        }
    }
</script>

<?php echo $this->Form->create('operator', array('id' => 'operator', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lbloperator'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label for="operatorsign"><?php echo __('lbloperatorsign'); ?> <span style="color: #ff0000">*</span></label>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('operatorsign', array('label' => false, 'id' => 'operatorsign', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => 'Operator Sign', 'onkeyup' => "validate(2,this.value,1,8)")) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label for="operator_name_en"><?php echo __('lbloperatorname'); ?><span style="color: #ff0000">*</span></label>
                                </div>


                                <?php
                                $i = 1;
                                foreach ($language2 as $language1) {

                                    if ($i % 6 == 0) {

                                        echo "<div class=row>";
                                    }
                                    ?>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('operator_name_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'operator_name_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'onkeyup' => "validate(2,this.value,1,8)")) ?>
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
                            <div class="panel panel-info" id="divoperator">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="tableoperator" class="table table-striped table-bordered table-hover">  
                                            <thead >  
                                                <tr>  
                                                    <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lbloperatorsign'); ?></td>
                                                    <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lbloperatorname'); ?></td>
                                                    <td style="text-align: center; font-weight:bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                                </tr>  
                                            </thead>

                                            <?php for ($i = 0; $i < count($operator); $i++) { ?>
                                                <tr>
                                                    <td style="text-align: center;"><?php echo $operator[$i][0]['operatorsign']; ?></td>
                                                    <td style="text-align: center;"><?php echo $operator[$i][0]['operator_name_' . $language]; ?></td>

                                                    <td style="text-align: center;">
                                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdate(
                                                                        ('<?php echo $operator[$i][0]['operator_id']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operatorsign']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_en']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_ll']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_ll1']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_ll2']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_ll3']; ?>'),
                                                                        ('<?php echo $operator[$i][0]['operator_name_ll4']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" onclick="javascript: return formdelete(('<?php echo $operator[$i][0]['operator_id']; ?>'));">
                                                            <span class="glyphicon glyphicon-remove"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table> 
                                        <?php if (!empty($operator)) { ?>
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




