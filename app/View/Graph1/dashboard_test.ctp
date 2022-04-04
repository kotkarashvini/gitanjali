<style>
.MyModel100{
    
    width:100%;
}

.MyModel80{
    
    width:80%;
}
.Margin{
    margin-right:-199px;
}
</style>
<script>
    $(document).ready(function () {
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });



        $('.graph').click(function () {
            

            var status = $(this).attr('id');
            var fun = null;
            if (status == 'regdoc') {
                 fun = 'docregistered';
            } else if (status == 'subdoc') {
                 fun = 'docsubmitted';
            } else if (status == 'srofc') {
                 fun = 'office';
            } else if (status == 'distcol') {
                 fun = 'distcollection';
            } else if (status == 'accheadcol') {
                 fun = 'acccollection';
            } else if (status == 'appoint') {
                 fun = 'appointment';
            } else {
                return false;
            } 
            var from = $("#fromdate").val();
            var to = $("#todate").val();
            $.post(fun, {from: from, to: to}, function (data)
            {   
                $("#divdashboard").html('');
                $("#divdashboard").html(data);
                
            });
//             $('#myModal').html('');
             $('#myModal').modal('show');
            return false;
        });
        $(".modal").on("hidden.bs.modal", function(){
    $(".modal-body").html("Graph Loading...!!!");
});

    });
    
   function formadd(){
        $('#dashboard_test').submit();
    }
</script>

<?php echo $this->Form->create('dashboard_test', array('id' => 'dashboard_test', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblngdrsdashboard'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="fromdate" class="col-sm-6 control-label"><?php echo __('lblfromdate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-6"><?php echo $this->Form->input('fromdate', array('type' => 'text', 'id' => 'fromdate', 'value' => $frmdate, 'label' => false, 'class' => 'date form-control input-sm')); ?></div>
                        <span id="fromdate_error" class="form-error"><?php // echo $errarr['fromdate_error'];    ?></span>
                    </div>
                    <div class="col-sm-4">
                        <label for="todate" class="col-sm-6 control-label"><?php echo __('lbltodate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-6"><?php echo $this->Form->input('todate', array('type' => 'text', 'id' => 'todate', 'value' => $todate, 'label' => false, 'class' => 'date form-control input-sm')); ?></div>
                        <span id="todate_error" class="form-error"><?php // echo $errarr['todate_error'];    ?></span>
                    </div>
                    <div class="col-sm-4">
                        <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblsearch'); ?>
                        </button>
                    </div>
                </div><br>
                <div class="row">
                    <div class="graph col-lg-3 col-xs-6" id="regdoc" data-toggle="modal" data-target="#myModal">
                        <!--<div class="graph col-lg-3 col-xs-6" id="regdoc">-->
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?php echo $registered[0][0]['total']; ?></h3>

                                <p><b>Registered Document</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="<?php echo $this->webroot; ?>Graph/districtwise" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="graph col-lg-3 col-xs-6" id="subdoc" data-toggle="modal" data-target="#myModal">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?php echo $submitted[0][0]['total']; ?></h3>
                                <p><b>Submitted Document</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="graph col-lg-3 col-xs-6" id="srofc" data-toggle="modal" data-target="#myModal">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?php echo $office[0][0]['total']; ?></h3>

                                <p><b>SRO Office</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="graph col-lg-3 col-xs-6" id="distcol" data-toggle="modal" data-target="#myModal">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><?php if($distcol[0][0]['pamount'] == null){$amount=0;}else{$amount=($distcol[0][0]['pamount']/100000).'L';} echo $amount; ?></h3>

                                <p><b>Revenue Collection <br> (District Wise)</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="graph col-lg-3 col-xs-6" id="accheadcol" data-toggle="modal" data-target="#myModal">
                        <!-- small box -->
                        <div class="small-box bg-black">
                            <div class="inner">
                                <h3><?php if($distcol[0][0]['pamount'] == null){$amount=0;}else{$amount=($distcol[0][0]['pamount']/100000).'L';} echo $amount; ?></h3>

                                <p><b>Revenue Collection <br> (Account Head Wise)</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                     <div class="graph col-lg-3 col-xs-6" id="appoint" data-toggle="modal" data-target="#myModal">
                        <!-- small box -->
                        <div class="small-box bg-gray">
                            <div class="inner">
                               <h3><?php echo $appointment[0][0]['total']; ?></h3>

                                <p><b>Appointment</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
<!--                    <div class="graph col-lg-3 col-xs-6" id="appoint" data-toggle="modal" data-target="#myModal">
                         small box 
                        <div class="small-box bg-blue">
                            <div class="inner">
                               <h3><?php echo $exemption[0][0]['total']; ?></h3>

                                <p><b>Exemption</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="graph col-lg-3 col-xs-6" id="appoint" data-toggle="modal" data-target="#myModal">
                         small box 
                        <div class="small-box bg-lime">
                            <div class="inner">
                               <h3><?php echo $exemption[0][0]['total']; ?></h3>

                                <p><b>Officewise Exemption</b></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="rowht"></div>
            <div class="rowht"></div>
<div id="myModal" class="modal fade MyModel100" role="dialog">
    <div class="modal-dialog modal-lg MyModel80">

        <!-- Modal content-->
        <div class="modal-content MyModel100">
            <div class="modal-header MyModel80">
                <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dashboard</h4>                
                <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
            </div>
            <div class="modal-body MyModel100" id="divdashboard">
                <p>Graph Loading...!!!!</p>
            </div>
            <!--<div class="modal-footer">-->
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                
            <!--</div>-->
        </div>

    </div>
</div>
        </div>
    </div>
</div><?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>















