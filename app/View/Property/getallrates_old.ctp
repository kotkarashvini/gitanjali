<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<table class="table table-bordered" id="ratetbl">
    <thead>
        <tr>
             <th>Sr.No.</th>
             <th>List ID</th>
            <th>Location</th>
            <th>Usage</th>
            <th>Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php
if(isset($result) && !empty($result)){        $temp=$result[0][0]['level1_list_id'];
       
        $classname='bg-success';
       for($i=0;$i<count($result);$i++) {
           if($temp!=$result[$i][0]['level1_list_id']){?>
       
               <?php 
               $temp =$result[$i][0]['level1_list_id'];
               if($classname=='bg-success'){
               $classname='bg-info';
               }else{
                   $classname='bg-success';
               }
           }
            ?>
            <tr class=<?php echo $classname;?>>
                <td>
    <?php echo $i+1; ?> 
                </td>
                <td>
    <?php echo $result[$i][0]['level1_list_id']; ?>  
                </td>
                <td>
    <?php echo $result[$i][0]['list_1_desc_' . $lang]; ?>  
                </td>
                <td>
    <?php echo $result[$i][0]['usage_sub_catg_desc_' . $lang]; ?>  
                </td>
                <td>
    <?php echo $result[$i][0]['prop_rate']; ?>  
                </td>
            </tr>
            
<?php  } } ?>  
    </tbody>
</table>