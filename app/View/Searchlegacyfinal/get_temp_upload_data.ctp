<?php

if(!empty($generalInfo)){ ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo "Uploaded Excel Data"; ?></h3></center>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div id="temp_legacy_data">
                           <?php 
                               echo $this->element('LegacyData/general_information');
                           if(!empty($propertyDetail)){
                               echo $this->element('LegacyData/property_details');
                           }
                           if(!empty($partyDetail)){
                               echo $this->element('LegacyData/party_details');
                           }
                           if(!empty($withnessDetail)){
                               echo $this->element('LegacyData/withness_detail');
                           }
                           if(!empty($identifierDetail)){
                               echo $this->element('LegacyData/identifier_detail');
                           }
                           if(!empty($feesDetail)){
                               echo $this->element('LegacyData/fees_details');
                           }?>
                    </div>
                    <div class="footer center">
                        <button type="button" id="btnImport" name="action" class="btn btn-info"><?php echo "Import"; ?></button>                        
                        <button type="button" id="btnDelete" class="btn btn-info" ><?php echo "Delete"; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>