<!--
<div class="row">
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="<?php echo $this->webroot;?>"><h4>return to Home Page</h4></a> 
            </p>
        </div>
      </div>
</div>

<div class="row">
    <div class="error-page">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-warning"></i>
                

                <h3 class="box-title">Error Messages</h3>
            </div>
            <div class="box-body">

                <div class="alert alert-info alert-dismissible">                    
                    <h4>Page not found !!!</h4>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<div class="row">
    <div class="error-page">


        <div style="margin-bottom: 10px;" >


            <a href="<?php echo $this->webroot; ?>">
                <?php echo $this->Html->image('4003.png', array('class' => '', 'width' => '70%', 'height' => '50%')); ?>
            </a> 
            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="<?php echo $this->webroot; ?>"><h4>return to Home Page</h4></a> 
         
        </div>
    </div>


</div>

<div class="row">
    <div class="error-page">
        <div class="box box-default">
            <div style="color:#CC0000">
                <i class="fa fa-warning"></i>
                <h3 class="box-title"></h3>
            </div>
        </div>
    </div>
</div>