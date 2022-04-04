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
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var action = $("#hdnAction").val();
            if (action != 'U') {
                $("#hdnAction").val('SV');
            }
            if ($("#fieldname").val() == '') {
                $("#fieldname").focus();
                $("#fieldname").val('');
                alert("Please Enter Field Name");
                return false;
            }
            else if ($("#label_id").val() == '')
            {
                $("#label_id").focus();
                $("#label_id").val('');
                alert("Please Select Label Name");
                return false;
            }
            else {
                $("#fieldname").val($("#fieldname").val());
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


    function formupdate(fldid, fldname, lblid) {
        $("#hdnLabelId").val(fldid);
        $("#fieldname").val(fldname);
        $("#label_id").val(lblid);
        $("#hdnAction").val('U');
        return false;
    }
    function formdelete(fldid) {
        $("#hdnLabelId").val(fldid);
        $("#hdnAction").val('D');
        $("#formID").submit();
    }

</script>
<?php echo $this->Form->create('fieldlist', array('type' => 'file', 'autocomplete' => 'off', 'id' => 'formID')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblfieldmaster'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="fieldname" class="col-sm-2 control-label"><?php echo __('lblfieldname'); ?><span style="color: #ff0000"> *</span></label>
                            <div class="col-sm-3"> <?php echo $this->Form->input($name[1], array('id' => 'fieldname', 'label' => false, 'type' => 'text', 'class' => 'form-control input-sm', 'required' => true)); ?></div>            
                            <label for="label_id" class="col-sm-2 control-label"><?php echo __('lbllabelname'); ?><span style="color: #ff0000"> *</span></label>
                            <div class="col-sm-3"><?php echo $this->Form->input($name[2], array('id' => 'label_id', 'label' => false, 'type' => 'select', 'empty' => '--select--', 'options' => $lbllist, 'class' => 'form-control input-sm', 'required' => true)); ?></div>            
                            <div class="col-sm-1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row" style="text-align: center;">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="btnSave" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('btnsave'); ?></button>
                            <input type="reset" value="<?php echo __('btncancel'); ?>" id="btnCancel" class="btn btn-primary">
                            <button id="btnExit" class="btn btn-primary " style="text-align: center;" ><span class="glyphicon"></span>&nbsp;<?php echo __('lblexit'); ?></button>            
                            <input type="hidden" id="hdnLabelId" name="lbl_label_id">
                            <input type="hidden" id="hdnAction" name="frmaction">
                        </div>
                    </div>
                </div>
                </div>
                            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblfieldmaster'); ?></b></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table  id="tblLabel" class="table table-striped table-bordered table-hover">
                                        <thead >
                                            <?php
                                            echo "<b><tr>"
                                            . "<td align=center width=10%>" . __('lblsrno') . "</td>"
                                            . "<td align=center>" . __('lblfieldname') . "</td>"
                                            . "<td align=center>" . __('lbllabelname') . "</td>"
                                            . "<td align=center width=10%>" . __('lblaction') . "</td>"
                                            . "</tr></b>";
                                            ?>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno = 1;
                                            foreach ($fields as $fld):
                                                $fld = $fld[0];
                                                echo "<tr>"
                                                . "<td>" . $srno++ . "</td>"
                                                . "<td>" . $fld[$name[1]] . "</td>"
                                                . "<td>" . $fld['label_desc_' . $lang] . "</td>"
                                                . "<td align=center>"
                                                . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "btn btn-default", 'onclick' => "javascript: return formupdate('" . $fld[$name[0]] . "','" . $fld[$name[1]] . "','" . $fld[$name[2]] . "');"))
                                                . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('class' => "btn btn-default", 'onclick' => "javascript: return formdelete('" . $fld[$name[0]] . "');"))
                                                . "</td>"
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
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
