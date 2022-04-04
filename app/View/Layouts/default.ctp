
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>NGDRS : National Generic Document Registration System</title>
        <?php
        /* Following JS file should be required in all pages */
        echo $this->Html->script('jQuery/jquery.min');                      // jQuery 2.2.3 
        echo $this->Html->script('jQuery/jquery.disable.autocomplete.min');       // Disable autocomplete
        echo $this->Html->script('jQuery/jquery.validationEngine');               // Validation Engine for login
        echo $this->Html->script('jQueryUI/jquery-ui.min');                       // Jquery-ui used for dragable, This file used in Document entry for status bar
        echo $this->Html->script('/bootstrap/js/bootstrap.min');                  //  Bootstrap 3.3.6 
        echo $this->Html->script('App/app.min');                                  // App Js : It controls some layout
        echo $this->Html->script('JS');                                          // Encode and Decode  
//echo $this->Html->script('sha256');

        echo $this->Html->script('Print/jQuery.print');
        /* Above JS file  should be required in all pages */

        /* Following CSS file should be required in all pages */
        echo $this->Html->css('/bootstrap/css/bootstrap');       // Bootstrap css
        echo $this->Html->css('NG_Template.min');                    // General Layout Style
        echo $this->Html->css('skins/_all-skins');               // Layout skin css   
        echo $this->Html->css('font-awesome.min');               // Font awesome css used for to show small icons    
        echo $this->Html->css('Dev_Define');                     // Devloper should add new css in this Dev_Define css file 
        /* Above CSS file should be required in all pages */
        echo $this->Html->css('dataTables.bootstrap.min');              // dataTables.bootstrap used for table design        
        echo $this->Html->css('bootstrap-datepicker3.min');             // used for bootstrap datepicker
        echo $this->Html->css('select2');
        echo $this->Html->css('jquery-ui');

        echo $this->Html->css('jquery.timepicker');
        echo $this->Html->script('jquery.timepicker');
        ?> 

        <script type="text/javascript">
            $(document).ready(function () {
                $(".chosen-select").select2();

                $("#menulangselect").on("change", function () {
                    fnlangaugechange1($("#menulangselect").val(), $("#menulangselect").text());
                });

            });

        </script>

        <script type="text/javascript">
            $(function () {

                $("input[type=text]").mouseenter(function () {
                    $(this).attr('title', $(this).val());
                });
                $("select").mouseenter(function () {
                    $(this).attr('title', $(this).find('option:selected').text());
                });

                $(".font-button").bind("click", function () {
                    var size = parseInt($('#contentfont').css("font-size"));
//                    alert(size);return false;
                    if ($(this).hasClass("plus")) {
//                        alert('hi');return false;
                        size = size + 2;
                        if (size >= 20) {
                            size = 20;
                        }
                    } else if ($(this).hasClass("minus")) {
                        size = size - 2;
                        if (size <= 10) {
                            size = 10;
                        }
                    } else {
                        size = 14;
                    }
                    $('#contentfont').css("font-size", size);
                });
            });
        </script>

        <script type="text/javascript">
            var imageAddr = "http://www.kenrockwell.com/contax/images/g2/examples/31120037-5mb.jpg";
            var downloadSize = 4995374;

            function ShowProgressMessage(msg) {
                if (console) {
                    if (typeof msg == "string") {
                        console.log(msg);
                    } else {
                        for (var i = 0; i < msg.length; i++) {
                            console.log(msg[i]);
                        }
                    }
                }

                var oProgress = document.getElementById("progress");
                if (oProgress) {
                    var actualHTML = (typeof msg == "string") ? msg : msg.join("&nbsp;&nbsp;");
                    oProgress.innerHTML = actualHTML;
                }
            }

            function InitiateSpeedDetection() {
                ShowProgressMessage("Please wait...!!!");
                window.setTimeout(MeasureConnectionSpeed, 1);
            }
            ;

            if (window.addEventListener) {
                window.addEventListener('load', InitiateSpeedDetection, false);
            } else if (window.attachEvent) {
                window.attachEvent('onload', InitiateSpeedDetection);
            }

            function MeasureConnectionSpeed() {
                var startTime, endTime;
                var download = new Image();
                download.onload = function () {
                    endTime = (new Date()).getTime();
                    showResults();
                }

                download.onerror = function (err, msg) {
                    ShowProgressMessage("Invalid image, or error downloading");
                }

                startTime = (new Date()).getTime();
                var cacheBuster = "?nnn=" + startTime;
                download.src = imageAddr + cacheBuster;

                function showResults() {
                    var duration = (endTime - startTime) / 1000;
                    var bitsLoaded = downloadSize * 8;
                    var speedBps = (bitsLoaded / duration).toFixed(2);
                    var speedKbps = (speedBps / 1024).toFixed(2);
                    var speedMbps = (speedKbps / 1024).toFixed(2);
                    ShowProgressMessage([
                        "Network speed:", speedMbps + " Mbps"

                    ]);
                }
            }
        </script>

        <script>
            function fnlangaugechange1(languagecode, languagetext) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>Users/langaugechange",
                    data: {'language': languagecode, 'languagetext': languagetext},
                    success: function (data) {

                        window.location.reload();
                    }
                });
            }
        </script>

        <style type="text/css">
            #myBtn {
                display: none;
                position: fixed;
                bottom: 20px;
                right: 30px;
                z-index: 99;
                border: none;
                outline: none;
                background-color: #00c0ef;
                color: white;
                cursor: pointer;
                padding: 15px;
                border-radius: 25px;
            }

            #myBtn:hover {
                background-color: #555;
            }
        </style>

        <script type="text/javascript">
            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function () {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("myBtn").style.display = "block";
                } else {
                    document.getElementById("myBtn").style.display = "none";
                }
            }

            // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        </script>
    </head>
    <body class="skin-yellow sidebar-mini" id="contentfont">
        <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
        <div class="wrapper">
            <!--***********************************####  Header Start  ###*******************************************/--> 
            <header class="main-header">
                <!-- Logo -->
                <a href="" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini Nlogo">
                        <b>N</b>                    
                    </span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="hvr-bounce-out"><b>NGDRS</b> </span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php if ($this->Session->check('Auth.User')) { ?>
                                <li>
                                    <a>
                                        <?php
                                        $ofcdesc = $this->requestAction(array('controller' => 'Users', 'action' => 'officedisplay'));
                                        if (!empty($ofcdesc)) {

                                            echo "<b style='color:white;'><big>" . $ofcdesc[0][0]['office_name_en'];
                                            echo "</big></b>";
                                        }
                                        ?>
                                    </a> </li>
                            <?php }
                            ?>
                            <li>
                                <a class="skip-main" href="#skiptomaincontent">Skip to Main Content</a>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <!--                            <li>
                                                            <div class="dropdown pull-right Sel_lang">
                             
                                                                <button class="dropdown-toggle" type="button" data-toggle="dropdown">Select Language
                                                                    <span class="caret"></span></button>
                                                                <ul class="dropdown-menu">
                            <?php
                            $langaugelist = $this->requestAction(array('controller' => 'Users', 'action' => 'language'));
                            foreach ($langaugelist as $langauge1) :
                                $langcode = $langauge1[0]['language_code'];
                                $langtext = $langauge1[0]['language_name'];
                                ?>
                                                                            <li>
                                                                                <a href="javascript:fnlangaugechange1('<?php echo $langcode; ?>','<?php echo $langtext . $langauge1[0]['language_name']; ?>')"> <?php echo $langtext; ?></a>
                                                                            </li>
                                
                            <?php endforeach; ?>
                                                                        
                                                                </ul>
                                                                
                                                                 
                                                            </div> 
                                                        </li>-->
                            <li style="margin-top: 13px;">
                                <select class="input-sm" id="menulangselect">


                                    <?php
                                    $lang = $this->Session->read("sess_langauge");

                                    $langaugelist = $this->requestAction(array('controller' => 'Users', 'action' => 'language'));
                                    foreach ($langaugelist as $langauge1) :
                                        $selected = '';
                                        $langcode = $langauge1[0]['language_code'];
                                        $langtext = $langauge1[0]['language_name'];
                                        if ($lang == $langcode) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $langcode; ?>" <?php echo $selected; ?>><?php echo $langtext; ?></option>

                                    <?php endforeach; ?> 
                                </select>
                            </li>  
                            <li>
                                <a class="font-button plus">A+</a>
                            </li>    
                            <li>
                                <a class="font-button original">A</a>
                            </li> 
                            <li>
                                <a class="font-button minus">A-</a>
                            </li>
                            <li class="dropdown user user-menu">
                                <?php if ($this->Session->check('Auth.User')) { ?>
                                    <?php
                                    $citizen = $this->Session->read("session_usertype");
//                                    if (!empty($citizen) && $this->Session->read("session_usertype") != 'C') {
//                                        $user_id = $this->Session->read('Auth.User.user_id');
//                                        $this->requestAction(array('controller' => 'Users', 'action' => 'check_login_status', $user_id));
//                                    }
                                    ?>
                                    <a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">
                                        <?php echo $this->Html->image('businessman.png', array('class' => 'user-image')); ?>
                                        <span class = "hidden-xs"><?php echo $this->Session->read('Auth.User.username'); ?></span>
                                    </a>
                                <?php }
                                ?>
                                <ul class = "dropdown-menu">
                                    <li class = "user-header">
                                        <?php echo $this->Html->image('businessman.png', array('class' => 'img-circle')); ?>
                                        <p>
                                            <?php
                                            if ($this->Session->check('Auth.User')) {
                                                echo 'Welcome : ' . $this->Session->read('Auth.User.username');
                                                $date1 = $this->Session->read('Auth.User.user_active_date');
                                                // echo '<small>Member since : ' . date("d/m/Y", strtotime($date1));
                                                // echo '</small>';
                                            }
                                            ?>
                                        </p>
                                    </li>
                                    <?php if ($this->Session->check('Auth.User')) { ?>
                                        <li class = "user-footer"> 
                                            <!--                                            <div class = "pull-left">
                                                                                            <a href = "#" class = "btn btn-primary">Profile</a>
                                                                                        </div>-->
                                            <!--                                            <div class = "pull-right">
                                            <?php // echo "<a class='btn btn-primary' href='" . $this->webroot . 'Users' . "/" . 'logout' . "'>" . 'Sign out' . "</a>";
                                            ?>
                                                                                        </div>-->

                                            <div class = "pull-right">
                                                <?php
                                                if (isset($_SESSION["csrfoutkey"]) and ! is_null($_SESSION["csrfoutkey"])) {
                                                    $csrfoutkey = $_SESSION["csrfoutkey"];
                                                } else {
                                                    $_SESSION["csrfoutkey"] = $csrfoutkey = rand(1111, 9999);
                                                }

                                                $citizen = $this->Session->read("session_usertype");
                                                if (!empty($citizen) && $this->Session->read("session_usertype") == 'C') {
                                                    echo "<a class='btn btn-primary' href='" . $this->webroot . 'Citizenentry' . "/" . 'citizenlogout' . "/" . $csrfoutkey . "'>" . 'Sign out' . "</a>";
                                                } else {
                                                    echo "<a class='btn btn-primary' href='" . $this->webroot . 'Users' . "/" . 'logout' . "/" . $csrfoutkey . "'>" . 'Sign out' . "</a>";
                                                }
                                                ?>

                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--***********************************####  Header End...  ###*******************************************/-->                    

            <!--***********************************####  Left Bar Start  ###*******************************************/-->        
            <aside class="main-sidebar">
                <section class="sidebar">

                    <!--<div class="user-panel" style="height: 65px;">-->
                    <div class="pull-left image">
                        <div style="width:30%"> 
                             <?php
                        $statelogo = $this->requestAction(array('controller' => 'Masters', 'action' => 'statelogo'));
                        if (isset($statelogo) && (!empty($statelogo))) {
                            echo $this->Html->image($statelogo['logo_path'], array('class' => 'img-responsive', 'style' => 'padding:5px;float: left;'));
                        }
                        ?>
                        </div>
                       



                        <!--<div class="pull-left info">-->
                        <?php
                        $statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
                        if (isset($statename) && (!empty($statename))) {
                           echo "<p class='text-primary' ><big><b><font size='2'>" . $statename[0][0]['dept_name']." " . __('lblgovtof') ." ". $statename[0][0]['state_name'] . "</font></b></big></p>";
                        } else {
                           echo "<p class='text-primary' style='content-align:center'><big><b><font size='2'> (Testing) </font></b></big></p>";
                        }

                        if ($this->Session->check('Auth.User')) {
                            $roledesc = $this->requestAction(array('controller' => 'Users', 'action' => 'roledisplay'));
                            if (isset($roledesc) && (!empty($roledesc))) {
                                if ($roledesc[0][0]['role_id'] == 999999) {
                                    ?>
                                    <p><b><?php echo $roledesc[0][0]['role_name_en']; ?></b></p>
                                    <?php
                                } else {
                                    echo '';
                                }
                            }
                        }
                        ?>

                    </div>
                    <!--</div>-->

                    <!--</div>-->
                    <div class="clearfix"></div>
<?php $modelapplication = $this->Session->read("session_redirect"); ?>
<?php if ($modelapplication == 'welcomemodel') { ?>
                        <div id="skipnavigation">
                        </div>
                    <?php } else { ?>
                        <div id="skipnavigation">
                        <?php echo $this->Element('navigation'); ?>
                        </div> <?php } ?>
                </section>
            </aside>
            <!--***********************************####  Left Bar End  ###*******************************************/-->

            <!--***********************************####  Middle containter start  ###********************************/-->
            <div class="content-wrapper">
                <div class="row">
<?php
echo $this->Html->image('h1.jpg', array('class' => 'img-responsive', 'width' => '100%', 'id' => 'header-img'));
?>
                </div>

                <section class="content" id="skiptomaincontent">
                    <big style="color: red; text-align: center"> <b><?php echo $this->Session->flash(); ?></b></big>

<?php echo $this->fetch('content'); ?>
                </section>  
            </div>
            <!--***********************************####  Middle containter End..  ###**************************/-->

            <!--***********************************####  Footer  ###*******************************************/-->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
<?php echo $this->Html->image('DI.png', array('class' => 'img-responsive')); ?>
                </div>
                <div class="pull-left hidden-xs">
                    <?php echo $this->Html->image('nic_logo.png', array('class' => 'img-responsive', 'height' => '100px', 'width' => '100px')); ?>
                </div>
                <center>
                    Site designed and developed by <a href="http://www.nic.in/" target="_blank">National Informatics Centre</a><br>
                    Contents provided and maintained by 
                    <!--Department of Revenue, Government of -->
<?php
$statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
// pr($statename);exit;

if (isset($statename) && (!empty($statename))) {
    echo $statename[0][0]['dept_name'] . ' ' . $statename[0][0]['state_name'];
} else {
    echo ' (Testing)';
}


/* if (!$this->Session->check('Auth.User')) {
  if (isset($statename) && (!empty($statename))) {
  echo $statename[0][0]['state_name'];
  }
  } else {
  if (isset($statename) && (!empty($statename))) {
  echo $statename[0][0]['state_name'];
  }
  } */
?>
                    <br>

                </center>  
                <div class="rowht"></div>
                <div class="hr1"></div>
                <div class="row" >
                    <div class="form-group" >
                        <div class="col-sm-3"><h4 id='progress' style="color: red" class="">&nbsp;Your browser is very slow.</h4></div>
                        <!--<div class="col-sm-3"><h4><a href="https://www.google.com/inputtools/windows/" class="" target="_blank"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Google Indic Keyboard</a></h4></div>-->
                    </div>
                </div>

            </footer>
            <!--***********************************####  Footer End ###*******************************************/-->

            <!--********************************### Right Side Bar Start  ###*******************************************/-->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Create the tabs -->
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <!--      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                  <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>-->
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Activity 1</h4>

                                        <p>Will be 23 on April 24th</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="menu-icon fa fa-user bg-yellow"></i>

                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Activity 2</h4>

                                        <p>Execution time 5 seconds</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Activity 3</h4>

                                        <p>Execution time 5 seconds</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Activity 4</h4>

                                        <p>Execution time 5 seconds</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- /.control-sidebar-menu -->

                        <h3 class="control-sidebar-heading">Tasks Progress</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:void(0)">
                                    <h4 class="control-sidebar-subheading">
                                        Design
                                        <span class="label label-danger pull-right">70%</span>
                                    </h4>

                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <h4 class="control-sidebar-subheading">
                                        Development
                                        <span class="label label-success pull-right">95%</span>
                                    </h4>

                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <h4 class="control-sidebar-subheading">
                                        Security
                                        <span class="label label-warning pull-right">50%</span>
                                    </h4>

                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <h4 class="control-sidebar-subheading">
                                        Testing
                                        <span class="label label-primary pull-right">68%</span>
                                    </h4>

                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- /.control-sidebar-menu -->

                    </div>
                    <!-- /.tab-pane -->
                    <!-- Stats tab content -->
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <!-- /.tab-pane -->
                    <!-- Settings tab content -->
                    <div class="tab-pane" id="control-sidebar-settings-tab">
                        <form method="post">
                            <h3 class="control-sidebar-heading">General Settings</h3>

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Report panel usage
                                    <input type="checkbox" class="pull-right" checked>
                                </label>

                                <p>
                                    Some information about this general settings option
                                </p>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Allow redirect
                                    <input type="checkbox" class="pull-right" checked>
                                </label>

                                <p>
                                    Other sets of options are available
                                </p>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Expose name in posts
                                    <input type="checkbox" class="pull-right" checked>
                                </label>

                                <p>
                                    Allow the user to show his name in posts
                                </p>
                            </div>
                            <!-- /.form-group -->

                            <h3 class="control-sidebar-heading">Other Settings</h3>

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Show me as online
                                    <input type="checkbox" class="pull-right" checked>
                                </label>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Turn off notifications
                                    <input type="checkbox" class="pull-right">
                                </label>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Delete chat history
                                    <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                                </label>
                            </div>
                            <!-- /.form-group -->
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
            <!--********************************### Right Side Bar End   ###*******************************************/-->  
        </div><!-- ./wrapper -->


<?php
echo $this->Html->script('SkinLayoutSetting/skin_layout_setting');           // skin_layout_setting Js used for theam changes and for layout setting
echo $this->Html->script('jquery_validationui');                                     // jquery_validationui : this file used in login form only 
echo $this->Html->script('sha256');
echo $this->Html->script('bootstrap-datepicker.min');               // used for bootstrap datepicker
echo $this->Html->script('jquery.dataTables.min');      // Required in Usage Category & location mapping page 
echo $this->Html->script('select2.full');                           // Select2.full required for usage category & location mapping
echo $this->Html->script('dataTables.bootstrap.min');
echo $this->Html->script('ajaxLoader/ajaxloader.min');             // Reuired for to show the loader image
echo $this->Element('Validationscript/dynamicscript_new');          // Validation Script
echo $this->Element('Validationscript/server_message_display');     // Required for to show server side validation message                 
?>
    </body>
</html>
