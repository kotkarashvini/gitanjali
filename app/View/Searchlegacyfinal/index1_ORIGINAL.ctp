<style>
table, td, th {  
  border: 3px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
</style>

<div class="actions">
    <h3>
    <?php echo $this->Html->link('Add New Taluka /Anchal',array('action'=>'add'))?> 
</h3>
</div>

<div class="index">
    <h4> <?php echo __('LIST OF CIRCLE /ANCHAL')?></h2>

    <table class="myTable">
        <thead>
            <tr>
                <th><?php echo 'DISTRICT';?></th>
                <th><?php echo 'TALUKA';?></th>
                <th class="actions"><?php echo __('Action')?></th>
                <th class="actions"><?php echo __('Action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $result): // pr('sdfsdf');pr($result);?>
            <tr>
             
               

                <td><?php if( $result['taluka']['district_id'])
                {
                    echo "West Tripura";  
                }
                else
                {
                    echo "East Tripura";     
                }
                
                
                
                
                
                ?></td>


                <td><?php echo $result['taluka']['taluka_name_en'];?></td>
                <td class="actions"><?php echo $this->Html->link(__('Edit'),array('action'=>'edit',$result['taluka']['taluka_name_en']))?></td>

                <td class="actions"><?php echo $this->Html->link(__('delete'),array('action'=>'delete',$result['taluka']['taluka_name_en']))?></td>

                <!-- <td><?php //echo $this->form->postLink(__('Delete'),array('action'=>'delete',$result['district']['district_id']),array('confirm'=> __('Areyou Sure Want to Delete This Record %d?'),['district']['district_id']));?> </td> -->
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    
</div>


































