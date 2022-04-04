<script type="text/javascript">
    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y')
        {
            $('#tableLanguage').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tableLanguage').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
    });
    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
    function formupdate(id, state_name_en, language_name) {
        $('#hfid').val(id);
        $('#state_id').val(state_name_en);
        $('#language_id').val(language_name);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>
<style>
    a.cll{
        color:red;
    }
</style>
<?php
echo $this->element("Master/language_main_menu");
?> 
<?php echo $this->Form->create('config_language', array('type' => 'file','id' => 'config_language')); ?>


<?php
if($st_coun==0)
{
?>
<br><br>
    <div class="row center">
        State/UT is not selected, before language configuration Please select State/UT using given user.
    </div>

<?php

}    
else{
?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo "Select Languages for State/UT"; ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ConfigLanguage/set_local_lang_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="language_name" class="col-sm-2 control-label"><?php echo __('lblselectstate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php
                            //echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $statename)));
                            echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'options' => $statename, 'default'=>$statename));
                            ?>
                            <span id="state_id_error" class="form-error"><?php// echo $errarr['state_id_error']; ?></span>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        </div>
                        <label for="language_id" class="col-sm-2 control-label"><?php echo __('lblselectlang'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                           <!-- <?php echo $this->Form->input('language_id', array('label' => false, 'id' => 'language_id', 'class' => 'form-control input-sm', 'options' => array($language))); ?>-->
                             <?php echo $this->Form->input('language_id', array('options' => $language, 'empty' => '--select--', 'id' => 'language_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>   
                            <span id="language_id_error" class="form-error"><?php //echo $errarr['language_id_error']; ?></span>
                        </div>

                   
                    </div>
                    
                </div>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button type="submit"  id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsave();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo "Save Language"; ?></button>
                        <!--<button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
              <div class="row center"><b style="color:red">Download excel file of English Label List given in below table
                      <br>Then create seperate excel sheets for each language & fill labels corresponding to english labels<br>
                      Upload each excel sheet as per given languages in table. 
                  </b></div><br>
                <table id="tableLanguage" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbladmstate'); ?></th>
                            <th class="center"><?php echo __('lbllangname'); ?></th>
                            <th class="center"><?php echo 'Upload language label list'; ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        
                        <?php foreach ($Config_language as $Config_language1):
                            //pr($Config_language1);
                            ?>
                            <tr>
                                <td ><?php echo $Config_language1[0]['state_name_en']; ?></td>
                                <td ><?php echo $Config_language1[0]['language_name']; ?></td>
                                <td >
                                <?php 
                                if($Config_language1[0]['language_id']==1)
                                {
                                    echo $this->Html->link('English Labels', array('controller' => 'Masters', 'action' => 'english_lables')); 
                                }    
                                else{
                                    if($Config_language1[0]['labellist_name']=='')
                                    {
                                    //echo $this->Form->create('oneform', array('type' => 'file','id' => 'oneform'));
                                    echo $this->Form->input('label_'.$Config_language1[0]['language_id'], array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'placeholder' => 'Upload Image', 'id' => 'label_'.$Config_language1[0]['language_id'], 'class' => 'Cntrl1'));
                                ?>
                                    <input type="submit" name="upload" id="commonfilesubmit" value="Upload" class="btn btn-warning"/>
                                
                                <?php
                                    }
                                    else{
                                        $lblnmm=$Config_language1[0]['labellist_name'];
                                        echo $this->Html->link($lblnmm, array('controller' => 'Masters', 'action' => 'download_lables_list',$lblnmm));
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                        ?>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', ' Delete File', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_lables_list', $lblnmm), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>
                                        <?php
                                        //echo '<span style="color:red;">';
                                        //echo $this->Html->link('Delete file', array('class'=>'cll'),array('controller' => 'Masters', 'action' => 'delete_lables_list',$lblnmm));
                                        //echo '</span>';
                                    }
                                    
                                   // echo $this->Form->end(); 
                                }
                                ?>
                                
                                </td>    
                                <!--<td >
                                    <?php
                                    //pr(phpinfo());
                                    if($Config_language1[0]['language_id']==1)
                                    {
                                            echo $this->Html->link(
                                            'Download', array(
                                            'disabled' => TRUE,
                                            'controller' => 'Masters', // controller name
                                            'action' => 'english_lables', //action name
                                            'full_base' => true, 'Formlables.csv')
                                        );
                                    }
                                    ?>
                                </td>-->
                                <td >
                                    <!--<button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                                        ('<?php echo $Config_language1[0]['id']; ?>'),
                                                        ('<?php echo $Config_language1[0]['state_id']; ?>'),
                                                        ('<?php echo $Config_language1[0]['language_id']; ?>')
                                                        );">
                                        <span class="glyphicon glyphicon-pencil"></span></button>-->
                                    <?php
                                    if($Config_language1[0]['language_id']!=1){
                                    $newid = $this->requestAction(
                                            array('controller' => 'Masters', 'action' => 'encrypt', $Config_language1[0]['id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'config_language_delete', $newid,$Config_language1[0]['language_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to delete?')); ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <?php unset($Config_language1); ?>
                </table> 
                <?php if (!empty($Config_language)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
</div>

<?php
}
?>

