  <table id="tableproceeding" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  
                            <th class="center">Date</th>
                            <th class="center"> Details </th>
                            <th class="center">By whom</th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tr>
                        <?php
                    pr($demo);
                        $datelist = date('Y-m-d');
                        foreach ($demo as $noticerecord1):
                            ?>
                            <td class="tblbigdata"><?php echo $noticerecord1[0]['notice_date'] ?></td>
    <!--                            <td class="tblbigdata"><?php echo $noticerecord1[0]['first_hearing_date'] ?></td>-->
                            <td class="tblbigdata"><?php
                                if ($noticerecord1[0]['first_hearing_date'] == $datelist) {
                                    echo "ON Board";
                                } else {
                                    echo $noticerecord1[0]['first_hearing_date'];
                                }
                                ?></td>
                            <td class="tblbigdata"><?php echo $noticerecord1[0]['office_name_en']; ?></td>
                            <td>
                                <!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Open Modal</button>-->
                                <?php echo $this->Form->button('View', array('type' => 'button', 'class' => 'btn btn-success', 'escape' => false, 'data-toggle' => 'modal', 'data-target' => '#myModal')); ?>
                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
                    <?php unset($noticerecord1); ?>
                </table> 