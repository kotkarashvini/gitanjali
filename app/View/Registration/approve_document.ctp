 

<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Document Approval'); ?></h3></center>
    </div>

    <div class="box-body">

        <table id="doclist" class="table table-bordred table-striped"> 
            <thead>
            <th><?php echo __('lbltokenno'); ?></th>
            <th><?php echo __('lblArticle'); ?></th>            
            <th><?php echo __('lbltitlename'); ?></th>
            <th><?php echo __('lblexecutiondt'); ?></th>            
            <!--<th><?php echo __('lblaction'); ?></th>-->  
            </thead>
            <tbody>
                <?php            

                if (isset($rpt_data)) {
                    foreach ($rpt_data as $document) {
                        $document = $document[0];
                        ?>
                <tr>
                    <td><?php echo $document['token_no']; ?></td>
                    <td><?php echo $document['article_desc_'.$lang]; ?></td> 
                    <td><?php echo $document['articledescription_en']; ?></td>
                    <td> <?php  $date=explode(" ",$document['exec_date']); echo $date[0]; ?></td>               
                    <!--<td> </td>-->
                </tr>

                    <?php
                    }
                }
                ?>
            </tbody>
        </table>


    </div> 
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">    


            <center>  
                <div id="uploadfiles">
                    <div>

                        <!-- Modal content-->
                        <div>
                            <div class="modal-header">
                                <!--                                            <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                                <h4 class="modal-title"><b><?php echo __('lbluploadedfileslist'); ?></b></h4>
                            </div>
                            <div class="modal-body"> 

                                <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                                    <thead>  
                                        <tr>  
                                            <th class="center"><?php echo __('lbldocumenttitle'); ?></th>
                                            <th class="center"><?php echo __('lblaction'); ?></th>
                                        </tr>  
                                    </thead>
                                    <tbody>
                                                    <?php
                                                    if (!empty($document_list)) {

                                                        foreach ($document_list as $upFile) {
                                                            ?>
                                        <tr>
                                            <td class="tblbigdata"><?php echo $upFile['document']['document_name_' . $lang]; ?></td>

                                            <td> <?php
                                                                    if ($upFile['uploaded_file_trn']['document_id'] == $upFile['document']['document_id']) {
                                                                        if ($upFile['uploaded_file_trn']['out_fname'] != '') {
                                                                            echo $this->Html->link(
                                                                                    'Download', array(
                                                                                'disabled' => TRUE,
                                                                                'controller' => 'Registration', // controller name
                                                                                'action' => 'downloadfile', //action name
                                                                                'full_base' => true, $upFile['uploaded_file_trn']['out_fname'], 'Uploads',$upFile['uploaded_file_trn']['token_no'] ), array('target' => '_blank')
                                                                            );
                                                                        }
                                                                    }
                                                                    ?></td>

                                        </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                    </tbody>
                                </table>


                            </div>

                        </div>

                    </div>
                </div>

            </center>    
        </div> 

        <div class="box-footer">
            <div class="col-md-6">

 <?php echo $this->form->create('final_stamp', array('id' => 'final_stamp')); ?>
 <?php echo $this->form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
            <div class="form-group">
                <label></label>
                <?php 
                $options = array('A' => ' Observation&nbsp;&nbsp;&nbsp;&nbsp;');
$attributes = array(
    'legend' => false,
    'value' => 'A',
    'checked'=> ('A' == "A") ? FALSE : TRUE,
    
     
);
echo $this->form->radio('sro_action_flag',$options, $attributes);
                ?>
                
                
            </div>
            
            <div class="form-group">
                <label>SRO Remark </label>
               <?php echo $this->form->input('sro_remark', array('label' => false, 'id' => 'sro_remark', 'type' => 'textarea','class'=>'form-control','rows'=>3)); ?>
 
                
                <span class="form-error" id="sro_remark_error"></span>  
            </div>
            
            
            <center>
                <button type="submit" class="btn btn-success"  ><?php echo __('Submit'); ?></button>  
            </center>
 <?php echo $this->form->end(); ?>
            <!--</div>-->
</div>
        </div>


    </div> 


</div> 




