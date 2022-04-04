
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
        //  echo $this->Html->script('/bootstrap/js/bootstrap.min');                  //  Bootstrap 3.3.6 
//         echo $this->Html->script('JS');

        /* Above JS file  should be required in all pages */

        /* Following CSS file should be required in all pages */
        // echo $this->Html->css('/bootstrap/css/bootstrap.min'); 

        echo $this->Html->css('bootstrap');

        echo $this->Html->css('custome');
        echo $this->Html->css('fontawesome-all');
        ?> 

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
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            }

        </script>


        <style>
            #myBtn {
                display: none; /* Hidden by default */
                position: fixed; /* Fixed/sticky position */
                bottom: 20px; /* Place the button at the bottom of the page */
                right: 30px; /* Place the button 30px from the right */
                z-index: 99; /* Make sure it does not overlap */
                border: none; /* Remove borders */
                outline: none; /* Remove outline */
                background-color: red; /* Set a background color */
                color: white; /* Text color */
                cursor: pointer; /* Add a mouse pointer on hover */
                padding: 15px; /* Some padding */
                border-radius: 10px; /* Rounded corners */
                font-size: 18px; /* Increase font size */
            }

            #myBtn:hover {
                background-color: #555; /* Add a dark-grey background on hover */
            }


        </style>    






    </head>
    <body>
        <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
        <!----*************************************** subheader**********************************************-->  
        <header id="top" style="background-color:black">
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="#main" style="color:white;font-size: 14px;"> <b>Skip to main content</b></a>
                </li>
                <!--                <li class="nav-item">
                                    <select data-placeholder="Choose a Language..." style="margin-top:5%;">
                                      <option value="AF">Select Language</option>
                                       <option value="SQ">Hindi</option>
                                        <option value="AR">Punjabi</option>
                  
                                      </select>
                  
                                   
                                </li>-->
                <li class="nav-item">
                    <a href="#" class="nav-link text-white " id="small">-A</a>
                </li>
                <li class="nav-item">
                    <a href="#" id="medium" class="selected nav-link text-white">A</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" id="large">A+</a>
                </li>

                <li class="nav-item"> <a href="#" class="nav-link"><?php echo $this->Html->image('./images/black-circle.png', array('class' => 'img-circle', 'onclick' => 'color("#202021")')); ?></a></li>
                <li class="nav-item"> <a href="#" class="nav-link"><?php echo $this->Html->image('./images/blue-circle.png', array('class' => 'img-circle', 'onclick' => 'color("#10248C")')); ?></a></li>
            </ul>

        </header>

        <!----*************************************** subheader end**********************************************-->  

        <div class="container-fluid" id="t">
            <!--hhhh-->
            <div class="row">
                <div class="col-md-3 text-center">
<?php echo $this->Html->image('./images/embelem1.png', array('class' => 'img-fluid',)); ?>  
                </div>
                <div class="col-md-6">
                    <h3 class="text-center"> National Generic Document Registration System</h3>  
                    <h5 class="text-center">Department of Land Resources - Government of India</h5>
                    <h6 class="text-center"><!--Department of Revenue, Government of -->
<?php
$statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));
        if (isset($statename) && (!empty($statename))) {
            echo $statename[0][0]['dept_name'].' '.$statename[0][0]['state_name'];
        }
        else{
            echo ' (Testing)';
        }
//pr($statename);exit;
//                    if (!$this->Session->check('Auth.User')) {
//                        if (isset($statename) && (!empty($statename))) {
//                            echo $statename[0][0]['state_name'];
//                        }
//                    } else {
//                        if (isset($statename) && (!empty($statename))) {
//                            echo $statename[0][0]['state_name'];
//                        }
//                    }
?></h6>
                </div>
                <div class="col-md-3 text-center">

                        <?php
                        $statelogo = $this->requestAction(array('controller' => 'Masters', 'action' => 'statelogo'));
                        //pr($statelogo);exit;
                        if ($this->Session->check('Auth.User')) {

                            if (isset($statelogo) && (!empty($statelogo))) {
                                echo $this->Html->image($statelogo['logo_path'], array('class' => 'img-responsive', 'width' => '90px', 'height' => '100px'));
                            }
                        } else {
//                            echo $this->Html->image('state_logos_img/punjab1.png', array('class' => 'img-responsive', 'width' => '100px', 'height' => '100px', 'id' => 'header-img'));
                        }
                        ?>





<?php //echo $this->Html->image('./images/punjab2.png', array('class' => 'img-fluid'));  ?> 
                </div>
            </div>
        </div>

        <!--********************************************* Title banner********************************************-->

        <!--              <div class="container-fluid">
                        <img src="images/title3.png" alt="img" class="img-fluid" id="banner" width="100%">  
<?php echo $this->Html->image('./images/title3.png', array('class' => 'img-fluid', 'id' => 'banner', 'width' => '100%')); ?>
                      </div>-->

        <!--********************************************* Title banner end ********************************************-->

        <!-----************************************* Menu ****************************************************-->
        <!--    <div id='main-menu' class='main-menu'>
                    <div class='container-menu'>
                      <nav class='navigation'>
                        <span class='hamburger-menu'>Menu
                          <span class='burger-1'></span>
                          <span class='burger-2'></span>
                          <span class='burger-3'></span>
                        </span>
                        <ul class='core-menu'>
                            <li><a href='homepage.html'>Home</a></li>
                            <li><a href='gallery.html'>Videos</a></li>
                            <li><a href='about.html'>About</a></li>
                            <li><a href='contact.html'>Contact</a></li>
                          <li><a href='#'>Dropdown<i class="fas fa-angle-down fa-lg" style="padding-left:5px;padding-top:3px;"></i></a>
                            <ul class='dropdown'>
                              <li><a href='#'>Windows 10<i class="fas fa-angle-down fa-lg" style="padding-left:5px;padding-top:3px;"></i></a></a>
                                <ul class='dropdown2'>
                                  <li><a href='#'>Windows 10 Pro</a></li>
                                  <li><a href='#'>Windows 10 Home</a></li>
                                  <li><a href='#'>Profesional</a></li>
                                </ul>
                              </li>
                              <li><a href='#'>Windows Phone</a></li>
                              <li><a href='#'>Laptop</a></li>
                            </ul>
                          </li>
                          <li><a href='#'>Featured<span class='toggle'></a>
                          <ul class='dropdown'>
                              <li><a href='#'>Cortana</a></li>
                              <li><a href='#'>Xboct</a></li>
                              <li><a href='#'>Microsoft Edge</a></li>
                            </ul>
                          </li> 
                          
                        </ul>
                      </nav>
                    </div>
                  </div>-->
        <!--*************************************** Menu end ********************************************-->

        <!---****************************** Main Start*****************************************************-->
        <div class="container-fluid" >
<?php echo $this->Session->flash(); ?>
<?php echo $this->fetch('content'); ?>
        </div>
        <!--******************* Slider End****************************************************************-->
        <!--*****************************************Footer start*******************************************-->

        <!--footer-->
        <footer id="myFooter" >
            <section id="el">
                <div class="container-fluid">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <a href="https://www.digitalindia.gov.in/" target="_blank">
<!--                                               <img src="images/digitalindia150-x-100.png" alt="digitalIndia" class="img-fluid"> --> 
<?php echo $this->Html->image('./images/digitalindia150-x-100.png', array('class' => 'img-fluid', 'alt' => 'digitalIndia')); ?>
                                </a>
                            </div>
                            <div class="col-md-2 text-center" style="padding-top:13px;">
                                <a href="https://www.nic.in/" target="_blank">
<!--                                               <img src="images/digitalindia150-x-100.png" alt="digitalIndia" class="img-fluid"> --> 
<?php echo $this->Html->image('./images/nic_logo.png', array('class' => 'img-fluid', 'alt' => 'nic')); ?>
                                </a>
                            </div>  
                            <div class="col-md-2 text-center">
                                <a href="https://india.gov.in/" target="_blank"> 
<!--                                                    <img src="images/india150x100.png" alt="indiagov" class="img-fluid">-->  
<?php echo $this->Html->image('./images/india150x100.png', array('class' => 'img-fluid', 'alt' => 'indiagov')); ?>
                                </a>
                            </div>
                            <div class="col-md-2 text-center">
                                <a href="https://rural.nic.in/" target="_blank">
<!--                                                    <img src="images/mord150x100.png" alt="digitalIndia" class="img-fluid"> -->  
<?php echo $this->Html->image('./images/mord150x100.png', array('class' => 'img-fluid', 'alt' => 'mord')); ?>
                                </a>
                            </div>
                            <div class="col-md-2 text-center" style="padding-top:15px">
                                <a href="https://swachhbharatmission.gov.in/" target="_blank"> 
<!--                                                    <img src="images/sba150x100.png" alt="digitalIndia" class="img-fluid">-->  
<?php echo $this->Html->image('./images/sba150x100.png', array('class' => 'img-fluid', 'alt' => 'digitalIndia')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <h5></h5>
                        <ul>
                            <li><a href="<?php echo $this->webroot; ?>/Users/feedback">FeedBack</a></li>
                            <!--                        <li><a href="about.html">About</a></li>
                                                    <li><a href="gallery.html">Videos</a></li>
                                                    <li><a href="contact.html">Contact</a></li>						-->
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <h5></h5>
                        <ul>
                            <!--                        <li> <a href="pdf/dummy.pdf" target="_blank">Website Policies </a></li>
                                                    <li><a href="pdf/dummy.pdf" target="_blank">Sitemap</a></li>-->
                            <li><a href="<?php echo $this->webroot; ?>/pdf/dummy.pdf" target="_blank">User Manual</a></li> 
                            <li><a href="<?php echo $this->webroot; ?>/Users/help">Help (Manuals)</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <h5></h5>
                        <ul>
                            <li><a href="<?php echo $this->webroot; ?>/Users/ngdrsclient"> NGDRS Downloads </a></li>
                            <!--                        <li> <a href="pdf/dummy.pdf" target="_blank">FAQ's</a></li>
                                                    <li><a href="pdf/dummy.pdf" target="_blank">Help</a></li>
                                                    <li><a href="contact.html" target="_blank">Feedback</a> </li>						-->


                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <h5></h5>
                        <ul>
                            <li id='progress'>Your browser is very slow.</li>

                            <!--                        <li><a href="https://www.google.com/inputtools/windows/" target="_blank">  Google Indic Keyboard</a></li>-->
                        </ul>
                    </div>
                </div>


            </div>
            <div class="social-networks">

                <div class="row">

                    <div class="col-md-12">
                        <p>Site designed and developed by <a href="https://www.nic.in/" target="_blank">National Informatics Centre </a><br>
                            Contents provided and maintained by 
                            <!--Department of Revenue, Government of -->
<?php
$statename = $this->requestAction(array('controller' => 'Users', 'action' => 'statedisplay'));

if (isset($statename) && (!empty($statename))) {
    echo $statename[0][0]['dept_name'].' '.$statename[0][0]['state_name'];
}
else{
    echo ' (Testing)';
}
        
//pr($statename);exit;
//if (!$this->Session->check('Auth.User')) {
//    if (isset($statename) && (!empty($statename))) {
//        echo $statename[0][0]['state_name'];
//    }
//} else {
//    if (isset($statename) && (!empty($statename))) {
//        echo $statename[0][0]['state_name'];
//    }
//}
?> </p>

                    </div>


                </div>

            </div>
            <div class="footer-copyright">
                <p> This site is best viewed in 1024 x 768 resolution & above </p>
            </div>

        </footer>
        <!-- //footer-->



        <!--************************************** footer end ***************************************************-->			

<?php
echo $this->Html->script('bootstrap');
echo $this->Html->script('colorswitcher');
echo $this->Html->script('fontawesome-all');
echo $this->Html->script('fontswitcher');
?>


        <script>
            $(document).ready(function () {
                //the trigger on hover when cursor directed to this class
                $(".core-menu li").hover(
                        function () {
                            //i used the parent ul to show submenu
                            $(this).children('ul').slideDown('fast');
                        },
                        //when the cursor away 
                                function () {
                                    $('ul', this).slideUp('fast');
                                });
                        //this feature only show on 600px device width
                        $(".hamburger-menu").click(function () {
                            $(".burger-1, .burger-2, .burger-3").toggleClass("open");
                            $(".core-menu").slideToggle("fast");
                        });
                    });

        </script>



    </body>
</html>
