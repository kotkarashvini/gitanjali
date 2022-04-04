<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });

//    $(document).ready(function () {
//        var popup = window.open(winPath, winName, winFeature, true);
//        setTimeout(function () {
//            if (!popup || popup.outerHeight === 0) {
//
//                alert("Popup Blocker is enabled! Please add this site to your exception list.");
//                window.location.href = 'warning.html';
//            } else {
//                window.open('', '_self');
//                window.close();
//            }
//        }, 25);
//    });
</script>
<?php echo $this->Form->create('Welcomenote'); ?>

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
                                <ul id="ticker2" class="ticker" style="overflow: hidden; list-style:none;">
                     <?php // echo $case_code."-".$case_year."-".$case_type_desc;?>
                                    
                                    
                                    <?php echo '<li style="margin-top: 0px;"><a  href="../NewCase/genernal_info">$case_code."-".$case_year."-".$case_type_desc</a></li>';?>
                                </ul>
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
                                Act 1: Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 
                                3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. 
                                Brunch 3 wolf moon tempor
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>




</div>

<?php echo $this->Form->end(); ?>
<script language="JavaScript" type="text/javascript">
    var message = "Not Allowed Right Click";
    function rtclickcheck(keyp)
    {
        if (navigator.appName == "Netscape" && keyp.which == 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>