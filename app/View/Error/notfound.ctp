

<div class="row">
    <div class="error-page">
        <h2 class="headline text-red">500</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-red"></i> Oops! not found.</h3>
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
                    <h4><?php echo $this->Session->flash(); ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>
