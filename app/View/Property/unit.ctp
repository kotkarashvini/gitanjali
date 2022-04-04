<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Update');
    }
    if ($('#hfhidden1').val() === 'Y')
    {
    $('#tableunit').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }



    $('#unitType1').click(function(){
    $('#conversiondiv').hide();
    });
            $('#unitType2').click(function(){
    $('#conversiondiv').show();
    });
    });</script>

<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    // This language list consist of code and name of language and we just concate it with construction_type_desc which is field name from database.Means construction_type_desc_en,or ll,or ll1,or ll2,or ll3..
    ?>
    <?php echo 'unit_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id, remark, standard_units, conversion_formula) {
            var r=confirm("Are you sure to edit");
if(r==true){
    document.getElementById("actiontype").value = '1';
           
            //dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php

foreach ($languagelist as $langcode) {
    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
    ?>
        $('#unit_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(unit_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>),
<?php } ?>
    $('#remark').val(remark);
         
            if (!$.isNumeric(standard_units)){
    $('#unitType1').click();
            $('#conversiondiv').hide();
    }
    $('#standard_units').val(standard_units);
            $('#conversion_formula').val(conversion_formula);
            $('#hfupdateflag').val('Y');
            $('#hfid').val(id);
            $('#btnadd').html('Update');
            return false;
    }
    }
</script> 

<?php echo $this->Form->create('unit', array('id' => 'unit', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-md-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblunit'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/unit_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblunitdescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('unit_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'unit_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'unit_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['unit_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remark" class="control-label"><?php echo __('Is Standered Unit'); ?><span style="color: #ff0000">*</span></label> 
                            <?php
                            $options = array(
                                '1' => 'Yes',
                                '2' => 'No'
                            );

                            $attributes = array(
                                'legend' => false,
                                'style' => 'margin:10px;',
                                'default' => 2
                            );

                            echo $this->Form->radio('type', $options, $attributes);
                            ?> 
                        </div>

                    </div> 
                </div>


                <div  class="row" id="conversiondiv">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="standard_units" class="control-label"><?php echo __('standard_units'); ?>:<span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('standard_units', array('label' => false, 'id' => 'standard_units', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $unitdata))); ?>
                            <span id="standard_units_error" class="form-error"><?php echo $errarr['standard_units_error']; ?></span>

                        </div>
                        <div class="form-group">
                            <label for="conversion_formula" class="control-label"><?php echo __('lblconversionformula'); ?>:<span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('conversion_formula', array('label' => false, 'id' => 'conversion_formula', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '12')) ?>
                            <span id="conversion_formula_error" class="form-error"><?php echo $errarr['conversion_formula_error']; ?></span>
                        </div>
                    </div>
                </div>



                <div class="row">             
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="remark" class="control-label"><?php echo __('lblremark'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>

                        </div>
                    </div>                    
                </div>

                <div class="row">  
                    <div class="col-md-4 col-md-offset-1">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                                
                            </button>
                             <button id="btnadd" name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                                
                                <a href="<?php echo $this->webroot; ?>Property/unit" class="glyphicon glyphicon-plus" ><?php echo __('btncancel'); ?></a>
                            </button>
                        </div>
                    </div>
                </div>





            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <table id="tableunit" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <?php
//creating dyanamic table header using same array of config language
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblunitdescription') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>
                            <th class="center"><?php echo __('lblremark'); ?></th>
                            <th class="center"><?php echo __('lblconversionformula'); ?></th>
                            <th class="center width10"><?php echo __('standard_units'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($unitrecord as $unitrecord1): ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $unitrecord1['unit']['unit_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                                <td ><?php echo $unitrecord1['unit']['remark']; ?></td>
                                <td ><?php echo $unitrecord1['unit']['conversion_formula']; ?></td>
                                <td ><?php echo $unitrecord1['stdunit']['unit_desc_' . $laug]; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-success "   onclick="javascript: return formupdate(
                                    <?php
                                    //  creating dyanamic parameters  using same array of config language for sending to update function
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                                    ('<?php echo $unitrecord1['unit']['unit_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                ('<?php echo $unitrecord1['unit']['unit_id']; ?>'),
                                                        ('<?php echo $unitrecord1['unit']['remark']; ?>'),
                                                        ('<?php echo $unitrecord1['unit']['standard_units']; ?>'),
                                                        ('<?php echo $unitrecord1['unit']['conversion_formula']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'unit_delete', $unitrecord1['unit']['unit_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($unitrecord1); ?>
                    </tbody>
                </table> 
                <?php if (!empty($unitrecord)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




