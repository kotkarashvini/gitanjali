<?php
echo $this->Html->script('Device/d3.v3.min');
?>

<STYLE type="text/css">
    div.div_Header {
        width: 100%;
        border:2px solid White;
        border-radius:7px;
        background: WhiteSmoke;
        font: bold 14px Arial;
        font-family:Arial, Helvetica, sans-serif;
        text-align:center;
    }
    h1.h1_BodyHeader {
        text-align:center;
        font: bold 1.5em Arial;
    }
    h2.h2_LeftMenuHeader {
        text-align:center;
        font: bold 1.2em Arial;
    }
    h3.h3_Body {
        text-align:center;
    }
    p.p_Red {
        color:Red;
    }
    table.table_Header {
        width: 100%;
        text-align:center;
    }
    td.td_HeaderLeft {
        text-align:left;
    }
    td.td_HeaderRight {
        text-align:right;
    }
    div.div_Menu {
        width: 100%;
        border:2px solid White;
        border-radius:7px;
        background: MidnightBlue;
        font: bold 14px Arial;
        font-family:Arial, Helvetica, sans-serif;
        color:White;
        text-align:center;
    }
    p.p_Left {
        font-family:Arial, Helvetica, sans-serif;
        color:Black;
        text-align:left;
        padding-left: 5px;
        font: normal 14px Arial;
    }
    table.table_Body {
        width: 100%;
        height: 100%;
        padding: 0;
    }
    td.td_BodyLeft {
        width: 250px;
        height: 100%;
        padding: 0;
    }
    li.li_LeftMenu {
        text-align:left;
        font: normal 14px Arial;
    }
    a.a_LeftMenuNoUnderLine {
        text-decoration:  none;
    }
    div.div_Body {
        height: 100%;
        width: 100%;
        position: relative;
        border:2px solid White;
        border-radius:7px;
        background: WhiteSmoke;
        font: bold 14px Arial;
        font-family:Arial, Helvetica, sans-serif;
        color:Black;
        text-align:center;
    }
    div.div_Footer {
        width: 100%;
        border:2px solid White;
        border-radius:7px;
        background: MidnightBlue;
        font: bold 14px Arial;
        font-family:Arial, Helvetica, sans-serif;
        color:White;
        text-align:center;
    }
    p.p_if4itMessage {
        width: 100%;
        background: White;
        font: bold .75em Arial;
        font-family:Arial, Helvetica, sans-serif;
        color:GoldenRod;
        text-align:center;
    }
    .menuButton{
        background-color: MidnightBlue;
    }
    .menuButton li{
        height: 100%;
        list-style: none;
        display: inline;
    }
    .menuButton li a{
        height: 100%;
        padding: 3px 0.5em;
        text-decoration: none;
        color: White;
        background-color: MidnightBlue;
        border: 2px solid MidnightBlue;
    }
    .menuButton li a:hover{
        height: 100%;
        color: MidnightBlue;
        background-color: White;
        border-style: outset;
        background-color: White;
    }
    .menuButton li a:active{
        height: 100%;
        border-style: inset;
        color: MidnightBlue;
        background-color: White;
    }
    .menuButton li a.disabled{
        height: 100%;
        padding: 3px 0.5em;
        text-decoration: none;
        color: MidnightBlue;
        background-color: White;
        border: 2px solid MidnightBlue;
        border-style: inset;
        border-color: White;
    }
</STYLE>

<STYLE type="text/css">
    div.div_RootBody {
        position: relative;
        border:1px solid black;
        border-radius:7px;
        background: WhiteSmoke;
        font: normal 12px Arial;
        font-family:Arial, Helvetica, sans-serif;
        color:Black;
        padding: 0px 1em;
        text-align:left;
    }
</STYLE>
<script>
    $(document).ready(function () {
        $("#fromdate").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'});
        $("#todate").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'});
    });
</script>
<!--<script type="text/javascript" src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>-->
<script type="text/javascript">

// This example draws vertical bar charts...
// Created by Frank Guerino : "http://www.guerino.net"

// Data Used for this example...
    var dataSet1 = [
<?php
foreach ($datagraph1 as $datagraph11) {
    ?>
            {xCoordinate: "<?php echo $datagraph11['office_name_en']; ?>", magnitude: <?php
    if ($datagraph11['income'] == NULL) {
        echo '0';
    } else {
        echo $datagraph11['income'];
    }
    ?>},
<?php } ?>
    ];
    var dataSet2 = [
<?php
foreach ($datagraph2 as $datagraph12 => $value) {
    ?>
            {xCoordinate: "<?php echo $datagraph12; ?>", magnitude: <?php
    if ($value == NULL) {
        echo '0';
    } else {
        echo $value;
    }
    ?>},
<?php } ?>
    ];
    var dataSet3 = [
<?php
foreach ($datagraph3 as $datagraph13 => $value) {
    ?>
            {xCoordinate: "<?php echo $datagraph13; ?>", magnitude: <?php
    if ($value == NULL) {
        echo '0';
    } else {
        echo $value;
    }
    ?>},
<?php } ?>
    ];
    var dataSet4 = [
<?php
foreach ($datagraph4 as $datagraph14 => $value) {
    ?>
            {xCoordinate: "<?php echo $datagraph14; ?>", magnitude: <?php
    if ($value == NULL) {
        echo '0';
    } else {
        echo $value;
    }
    ?>},
<?php } ?>
    ];
    var dataSet5 = [
<?php
foreach ($datagraph5 as $datagraph15) {
    ?>
            {xCoordinate: "<?php echo $datagraph15['office_name_en']; ?>", magnitude: <?php
    if ($datagraph15['count'] == NULL) {
        echo '0';
    } else {
        echo $datagraph15['count'];
    }
    ?>},
<?php } ?>
    ];
//    var dataSet2 = [
//      {xCoordinate: "Legend String 8", magnitude: 31, link: "http://www.if4it.com/glossary.html"},
//      {xCoordinate: "Legend String 9", magnitude: 54, link: "http://www.if4it.com/resources.html"},
//      {xCoordinate: "Legend String 10", magnitude: 21, link: "http://www.if4it.com"},
//      {xCoordinate: "Legend String 11", magnitude: 31, link: "http://www.if4it.com/taxonomy.html"},
//      {xCoordinate: "Legend String 12", magnitude: 54, link: "http://www.if4it.com/glossary.html"},
//      {xCoordinate: "Legend String 13", magnitude: 14, link: "http://www.if4it.com/resources.html"},
//      {xCoordinate: "Legend String 14", magnitude: 14, link: "http://www.if4it.com/disciplines.html"},
//      {xCoordinate: "Legend String 15", magnitude: 27, link: "http://www.if4it.com/glossary.html"}];

//    var dataSet4 = [
//        {xCoordinate: "Legend String 16", magnitude: 21, link: "http://www.if4it.com"},
//        {xCoordinate: "Legend String 17", magnitude: 41, link: "http://www.if4it.com/resources.html"},
//        {xCoordinate: "Legend String 18", magnitude: 34, link: "http://www.if4it.com/glossary.html"},
//        {xCoordinate: "Legend String 19", magnitude: 9, link: "http://www.if4it.com/taxonomy.html"},
//        {xCoordinate: "Legend String 20", magnitude: 47, link: "http://www.if4it.com/glossary.html"},
//        {xCoordinate: "Legend String 21", magnitude: 17, link: "http://www.if4it.com/resources.html"},
//        {xCoordinate: "Legend String 22", magnitude: 47, link: "http://www.if4it.com/glossary.html"},
//        {xCoordinate: "Legend String 23", magnitude: 37, link: "http://www.if4it.com/disciplines.html"},
//        {xCoordinate: "Legend String 24", magnitude: 47, link: "http://www.if4it.com/resources.html"},
//        {xCoordinate: "Legend String 25", magnitude: 4, link: "http://www.if4it.com/glossary.html"},
//        {xCoordinate: "Legend String 26", magnitude: 18, link: "http://www.if4it.com"},
//        {xCoordinate: "Legend String 27", magnitude: 41, link: "http://www.if4it.com/resources.html"},
//        {xCoordinate: "Legend String 28", magnitude: 37, link: "http://www.if4it.com/glossary.html"},
//        {xCoordinate: "Legend String 29", magnitude: 27, link: "http://www.if4it.com"}];

    function drawVerticalBarChart(chartID, dataSet, selectString, colors) {

        // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
        // dataSet => Input Data for the chart, itself.
        // selectString => String that allows you to pass in
        //           a D3 select string.
        // colors => String to set color scale.  Values can be...
        //           => "colorScale10"
        //           => "colorScale20"
        //           => "colorScale20b"
        //           => "colorScale20c"

        var canvasWidth = 700;
        var barsWidthTotal = 300
        var barWidth = barsWidthTotal / dataSet.length;
        //var canvasHeight = 200;
        var canvasHeight = dataSet.length * 20 + 40;
        var legendOffset = 30;
        var legendBulletOffset = 30;
        var legendTextOffset = 20;

        var x = d3.scale.linear().domain([0, dataSet.length]).range([0, barsWidthTotal]);
        var y = d3.scale.linear().domain([0, d3.max(dataSet, function (d) {
                return d.magnitude;
            })]).rangeRound([0, canvasHeight]);


        //document.writeln(selectString);

        // Color Scale Handling...
        var colorScale = d3.scale.category20c();
        switch (colors)
        {
            case "colorScale10":
                colorScale = d3.scale.category10();
                break;
            case "colorScale20":
                colorScale = d3.scale.category20();
                break;
            case "colorScale20b":
                colorScale = d3.scale.category20b();
                break;
            case "colorScale20c":
                colorScale = d3.scale.category20c();
                break;
            default:
                colorScale = d3.scale.category20c();
        }
        ;

        var synchronizedMouseOver = function () {
            var bar = d3.select(this);
            var indexValue = bar.attr("index_value");

            var barSelector = "." + "bars-" + chartID + "-bar-" + indexValue;
            var selectedBar = d3.selectAll(barSelector);
            selectedBar.style("fill", "Maroon");

            var bulletSelector = "." + "bars-" + chartID + "-legendBullet-" + indexValue;
            var selectedLegendBullet = d3.selectAll(bulletSelector);
            selectedLegendBullet.style("fill", "Maroon");

            var textSelector = "." + "bars-" + chartID + "-legendText-" + indexValue;
            var selectedLegendText = d3.selectAll(textSelector);
            selectedLegendText.style("fill", "Maroon");
        };

        var synchronizedMouseOut = function () {
            var bar = d3.select(this);
            var indexValue = bar.attr("index_value");

            var barSelector = "." + "bars-" + chartID + "-bar-" + indexValue;
            var selectedBar = d3.selectAll(barSelector);
            var colorValue = selectedBar.attr("color_value");
            selectedBar.style("fill", colorValue);

            var bulletSelector = "." + "bars-" + chartID + "-legendBullet-" + indexValue;
            var selectedLegendBullet = d3.selectAll(bulletSelector);
            var colorValue = selectedLegendBullet.attr("color_value");
            selectedLegendBullet.style("fill", colorValue);

            var textSelector = "." + "bars-" + chartID + "-legendText-" + indexValue;
            var selectedLegendText = d3.selectAll(textSelector);
            selectedLegendText.style("fill", "Blue");
        };

        // Create the svg drawing canvas...
        var canvas = d3.select(selectString)
                .append("svg:svg")
                //.style("background-color", "yellow")
                .attr("width", canvasWidth)
                .attr("height", canvasHeight);

        // Draw individual hyper text enabled bars...
        canvas.selectAll("rect")
                .data(dataSet)
                .enter().append("svg:a")
                .attr("xlink:href", function (d) {
                    return d.link;
                })
                .append("svg:rect")
                .attr("x", function (d, i) {
                    return x(i);
                })
                // NOTE: The following "+15" adds an offset that ensures some space
                // between the top of the canvas and the top of the highest bar, so
                // that text can be added in that space, later.
                .on('mouseover', synchronizedMouseOver)
                .on("mouseout", synchronizedMouseOut)
                .attr("y", function (d) {
                    return canvasHeight - y(d.magnitude) + 15;
                })
                .attr("height", function (d) {
                    return y(d.magnitude);
                })
                .attr("width", barWidth)
                .style("fill", "White")
                .style("stroke", "White")
                .transition()
                .duration(150)
                .delay(function (d, i) {
                    return i * 100;
                })
                .style("fill", function (d, i) {
                    colorVal = colorScale(i);
                    return colorVal;
                })
                .attr("index_value", function (d, i) {
                    return "index-" + i;
                })
                .attr("class", function (d, i) {
                    return "bars-" + chartID + "-bar-index-" + i;
                })
                .attr("color_value", function (d, i) {
                    return colorScale(i);
                }) // Bar fill color...
                .style("stroke", "white"); // Bar border color...


        // Create text values that go at top of each bar...
        canvas.selectAll("text")
                .data(dataSet) // Instruct to bind dataSet to text elements
                .enter().append("svg:text") // Append text elements
                // Identify root coordinate (x,y)
                //.attr("x", function(d, i) { return x(i) + barWidth; }) // <-- Can't use because of bug in FireFox
                .attr("x", function (d, i) {
                    return x(i) + barWidth / 2;
                }) // <-- Using because of bug in Firefox
                // Note: the following "+1" offset places value above bar in
                // Space between the top of the bar and the top of the canvas.
                .attr("y", function (d) {
                    return canvasHeight - y(d.magnitude);
                })
                //.attr("dx", -barWidth/2) // <-------------- Can't use because of bug in FireFox
                .attr("dy", "1em") // Controls padding to place text above bars
                .attr("text-anchor", "middle")
                .text(function (d) {
                    return d.magnitude;
                })
                .attr("fill", "Black");

        // Plot the bullet circles...
        canvas.selectAll("circle")
                .data(dataSet).enter().append("svg:circle") // Append circle elements
                .attr("cx", barsWidthTotal + legendBulletOffset)
                .attr("cy", function (d, i) {
                    return legendOffset + i * 20;
                })
                .attr("stroke-width", ".5")
                .style("fill", function (d, i) {
                    return colorScale(i);
                }) // Bar fill color
                .attr("index_value", function (d, i) {
                    return "index-" + i;
                })
                .attr("class", function (d, i) {
                    return "bars-" + chartID + "-legendBullet-index-" + i;
                })
                .attr("r", 5)
                .attr("color_value", function (d, i) {
                    return colorScale(i);
                }) // Bar fill color...
                .on('mouseover', synchronizedMouseOver)
                .on("mouseout", synchronizedMouseOut);

        // Create hyper linked text at right that acts as label key...
        canvas.selectAll("a.legend_link")
                .data(dataSet) // Instruct to bind dataSet to text elements
                .enter().append("svg:a") // Append legend elements
                .attr("xlink:href", function (d) {
                    return d.link;
                })
                .append("text")
                .attr("text-anchor", "left")
                .attr("x", barsWidthTotal + legendBulletOffset + legendTextOffset)
                .attr("y", function (d, i) {
                    return legendOffset + i * 20 - 10;
                })
                .attr("dx", 0)
                .attr("dy", "1em") // Controls padding to place text above bars
                .text(function (d) {
                    return d.xCoordinate;
                })
                .style("fill", "Blue")
                .attr("index_value", function (d, i) {
                    return "index-" + i;
                })
                .attr("class", function (d, i) {
                    return "bars-" + chartID + "-legendText-index-" + i;
                })
                .on('mouseover', synchronizedMouseOver)
                .on("mouseout", synchronizedMouseOut);

    }
    ;


</script>



<?php echo $this->Form->create('dashboard', array('id' => 'dashboard', 'autocomplete' => 'off')); ?>

<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblngdrsdashboard'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="fromdate" class="col-sm-6 control-label"><?php echo __('lblfromdate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-6"><?php echo $this->Form->input('fromdate', array('type' => 'text', 'id' => 'fromdate', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        <span id="fromdate_error" class="form-error"><?php echo $errarr['fromdate_error']; ?></span>
                    </div>
                    <div class="col-sm-4">
                        <label for="todate" class="col-sm-6 control-label"><?php echo __('lbltodate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-6"><?php echo $this->Form->input('todate', array('type' => 'text', 'id' => 'todate', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        <span id="todate_error" class="form-error"><?php echo $errarr['todate_error']; ?></span>
                    </div>
                    <div class="col-sm-4">
                        <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblsearch'); ?>
                        </button>
                    </div>
                </div>
                <div class="rowht"></div><div class="rowht"></div>
                <?php if ($status == "show") { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="div_RootBody col-sm-6" id="bar_chart_1">
                                <h3 class="h3_Body"><?php echo __('lblofcincome'); ?></h3>
                                <div class="chart"></div>
                            </div>
                            <div class="div_RootBody col-sm-6" id="bar_chart_2">
                                <h3 class="h3_Body"><?php echo __('lbldockcnt'); ?></h3>
                                <div class="chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="div_RootBody col-sm-6" id="bar_chart_3">
                                <h3 class="h3_Body"><?php echo __('lbltdlogstat'); ?></h3>
                                <div class="chart"></div>
                            </div>
                            <div class="div_RootBody col-sm-6" id="bar_chart_4">
                                <h3 class="h3_Body"><?php echo __('lblofcwiseappointment'); ?></h3>
                                <div class="chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="div_RootBody col-sm-6" id="bar_chart_5">
                                <h3 class="h3_Body"><?php echo __('lblofcregdoc'); ?></h3>
                                <div class="chart"></div>
                            </div>
                            <!--                    <div class="div_RootBody col-sm-6" id="bar_chart_6">
                                                    <h3 class="h3_Body"><?php echo __('lblofcwiseappointment'); ?></h3>
                                                    <div class="chart"></div>
                                                </div>-->
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    drawVerticalBarChart("Bars1", dataSet1, "#bar_chart_1 .chart", "colorScale20b");
    drawVerticalBarChart("Bars2", dataSet2, "#bar_chart_2 .chart", "colorScale10");
    drawVerticalBarChart("Bars3", dataSet3, "#bar_chart_3 .chart", "colorScale20");
    drawVerticalBarChart("Bars4", dataSet4, "#bar_chart_4 .chart", "colorScale20c");
    drawVerticalBarChart("Bars5", dataSet5, "#bar_chart_5 .chart", "colorScale10");
</script>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>














