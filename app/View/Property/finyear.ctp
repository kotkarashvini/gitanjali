<?php
echo $this->element("Helper/jqueryhelper");
?> <script type="text/javascript" language="javascript">
     
           function ValidateDate(val,val1)
        {
            
        var regexYear= /^(?:\d{4}|\d{2})[\-](?:\d{4}|\d{2})$/;
        var Yearr= val;
        /*check if year value is not null or blank*/
     if( Yearr != "" && Yearr != null)
        {
        /*check year expression*/
        if(Yearr.match(regexYear))
        {


            var SplitYear = Yearr.split('-');
            var yearFirst = parseInt(SplitYear[0]);
            var yearSecond  = parseInt(SplitYear[1]);
            
            if(yearFirst>2000)
             {
                 yearFirst= yearFirst-2000;
             
              }
        
            if(yearSecond>2000)
            
            { 
                yearSecond= yearSecond-2000;
            
             }
	 var DiffYear = yearSecond- yearFirst;
            if(DiffYear==1)
             {

                    alert('suceess')
                      return true;

             }
            else
            {
                alert('Year Range is not valid. Please enter Year like 2010-2011 ');
                
                if (val1==1)
                {
                    
                    document.getElementById('finyear_desc').value = '';
                    document.getElementById('finyear_desc').focus();
                }
                
                if (val1==2)
                { 
                    document.getElementById('finyear_desc_short').value = '';
                    document.getElementById('finyear_desc_short').focus();
                }
                
                return false;
            }

         }
        else
            {
                alert('Enter a Valid Financial year');
            }
            }
         }
</script>
<script>
    $(document).ready(function () {

        $('#table').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });</script>
<?php // echo $this->element("BlockLevel/main_menu"); ?>

<?php echo $this->Form->create('finyear', array('id' => 'finyear', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblyearinitialization'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ValuationRules/finyear_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">


                <div class="row">
                    <div class="col-md-12">


                        <!--<div class="form-group">-->
                            <div class="col-md-3">
                                <label><?php echo __('lblfineyer') ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('finyear_desc', array('label' => false, 'id' => 'finyear_desc', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'onchange' => 'ValidateDate(this.value,1)' )) ?>
                                <span id="<?php echo 'finyear_desc_error'; ?>" class="form-error"></span> 
                            </div> 
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                        <!--</div>-->
                        <!--<div class="form-group">-->
                            <div class="col-md-3">
                                <label><?php echo __('lblfinyearinshort') ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('finyear_desc_short', array('label' => false, 'id' => 'finyear_desc_short', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10', 'onchange' => 'ValidateDate(this.value,2)')) ?>
                                <span id="<?php echo 'finyear_desc_short_error'; ?>" class="form-error"></span> 
                            </div> 
                        <!--</div>-->


                        <!--<div class="form-group">-->
                            <div class="col-md-3">
                                <label><?php echo __('lblyear_for_token') ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('year_for_token', array('label' => false, 'id' => 'year_for_token', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '10')) ?>
                                <span id="<?php echo 'year_for_token_error'; ?>" class="form-error"></span> 
                            </div> 
                        <!--</div>-->

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!--<div class="form-group">-->
                            <div class="col-sm-3">
                                <label for="area_type_flag" class="control-label"><?php echo __('lblcurrentyear'); ?><span style="color: #ff0000">*</span></label>

                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('current_year', array('options' => array($options), 'empty' => '--select--', 'id' => 'current_year', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                                ?> 
                                <span id="<?php echo 'current_year_error'; ?>" class="form-error"></span> 
                            </div>  

                        <!--</div>-->


                        <!--<div class="form-group">-->
                            <div class="col-sm-3">
                                    <label for="area_type_flag" class="control-label"><?php echo __('lbldisplayflag'); ?><span style="color: #ff0000">*</span></label>

                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('display_flag', array('options' => array($options), 'empty' => '--select--', 'id' => 'display_flag', 'class' => 'inputItemRelated form-control input-sm', 'label' => false));
                                ?> 
                                <span id="<?php echo 'display_flag_error'; ?>" class="form-error"></span> 
                            </div>  

                        <!--</div>-->  
                    </div>
                </div>






                <?php
//                pr($result);exit;
//                if (isset($result)) {
                echo $this->Form->input('finyear_id', array('label' => false, 'id' => 'finyear_id', 'type' => 'hidden'));
//                }
                ?>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br> <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>Property/finyear" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>




<div class="row">
    <div class="col-md-12">
        <div class="box box-primary"> 
            <div class="box-body">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

                        <tr>  
                            <th class="center"><?php echo __('lblfineyer'); ?></th>
                            <th class="center"><?php echo __('lblfinyearinshort'); ?></th> 
                            <th class="center"><?php echo __('lblyear_for_token'); ?></th>
                            <th class="center"><?php echo __('lblcurrentyear'); ?></th>
                            <th class="center"><?php echo __('lbldisplayflag'); ?></th> 
                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        foreach ($landtdata as $talukarecord1) {
                            ?>
                            <tr> 
                                <td><?php echo $talukarecord1[0]['finyear_desc']; ?></td> 
                                <td><?php echo $talukarecord1[0]['finyear_desc_short']; ?></td> 
                                <td><?php echo $talukarecord1[0]['year_for_token']; ?></td> 
                                <td><?php echo $talukarecord1[0]['current_year']; ?></td> 
                                <td><?php echo $talukarecord1[0]['display_flag']; ?></td> 
                                <td>

                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'finyear', $talukarecord1[0]['finyear_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?> 
                                    <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_finyear', $talukarecord1[0]['finyear_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?> 
                                </td>  </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>