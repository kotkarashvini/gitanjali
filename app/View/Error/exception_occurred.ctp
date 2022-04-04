
<div class="row">
    <div class="error-page">
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
                 <h3 class="box-title">Error Messages</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4> <?php echo $this->Session->flash(); ?> </h4>
                </div>
            </div>
        </div>
    </div>
</div>