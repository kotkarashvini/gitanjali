<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {

//        if (document.getElementById('hfhidden1').value == 'Y') {
//            $('#divgrid').slideDown(1000);
//        }
//        else {
//            $('#divgrid').hide();
//        }
        $('#tablegrid').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $("#map_user_id").change(function () {
            document.getElementById("actiontype").value = '4';
            $('#conc_jurisdiction').submit();
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

//    function formdelete(id) {
//        var result = confirm("Are you sure you want to delete this record?");
//        if (result) {
//            document.getElementById("actiontype").value = '3';
//            $('#hfid').val(id);
//        } else {
//            return false;
//        }
//    }
</script>

<?php echo $this->Form->create('conc_jurisdiction', array('id' => 'conc_jurisdiction', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblconcurrentjuri'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/conc_jurisdiction_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="map_user_id " class="col-sm-2 control-label"><?php echo __('lblselectuser'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('map_user_id', array('label' => false, 'id' => 'map_user_id', 'class' => 'form-control input-sm', 'options' => array($user), 'empty' => '--Select--')); ?>
                            <span id="map_user_id_error" class="form-error"><?php echo $errarr['map_user_id_error']; ?></span>
                        </div>
                        <label for="officename " class="col-sm-2 control-label"><?php echo __('lbloffice'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('officename', array('label' => false, 'id' => 'officename', 'value' => $officename, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')); ?>
                        </div>
                        <label for="office_id " class="col-sm-2 control-label"><?php echo __('lblselectadditionalofc'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array($office), 'empty' => '--Select--')); ?>
                            <span id="office_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>
                    </div>
                </div><br>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group center">
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp; &nbsp;<?php echo __('lblbtnAdd'); ?></button>
                        <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">

                <table id="tablegrid" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblusername'); ?></th>
                            <th class="center"><?php echo __('lbladditionalofc'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($grid); $i++) { ?>
                        <tr>
                            <td ><?php echo $grid[$i][0]['username']; ?></td>
                            <td ><?php echo $grid[$i][0]['office_name_' . $laug]; ?></td>
                            <td >
                              
                              <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_conc', $grid[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>       
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($grid)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>

        </div>
    </div>   
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




