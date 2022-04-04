<?php
foreach ($stampconfig as $stamprec) {
    if(isset($stamprec['functions'])){
    foreach ($stamprec['functions'] as $funrec) {
        if ($funrec['action'] == $this->request->params['action']) {
            $btnaccept_label = $funrec['btnaccept'];
            $stampflag = $stamprec['stamp_flag'];
            $funflag = $funrec['function_flag'];
        }
    }
    }
}

echo $this->element("Registration/main_menu");
?>
<br>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create('final_stamp', array('id' => 'final_stamp')); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="box-title headbolder"><?php echo __('lblsrochecklistdetails'); ?></h3></center>
            </div>
            <div class="box-body"> 
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th> <?php echo __('lblsrno'); ?></th>
                            <th><?php echo __('lblsrochecklistdetails'); ?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $c = 0;
 
                        foreach ($Srochecklist as $single) {
                            $c++;
                            $checked="";
                           
                            if($documents[0][0][$funflag]=='Y'){
                                $checked="checked";
                            }
                            ?>
                            <tr>
                                <th width="10%"><?php echo $c; ?></th>
                                <th>
                                    <div class="checklist<?php echo $single[0]['checklist_id'] ?>">
                                        <label>
                                            <input type="checkbox" name="data[final_stamp][checklist<?php echo $single[0]['checklist_id'] ?>]" value="<?php echo $single[0]['checklist_id'] ?>" <?php echo $checked; ?> >
                                            <?php echo $single[0]['checklist_desc_' . $lang] ?>  
                                        </label><br>
                                        <span class="form-error" id="checklist<?php echo $single[0]['checklist_id'] ?>_error"></span>
                                    </div>
                                </th>

                            </tr>

                        <?php } ?>
                        <tr>
                            <td colspan="2"></td>
                        </tr> 
                        <?php
                        if ($c == 0) {
                            ?>
                            <tr>
                                <td colspan="2"><?php echo __('lblrecordnotfound'); ?></td>
                            </tr>   
                            <?php
                        }
                        ?>

                    </tbody>
                </table>


               
                 
            </div>
            <div class="panel-footer center">
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                <?php
                if ($documents[0][0][$funflag] == 'N' ) {
                    echo $this->Form->button(__($btnaccept_label), array('type' => 'submit', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success'));
                } else {
                    echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-disabled'));
                }
                ?>


            </div>
        </div>
  

        <?php echo $this->Form->end(); ?>

    </div>
</div>
