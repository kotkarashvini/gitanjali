<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script>
    $(document).ready(function () {
//        $('#docrecorddetails').hide();
        $('input[type=radio][name=docno]').click(function () {

            if ($(this).val() == 'D') {
                document.getElementById("actiontype").value = 'Docregsearch';
                document.getElementById("hfsetradio").value = 'Document Registred No';

                $("#divdocregno").show();
            }
        });

        if ($('#hfhidden1').val() == 'Y')
        {
            $('#docrecorddetails').show();
            $("#divdocregno").show();
//    $('#tabledoc').dataTable({
//    "iDisplayLength": 10,
//            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
//    });
        }
    });

</script>


<?php echo $this->Form->create('srosearch', array('id' => 'srosearch', 'autocomplete' => 'off')); ?>

<div class="row">

    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"> <?php echo __('lblsrosearch'); ?> </h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="docno" class="control-label col-sm-2"><?php echo __('lblselsearchtype'); ?> :</label>            
                        <div class="col-sm-6"> 
                            <?php echo $this->Form->input('docno', array('type' => 'radio', 'options' => array('D' => '&nbsp;&nbsp;Document Registred No&nbsp;&nbsp;'), 'value' => 'Document Registred No', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'docno', 'name' => 'docno')); ?>
                            <?php //echo $this->Form->input('docno', array('type' => 'radio', 'options' => array('D' => '&nbsp;&nbsp;Document Registred No&nbsp;&nbsp;', 'O' => '&nbsp;&nbsp;Office Search&nbsp;&nbsp;','N'=>'&nbsp;&nbsp;Name Search'), 'value' => 'Document Registred No', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'docno', 'name' => 'docno'));?>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="box-body" id="divdocregno" hidden="true">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-4"><label><?php echo __('lbldocregno'); ?> :

                            </label>    </div>
                        <div  class="col-sm-3">  <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'id' => 'doc_reg_no', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?></div>    
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>   
                        <span id="doc_reg_no_error" class="form-error"><?php //echo $errarr['doc_reg_no_error'];       ?></span>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnsearch" name="btnsearch" class="btn btn-info "  >
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblsearch'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="box box-primary"  >
            <div class="box-body"id="docrecorddetails" hidden="true">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lblrecdetails'); ?>  </h3></center>
                </div>
                <div id="selectdocument" class="table-responsive">
                    <table id="tabledoc" class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>  
                            <?PHP if ($documentrecord != NULL) { ?>
                                <tr>  
                                    <th class="center"><?php echo __('lbldocrno'); ?></th>
                                    <th class="center"><?php echo __('lblpartytype'); ?></th>
                                    <th class="center"><?php echo __('lblpartyname'); ?></th>
                                    <th class="center"><?php echo __('lblAddress'); ?></th>

                                </tr>  
                            </thead>
                            <tbody>
                                <?php foreach ($documentrecord as $documentrecord1): ?>
                                    <tr>
                                        <td><?php echo $documentrecord1['0']['doc_reg_no']; ?></td>
                                        <td><?php echo $documentrecord1['0']['party_type_desc_en']; ?></td>
                                        <td><?php echo $documentrecord1['0']['party_full_name_en']; ?></td>
                                        <td><?php echo $documentrecord1['0']['address_en']; ?></td>
                                    </tr>


                                <?php
                                endforeach;
                            }
                            ?>
                            <tr>
                                <td colspan="3"><?php //echo $this->Html->link('Download', array('controller' => 'Search', 'action' => 'document_download'));    ?></td>

                                <td><?php
                                    echo $this->Html->link(
                                            'Download', array(
                                        'controller' => 'Search', // controller name
                                        'action' => 'document_download', //action name
                                        base64_encode($documentrecord1['0']['doc_reg_no'])
                                            )
                                    );
                                    ?>
                                </td> 
                            </tr>

                        </tbody>
                    </table>
<?php if (!empty($documentrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>





            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfsetradio; ?>' name='hfsetradio' id='hfsetradio'/>
        <input type='hidden' value='<?php //echo $hfupdateflag;   ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Js->writeBuffer(); ?>




