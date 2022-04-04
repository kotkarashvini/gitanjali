
<div class="row">
    <div class="error-page">
         <h2 class="headline text-red"></h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
            <p>
                We will work on fixing that right away.
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
                 <h3 class="box-title">Error Messages <?php echo __d('cake_dev', 'Missing Database Connection'); ?></h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info alert-dismissible">
                    
                    <h4> <?php echo $this->Session->flash(); ?>
                        
                        	<strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
                                <?php echo __d('cake_dev', 'A Database connection using "%s" was missing or unable to connect.', h($class)); ?>
                                <br />
                                <?php
                                if (isset($message)):
                                        echo __d('cake_dev', 'The database server returned this error: %s', h($message));
                                endif;
                                ?>
                                <?php if (!$enabled) : ?>
                                <p class="error">
                                        <strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
                                        <?php echo __d('cake_dev', '%s driver is NOT enabled', h($class)); ?>
                                </p>
                                <?php endif; ?>
                                <p class="notice">
                                        <strong><?php echo __d('cake_dev', 'Notice'); ?>: </strong>
                                        <?php echo __d('cake_dev', 'If you want to customize this error message, create %s', APP_DIR . DS . 'View' . DS . 'Errors' . DS . basename(__FILE__)); ?>
                                </p>

                                <?php
                                echo $this->element('exception_stack_trace');
                                ?>
                     
                    </h4>

                </div>
            </div>
        </div>
    </div>
</div>
