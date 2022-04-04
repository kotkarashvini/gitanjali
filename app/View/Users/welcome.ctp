<!--<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>-->

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

</script>
<!--<div >-->

<!--
<?php echo $this->Form->create('welcome'); ?>

<div class="box-header with-border">
               <h3 class="box-title headbolder"></h3>
               <div class="box-tools pull-right">
                  
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Help Desk</button>
                    
                </div>
               
               
            </div>

<p style="font-size:30px;text-align: center;"><b> Welcome to NGDRS </b></p> <br>

<div class="container">
    <div class="row">
        
        <div class="form-group col-md-4">
    <label for="exampleFormControlSelect1">Enter / Select State Name</label>
    <select class="form-control " id="exampleFormControlSelect1">
        <option><b>------STATE------</b></option>
      <option>Andhra Pradesh</option>
      <option>Arunachal Pradesh</option>
      <option>Assam</option>
      <option>Bihar</option>
      <option>Chhattisgarh</option>
      <option>Goa</option>
      <option>Gujarat</option>
      <option>Haryana</option>
      <option>Himachal Pradesh</option>
      <option>Jharkhand</option>
      <option>Karnataka</option>
      <option>Kerala</option>
      <option>Madhya Pradesh</option>
      <option>Maharashtra</option>
      <option>Manipur</option>
      <option>Meghalaya</option>
      <option>Mizoram</option>
      <option>Nagaland</option>
      <option>Odisha</option>
      <option>Punjab</option>
      <option>Rajasthan</option>
      <option>Sikkim</option>
      <option>Tamil Nadu</option>
      <option>Telangana</option>
      <option>Tripura</option>
      <option>Uttar Pradesh</option>
      <option>Uttarakhand</option>
      <option>West Bengal</option> <br>
      <option> <b>------UNION TERITORY------</b> </option>
      <option>Andaman and Nicobar Islands</option>
      <option>Chandigarh</option>
      <option>Dadra and Nagar Haveli</option>
      <option>Daman and Diu</option>
      <option>Delhi</option>
      <option>Jammu and Kashmir</option>
      <option>Ladakh</option>
      <option>Lakshadweep</option>
      <option>Puducherry</option>
    </select>
      
        </div> 
        <button type="submit" class="btn btn-primary my-1" style="margin-top:23px;">Save</button> 
 Button trigger modal 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-top:23px;">
  Save
</button>

 Modal 
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
          <h5 class="modal-title" id="exampleModalLabel"><b>This will create new state NGDRS instance. Are you sure!!!</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>

  </div>
      
</div>

 <?php echo $this->Form->end(); ?>
-->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color: red; font-weight: bold;">Help Desk</h4>
            <div class="modal-footer">
                 <div class="col-sm-10">
                     <table><tr><td width="50%" style="font-weight: bold; color:blue; padding: 5px;">Email ID :- </td> <td width="50%" style="font-weight: bold">helpdesk.ngdrs@nic.in</td></tr></table>
                 </div>
<!--                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>-->
            </div>
                 <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>
</div>
<!--</div>-->
<script>

    $('button[name="remove_levels"]').on('click', function(e) {
  var $form = $(this).closest('form');
  e.preventDefault();
  $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
  })
  .on('click', '#delete', function(e) {
      $form.trigger('submit');
    });
});

</script>
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

</script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {

//        if (!navigator.onLine)
//        {
//            window.location = '../cterror.html';
//        }
//        function disableBack() {
//            window.history.forward()
//        }
//
//        window.onload = disableBack();
//        window.onpageshow = function (evt) {
//            if (evt.persisted)
//                disableBack()
//        }
    });
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
