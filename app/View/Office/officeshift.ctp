<?php $doc_lang = $this->Session->read('doc_lang'); ?> 

<!--<script>
    function bindTimeTest()
    {
        alert("tesT");
    }
    </script>-->
    
    
    <script>
        
$(document).ready(function(){
  $("#to_time").focusin(function(){
//    alert("This input field has lost its focus.");
    
    var timeBind_From= document.getElementById("from_time").value;
   // alert(timeBind_From);
    $('#to_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: '10:00pm',
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>

 <script>
        
$(document).ready(function(){
  $("#appnt_from_time").focusin(function(){
//    alert("This input field has lost its focus.");
    
    var timeBind_From= document.getElementById("from_time").value;
     var timeBind_to= document.getElementById("to_time").value;
   // alert(timeBind_From);
    $('#appnt_from_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: timeBind_to,
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>


 <script>
        
$(document).ready(function(){
  $("#appnt_to_time").focusin(function(){
//    alert("This input field has lost its focus.");
    var timeBind_From= document.getElementById("appnt_from_time").value;
    var timeBind_to= document.getElementById("to_time").value;
    
   // alert(timeBind_From);
    $('#appnt_to_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: timeBind_to,
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>





 <script>
        
$(document).ready(function(){
  $("#lunch_from_time").focusin(function(){
//    alert("This input field has lost its focus.");
    
    var timeBind_From= document.getElementById("from_time").value;
    var timeBind_to= document.getElementById("to_time").value;
   // alert(timeBind_From);
    $('#lunch_from_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: timeBind_to,
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>

 <script>
        
$(document).ready(function(){
  $("#lunch_to_time").focusin(function(){
//    alert("This input field has lost its focus.");
    
    var timeBind_From= document.getElementById("lunch_from_time").value;
    var timeBind_to= document.getElementById("to_time").value;
   // alert(timeBind_From);
    $('#lunch_to_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: timeBind_to,
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>

<!-- <script>
        
$(document).ready(function(){
  $("#tatkal_to_time").focusin(function(){
//    alert("This input field has lost its focus.");
    
    var timeBind_From= document.getElementById("tatkal_from_time").value;
   // alert(timeBind_From);
    $('#tatkal_to_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 15,
   // minTime: '6',
    minTime: timeBind_From,
    maxTime: '10:00pm',
    //defaultTime: '11',
    startTime: timeBind_From,
    dynamic: false,
    dropdown: true,
    scrollbar: true
});


  });
});
</script>-->

<script>

    $(document).ready(function () {


        $('#tablelofficeshift').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });



$('#from_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '6',
    maxTime: '10:00pm',
    //defaultTime: '11',
    startTime: '06:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
 

//$('#to_time').timepicker({
//    timeFormat: 'HH:mm',
//    interval: 15,
//    minTime: '6',
//    maxTime: '10:00pm',
//    //defaultTime: '11',
//    startTime: '06:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});

        
      //  $('#from_time').timepicker({'timeFormat': 'HH:mm'});
      //  $("#to_time").timepicker({'timeFormat': 'HH:mm'});
      
//  $('#lunch_from_time').timepicker({
//    timeFormat: 'HH:mm',
//    interval: 15,
//    minTime: '6',
//    maxTime: '10:00pm',
//    //defaultTime: '11',
//    startTime: '06:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});  

      
//  $('#lunch_to_time').timepicker({
//    timeFormat: 'HH:mm',
//    interval: 15,
//    minTime: '6',
//    maxTime: '10:00pm',
//    //defaultTime: '11',
//    startTime: '06:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});  

    
  $('#tatkal_from_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '6',
    maxTime: '10:00pm',
    //defaultTime: '11',
    startTime: '06:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});  

    
  $('#tatkal_to_time').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '6',
    maxTime: '10:00pm',
    //defaultTime: '11',
    startTime: '06:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});  


  
//  $('#appnt_from_time').timepicker({
//    timeFormat: 'HH:mm',
//    interval: 15,
//    minTime: '6',
//    maxTime: '10:00pm',
//    //defaultTime: '11',
//    startTime: '06:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});  



//$('#appnt_to_time').timepicker({
//timeFormat: 'HH:mm',
//interval: 15,
//minTime: '6',
//maxTime: '10:00pm',
////defaultTime: '11',
//startTime: '06:00',
//dynamic: false,
//dropdown: true,
//scrollbar: true
//});  



      
      //  $("#lunch_from_time").timepicker({'timeFormat': 'HH:mm'});
      //  $("#lunch_to_time").timepicker({'timeFormat': 'HH:mm'});
       // $("#tatkal_from_time").timepicker({'timeFormat': 'HH:mm'});
     ///   $("#tatkal_to_time").timepicker({'timeFormat': 'HH:mm'});
       
        //$("#appnt_from_time").timepicker({'timeFormat': 'HH:mm'});
     //   $("#appnt_to_time").timepicker({'timeFormat': 'HH:mm'});
    });
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }




</script>

<script>
$(document).ready(function(){
  
  
   var StartWalaTime= document.getElementById("#from_time").title;
   if(StartWalaTime!= null)
   { alert(StartWalaTime);}
function setTo(){
    

    var StartWalaTime= document.getElementById("#from_time").title;
    alert(StartWalaTime);
    
//    $('#to_time').timepicker({
//    timeFormat: 'HH:mm',
//    interval: 15,
//    minTime: '6',
//    maxTime: '10:00pm',
//    //defaultTime: '11',
//    startTime: '06:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});
} 
  

 
});
</script>

<?php echo $this->Form->create('officeshift', array('id' => 'officeshift')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
       
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofficeshift'); ?></h3>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/officeshift_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12 col-md-offset-4">

                    <div class="row">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?> 
                            <div class="col-md-2">  
                                <label><?php echo __('shiftdescription') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label> 
                                <?php echo $this->Form->input('desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                                <span id="<?php echo 'desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"> 
                                </span>
                            </div>
                        <?php } ?>

                    </div> 
                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row">                     
                        <div class="col-sm-2">
                            <label for="from_time" class="control-label"><?php echo __('lblofficefromtime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('from_time', array('label' => false, 'id' => 'from_time', 'class' => 'form-control input-sm', 'type' => 'text', 'onchange' => 'setTo()')) ?>
                            <span id="from_time_error" class="form-error"></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="to_time" class="control-label"><?php echo __('lblofficetotime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('to_time', array('label' => false, 'id' => 'to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="to_time_error" class="form-error"></span>
                        </div>
                    </div> 
                    <div  class="rowht"></div>  
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="from_time" class="control-label"><?php echo __('lblofficeappointfromtime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('appnt_from_time', array('label' => false, 'id' => 'appnt_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="appnt_from_time_error" class="form-error"></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="to_time" class="control-label"><?php echo __('lblofficeappointtotime'); ?> <span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('appnt_to_time', array('label' => false, 'id' => 'appnt_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="appnt_to_time_error" class="form-error"></span>
                        </div>
                    </div>
                    <div  class="rowht"></div>  
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="from_time" class="control-label"><?php echo __('lbllunchfromtime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('lunch_from_time', array('label' => false, 'id' => 'lunch_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="lunch_from_time_error" class="form-error"></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="to_time" class="control-label"><?php echo __('lbllunchtotime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('lunch_to_time', array('label' => false, 'id' => 'lunch_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="lunch_to_time_error" class="form-error"></span>
                        </div>
                    </div>
                    <div  class="rowht"></div>  
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="from_time" class="control-label"> <?php echo __('lbltatkalfromtime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('tatkal_from_time', array('label' => false, 'id' => 'tatkal_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="tatkal_from_time_error" class="form-error"></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="to_time" class="control-label"> <?php echo __('lbltatkaltotime'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('tatkal_to_time', array('label' => false, 'id' => 'tatkal_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="tatkal_to_time_error" class="form-error"></span>
                        </div> 
                    </div>

                    <div  class="rowht"></div> <div  class="rowht"></div>

                    <div class="row"> 
                        <div class="col-sm-2">
                            <label for="from_time" class="control-label"> Tatkal Days<span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('tatkal_days', array('label' => false, 'id' => 'tatkal_days', 'class' => 'form-control input-sm', 'type' => 'number', 'maxlength' => '1','min'=>'0', 'max' => '7')) ?>
                            <span id="tatkal_days_error" class="form-error"><?php echo $errarr['tatkal_days_error']; ?></span>
                             <?php echo $this->Form->input('shift_id', array('label' => false, 'id' => 'shift_id', 'class' => 'form-control input-sm', 'type' => 'hidden')) ?>
                         
                        </div> 
                    </div>

                    <div  class="rowht"></div>  <div  class="rowht"></div>
                    <div class="row"> 
                        <div class="col-sm-2">
                            
                            
                           <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                            
                                 <a href="<?php echo $this->webroot; ?>Office/officeshift" class="btn btn-info"><?php echo __('btncancel'); ?></a>
                        </div>
                    </div>


                </div>    
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectlocal_governing_body">
                    <table id="tablelofficeshift" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblofficefromtime'); ?></th>
                                <th class="center"><?php echo __('lblofficetotime'); ?></th>
                                <th class="center"><?php echo __('lbltatkalfromtime'); ?></th>
                                <th class="center"><?php echo __('lbltatkaltotime'); ?></th>

                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('Description') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($officeshift as $officeshift1): ?>
                                <tr>
                                    <td ><?php echo $officeshift1['officeshift']['from_time']; ?></td>
                                    <td ><?php echo $officeshift1['officeshift']['to_time']; ?></td>
                                    <td ><?php echo $officeshift1['officeshift']['tatkal_from_time']; ?></td>

                                    <td ><?php echo $officeshift1['officeshift']['tatkal_to_time']; ?></td>

                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $officeshift1['officeshift']['desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td>      
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'officeshift', $officeshift1['officeshift']['shift_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to edit?')); ?></a>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_officeshift', $officeshift1['officeshift']['shift_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to delete?')); ?></a>
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($officeshift1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($officeshift)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>