<script>
 $(document).ready(function () {

    $('#tablearticleparty').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
 });
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(article_id,party_type_id, id) {
        document.getElementById("actiontype").value = '1';
        $('#article_id').val(article_id);
        $('#party_type_id').val(party_type_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

</script>


<?php echo $this->Form->create('counter', array('id' => 'counter')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Set Token Sequence'); ?>  </h3></center>
				<div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/set_token_sequence.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article_id" class="col-sm-2 control-label"><?php echo __('Financial year'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('fin_year_id', array('label' => false, 'id' => 'fin_year_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $finyear))); ?>
                            <span id="article_id_error" class="form-error"><?php //echo $errarr['fin_year_id']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('Enter zeros'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('token_no_count', array('label' => false, 'id' => 'token_no_count', 'class' => 'form-control input-sm','type' => 'text')); ?>
                            <span id="party_type_id_error" class="form-error"><?php //echo $errarr['token_no_count_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('Update'); ?>
                        </button>
                          <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
   
</div>