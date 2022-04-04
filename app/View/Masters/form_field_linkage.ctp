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
        $('#tblLabel').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });


        $("#btnSave").click(function () {
            action = $("#hdnAction").val();
            if (action != 'U') {
//                alert('hi');
                $("#hdnAction").val('SV');
            }
            if ($.trim($("#formname").val()) == '')
            {
                $("#formname").focus();
                $("#formname").val('');

                alert("Please Select Form Name");
                return false;
            }
            if ($.trim($("#fieldname").val()) == '') {
                $("#fieldname").focus();
                $("#fieldname").val('');
                alert("Please Select Field Name");
                return false;
            }
            else {
                $("#formID").submit();
            }

        });


        $("#btnCancel").click(function () {
            $("#hdnAction").val('C');
            $("#fieldname").val("");
            $("#label_id").val("");
        });

        $("#btnExit").click(function () {
            $("#hdnAction").val('E');
            $("#formID").submit();
        });

    });


    function formupdate(lblid, formid, fieldid, bvrs) {
        $("#hdnLabelId").val(lblid);
        $("#formname").val(formid);
        $("#fieldname").val(fieldid);
        $("#bvr").val(bvrs);
        $("#hdnAction").val('U');
        return false;
    }

</script>
<?php echo $this->Form->create('fieldformlinkage', array('type' => 'file', 'autocomplete' => 'off', 'id' => 'formID')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblformfieldlinkage'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="Form name" class="control-label col-sm-2"><?php echo __('lblformname'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"> <?php echo $this->Form->input($name[1], array('id' => 'formname', 'label' => false, 'type' => 'select', 'empty' => '--select--', 'options' => $formlist, 'class' => 'form-control input-sm', 'required' => true)); ?></div>            
                            <label for="Field name" class="control-label col-sm-2"><?php echo __('lblfieldname'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"> <?php echo $this->Form->input($name[2], array('id' => 'fieldname', 'label' => false, 'type' => 'select', 'empty' => '--select--', 'options' => $fieldlist, 'class' => 'form-control input-sm', 'required' => true)); ?></div>            
                            <label for="Behaviour id" class="control-label col-sm-2"> <?php echo __('lblbehaviour'); ?> <span style="color: #ff0000">*</span></label>
                            <div class="col-sm-2"> <?php echo $this->Form->input($name[7], array('id' => 'bvr', 'label' => false, 'type' => 'select', 'empty' => '--select--', 'options' => $behaviourlist, 'class' => 'form-control input-sm', 'required' => true)); ?></div>            
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="button" value="<?php echo __('btnsave'); ?>" id="btnSave"  class="btn btn-primary">
                            <input type="reset" value="<?php echo __('btncancel'); ?>" id="btnCancel" class="btn btn-primary">
                            <input type="button" value="<?php echo __('lblexit'); ?>" id="btnExit" class="btn btn-primary">
                            <input id="hdnLabelId" name="field_form_id" type="hidden">
                            <input type="hidden" id="hdnAction" name="frmaction" >
                        </div>
                    </div>
                </div>
               </div>
                            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblformfieldlinkage'); ?></b></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table  id="tblLabel" class="table table-striped table-bordered table-hover">
                                        <thead >
                                        <?php
                                        echo "<tr>"
                                        . "<td  align=center width=10%>" . __('lblsrno') . "</td>"
                                        . "<td  align=center>" . __('lblformname') . "</td>"
                                        . "<td  align=center>" . __('lblfieldname') . "</td>"
                                        . "<td  align=center width=15%>" . __('lblbehaviour') . "</td>"
                                        . "<td  align=center width=5%>" . __('lblaction') . "</td>"
                                        . "</tr>";
                                        ?>             
                                    </thead>
                                    <tbody>
                                        <?php
                                        $srno = 1;
                                        foreach ($ffldata as $ffldata):
                                            $ffl = $ffldata[0];
                                            echo "<tr>"
                                            . "<td align=center>" . $srno++ . "</td>"
                                            . "<td>" . $ffl['form_name'] . "</td>"
                                            . "<td>" . $ffl['fieldname'] . "</td>"
                                            . "<td>" . $ffl['behaviour_desc'] . "</td>"
                                            . "<td align=center>" . $this->Form->button('View', array('class' => "btn btn-default", 'onclick' => "javascript: return formupdate('" . $ffl[$name[0]] . "','" . $ffl[$name[1]] . "','" . $ffl[$name[2]] . "','" . $ffl[$name[7]] . "');")) . "</td>"
                                            . "</tr>";
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    

        
    </div>
</div>
</div>

<?php echo $this->form->end(); ?>
