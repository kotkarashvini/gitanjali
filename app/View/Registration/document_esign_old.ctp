<?php
echo $this->element("Registration/main_menu");
 
?>

<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create('document_esign', array('id' => 'document_esign')); ?>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
             
            <div class="panel-body"> 
                <div class="panel with-nav-tabs panel-danger">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1primary" data-toggle="tab"><?php echo __('lbldocumentesign'); ?></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1primary">
                                <div class="col-md-6">
                                    <div class="well">
                                        <div class="pad">
                                            <div class="pad">
                                                <div class="form-group"> 
                                                    <?php
                                                    echo $this->Form->input('consent', array(
                                                        'type' => 'checkbox',
                                                        'value'=>'Y',
                                                        'id'=>'consent',                                                       
                                                        'label'=>__('lblesignconsent'),
                                                        'format' => array('before', 'input', 'between', 'label', 'after', 'error')
                                                    ));
                                                    ?>
                                                </div>
                                                <div class="form-group"> 
                                                    <?php echo $this->Form->submit(__('btnrequestotp'), array('name'=>'otprequest',  'label' => false, 'div' => false, 'id' => 'otprequest', 'Class' => 'btn btn-default btn-sm', 'type' => 'submit')); ?>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="well">
                                        <div class="form-group">
                                            <label><?php echo __('lblenterotp'); ?></label>
                                            <?php echo $this->Form->input('otp', array('label' => false, 'div' => false, 'id' => 'otp', 'Class' => 'form-control', 'type' => 'text')); ?>
                                        </div>
                                        <div class="form-group"> 

                                            <?php echo $this->Form->submit(__('btnesign'), array('label' => false, 'id' => 'csrftoken', 'Class' => 'btn btn-primary btn-sm', 'type' => 'submit', 'name' => 'esignrequest')); ?>
                                        </div>

                                    </div>

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
