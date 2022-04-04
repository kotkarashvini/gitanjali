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
                    <h3>10</h3>
                    <p>Registered Employees</p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!--<a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <center>
                    <h3>10</h3>
                    <p>Registered Citizens</p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <!--<a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <center>
                    <h3>10</h3>
                    <p>Property Valuations</p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <!--<a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6 hvr-bounce-out">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <center>
                    <h3>10</h3>
                    <p>Registered Properties</p>
                </center>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <!--<a class="small-box-footer" href="#">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
<!--                    <h3 class="box-title"><?php // echo "<a href='" . $this->webroot . 'Users' . "/" . 'citizenlogin' . "'><i class='fa fa-share'></i><span>" . 'Citizen Login' . "</span></a>";  ?></h3>-->
                <h3 class="box-title"><?php echo "<a href='" . $this->webroot . 'Citizenentry' . "/" . 'citizenlogin' . "'><i class='fa fa-share'></i><span>" . 'Citizen Login' . "</span></a>"; ?></h3>
            </div>
            <div class="box-body">
                <div class="view view-sixth">
                    <center>
                        <?php echo $this->Html->image('citizens.png', array('height' => '60px')); ?>
                    </center>
                    <div class="mask">
                        <h2>For Citizens</h2>
                        <a href="#" class="info">Read More</a>
                    </div>   
                </div>
                <div class="ftpan1">
                    <strong><i class="fa fa-file-text-o margin-r-5"></i> ..</strong>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Search</h3>
            </div>
            <div class="box-body">
                <div class="view view-sixth">
                    <center>
                        <?php echo $this->Html->image('search6.jpg', array('height' => '60px')); ?>
                    </center>
                    <div class="mask">
                        <h2>Search</h2>
                        <a href="#" class="info">Read More</a>
                    </div>   
                </div>
                <div class="ftpan1">
                    <strong><i class="fa fa-file-text-o margin-r-5"></i> ..</strong>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo "<a href='" . $this->webroot . 'Users' . "/" . 'login' . "'><i class='fa fa-share'></i><span>" . 'Organization Login' . "</span></a>"; ?></h3>
            </div>
            <div class="box-body">
                <div class="view view-sixth">
                    <center>
                        <?php echo $this->Html->image('ngdrs.jpg', array('height' => '60px')); ?>
                    </center>
                    <div class="mask">
                        <h2>For Department Use</h2>
                        <a href="#" class="info">Read More</a>

                    </div>
                </div>
                <div class="ftpan1">
                    <strong><i class="fa fa-file-text-o margin-r-5"></i> ..</strong>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Property Registration Act:</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="accordion" class="box-group">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" aria-expanded="true" class="">
                                    Act:1 
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
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a href="#collapseTwo" data-parent="#accordion" data-toggle="collapse" class="collapsed" aria-expanded="false">
                                    Act:2
                                </a>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapseTwo" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                Act 2: Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 
                                3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. 
                                Brunch 3 wolf moon tempor
                            </div>
                        </div>
                    </div>
                    <div class="panel box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a href="#collapseThree" data-parent="#accordion" data-toggle="collapse" class="collapsed" aria-expanded="false">
                                    Act:3
                                </a>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse" id="collapseThree" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                Act 3: Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 
                                3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. 
                                Brunch 3 wolf moon tempor
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->


    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Slider</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div data-ride="carousel" class="carousel slide" id="carousel-example-generic">
                    <ol class="carousel-indicators">
                        <li class="" data-slide-to="0" data-target="#carousel-example-generic"></li>
                        <li class="" data-slide-to="1" data-target="#carousel-example-generic"></li>
                        <li class="active" data-slide-to="2" data-target="#carousel-example-generic"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="item " >
                            <?php echo $this->Html->image('slide3.jpg', array('height'=>'100px', 'width' => '900px')); ?>

                        </div>
                        <div class="item">
                            <?php echo $this->Html->image('slide2.jpg', array('height'=>'100px', 'width' => '900px')); ?>

                        </div>
                        <div class="item active ">
                            <?php echo $this->Html->image('slide1.png', array('height'=>'100px', 'width' => '900px')); ?>

                        </div>
                        <div class="item">
                            <?php echo $this->Html->image('slide4.jpg', array('height'=>'100px', 'width' => '900px')); ?>

                        </div>
                    </div>
                    <a data-slide="prev" href="#carousel-example-generic" class="left carousel-control">
                        <span class="fa fa-angle-left"></span>
                    </a>
                    <a data-slide="next" href="#carousel-example-generic" class="right carousel-control">
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="">
                <center>External Links</center>
            </div>

            <div class="box-body no-padding">
                <table class="table table-condensed btn-vk tbltd">
                    <tbody>
                        <tr>
                            <td><a href="http://dolr.nic.in/" target="_blank">Ministry of Rural Development</a></td>
                        </tr>
                        <tr>
                            <td><a href="https://uidai.gov.in/" target="_blank">Unique Ident., Auth. of India</a></td>
                        </tr>
                        <tr>
                            <td><a href="http://www.digitalindia.gov.in/" target="_blank">Digital India</a></td>
                        </tr>
                        <tr>
                            <td><a href="https://india.gov.in/" target="_blank">India.gov.in</a></td>
                        </tr>
                        <tr>
                            <td><a href="https://swachhbharat.mygov.in/" target="_blank">Swachh Bharat, My Clean India</a></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="">
                <center>Legal Information</center>
            </div>

            <div class="box-body no-padding">
                <table class="table table-condensed btn-vk tbltd">
                    <tbody>
                        <tr>
                            <td><?php echo $this->Html->link('Disclaimer', array('controller' => 'Users', 'action' => 'Disclaimer')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Terms & Conditions', array('controller' => 'Users', 'action' => 'termsandconditions')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Policies', array('controller' => 'Users', 'action' => 'policies')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Accessibility Statement', array('controller' => 'Users', 'action' => 'accessabilitystmt')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Link 5', array('controller' => 'Employee', 'action' => 'empdemo')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="">
                <center>Site Links </center>
            </div>

            <div class="box-body no-padding">
                <table class="table table-condensed btn-vk tbltd">
                    <tbody>
                        <tr>
                            <td><?php echo $this->Html->link('About The NGDRS', array('controller' => 'Users', 'action' => 'aboutus')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Contact Us', array('controller' => 'Users', 'action' => 'contactus')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Feedback', array('controller' => 'Users', 'action' => 'feedback')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Help', array('controller' => 'Users', 'action' => 'help')); ?></td>
                        </tr>                             
                        <tr>
                            <td><?php echo $this->Html->link('Site map', array('controller' => 'Users', 'action' => 'sidemap')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="">
                <center>Other Links </center>
            </div>

            <div class="box-body no-padding">
                <table class="table table-condensed btn-vk tbltd">
                    <tbody>
                        <tr>
                            <td><?php echo $this->Html->link('NGDRS Client', array('controller' => 'Users', 'action' => 'ngdrsclient')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Accessibility Statement', array('controller' => 'Users', 'action' => 'Disclaimer')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Visitor Summary', array('controller' => 'Users', 'action' => 'Disclaimer')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Services', array('controller' => 'Users', 'action' => 'Disclaimer')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->Html->link('Connect With Us', array('controller' => 'Users', 'action' => 'Disclaimer')); ?></td>
                        </tr>
                    </tbody>
                </table>
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