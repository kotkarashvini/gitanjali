
<div class="row">
    <div class="error-page">
        <!--<h2 class="headline text-red"> 500</h2>-->
        <!--<div class="error-content">-->
            <!--<h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>-->
        <?php echo $this->Html->image('somthinwentwrong1.jpg', array('class' => 'img-circle')); ?>
        <p>
            Please try after sometime.
            <a href=" <?php echo $this->webroot; ?> "> <h4>Return to Home Page</h4> </a>

        </p>
        <!--</div>-->
    </div>
</div>
<!--<div class="row">
    <div class="error-page">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-warning"></i>
                 <h3 class="box-title">Error Messages <?php echo __d('cake_dev', 'Fatal Error'); ?></h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info alert-dismissible">
                    
                    <h4> <?php echo $this->Session->flash(); ?>
                        
                                <strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
<?php echo h($error->getMessage()); ?>


                                        <strong><?php //echo __d('cake_dev', 'File');   ?>: </strong>
<?php //echo h($error->getFile()); ?>
                                        <br>

                                        <strong><?php //echo __d('cake_dev', 'Line');   ?>: </strong>
<?php //echo h($error->getLine()); ?>
                        
                    </h4>

                </div>
            </div>
        </div>
    </div>
</div>-->
