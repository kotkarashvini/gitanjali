    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo 'List of State wise Languages'; ?></h3></center>
                </div>

                <div class="box-body">

                    <table class="table table-striped table-bordered table-hover" id="name_list_tbl">
                           <tbody>
                               <tr>
                                   <th>Sr. No</th>
                                   <th>State Name</th>
                                   <th>Language Name</th>
                               </tr>   
                            <tr>

                                <?php
                                    $stid=0;
                                    $stid2=0;
                                    for ($i = 1; $i < count($statelng); $i++) { ?>
                                    <td><?php 
                                    if($statelng[$i][0]['state_id']!=$stid2)
                                    echo $i; 
                                    $stid2=$statelng[$i][0]['state_id'];
                                    ?></td>
                                    <td style="text-align:left;"><?php
                                    //$stnm=$statelng[$i][0]['state_name_en'];
                                    if($statelng[$i][0]['state_id']!=$stid)
                                    echo $statelng[$i][0]['state_name_en']; 
                                    $stid=$statelng[$i][0]['state_id'];
                                    ?></td>
                                    <td style="text-align:left;"><?php echo $statelng[$i][0]['language_name'];
                                    
                                    
                                    //for($j=0;$j<count($statelng);$j++){
                                      //  pr($statelng);
                                        //if($states[$i][0]['id']==$statelng[$j][0]['state_id']){
                                           // echo '<br>'.$statelng[$j][0]['language_name'];
                                        //}
                                    //}
                                    ?></td>


                                    
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div> 
    </div>