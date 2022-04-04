<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        $('#tblfyear').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });

    function btnupdate1(id, desc, curr_flag) {
        $('#fin_id').val(id);
        $('#finyear_desc').val(desc);
        $('input:radio[name="data[year_initialization][current_year]"][value=' + curr_flag + ']').prop('checked', true);
    }
</script>


<?php echo $this->Form->create('year_initialization', array('id' => 'year_initialization', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblyearinitialization'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/year_initialization_<?php echo $lang;   ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">

                        <div class="col-md-2">
                            <label> <?php echo __('lblenteryear'); ?>   <span style="color: #ff0000">*</span></label> 
                        </div>
                        <div class="col-md-2">
                            <?php
                            echo $this->Form->input('finyear_desc', array('label' => false, 'id' => 'finyear_desc', 'class' => 'form-control input-sm',
                                'type' => 'text', 'maxlength' => '9'))
                            ?>
                            <span id="finyear_desc_error" class="form-error"><?php echo $errarr['finyear_desc_error']; ?></span>
                        </div>
                        <div class="col-md-2">
                            <label> <?php echo __('lblcurrentyear'); ?> <span style="color: #ff0000">*</span> </label> 

                        </div>
                        <div class="col-sm-2"> 
                            <?php echo $this->Form->input('current_year', array('type' => 'radio', 'options' => array('Y' => '&nbsp;' . __('Yes') . '&nbsp;&nbsp;', 'N' => '&nbsp;' . __('No') . '&nbsp;&nbsp;&nbsp;'), 'value' => 'N', 'legend' => false, 'div' => false, 'id' => 'current_year')); ?></div>  

                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        <?php echo $this->Form->input('fin_id', array('label' => false, 'id' => 'fin_id', 'type' => 'hidden', 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button  id="btncancel" name="btncancel" class="btn btn-info" onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>

        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tblfyear" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th><?php echo __('lblenteryear'); ?></th>
                                <th><?php echo __('lblcurrentyear'); ?></th>
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($year as $year1): ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $year1['finyear']['finyear_desc']; ?></td>
                                    <td style="text-align: center;"><?php echo $year1['finyear']['current_year']; ?></td>
                                    <td style="text-align: center;">
                                        <input type="button" id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" value="Edit" onclick="javascript: return btnupdate1('<?php echo $year1['finyear']['id'] ?>', '<?php echo $year1['finyear']['finyear_desc']; ?>', '<?php echo $year1['finyear']['current_year']; ?>');" />

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'year_delete', $year1['finyear']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($year1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($bankrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>