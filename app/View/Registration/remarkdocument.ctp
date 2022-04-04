<?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">

    $(document).ready(function () {

     
    });

</script>

<div class="row">
    <div class="col-lg-12">
        <?php echo $this->Form->create('remarkdocument', array('url' => array('controller' => 'Registration', 'action' => 'remarkdocument'))); ?>   

        <div class="box box-primary"> 

            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocumentcancellation'); ?></h3></center>
            </div> 
            <div class="box-body">
                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
                        
                        <div class="row">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lbltokenno'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('token_no', array('label' => false, 'id' => 'token_no', 'class' => 'form-control input-sm', 'type' => 'text','value' => $token,'readonly')) ?>                         
                                </div>
                            </div> 
                        </div>
                        <div class="rowht"></div>
                        <div class="row">
                            <div class="col-md-12" id="paymentmode_selection_div">
                                <label for="" class="col-sm-3 control-label"><?php echo __('lblremark'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-3"> 
                                    <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'class' => 'form-control input-sm', 'type' => 'text')) ?>                         
                                 <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>
                                </div>
                            </div> 
                        </div>
                        
                        
                        <div class="col-md-12" id="paydetails"></div> 
                        <br>
                       
                        <div class="row center">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button id="btnadd" type="submit" name="btnadd" class="btn btn-info ">

                                        <span class="glyphicon glyphicon-plus"></span> <?php echo __('btnsave'); ?>

                                    </button>
<!--                                    <button id="btncancel" name="btncancel" class="btn btn-info " type="reset">
                                        <span class="glyphicon glyphicon-reset"><?php ?></span><?php // echo __('lblreset'); ?>
                                    </button>-->

                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>

        </div>

        <?php echo $this->Form->end(); ?>          
     


    </div> 

</div>


