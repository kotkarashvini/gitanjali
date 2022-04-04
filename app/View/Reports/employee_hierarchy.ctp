<?php

$myInfo = $my_details[0][0];
$srNo = 1;
$design = "
    <style>td,th{padding:3px;}th{text-align:center;}</style>
<h3 align='center'>Employee Hierarchy</h3>
<br>
<div>
    <div>
        <table border=1 width=100%>
            <thead>            
                <tr>
                    <th>Sr.No.</th>
                    <th>Employee Name</th>
                    <th>Reporting Officer Name</th>
                    <th>Office Name</th>
                    <th>Hierarchy</th>
                </tr>
            </thead>            
            <tbody>
                
                <tr>
                    <td align=center>" . $srNo++ . "</td>
                    <td>" . $myInfo['emp_name'] . "</td>
                    <td>" . $myInfo['reporting_officer_name'] . "</td>
                    <td>" . $myInfo['office_name_en'] . "</td>
                    <td>" . $myInfo['hierarchy_desc_en'] . "</td>
                </tr>";
foreach ($hierarchy_detail as $empInfo) {
    $empInfo = $empInfo[0];
    $design.="<tr>
                    <td align=center>" . $srNo++ . "</td>
                    <td>" . $empInfo['emp_name'] . "</td>
                    <td>" . $empInfo['reporting_officer_name'] . "</td>
                    <td>" . $empInfo['office_name_en'] . "</td>
                    <td>" . $empInfo['hierarchy_desc_en'] . "</td>
                </tr>";
}

$design.="</tbody>
</table>
</div>
</div>
";
echo $design;
