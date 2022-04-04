<style>
    .results tr[visible='false'],
    .no-result{
        display:none;
    }

    .results tr[visible='true']{
        display:table-row;
    }

    .counter{
        padding:8px; 
        color:#ccc;
    }
</style>
<script>
    $(document).ready(function () {
        $(".search").keyup(function () {
            var searchTerm = $(".search").val();
            var listItem = $('.results tbody').children('tr');
            var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

            $.extend($.expr[':'], {'containsi': function (elem, i, match, array) {
                    return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });

            $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function (e) {
                $(this).attr('visible', 'false');
            });

            $(".results tbody tr:containsi('" + searchSplit + "')").each(function (e) {
                $(this).attr('visible', 'true');
            });

            var jobCount = $('.results tbody tr[visible="true"]').length;
            $('.counter').text(jobCount + ' item');

            if (jobCount == '0') {
                $('.no-result').show();
            }
            else {
                $('.no-result').hide();
            }
        });
    });
</script>
<h3 align="center"> Sub Rule Detail</h3>
<div class="col-sm-12"><div class="table-responsive">
        <div class="form-group pull-right">
            <input type="text" class="search form-control" placeholder="Search Here...">
        </div>
        <span class="counter pull-right"></span>
        <table  id="tblEvalSubRule" class="table table-striped table-bordered table-hover results" style="overflow: scroll;overflow: auto; height: 50vh">
            <thead><tr>
                    <th class='center'><?php echo __('lblsrno'); ?></th>
                    <th class='center'><?php echo __('lblid'); ?></th>
                    <th class='center'><?php echo __('lblcond1'); ?></th>
                    <th class='center'><?php echo __('lblformula1'); ?></th>
                    <th class='center'><?php echo __('lblismax'); ?></th>
                    <th class='center'><?php echo __('lblroadvicinity'); ?></th>
                    <th class='center'><?php echo __('lbloutputitem'); ?></th>
                    <th class='center'><?php echo __('lblorder'); ?></th>
                    <th class='center'><?php echo __('lblaction'); ?></th>
                </tr></thead><tbody>
                <?php
                $i = 1;
                foreach ($subrule_list as $val) {
                    $slist = $val[0];
                    ?>
                    <tr id = "<?php echo 'subrule_' . $slist['subrule_id']; ?>" >
                        <td class = 'center width5 tblbigdata'> <?php echo $i++; ?> </td>
                        <td class = 'center width5 tblbigdata'> <?php echo $slist['subrule_id']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['evalsubrule_cond1']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['evalsubrule_formula1']; ?> </td>
                        <td class = 'center width5 tblbigdata'> <?php echo $slist['max_value_condition_flag']; ?> </td>
                        <td class = 'center tblbigdata'> <?php echo (($slist['road_vicinity_desc_en'] != null) ? $slist['road_vicinity_desc_en'] : '-NA-') ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['usage_param_desc_en'] ?> </td>
                        <td class = 'center tblbigdata'> <?php echo $slist['out_item_order'] ?> </td>
                        <td class = 'center width10 tblbigdata'> <button class = 'btn btn-default' onClick = 'return getSubruleDetails(<?php echo $slist['evalrule_id']; ?>, <?php echo $slist['subrule_id']; ?>);'><span class = 'glyphicon glyphicon-pencil'></span> </button>
                            <button class = 'btn btn-default' onClick = 'return removeSubRule(<?php echo $slist['evalrule_id']; ?>, <?php echo $slist['subrule_id']; ?>);'><span class = 'glyphicon glyphicon-remove'></span> </button>
                        </td></tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

