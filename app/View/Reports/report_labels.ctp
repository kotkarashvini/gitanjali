<?php //------------------------------updated on 14-June-2017 by Shridhar-------------                                                                                     ?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<script type="text/javascript">
    $(document).ready(function () {
//--------------------------------------------------------------------------------------------------------------------------------------
        $('#table_report_labels').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        $('#btnnew').hide();
        //-------------------------------------------------------------------------------------------------------------------------
        $('#btnadd').click(function () {
            if ($('#report_labels')[0].checkValidity()) {
                $('#report_labels').submit();
            }
            else {
                alert('fill all mandatory info');
                return false;
            }
        });
    });

    //------------------------------------------------------------------------------------------------------
    var host = "<?php echo $this->webroot; ?>";
    //------------------------------------------------------------------------------------------------------------
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'label_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>id, report_id) {
        $('#hfid').val(id);
        $('#report_id').val(report_id);
<?php
foreach ($languagelist as $langcode) {
    ?>
            $('#label_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(label_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
        $('#btnadd').html('Save');
        $('#btnnew').show();

        return false;
    }
    //-------------------------------------------------------------------------------------------------------------
    function formReset() {
        $('#report_labels')[0].reset();
        $('#hfid').val(null);
        return false;
    }
</script>

<?php echo $this->Form->create('report_labels', array('id' => 'report_labels', 'autocomplete' => 'off')); ?>
<?php // pr($languagelist);exit;?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreport') . ' ' . __('lblLabel'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row headcode">
                    <div class="form-group">
                        <label for="Report Label Description" class="col-sm-2 control-label"><?php echo __('lblreport') . ' ' . __('lblname'); ?></label>
                        <div class="min col-sm-2" >
                            <?php echo $this->Form->input('report_id', array('label' => false, 'id' => 'report_id', 'empty' => '--Select Report--', 'options' => $reports, 'class' => 'form-control input-sm')); ?>                                                                                                                               
                            <span id="report_id_error" class="form-error"><?php echo $errarr['report_id_error']; ?></span>
                        </div>
                    </div> 
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row" >
                    <div class="form-group">
                        <label for="Report Label Description" class="col-sm-2 control-label"><?php echo __('lbllabeldescription'); ?><span style="color: #ff0000">*</span></label>
                        <?php
                        $i = 1;
                        foreach ($languagelist as $language1) {

                            if ($i % 6 == 0) {
                                echo "<div class=row>";
                            }
                            ?>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('label_desc_' . $language1['mainlanguage']['language_code'] . '', array('label' => false, 'id' => 'label_desc_' . $language1['mainlanguage']['language_code'] . '', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $language1['mainlanguage']['language_name'], 'maxlength' => "100")) ?>
                                <span id="<?php echo 'label_desc_' . $language1['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['label_desc_' . $language1['mainlanguage']['language_code'] . '_error']; ?>
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
                <div  class="rowht"></div> <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group center">
                        <div class="hidden">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken")));
                            echo $this->Form->input('label', array('id' => 'hfid', 'type' => 'hidden'));
                            ?>

                        </div>

                        <button id="btnadd" type="submit" name="btnadd" class="btn btn-info "    onclick="javascript: return formadd();">
                            <?php echo __('lblbtnAdd'); ?></button> &nbsp;&nbsp;
                        <button id="btnnew" class="btn btn-info "    onclick="javascript: return formReset();">
                            <?php echo __('lblnewentry'); ?></button>
                    </div>
                </div>

            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body" id="divfees_items">

                <table id="table_report_labels" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr> 
                            <th class="center width10"><?php echo __('lblid'); ?></th>
                            <th class="center"><?php echo __('lblreport') . ' ' . __('lblname'); ?></th>
                            <th class="center"><?php echo __('lbllabeldescription') . ' ' . '[English]'; ?></th>
                            <th class="center"><?php echo __('lbllabeldescription') . ' ' . '[Local]'; ?></th>
                            <th class="width10 center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php foreach ($reportsLabels as $rptLabel) { ?>
                        <tr id="<?php echo $rptLabel['ReportLabel']['label_id']; ?>">
                            <td class="tblbigdata"><?php echo $rptLabel['ReportLabel']['label_id']; ?></td>
                            <td class="tblbigdata"><?php echo $rptLabel['report']['report_name_' . $lang]; ?></td>
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <td ><?php echo $rptLabel['ReportLabel']['label_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>

                            <?php } ?>
                            <td class="tblbigdata" width='10%'>
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                <?php foreach ($languagelist as $langcode) { ?>
                                                        ('<?php echo $rptLabel['ReportLabel']['label_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                <?php } ?>
                                                    ('<?php echo base64_encode($rptLabel['ReportLabel']['label_id']); ?>'),
                                                            ('<?php echo $rptLabel['ReportLabel']['report_id']; ?>')
                                                            );">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                                <?php // echo $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('title' => 'Delete', 'type' => 'button', 'onclick' => "javascript: return removeFeeItem('" . (base64_encode($rptLabel['ReportLabel']['label_id'])) . "')")); ?>

                            </td>
                        </tr>
                        <?php }
                    ?>
                </table>                        
            </div>
        </div>  
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
