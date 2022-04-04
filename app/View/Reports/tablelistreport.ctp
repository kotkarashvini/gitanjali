

<?php
echo $this->Html->script('jquery.dataTables.min');
?>
<script>
    $(document).ready(function () {
        $('#tblLabel').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
        });

        $("#tblname").hide();
        $("#tablename").show();
        $("#htablename").prop('disabled', false);

        $("#btnSave").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var action = $("#hdnAction").val();
            if (action != 'U') {
                $("#hdnAction").val('SV');
                $("#htablename").val($("#tablename option:selected").text());
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
            $("#tblname").hide();
            $("#tablename").show();
            $("#htablename").prop('disabled', false);
        });

        $("#btnExit").click(function () {
            $("#hdnAction").val('E');
            $("#formID").submit();
        });

    });


    function formupdate(fldid, fldname, descen, descll) {
        $("#hdnLabelId").val(fldid);
        $("#tblname").html(fldname);
        $("#tabledescen").val(descen);
        $("#tabledescll").val(descll);
        $("#tblname").show();
        $("#tablename").hide();
        $("#htablename").prop('disabled', true);
        $("#hdnAction").val('U');
        return false;
    }
    function formdelete(fldid) {
        $("#hdnLabelId").val(fldid);
        $("#hdnAction").val('D');
        $("#formID").submit();
    }

</script>

<?php echo $this->Form->create('tableaddpdf', array('autocomplete' => 'off', 'id' => 'formID')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbltablelist'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="tablename" class="col-sm-3 control-label"><?php echo __('lbltablename'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3"><?php
                            echo $this->Form->input('', array('id' => 'tablename', 'label' => false, 'type' => 'select', 'empty' => '--select--', 'options' => $tlistap, 'class' => 'form-control input-sm'));
                            echo $this->Form->input($name[1], array('type' => 'hidden', 'id' => 'htablename'));
                            ?>
                            <label for="label name" class="col-sm-3" id="tblname"> </label>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="tablename" class="col-sm-3 control-label"><?php echo __('lbltabledescen'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3"><?php echo $this->Form->input($name[2], array('id' => 'tabledescen', 'label' => false, 'type' => 'text', 'class' => 'form-control input-sm', 'required' => true)); ?></div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="tablename" class="col-sm-3 control-label"><?php echo __('lbltabledescll'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3"><?php echo $this->Form->input($name[3], array('id' => 'tabledescll', 'label' => false, 'type' => 'text', 'class' => 'form-control input-sm', 'required' => true)); ?></div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group" style="text-align: center">
                        <div class="col-sm-12" style="text-align: center">
                            <input type="button" value="<?php echo __('btnsave'); ?>" id="btnSave"  class="btn btn-primary">
                            <input type="reset" value="<?php echo __('btncancel'); ?>" id="btnCancel" class="btn btn-primary">
                            <input type="button" value="<?php echo __('lblexit'); ?>" id="btnExit" class="btn btn-primary">
                            <input type="hidden" id="hdnLabelId" name="<?php echo $name[0]; ?>">
                            <input type="hidden" id="hdnAction" name="frmaction"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="table-responsive">
                    <table  id="tblLabel" class="table table-striped table-bordered table-hover">
                        <thead >                    
                            <?php
                            echo "<b><tr>"
                            . "<td align = center width = 10%>" . __('lblsrno') . "</td>"
                            . "<td align = center>" . __('lbltabledescen') . "</td>"
                            . "<td align = center>" . __('lbltabledescll') . "</td>"
                            . "<td align = center width = 10%>" . __('lblaction') . "</td>"
                            . "</tr></b>";
                            ?>
                        </thead>
                        <tbody>

                            <?php
                            $srno = 1;
                            foreach ($pdftables as $fld):
                                $fld = $fld['tablenamepdf'];
                                echo "<tr>"
                                . "<td align = center>" . $srno++ . "</td>"
                                . "<td>" . $fld[$name[2]] . "</td>"
                                . "<td>" . $fld[$name[3]] . "</td>"
                                . "<td align = center>"
                                . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "btn btn-default", 'onclick' => "javascript: return formupdate('" . $fld[$name[0]] . "', '" . $fld[$name[1]] . "', '" . $fld[$name[2]] . "', '" . $fld[$name[3]] . "'); "))
                                . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('class' => "btn btn-default", 'onclick' => "javascript: return formdelete('" . $fld[$name[0]] . "'); "))
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


<?php echo $this->form->end(); ?>


