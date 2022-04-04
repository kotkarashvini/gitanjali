<script>
    $(document).ready(function () {


        $('#from').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
        $('#to').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true

        });

        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();

            dist_change_event(dist);
           
            $.post('<?php echo $this->webroot; ?>Reports/get_office_list', {dist: dist}, function (data1)
            {

                var sc1 = '<option value="">--select--</option>';
                $.each(data1.office, function (index1, val1) {

                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });

                $("#office_id option").remove();
                $("#office_id").append(sc1);
            }, 'json');

        });
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Reports/get_office_list', {tal: tal}, function (data1)
            {

                var sc1 = '<option value="">--select--</option>';
                $.each(data1.office, function (index1, val1) {

                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });

                $("#office_id option").remove();
                $("#office_id").append(sc1);
            }, 'json');
        });

    });

    function formprint() {
        document.getElementById("actiontype").value = '1';
    }

    function dist_change_event(dist_id) {


        $.post("<?php echo $this->webroot; ?>Reports/district_change_event", {dist: dist_id}, function (data)
        {

            var sc = '<option value="">--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);
            //sortSelect('#taluka_id', 'text', 'asc');
        }, 'json');
    }
</script>
<?php echo $this->Form->create('income_tax', array('id' => 'income_tax')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Generic Report 1 </h3></center>
            </div>
            <div class="box-body">


                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <center><h3 class = "box-title headbolder"><?php echo $officename; ?> </h3></center>

                        </div>
                    </div>
                </div> 
<!--                 <div class="row" >
                            <div class="col-sm-12">
                                <div class="form-group">
                                            
                                    <div class="col-sm-6"><?php //echo $this->Form->input('report_flag', array('type' => 'radio', 'options' => array('D' => '&nbsp;Detail Report&nbsp;&nbsp;&nbsp;&nbsp;', 'C' => '&nbsp;Only collection status'), 'value' => 'D', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'listflag')); ?></div> 
                                </div>
                            </div>
                        </div>-->
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?></div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?></div>

                </div>
                <div  class="rowht"></div> 
<!--                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select Article</label>    
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('article_id', array('label' => false, 'id' => 'val_amount', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select All----', 'options' => array($article))); ?>
                        </div>

                    </div>     
                </div>-->
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select District</label>  
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select All----', 'options' => array($districtdata))); ?>
                        </div>

                    </div>     
                </div>
                <div  class="rowht"></div> 

<!--                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select Tehsil</label>  
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select All----', 'options' => array())); ?>
                        </div>

                    </div>     
                </div>-->
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select Office</label>  
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select All----', 'options' => array())); ?>
                        </div>

                    </div>     
                </div>
                 <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select Fee Type</label>  
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('fee_item_id', array('label' => false, 'id' => 'fee_item_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select All----', 'options' => array($outitemlist))); ?>
                        </div>

                    </div>     
                </div>

                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2">

                            <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>  </div>
                    </div>     
                </div>
            </div>
            <br><br>
            <?php if (!empty($htmldesign)) { ?>
                <div class="row center" style='overflow:auto; width:100%;height:400px;'>
                    <div class="form-group" > 
                        <div class="col-sm-1"></div>                   
                        <div class="col-sm-10"><?php echo $htmldesign; ?></div>
                        <div class="col-sm-1"></div>          
                    </div>
                </div><br><br>
                <div class="row center">
                    <div class="form-group" > 
                        <button id="btnadd" name="btnadd1" class="btn btn-info " onclick="javascript: return formprint();">
                            <?php echo __('Print'); ?></button> 
                    </div>
                </div>
            <?php } ?><br><br>

        </div> 
    </div> 
    <input type="hidden" id="actiontype" value='<?php echo $actiontype; ?>' name="actiontype" class="btn btn-primary">
    <input type="hidden" id="hfname" value='<?php echo $hfname; ?>' name="hfname" class="btn btn-primary">
</div>

