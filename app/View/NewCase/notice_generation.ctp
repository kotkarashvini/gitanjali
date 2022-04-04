<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>

<script>
    $(document).ready(function () {
        $('#notice_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#first_hearing_date').Zebra_DatePicker({
            view: 'years'
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(notice_date, first_hearing_date, place_of_hearing,stamp_duty_revised, remark, notice_gen_id) {
        document.getElementById("actiontype").value = '1';
        $('#notice_date').val(notice_date);
        $('#first_hearing_date').val(first_hearing_date);
        $('#place_of_hearing').val(place_of_hearing);
        $('#stamp_duty_revised').val(stamp_duty_revised);
        $('#remark').val(remark);
        $('#hfid').val(notice_gen_id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>  
<?php echo $this->Form->create('notice_generation', array('url' => array('controller' => 'NewCase', 'action' => 'notice_generation'), 'id' => 'notice_generation', 'autocomplete' => 'off')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder">Notice Details</h3></center>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Case Number:-<span style="color: #ff0000"></span></label>
                    <div class="col-sm-2">
                        <b><?php echo $case_code_id; ?></b>
                    </div>
                </div>
            </div>
            <!--<br>-->
            <div class="row">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>
                    <div class="col-sm-2">
                        <b><?php echo $ccms_case; ?></b>
                    </div>
                </div>
            </div>

            <!--            <div class="row">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Selected Case:-<span style="color: #ff0000"></span></label>    
                                <div class="col-sm-2">
            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                </div>
                                <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>    
                                <div class="col-sm-2">
            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_code, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                </div>
                                <label for="" class="col-sm-2 control-label">Case Year:-<span style="color: #ff0000"></span></label>    
                                <div class="col-sm-2">
            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_year, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                </div>
                            </div>
                        </div>-->

        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="notice_date" class="col-sm-2 0control-label">Notice Date <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('notice_date', array('label' => false, 'id' => 'notice_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="notice_date_error" class="form-error"><?php echo $errarr['notice_date_error']; ?></span>
                        </div>
                        <label for="first_hearing_date" class="col-sm-2 0control-label">Hearing Date<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('first_hearing_date', array('label' => false, 'id' => 'first_hearing_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="first_hearing_date_error" class="form-error"><?php echo $errarr['first_hearing_date_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="remark" class="col-sm-2 0control-label">Place of Hearing <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('place_of_hearing', array('label' => false, 'id' => 'place_of_hearing', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $office))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="place_of_hearing_error" class="form-error"><?php echo $errarr['place_of_hearing_error']; ?></span>
                        </div>  

                        <label for="first_hearing_date" class="col-sm-2 0control-label">Stamp Duty Revised (Rs.)<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('stamp_duty_revised', array('label' => false, 'id' => 'stamp_duty_revised', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="stamp_duty_revised_error" class="form-error"><?php echo $errarr['stamp_duty_revised_error']; ?></span>
                        </div> 

                    </div>

                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">

                        <label for="remark" class="col-sm-2 0control-label">Remark <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'type' => 'textarea', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>
                        </div>
                    </div>
                </div>

                <?php // echo $this->Form->input('case_status_id', array('label' => false, 'id' => 'case_status_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $case_status))); ?>
<!--<span id="case_status_id_error" class="form-error"><?php // echo $errarr['case_status_id_error'];                      ?></span>-->
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    <button type="reset" id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                    <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('Next'); ?>
                    </button>
                </div>  
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tableproceeding" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  
                            <th class="center">Notice  Date</th>
                            <th class="center">Hearing Date </th>
                            <th class="center">Place of Hearing</th>
                            <th class="center">Remark</th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tr>
                        <?php
                        //  $datelist = date('Y-m-d');
                        foreach ($noticerecord as $noticerecord1):
                            ?>
                            <td class="tblbigdata"><?php
                                $date = date_create($noticerecord1[0]['notice_date']);
                                echo date_format($date, "d/m/Y");
//                            echo $noticerecord1[0]['notice_date'] 
                                ?></td>
    <!--                            <td class="tblbigdata"><?php echo $noticerecord1[0]['first_hearing_date'] ?></td>-->
                            <td class="tblbigdata"><?php
//                                if ($noticerecord1[0]['first_hearing_date'] == $datelist) {
//                                    echo "ON Board";
//                                } else {
//                           $date=$noticerecord1[0]['first_hearing_date'];
//                           echo $date;
// $newDate = date("d-m-Y",$date);
//echo $newDate;

                                $date = date_create($noticerecord1[0]['first_hearing_date']);
                                echo date_format($date, "d/m/Y");
//                                    echo $noticerecord1[0]['first_hearing_date'];
//                                }
                                ?></td>
                            <td class="tblbigdata"><?php echo $noticerecord1[0]['office_name_en']; ?></td>
                            <td class="tblbigdata"><?php echo $noticerecord1[0]['remark']; ?></td>
                            <td>
                                <?php
                                $newid = $this->requestAction(
                                        array('controller' => 'NewCase', 'action' => 'encrypt', $noticerecord1[0]['notice_gen_id'], $this->Session->read("randamkey"),
                                ));
                                ?>

                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $noticerecord1[0]['notice_date']; ?>'),
                                                    ('<?php echo $noticerecord1[0]['first_hearing_date']; ?>'),
                                                    ('<?php echo $noticerecord1[0]['place_of_hearing']; ?>'),
                                             ('<?php echo $noticerecord1[0]['stamp_duty_revised']; ?>'),
                                                    ('<?php echo $noticerecord1[0]['remark']; ?>'),
                                                    ('<?php echo $noticerecord1[0]['notice_gen_id']; ?>')
                                                    );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>


                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'notice_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>

                                <!--                                <button id="formprint" name="formprint" class="btn btn-info " onclick="javascript: return formprint();">
                                <?php //echo __('lblPrint');  ?></button> -->

                                <!--<button type="btnprint"  id="btnprint" name="btnprint" class="btn btn-info">Print Notice</button>-->
                                <?php // echo $this->html->link('Print Notice',array('controller' => 'NewCase', 'action' => 'printnotice')) ?>
                                <?php //echo $this->Html->link('View', array('controller' => 'NewCase', 'action' => 'sample', $noticerecord1[0]['notice_gen_id'])); ?>
                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
                    <?php unset($noticerecord1); ?>
                </table> 
            </div>

        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>

    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




