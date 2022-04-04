<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<script>
    $(document).ready(function () {
        
         $("#token").hide();
        $("#bydate").hide();
        
       
        $('#from,#to,#curfrom,#curto').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
       


        $("#selectdocnoT").click(function () {
            $("#token").show();
            $("#bydate").hide();
            $("#actiontype").val('T');



        });

        $("#selectdocnoD").click(function () {
            $("#token").hide();
            $("#bydate").show();
            $("#actiontype").val('D');
        });

    });
    function summery1(token_no) {

        $('#rpt_modal_body').html("<?php echo $this->webroot; ?>viewRegSummary1/" + Base64.encode(token_no) + "/I");
        $('#myModal_rpt').modal("show");

        window.scrollTo(500, 200);
        return false;

    }
</script>
<?php echo $this->Form->create('doc_search', array('id' => 'doc_search', 'autocomplete' => 'off'));
 $language = $this->Session->read("sess_langauge");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocsearch'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/document_search_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="docno" class="control-label col-sm-2"><?php echo __('lblselsearchtype'); ?></label>            
                        <div class="col-sm-6"> 
                            <?php echo $this->Form->input('selectdocno', array('type' => 'radio', 'options' => array('T' => '&nbsp;&nbsp;Search By Document Registred Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'D' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search By Date&nbsp;&nbsp;'), 'value' => 'Document Registred No', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'selectdocno')); ?>
                            <span id="selectdocno_error" class="form-error"><?php //echo $errarr['selectdocno_error'];           ?></span>
                        </div> 
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row" id="bydate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];           ?></span>
                        </div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                            <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];           ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row" id="token">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblenterdocregno'); ?></label>    
                        <div class="col-sm-3" ><?php echo $this->Form->input('reg_no', array('label' => false, 'id' => 'reg_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                        <span id="reg_no_error" class="form-error"><?php //echo $errarr['reg_no_error'];           ?></span>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center" >
                    <div class="col-sm-12" >
                        <button   id="viewDetails" name="viewDetails" class="btn btn-info"><?php echo __('lblviewdetails'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($documentrecord)) { ?>

    <!--    <div class="box box-primary">
            <div class="form-group">-->
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">

            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                <thead>  
                    <tr> 
                        <th class="center"><?php echo __('lblsrno'); ?></th>
                        <th class="center"><?php echo __('lblregno'); ?></th>
                        <th class="center"><?php echo __('lblarticlename'); ?></th>
                        <th class="center"><?php echo __('lblsummery1'); ?></th>
                        <th class="center"><?php echo __('lblsummery2'); ?></th>

                    </tr>  
                </thead>
                <?php
                $i = 1;
                foreach ($documentrecord as $rec) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rec[0]['doc_reg_no']; ?></td>
                        <td><?php echo $rec[0]['article_desc_'.$language]; ?></td>
                        <td>  <a type="button" href="<?php echo $this->webroot; ?>viewRegSummary1/<?php echo base64_encode($rec[0]['token_no']); ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt">View Summery 1</a></td>
                        <td><a type="button" href="<?php echo $this->webroot; ?>viewRegSummary2/<?php echo base64_encode($rec[0]['token_no']); ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt2">View Summery 2</a></td>

                    </tr>
    <?php } ?>


            </table> 
        </div>

    </div>
    <!--        </div>
        </div>-->
<?php } ?>

<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfsetradio; ?>' name='hfsetradio' id='hfsetradio'/>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>



<div id="myModal_rpt" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblsummery'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>

<div id="myModal_rpt2" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblsummery'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>
