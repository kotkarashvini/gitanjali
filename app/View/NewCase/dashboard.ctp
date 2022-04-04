<?php

echo $this->Html->script('scroll_auto');?>
<div class="row">
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <center>
                    <h4><b>Case Wise</b></h4>
                    <p><h4><b>Status</b></h4></p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a class="small-box-footer" href="../NewCase/genernal_info">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <center>
                    <h4><b>Date Wise </b></h4>
                    <p><h4><b>Status</b></h4></p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <center>
                    <h4><b>Revenue Wise</b></h4>
                    <p><h4><b>Status</b></h4></p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <center>
                    <h4><b>Office Wise</b></h4>
                    <p><h4><b>Status</b></h4></p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
</div>

<br>
<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><b>On Board Status</b></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="accordion" class="box-group">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" aria-expanded="true" class="">
                                    List:
                                </a>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse in" id="collapseOne" aria-expanded="true" style="">
                            <div class="box-body">
                  <?php
                  
                  foreach($onboardcases as $rs){
                      $case_code=$rs[0]['case_code'];
                      $case_year=$rs[0]['case_year'];
                      $case_type_desc=$rs[0]['case_type_desc'];
?>
                                <div class="cases_rol">
                                    <ul id="ticker2" class="ticker" style="overflow: hidden; list-style:none;">
                                        <li style="margin-top: 0px;"><a  href="../NewCase/genernal_info"><?php echo $case_code."-".$case_year."-".$case_type_desc;?></a></li>
                                    </ul>
                                </div>
                  <?php }  ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Registered Cases</b></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="accordion" class="box-group">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" aria-expanded="true" class="">
                                    List:
                                </a>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse in" id="collapseOne" aria-expanded="true" style="">
                            <div class="box-body">
                                  <?php
                  
                  foreach($caseresult as $rs1){
                      pr($rs1);
                      $case_id=$rs[0]['case_id'];
//                      $case_year=$rs[0]['case_year'];
//                      $case_type_desc=$rs[0]['case_type_desc'];
?>
                                <div class="cases_rol">
                                    <ul id="ticker2" class="ticker" style="overflow: hidden; list-style:none;">
                                        <li style="margin-top: 0px;"><a  href="../NewCase/genernal_info"><?php // echo $case_id."-".$case_year."-".$case_type_desc;?></a></li>
                                    </ul>
                                </div>
                  <?php }  ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


