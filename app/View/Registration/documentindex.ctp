<script>
    $(document).ready(function () {
        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,            
            startDate: '<?php echo $search_date_minus; ?>',
            endDate: '<?php echo $search_date_plus; ?>'
        });
    });
</script>

<?php
echo $this->element("Registration/main_menu");
?> 
<?php echo $this->Form->create('documentindex', array('id' => 'documentindex', 'class' => 'form-inline')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <div class="form-group">
                    <label   class="control-label"><?php echo __('Appointment Date'); ?></label>  
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'div' => false, 'placeholder' => 'From Date')); ?>
                </div>
                <div class="form-group">
                    <button id="go" class="btn btn-primary" type="submit" value="Show"> <?php echo __('Show'); ?> </button>
                </div>

            </div>


        </div> 
    </div> 

</div>
<?php $this->Form->end(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblnewlysubmittedtoken'); ?></h3></center>       
                <div class="box-tools pull-right">
                    <!--<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><?php echo __('lblstamphierarchy'); ?></button>-->
                </div>
            </div>

            <div class="box-body">

                <div class="table-responsive"> 
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lbldocktype'); ?></th>
                                <th><?php echo __('lblpresentername'); ?></th>
                                <th><?php echo __('lblappointment'); ?></th> 
                                <th><?php echo __('Slot number'); ?></th>
                                <?php if ($inspectionflag == 1) { ?>
                                    <th><?php echo __('lblinspection'); ?></th>
                                <?php } ?>
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            if (isset($alldocuments)) {
                                foreach ($alldocuments as $documents) {

                                    $status = $this->requestAction('/Registration/inspection_status/' . $documents[0]['token_no']);
                                    if ($status == 0) {
                                        $insp = __('lblpropertynotavailable');
                                    } elseif ($status == 1) {
                                        $insp = __('lblpending');
                                    } elseif ($status == 2) {
                                        $insp = __('lbldone');
                                    }
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo ++$counter; ?></th>
                                        <td><?php echo $documents[0]['token_no']; ?></td>
                                        <td><?php echo $documents[0]['article_desc_' . $doc_lang]; ?></td>
                                        <td><?php echo $documents[0]['party_full_name_' . $doc_lang]; ?></td>
                                        <td> <?php
                                            if ($documents[0]['appointment_id'] != NULL) {
                                                $date = date_create($documents[0]['appointment_date']);
                                                $appo = date_format($date, 'd M Y') . '  ' . $documents[0]['sheduled_time'];
                                            } else {
                                                $appo = __('lblnotavailable');
                                            }
                                            echo $appo
                                            ?></td> 
                                        <td><?php echo @$documents[0]['slot_no']; ?></td>
                                        <?php if ($inspectionflag == 1) { ?>
                                            <th><?php echo $insp; ?></th>
                                        <?php } ?>
                                        <td>
                                            <?php if ($status == 1 && $inspectionflag == 1) {
                                                ?>     <button type="button"  class="btn btn-warning disabled"><?php echo __('lblcheckin'); ?></button>

                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $this->webroot; ?>Registration/printgeneralinfo/<?php echo $documents[0]['token_no']; ?>" class="btn btn-warning" onclick="return confirm('Are You Sure To Check In ')"><?php echo __('lblcheckin'); ?></a>
                                            <?php } ?>  
                                        </td>
                                    </tr> 
                                <?php }
                            }
                            ?>
                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });

</script>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblstamphierarchy'); ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __('lblstamps'); ?></th>
                            <th><?php echo __('lblfunctions'); ?></th>
                            <th><?php echo __('lblflags'); ?></th>
                            <th><?php echo __('lblworkflow'); ?></th>
                            <th><?php echo __('lblrolename'); ?></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        $notice = "";
                        foreach ($stamp_conf as $stamp) {   //pr($stamp);
                            ?>
                            <tr class="bg-success">
                                <th><?php echo $stamp['stamp_desc']; ?></th>
                                <th></th>
                                <th><?php echo $stamp['stamp_flag']; ?></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php
                            if (isset($stamp['functions'])) {
                                foreach ($stamp['functions'] as $function) {
                                    ?>

                                    <tr>
                                        <th><?php echo $function['function_sr_no']; ?></th>
                                        <th><?php echo $function['function_title'] . "-" . $function['function_desc']; ?></th>
                                        <th><?php echo $function['function_flag']; ?></th>
                                        <th><?php echo $function['work_flow']; ?></th>
                                        <th><?php echo $function['role']; ?></th>
                                    </tr>

                                    <?php
                                }
                            } else {
                                $notice = "<br>Functions Not Avalable for " . $stamp['stamp_title'];
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <span class="form-error"> <?php
                    if (!empty($notice)) {
                        echo "Errors : " . $notice;
                    }
                    ?></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>