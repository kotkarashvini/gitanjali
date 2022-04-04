<script type="text/javascript">

    $(document).ready(function () {
        
         $('#tableLanguage').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
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

     function formupdate(id,language_name,language_code) {
   
        $('#hfid').val(id);
        $('#language_name').val(language_name);
         $('#lang_code').val(language_code);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

</script>
<?php echo $this->Form->create('mainlanguage', array('id' => 'language')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="panel panel-primary">
             
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lbladdlang'); ?></b>
			<div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/language.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            
            
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="language_name" class="col-sm-2 control-label"><?php echo __('lbllangname'); ?>:-<span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('language_name', array('label' => false, 'id' => 'language_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                           
                        </div>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="lang_code" class="col-sm-2 control-label"><?php echo __('lbllangcode'); ?>:-<span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('lang_code', array('label' => false, 'id' => 'lang_code', 'class' => 'form-control input-sm', 'type' => 'text','maxlength'=>2)) ?>
                            </div>
                           
                        </div>

                    </div>
                </div>
                <br>
                <div class="row" style="text-align: center;">
                    <div class="col-sm-12">
                         <button type="submit" style="width: 100px;" id="btnCancel" name="btnCancel" class="btn btn-primary" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                         <button type="submit" style="width: 100px;" id="btnNext" name="btnNext" class="btn btn-primary" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
                <br>
                       <table id="tableLanguage" class="table table-striped table-bordered table-condensed">  
                    <thead style="background-color: rgb(243, 214, 158);">  
                        <tr>  

                            <td style="text-align: center; width: 10%;"><?php echo __('lbllangname'); ?></td>
                            <td style="text-align: center; width: 10%;"><?php echo __('lbllangcode'); ?></td>
                            <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>

                        </tr>  
                    </thead>

                    <tr>
                        <?php
                        foreach ($language as $language1):
                  
                 
                            ?>

                            <td style="text-align: center;"><?php echo $language1[0]['language_name']; ?></td>
                            <td style="text-align: center;"><?php echo $language1[0]['lang_code']; ?></td>
                          
                            <td style="text-align: center;">
                                <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdate(
                                               ('<?php echo $language1[0]['id']; ?>'),  
                                            ('<?php echo $language1[0]['language_name']; ?>'),
                                                ('<?php echo $language1[0]['lang_code']; ?>')
                                               
                                                );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>

                                  <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                        onclick="javascript: return formdelete(('<?php echo $language1[0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </td>

                        </tr>


                    <?php endforeach;
                    ?>
                    <?php unset($language1); ?>


                </table> 
                
            </div>
             <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
            <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
            <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
            <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        </div>
    </div>
</div>