<!--<script src="https://code.jquery.com/jquery-1.12.4.min.js" charset="utf-8"></script>
<script src="https://d3js.org/d3.v2.min.js"></script>-->
<?php echo $this->Html->script('JSgraph/jquery-1.12.4.min'); ?>
<?php echo $this->Html->script('JSgraph/d3.v2.min'); ?>

<style type="text/css">
    #chart text {
        fill: black;
        font: 12px sans-serif ;
        font-weight: bold;
        text-anchor: end;
    }
    .axis text {
        font: 10px sans-serif;
    }
    .axis path,
    .axis line {
        fill: none;
        /*stroke: #fff;*/
        shape-rendering: crispEdges;
    }
    /*    body {
            background: #1a1a1a;
            color: #eaeaea;
            padding: 10px;
        }*/
    path {
        stroke: steelblue;
        stroke-width: 2;
        fill: none;
    }
</style>
<?php echo $this->Form->create('docsubmitted', array('id' => 'docsubmitted', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo 'Submitted Document Graph'; ?></h3></center>
            </div>
            <?php if (!empty($data)) { ?>
        <!--<div class="col-sm-2"><?php // echo $this->Form->input('totalcollection', array('label' => false, 'id' => 'totalcollection', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly'))  ?></div>-->
            <div id="chart" style="height:600px;width:900px">
                <div class="innerCont" style="overflow: auto; top:100px; left: 100px; height:91% ; Width:100% ;position: relative;overflow: hidden;">
                </div>

            </div>
            <br>
            <br>
            <br>
            <br>
             <input type='hidden' value='<?php // echo $filter1; ?>' name='filter1' id='filter1'/>
<input type='hidden' value='<?php // echo $filter2; ?>' name='filter2' id='filter2'/>
            <?php } else { ?>

            <div class="row center">
                <div class="form-group col-sm-12" > 
                   <div class="col-sm-12"><h2 style="color: red">Record Not Found...!!!!!</h2></div>
                </div>           
        </div>
         <?php } ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script type="text/javascript">
    var salesData;
    var truncLengh = 30;
    $(document)
        .ready(function () {
//            alert("ready function");
            Plot();
        });

    function Plot() {
//        alert("plot function");
        TransformChartData(chartData, chartOptions);
        BuildBar("chart", chartData, chartOptions);
    }

    function BuildBar(id, chartData, options, level) {
//        alert("BuildBar function");
        //d3.selectAll("#" + id + " .innerCont").remove();
        //$("#" + id).append(chartInnerDiv);
        chart = d3.select("#" + id + " .innerCont");
        var margin = { top: 50, right: 10, bottom: 100, left: 100 },
            width = $(chart[0])
            .outerWidth() - margin.left - margin.right,
            height = $(chart[0])
            .outerHeight() - margin.top - margin.bottom
        var xVarName;
        var yVarName = options[0].yaxis;
//        alert("yVarName : "+yVarName);
//         alert("BuildBar function level1 : " +level);
       
            xVarName = options[0].xaxis;
        
        var xAry = runningData.map(function (el) {
            return el[xVarName];
        });
        var yAry = runningData.map(function (el) {
            return el[yVarName];
        });
        var capAry = runningData.map(function (el) { return el.caption; });
        var x = d3.scale.ordinal()
            .domain(xAry)
            .rangeRoundBands([0, width], .5);
        var y = d3.scale.linear()
            .domain([0, d3.max(runningData, function (d) { return d[yVarName]; })])
            .range([height, 0]);
        var rcolor = d3.scale.ordinal()
            .range(runningColors);
        chart = chart
            .append("svg") //append svg element inside #chart
            .attr("width", width + margin.left + margin.right) //set width
            .attr("height", height + margin.top + margin.bottom); //set height
        var bar = chart.selectAll("g")
            .data(runningData)
            .enter()
            .append("g")
            //.attr("filter", "url(#dropshadow)")
            .attr("transform", function (d) {
                return "translate(" + x(d[xVarName]) + ", 0)";
            });
        var ctrtxt = 0;
        var xAxis = d3.svg.axis()
            .scale(x)
            //.orient("bottom").ticks(xAry.length).tickValues(capAry);  //orient bottom because x-axis tick labels will appear on the
            .orient("bottom")
            .ticks(xAry.length)
            .tickFormat(function (d) {
//                alert("BuildBar function level2 : " +level);
             
                    var mapper = options[0].captions[0];
//                    alert("mapper[d] : "+mapper[d]);
                    return mapper[d];
               
            });
        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left")
            .ticks(5); //orient left because y-axis tick labels will appear on the left side of the axis.
        bar.append("rect")
            .attr("y", function (d) {
                return y(d.Total) + margin.top - 15;
            })
            .attr("x", function (d) {
                return (margin.left);
            })
            .on("mouseenter", function (d) {
                d3.select(this)
                    .attr("stroke", "white")
                    .attr("stroke-width", 1)
                    .attr("height", function (d) {
                        return height - y(d[yVarName]) + 5;
                    })
                    .attr("y", function (d) {
                        return y(d.Total) + margin.top - 20;
                    })
                    .attr("width", x.rangeBand() + 10)
                    .attr("x", function (d) {
                        return (margin.left - 5);
                    })
                    .transition()
                    .duration(200);
            })
            .on("mouseleave", function (d) {
                d3.select(this)
                    .attr("stroke", "none")
                    .attr("height", function (d) {
                        return height - y(d[yVarName]);;
                    })
                    .attr("y", function (d) {
                        return y(d[yVarName]) + margin.top - 15;
                    })
                    .attr("width", x.rangeBand())
                    .attr("x", function (d) {
                        return (margin.left);
                    })
                    .transition()
                    .duration(200);
            })
            .on("click", function (d) {
                if (this._listenToEvents) {
                    // Reset inmediatelly
                    d3.select(this)
                        .attr("transform", "translate(0,0)")
                    // Change level on click if no transition has started                
                    path.each(function () {
                        this._listenToEvents = false;
                    });
                }
                d3.selectAll("#" + id + " svg")
                    .remove();
//                alert("BuildBar function level3 : " +level);
//                alert("xVarName  : "+d[xVarName]);
                
                    TransformChartData(chartData, options, 0, d[xVarName]);
                    BuildBar(id, chartData, options, 0);
               
                
            });
        bar.selectAll("rect")
            .attr("height", function (d) {
                return height - y(d[yVarName]);
            })
            .transition()
            .delay(function (d, i) { return i * 300; })
            .duration(1000)
            .attr("width", x.rangeBand()) //set width base on range on ordinal data
            .transition()
            .delay(function (d, i) { return i * 300; })
            .duration(1000);
        bar.selectAll("rect")
            .style("fill", function (d) {
                return rcolor(d[xVarName]);
            })
            .style("opacity", function (d) {
                return d["op"];
            });
        bar.append("text")
            .attr("x", x.rangeBand() / 2 + margin.left + 25)//total display center
            .attr("y", function (d) { return y(d[yVarName]) + margin.top - 25; })
            .attr("dy", ".35em")
            .text(function (d) {
                return d[yVarName];
            });
        bar.append("svg:title")
            .text(function (d) {
                //return xVarName + ":  " + d["title"] + " \x0A" + yVarName + ":  " + d[yVarName];
                return d["title"] + " (" + d[yVarName] + ")";
            });
        chart.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(" + margin.left + "," + (height + margin.top - 15) + ")")
            .call(xAxis)
            .append("text")
            .attr("x", width)
            .attr("y", -6)
            .style("text-anchor", "end")
        //.text("Year");
        chart.append("g")
            .attr("class", "y axis")
            .attr("transform", "translate(" + margin.left + "," + (margin.top - 15) + ")")
            .call(yAxis)
            .append("text")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end")
        //.text("Sales Data");
//         alert("BuildBar function level4 : " +level);

            chart.select(".x.axis")
                .selectAll("text")
            .attr("transform", " translate(-20,10) rotate(-30)");
        
//        alert("BuildBar function end");
    }

    function TransformChartData(chartData, opts, level, filter) {
      //  alert("TransformChartData function");
        var result = [];
        var resultColors = [];
        var counter = 0;
        var hasMatch;
        var xVarName;
        var yVarName = opts[0].yaxis;
      //  alert("TransformChartData function level : " +level);
        
//            alert("TransformChartData function level0");
            xVarName = opts[0].xaxis;
            for (var i in chartData) {
                hasMatch = false;  
//                alert("result : "+result);
//                alert("result length : "+result.length);
//                alert("chartData[i][opts[0].xaxisl1] : "+chartData[i][opts[0].xaxisl1]);
                for (var index = 0; index < result.length; ++index) {
                    var data = result[index];
//                    alert("data[xVarName] : "+data[xVarName]);
//                    alert("chartData[i][xVarName] : "+chartData[i][xVarName]);                    
                    if (data[xVarName] == chartData[i][xVarName]) {
                        result[index][yVarName] = result[index][yVarName] + chartData[i][yVarName];
                        hasMatch = true;
                        break;
                    }
                }
                if (hasMatch == false) {
                    ditem = {};
                    ditem[xVarName] = chartData[i][xVarName];
                    ditem[yVarName] = chartData[i][yVarName];
                    ditem["caption"] = opts[0].captions != undefined ? opts[0].captions[0][chartData[i][xVarName]] : "";
                    ditem["title"] = opts[0].captions != undefined ? opts[0].captions[0][chartData[i][xVarName]] : "";
                    ditem["op"] = 1;
                    result.push(ditem);
                    resultColors[counter] = opts[0].color != undefined ? opts[0].color[0][chartData[i][xVarName]] : "";
                    counter += 1;
                }
            }
        
                    var totalcol = 0;
                    for (s = 0; s < result.length; s++){
//                            result[s]['Total'] = result[s]['Total']/100000;
            totalcol = totalcol + result[s]['Total'];
            }
            $('#totalcollection').val(totalcol);
        runningData = result;
        runningColors = resultColors;
        console.log(result);
//        alert("TransformChartData function end");
        return;
    }

</script>
<script type="text/javascript">
    var chartData = [
<?php foreach ($data as $data1) {
    ?>
        {
                     
            Office: "<?php echo $data1['office_name_en']; ?>",
            Total: <?php echo $data1['total']; ?>,
        },
         <?php } ?>
    ];
    chartOptions = [{
        "captions": [{
            <?php foreach ($data as $data1) { ?>

            "<?php echo $data1['office_name_en']; ?>": "<?php echo $data1['office_name_en']; ?>",
            <?php } ?>
            }],
        "color": [{
            <?php foreach ($data as $data1) { ?>

            "<?php echo $data1['office_name_en']; ?>": getRandomColor(),
            <?php } ?>

                    }],
        "xaxis": "Office",
        "yaxis": "Total"
            }]


    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

</script>