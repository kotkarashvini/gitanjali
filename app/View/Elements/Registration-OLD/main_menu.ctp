<?php
if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {

    $mainresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'main_menu'));
    $subresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'sub_menu'));
    $subsubresult = $this->requestAction(array('controller' => 'Registration', 'action' => 'subsub_menu'));


    $mainmenuflag = "";
    $submenuflag = "";
    $submenuid="";
    $mainmenuid="";
    foreach ($subsubresult as $subsubmenu) {
        if ($this->params['action'] == $subsubmenu['RegistrationSubsubmenu']['action']) {
            $submenuflag = 'Y';
            $submenuid=$subsubmenu['RegistrationSubsubmenu']['submenu_id'];
        }
    }
    
    foreach ($subresult as $submenu) {
        if ($submenu['RegistrationSubmenu']['submenu_id'] == $submenuid) {
            $mainmenuflag = 'Y';
            $mainmenuid=$submenu['RegistrationSubmenu']['mainmenu_id'];
        }
    }
    
    ?>


    <div class="btn-group btn-group-justified ">
        <nav>
            <ul class="nav nav-justified">
    <?php
     
      foreach ($mainresult as $menu) {
        if ($this->params['action'] == $menu['RegistrationMainmenu']['action'] || $mainmenuid == $menu['RegistrationMainmenu']['mainmenu_id']) {
            ?> 
                        <li class="active">  <a href="<?php echo $this->webroot; ?><?php echo $menu['RegistrationMainmenu']['controller'] . "/" . $menu['RegistrationMainmenu']['action']; ?>" ><?php echo $menu['RegistrationMainmenu']['mainmenu_desc_en']; ?></a> </li>           
                    <?php } else { ?>              
                        <li>  <a href="<?php echo $this->webroot; ?><?php echo $menu['RegistrationMainmenu']['controller'] . "/" . $menu['RegistrationMainmenu']['action']; ?>" ><?php echo $menu['RegistrationMainmenu']['mainmenu_desc_en']; ?></a></li>
                        <?php
                    }
                }
     
                ?>  
            </ul>
        </nav>
    </div>
    <div  class="rowht">&nbsp;</div>

    
    <?php
    if ($submenuflag == 'Y') {
        ?>

        <div class="row">  
           
            <div class="col-sm-12">

                <div class="btn-group  btn-group-justified">
                    <nav>
                        <ul class="nav nav-justified btn-breadcrumb">
        <?php
        foreach ($subresult as $submenu) {
            $nextlink = "";
             
            foreach ($subsubresult as $subsubmenu) {
                if ($submenu['RegistrationSubmenu']['submenu_id'] == $subsubmenu['RegistrationSubsubmenu']['submenu_id']) {
                    $nextlink = $subsubmenu['RegistrationSubsubmenu']['controller']."/".$subsubmenu['RegistrationSubsubmenu']['action'];
                }
            }
            if (!empty($nextlink)) {
                if ( $submenu['RegistrationSubmenu']['submenu_id'] == $submenuid) {
                    ?> 
                                        <li class="active">  <a href="<?php echo $this->webroot; ?><?php echo  $nextlink; ?>" class=""><?php echo $submenu['RegistrationSubmenu']['submenu_desc_en']; ?></a>            </li>
                                    <?php } else { ?>              
                                        <li>        <a href="<?php echo $this->webroot; ?><?php echo  $nextlink; ?>" class=""><?php echo $submenu['RegistrationSubmenu']['submenu_desc_en']; ?></a></li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul></nav></div>
            </div>

        </div> 
    <?php } ?> 

    <div  class="rowht">&nbsp;</div>
    <?php if (!empty($submenuid)) {
        ?>
        <div class="row">  
         
            <div class="col-sm-12">

                <div class="btn-group  btn-group-justified">
                    <nav>
                        <ul class="nav nav-justified btn-breadcrumb">
        <?php
        foreach ($subsubresult as $subsubmenu) {
            if ($subsubmenu['RegistrationSubsubmenu']['submenu_id'] == $submenuid) {

                if ($this->params['action'] == $subsubmenu['RegistrationSubsubmenu']['action']) {
                    ?> 
                                        <li class="active">    <a href="<?php echo $this->webroot; ?><?php echo $subsubmenu['RegistrationSubsubmenu']['controller'] . "/" . $subsubmenu['RegistrationSubsubmenu']['action']; ?>" class=""><?php echo $subsubmenu['RegistrationSubsubmenu']['subsubmenu_desc_en']; ?></a>   </li>         </li>
                                    <?php } else { ?>              
                                        <li>   <a href="<?php echo $this->webroot; ?><?php echo $subsubmenu['RegistrationSubsubmenu']['controller'] . "/" . $subsubmenu['RegistrationSubsubmenu']['action']; ?>" class=""><?php echo $subsubmenu['RegistrationSubsubmenu']['subsubmenu_desc_en']; ?></a> </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    <?php } ?> 

    
<?php } ?>
<?php
//pr($documents);
if (isset($documents)) {
    if (!empty($documents[0][0]['stamp1_date'])) {
        ?>
        <script type="text/javascript">
            $(function () {
                $("#timediff").show();
                setInterval(function () {
                    var dateFuture =  new Date();;
                    var dateNow = new Date("<?php echo $documents[0][0]['stamp1_date']; ?>");

                    var seconds = Math.floor((dateFuture - (dateNow)) / 1000);
                    var minutes = Math.floor(seconds / 60);
                    var hours = Math.floor(minutes / 60);
                    var days = Math.floor(hours / 24);

                    hours = hours - (days * 24);
                    minutes = minutes - (days * 24 * 60) - (hours * 60);
                    seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);
                    $("#timediff").html("Time : " + hours + " : " + minutes + " : " + seconds);

                }, 1000);
            });
                    
        
        
        </script>
        
        <?php
    }
}
?>
    
<div class="digitaltime pull-right" id="timediff"> </div> 
<?php
//pr($documents);
if (isset($documents)) {
    if (!empty($documents[0][0]['stamp1_date'])) {
        ?>
 <script type="text/javascript">

 
        $(document).ready(function() {
         $(".analog-clock-popup").draggable();
           
              setInterval( function() {
               //-------------------new Date().getSeconds();
                    var dateFuture = new Date();
                    var dateNow = new Date("<?php echo $documents[0][0]['stamp1_date']; ?>");
                    var seconds = Math.floor((dateFuture - (dateNow)) / 1000);
                    var minutes = Math.floor(seconds / 60);
                    var hours = Math.floor(minutes / 60);
                    var days = Math.floor(hours / 24);

                    hours = hours - (days * 24);
                   var minutes = minutes - (days * 24 * 60) - (hours * 60);
                    seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);
              //-----------------
              var sdegree = seconds * 6;
              var srotate = "rotate(" + sdegree + "deg)";
              
              $("#sec").css({"-moz-transform" : srotate, "-webkit-transform" : srotate});
                  
              }, 1000 );
              
         
              setInterval( function() {
              //var hours = new Date().getHours();
             // var mins = new Date().getMinutes();
              //-------------------
                    var dateFuture = new Date();
                    var dateNow = new Date("<?php echo $documents[0][0]['stamp1_date']; ?>");
                    var seconds = Math.floor((dateFuture - (dateNow)) / 1000);
                    var minutes = Math.floor(seconds / 60);
                    var hours = Math.floor(minutes / 60);
                    var days = Math.floor(hours / 24);

                    hours = hours - (days * 24);
                   var minutes = minutes - (days * 24 * 60) - (hours * 60);
                    seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);
              //-----------------
              
              var hdegree = hours * 30 + (minutes / 2);
              var hrotate = "rotate(" + hdegree + "deg)";
              
              $("#hour").css({"-moz-transform" : hrotate, "-webkit-transform" : hrotate});
                  
              }, 1000 );
        
        
              setInterval( function() {
               var dateFuture = new Date();
                    var dateNow = new Date("<?php echo $documents[0][0]['stamp1_date']; ?>");
                     var seconds = Math.floor((dateFuture - (dateNow)) / 1000);
                    var minutes = Math.floor(seconds / 60);
                    if(minutes>15)
                    {
//                        $(".analog-clock-popup").toggleClass("flash");
//                        $("#clock").toggleClass("clock1");
                    }
              var mdegree = minutes * 6;
              var mrotate = "rotate(" + mdegree + "deg)";
              
              $("#min").css({"-moz-transform" : mrotate, "-webkit-transform" : mrotate});
                  
              }, 1000 );
         
        });     
 </script>
    <div class="analog-clock-popup  pull-left" id="clockdiv"> 
        <ul id="clock" class="clock">	
	   	<li id="sec"></li>
	   	<li id="hour"></li>
		<li id="min"></li>
	</ul>
            </div>
 <style type="text/css">
        * {
        	margin: 0;
        	padding: 0;
        }
        
        .clock {
        	position: relative;
        	width: 150px;
        	height: 150px;
        	margin: 20px auto 0 auto;
        	background: url(<?php echo $this->webroot; ?>img/clockface.png);
        	list-style: none;
        	}
                .clock1 {
        	position: relative;
        	width: 150px;
        	height: 150px;
        	margin: 20px auto 0 auto;
        	background: url(<?php echo $this->webroot; ?>img/clock1.png);
        	list-style: none;
        	}
        
        #sec, #min, #hour {
        	position: absolute;
        	width: 7.5px;
        	height: 150px;
        	top: 0px;
        	left: 70px;
        	}
        
        #sec {
        	background: url(<?php echo $this->webroot; ?>img/sechand.png);
        	z-index: 3;
           	}
           
        #min {
        	background: url(<?php echo $this->webroot; ?>img/minhand.png);
        	z-index: 2;
           	}
           
        #hour {
        	background: url(<?php echo $this->webroot; ?>img/hourhand.png);
        	z-index: 1;
           	}
           	
        p {
            text-align: center; 
            padding: 10px 0 0 0;
            }
            
            
            
            
    </style>
    
      <?php
    }
}
?>