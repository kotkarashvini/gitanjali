<?php


if (isset($arr_new['Register2data']) && !empty($arr_new['Register2data'])) {
    
   
   
    
    ?>

    <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                <th><?php echo 'Srno'; ?></th>
                <th><?php echo 'District Name'; ?></th>
                <th><?php echo 'circle Name'; ?></th>
                <th><?php echo 'Halka Name'; ?></th> 
                 <th><?php echo 'Mauja Name'; ?></th>
                <th><?php echo 'Volume'; ?></th>           
                <th><?php echo 'Page No'; ?></th>
               
                </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            ?>
           
             <td>
                                <?php echo $i; ?>  
                            </td>
                
                            
                            <td>
                                <?php  if(isset($arr_new['Register2data']['dst_name'])) {echo $arr_new['Register2data']['dst_name']; }?>  
                            </td>
                            <td>
                                <?php if(isset($arr_new['Register2data']['circle_name'])){echo $arr_new['Register2data']['circle_name']; }?>  
                            </td>
                              <td>
                                <?php if(isset($arr_new['Register2data']['halka_name'])){echo $arr_new['Register2data']['halka_name'];} ?>  
                            </td>
                            <td> <?php if(isset($arr_new['Register2data']['mauja_name'])){echo $arr_new['Register2data']['mauja_name'];} ?></td>		
                            <td>
                                <?php if(isset($arr_new['Register2data']['volume_cur'])){echo $arr_new['Register2data']['volume_cur']; }?>  
                            </td>
                            <td>
                                <?php if(isset($arr_new['Register2data']['page_no_cur'])){echo $arr_new['Register2data']['page_no_cur'];} ?>  
                            </td>
                            

                          

                        </tr>

          
 
        </tbody>
    </table>

<h4>Property Details</h4>

  <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                 <th><?php echo 'Sr no.'; ?></th>
                <th><?php echo 'Khata Number'; ?></th>
                <th><?php echo 'Plot Number'; ?></th>
                <th><?php echo 'Area 1'; ?></th>
                <th><?php echo 'Area 1 Unit'; ?></th> 
                 <th><?php echo 'Area 2 '; ?></th>
                <th><?php echo 'Area 2 Unit'; ?></th>  
                 <th><?php echo 'Area 3 '; ?></th>
                <th><?php echo 'Area 3 Unit'; ?></th>  
                  <th><?php echo 'Transfer Details'; ?></th>  
                   <th><?php echo 'Cess'; ?></th>  
                    <th><?php echo 'Tax'; ?></th>  
               
               
                </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
if(!empty($arr_new['Register2data']['PropertyDt'])){
     $prop=$arr_new['Register2data']['PropertyDt'];


          
            ?>
           
             <td>
                                <?php echo $i; ?>  
                            </td>
                
                            
                            <td>
                                <?php echo $prop['Property']['khata_no']; ?>  
                            </td>
                            <td>
                                <?php echo $prop['Property']['plot_no']; ?>  
                            </td>
                              <td>
                                <?php echo $prop['Property']['Area1']; ?>  
                            </td>
                            <td> <?php echo $prop['Property']['Area1_Unit']; ?></td>		
                            <td>
                                <?php echo $prop['Property']['Area2']; ?>  
                            </td>
                            <td>
                                <?php echo $prop['Property']['Area2_Unit']; ?>  
                            </td>
                            <td>
                                <?php echo $prop['Property']['Area3']; ?>  
                            </td>
                            <td>
                                <?php echo $prop['Property']['Area3_Unit']; ?>  
                            </td>
                            <?php if(!empty($arr_new['Register2data']['reg2final']['FinalTax'])){ 
                                $tax=$arr_new['Register2data']['reg2final']['FinalTax'];
                                ?>
                             <td>
                                <?php echo $tax['transf_det']; ?>  
                            </td>
                             <td>
                                <?php echo $tax['cess']; ?>  
                            </td>
                             <td>
                                <?php echo $tax['tax']; ?>  
                            </td>
                            
                            <?php } ?>
                          

                        </tr>
<?php  $i++; } ?>
          
 
        </tbody>
    </table>

<h4>Owner Details</h4>

<table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                 <th><?php echo 'Sr no.'; ?></th>
                <th><?php echo 'Owner Name'; ?></th>
                <th><?php echo 'Gurdian Name'; ?></th>
               
                </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if(!empty($arr_new['Register2data']['Owner']['Owners'])){
     $owner=$arr_new['Register2data']['Owner']['Owners'];
            foreach($owner as $party){
          
            ?>
            <tr>
             <td>
                                <?php echo $i; ?>  
                            </td>
                
                            
                            <td>
                                <?php echo $party['Owner_Name']; ?>  
                            </td>
                            <td>
                                <?php echo $party['Gurdian_Name']; ?>  
                            </td>
                            
                            

                          

                        </tr>
                        
            <?php $i++; }}?>

          
 
        </tbody>
    </table>
    <?php
}?>