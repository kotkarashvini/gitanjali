<script type="text/javascript">
    $(document).ready(function () {
        $('#doc_reg_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d',
            format: "dd-mm-yyyy"
        });
        /* $('input[type=radio][name=docno]').click(function () {
         //$("#searchform").submit();
         
         });*/
        $('#btnNext1').click(function () {
            var docregno = $('#doc_reg_no').val();
            //alert(docregno);
            var docregdate = $('#doc_reg_date').val();
            //var docsearchtype= $('#doc_search option:selected').val();
            var docsearchtype = $('input[name=docno]:checked').val();
            //$('input[type=radio][name=docno]').val();
            //alert(docsearchtype);
            //alert(docregdate);
            //$("#searchform").submit();
            //var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            //alert(csrftoken);
            $.post('<?php echo $this->webroot; ?>SearchModule/srchrec', {office_id: $('#office_id option:selected').val(), docregno: docregno, docregdate: docregdate, docsearchtype: docsearchtype}, function (data) {

                //alert(data);
                $('#depfd').empty();
                $("#depfd").append(data);
                /*var obj = jQuery.parseJSON( data );
                 
                 var office_id_disp=obj.office_id;
                 var docregno_disp=obj.docregno;
                 var docregdate_disp=obj.docregdate;
                 var office_name=obj.office_name;
                 var articlenm=obj.articlenm;
                 var amt_paid=obj.amt_paid;
                 
                 
                 var sc ='<div class="box-body"><div class="table-responsive" id="SDCalcDetail" style="height:35vh; "><style>td{padding:2px 10px 2px 10px;}</style><table border="1" width="100%" align="center" style="background-color:#F0F0F0;">		<tbody><tr style="background-color: #72AFD2; color: white;"><td ><b>Document Registration Number : '+docregno_disp+' </b></td><td><b>Document Registration Date : '+docregdate_disp+' </b></td>		<td ><b>Office : '+office_name+' </b></td></tr></table><table border="1" width="100%" align="center" style="background-color:#F0F0F0;"><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Article</b></td><td align="center" width="25%">'+articlenm+'</td>	<td align="center" width="25%"><b>Fee</b></td><td align="center" width="25%">'+amt_paid+'</td></tr><tr style="background-color: #F1F0FF;"><td align="center" width="25%"><b>Party Type</b></td><td align="center" width="25%">&nbsp;</td><td align="center" width="25%"><b>Party Name</b></td><td align="center" width="25%">&nbsp;</td></tr></tbody></table></div></div>';
                 
                 $("#depfd").append(sc);*/
            });
        });

    });

    /*
     function getdata() {
     var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
     $.post('<?php echo $this->webroot; ?>Citizenentry/getdependent_article', {article_id: $('#article_id option:selected').val(), csrftoken: csrftoken}, function (data) {
     $("#depfd").html(data);
     $(document).trigger('_page_ready');
     });
     }
     */
</script>
<?php
echo $this->Form->create('searchform', array('id' => 'searchform', 'autocomplete' => 'off'));

$laug = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo 'Search'; ?></h3></center>
                <div class="box-tools pull-right">
                                        <!--<a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/genernal_info_en.html" class="btn btn-small btn-info pull-right" target="_blank"> <?php echo __('Help??'); ?> </a>-->
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="row" >
                        <div class="form-group">
                            <label for="fee_item_desc_en" class="col-sm-4 control-label"><?php echo 'Select Database : '; ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('docno', array('type' => 'radio', 'options' => array('O' => '&nbsp;&nbsp; Old &nbsp;&nbsp;', 'C' => '&nbsp;Current'), 'value' => 'O', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'docno', 'name' => 'docno')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" >
                    <center><h4 class="box-title headbolder">
                            Please Enter The Following Details  </h3></center>


                </div>
                <div><br></div><div><br></div>
                <div class="row" >
                    <div class="form-group">
                        <label for="fee_item_desc_en" class="col-sm-3 control-label"><?php echo 'Document Registration Number : '; ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'id' => 'doc_reg_no', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                         <span id="doc_reg_no_error" class="form-error"><?php echo $errarr['doc_reg_no_error']; ?></span>
                        
                        </div>

                        <label for="fee_item_desc_en" class="col-sm-3 control-label"><?php echo 'Document Registration Date : '; ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('doc_reg_date', array('label' => false, 'id' => 'doc_reg_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                            <span id="doc_reg_date_error" class="form-error"><?php //echo $errarr['doc_reg_date_error'];    ?></span>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    &nbsp;
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label for="fee_item_desc_en" class="col-sm-3 control-label"><?php echo 'Office : '; ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('options' => array(), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm chosen-select', 'options' => array($office), 'label' => false)); ?>
                         <span id="office_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>


                    </div>
                </div>
                <?php //echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

                <div><br></div>
                <div class="row" >
                    <div class="form-group">
                        <div class="row center" >
                            <input type="submit" id="btnNext" name="btnNext" class="btnsave" style="width:155px;" value="<?php echo 'Search'; ?>">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="box box-primary">
            <div class="box-body">
                <!--<div class="row" >
                       <center><h4 class="box-title headbolder">
                       Following are the details: </h3></center>
                </div>-->
                <div class="row" >
                    <div class="form-group">
                        <div id="depfd">
<?Php echo @$dispcolf; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>