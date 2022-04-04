
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $.getJSON("get_taluka_name", {district: district}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
            
        

//            $.post("unitgrid", {district: district}, function (data)
//            {
//                $("#divrategrid").html("");
//                $("#divrategrid").html(data);
//            });

        })

        $('#taluka_id').change(function () {
            var district = $("#district_id option:selected").val();
            var taluka = $("#taluka_id option:selected").val();

            $.post("unitgrid", {district: district, taluka: taluka}, function (data)
            {
                $("#divrategrid").html(data);
            });
        })

        $('#ulb_type_id').change(function () {
            var corp = $("#ulb_type_id option:selected").val();
            $.getJSON("get_corp_list", {corp: corp}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            });
        })
        
        
    });

   

</script>

<?php echo $this->Form->create('rate', array('id' => 'rate')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Unit Updation'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Rate Chart/rate_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group"><br>
                        <div class="col-sm-2">
                            <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        </div>

                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                            <?php echo $this->Form->input('taluka_id', array('options' => array($talukadata), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        </div>

                       

                    </div>
                </div><br><br>
              

                <div class="rowht"></div>
                <div class="rowht"></div>

                <div id="divrategrid" class="table-responsive">                   
                </div>
            </div>
        </div>
    </div>

    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <!--<input type='hidden' value='<?php // echo $saveflag;       ?>' name='saveflag' id='saveflag'/>
    <input type='hidden' value='<?php // echo $selectflag;       ?>' name='selectflag' id='selectflag'/>
    <input type='hidden' value='<?php // echo $surveyno;       ?>' name='surveyno' id='surveyno'/>
    <input type='hidden' value='<?php // echo $actiontypeval;       ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php // echo $hfvillage;       ?>' name='hfvillage' id='hfvillage'/>-->
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
