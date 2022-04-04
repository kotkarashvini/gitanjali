<div class="row">
<div class="col-md-4" >

    <form action="https://ngdrsgoa.gov.in/GAWebService/ngdrsgoaapi1" method="post">
       <!--<form action="https://10.189.47.185/GAWebService/ngdrsgoaapi1" method="post">-->  
        
        
  <div class="form-group">
    <label for="email">Username</label>
    <input type="text" class="form-control" id="api_username" name="api_username" value="apiuser001">
  </div>
   <div class="form-group">
    <label for="email">Password</label>
    <input type="text" class="form-control" id="api_username" name="api_password" value="9019a18d85025cdd33981b28e38848c4e4a82e60ddc5c9005a0e0db032bdaa2a" >
  </div>
    <div class="form-group">
    <label for="email">Doc_Reg_Number</label>
    <input type="text" class="form-control" id="Doc_Reg_Number" name="Doc_Reg_Number" >
  </div>
    <div class="form-group">
    <label for="email">Doc_Reg_Date</label>
    <input type="text" class="form-control" id="Doc_Reg_Date" name="Doc_Reg_Date" >
  </div>
    <div class="form-group">
    <label for="email">Taluka_Name</label>
    <input type="text" class="form-control" id="Taluka_Name" name="Taluka_Name" >
  </div>
    <div class="form-group">
    <label for="email">Village_Name</label>
    <input type="text" class="form-control" id="Village_Name" name="Village_Name" >
  </div>
    <div class="form-group">
    <label for="email">Seller_Name</label>
    <input type="text" class="form-control" id="Seller_Name" name="Seller_Name" >
  </div>
    <div class="form-group">
    <label for="email">Buyer_Name</label>
    <input type="text" class="form-control" id="Buyer_Name" name="Buyer_Name" >
  </div>
    
    <div class="form-group">
    <label for="email">Mutation_Date</label>
    <input type="text" class="form-control" id="Mutation_Date" name="Mutation_Date" >
  </div>
    <div class="form-group">
    <label for="email">Survey_Number</label>
    <input type="text" class="form-control" id="Survey_Number" name="Survey_Number" >
  </div>
    
     <div class="form-group">
    <label for="email">Subdivision_Number</label>
    <input type="text" class="form-control" id="Subdivision_Number" name="Subdivision_Number" >
  </div>
    
  <button type="submit" class="btn btn-default">Submit</button>
</form> 
        
</div>
    <div class="col-md-8">
        <?php echo @$result;?>
    </div>
    </div> 