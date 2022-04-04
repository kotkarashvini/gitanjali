<script>

    $(document).ready(function () {
        $('#tabledocument').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });
</script>
<?php $doc_lang = $this->Session->read('doc_lang'); ?>


<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <?php
        if (isset($this->request->data['document_number'])) {

            echo $this->Form->create('document_number', array('url' => array('controller' => 'Registration', 'action' => 'document_number', $this->request->data['document_number']['format_field_id']), 'id' => 'document_number', 'autocomplete' => 'off'));
            ?>       
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbldocrno'); ?></h3></center>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Registration/document_number_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="format_field" class=" col-sm-3 control-label"><?php echo __('lblformatfield'); ?>:<span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('format_field', array('label' => false, 'id' => 'format_field', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                <span class="form-error" id="format_field_error"></span>
                            </div>
                            <label for="format_field_desc" class="col-sm-3 control-label"><?php echo __('lblformatfielddesc'); ?> :<span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('format_field_desc', array('label' => false, 'id' => 'format_field_desc', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                <span class="form-error" id="format_field_desc_error"></span>
                            </div>
                        </div>
                    </div>   

                    <div  class="rowht"></div><div  class="rowht"></div>
                    <?php if ($this->request->data['document_number']['is_static'] == 'N') { ?>
                        <div class="row">
                            <div class="form-group">
                                <label for="display_order" class=" col-sm-3 control-label"><?php echo __('lblDisplayOrder'); ?>:<span style="color: #ff0000">*</span></label> 
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('display_order', array('label' => false, 'id' => 'display_order', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span class="form-error" id="display_order_error"></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht"></div><div  class="rowht"></div>
                    <?php } ?>

                    <div class="row">
                        <div class="form-group">
                            <label for="format_field_flag" class="col-sm-3"><?php echo __('lblruwanttouse'); ?>:-<span style="color: #ff0000">*</span></label>            
                            <div class="col-sm-2"> 
                                <?php
                                $options2 = array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                    'N' => '&nbsp;No',
                                );
                                $attributes2 = array(
                                    'legend' => false,
                                    'value' => $this->request->data['document_number']['format_field_flag'],
                                );
                                echo $this->Form->radio('format_field_flag', $options2, $attributes2);
                                echo $this->Form->input('format_field_id', array('label' => false, 'id' => 'format_field_id', 'class' => 'form-control input-sm', 'type' => 'hidden'))
                                ?>
                                <span class="form-error" id="format_field_flag_error"></span>
                            </div>   

                            <?php if ($this->request->data['document_number']['is_static'] == 'Y') { ?>

                                <div id="static_value" >
                                    <label for="static_value" class="col-sm-3"><?php echo __('lblseparater'); ?><span style="color: #ff0000">*</span></label>            
                                    <div class="col-sm-2"> 
                                        <?php echo $this->Form->input('static_value', array('label' => false, 'id' => 'static_value', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span class="form-error" id="static_value_error"></span>

                                    </div> 
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="form-group">
                            <button id="btnadd" type="submit" name="btnadd" class="btn btn-info " >
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnupdate'); ?>
                            </button>
                            <button id="btncancel" name="btncancel" class="btn btn-info " style="text-align: center;" onclick="javascript: return formcancel();">
                                <?php echo __('btncancel'); ?>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            echo $this->Form->end();
        }
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbldocrno'); ?></h3></center>
            </div>

            <div class="panel-body">
                <table id="tabledocument" class="table table-striped table-bordered table-hover">  
                    <thead> 
                        <tr>  
                            <th class="center width10"><?php echo __('lblformatfield'); ?></th>
                            <th class="center width10"><?php echo __('lblformatfielddesc'); ?></th>
                            <th class="center width10"><?php echo __('lblDisplayOrder'); ?></th>
                            <th class="center width10"><?php echo __('lblusedstatus'); ?></th>
                            <th class="center width10"><?php echo __('lblseparater'); ?></th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($docrecord as $docrecord1): ?>
                            <tr>
                                <td ><?php echo $docrecord1['DocumentNumber']['format_field']; ?></td>
                                <td ><?php echo $docrecord1['DocumentNumber']['format_field_desc']; ?></td>
                                <td ><?php echo $docrecord1['DocumentNumber']['display_order']; ?></td>
                                <td ><?php echo $docrecord1['DocumentNumber']['format_field_flag']; ?></td>
                                <td ><?php echo $docrecord1['DocumentNumber']['static_value']; ?></td>

                                <td style="text-align: center;">                                       
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-edit')), array('action' => 'document_number', $docrecord1['DocumentNumber']['format_field_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('lblbtnedit'), 'class' => "btn btn-default"), array('Are you sure to edit?')); ?></a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <?php unset($docrecord1); ?>
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>