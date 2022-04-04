<script src="https://code.jquery.com/jquery-1.12.4.min.js" charset="utf-8"></script>
<script src="https://d3js.org/d3.v2.min.js"></script>
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

    body {
        /*background: #1a1a1a;*/
        color: #eaeaea;
        padding: 10px;
    }

    path {
        stroke: steelblue;
        stroke-width: 2;
        fill: none;
    }

</style>


<body>
    <div id="chart" style="height:600px;width:900px">
        <div class="innerCont" style="overflow: auto; top:100px; left: 100px; height:91% ; Width:100% ;position: relative;overflow: hidden;"></div>
    </div>
</body>
<input type='text' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<br><br><br><br>

<script type="text/javascript">
    var salesData;
    var truncLengh = 30;
    $(document)
        .ready(function () {
//            console.log("ready function");
            Plot();
        });

    function Plot() {
//        console.log("plot function");
        TransformChartData(chartData, chartOptions);
        BuildBar("chart", chartData, chartOptions);
    }

    function BuildBar(id, chartData, options, level) {
//        console.log("BuildBar function");
        //d3.selectAll("#" + id + " .innerCont").remove();
        //$("#" + id).append(chartInnerDiv);
        chart = d3.select("#" + id + " .innerCont");
        var margin = { top: 50, right: 10, bottom: 50, left: 50 },
            width = $(chart[0])
            .outerWidth() - margin.left - margin.right,
            height = $(chart[0])
            .outerHeight() - margin.top - margin.bottom
        var xVarName;
        var yVarName = options[0].yaxis;
//        console.log("yVarName : "+yVarName);
//         console.log("BuildBar function level1 : " +level);
        if (level == 2) {
            xVarName = options[0].xaxisl2;
        } else if (level == 1) {
            xVarName = options[0].xaxisl1;
        } else {
            xVarName = options[0].xaxis;
        }
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
//                console.log("BuildBar function level2 : " +level);
                if (level == 0) {
                    var mapper = options[0].captions[0];
//                    console.log("mapper[d] : "+mapper[d]);
                    return mapper[d];
                }  else {
                    var r1 = runningData[ctrtxt].caption;
                    ctrtxt += 1;                    
//                     console.log("r1 : "+r1);
                    return r1;
                }
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
//                console.log("BuildBar function level3 : " +level);
//                console.log("xVarName  : "+d[xVarName]);
                if (level == 2) {
                    TransformChartData(chartData, options, 0, d[xVarName]);
                    BuildBar(id, chartData, options, 0);
                } else if (level == 1) {
                    var filter1 = $('#hfid').val();
                    var nonSortedChart = chartData.sort(function (a, b) {
                        return parseFloat(b[options[0].yaxis]) - parseFloat(a[options[0].yaxis]);
                    });
                    TransformChartData(nonSortedChart, options, 2, d[xVarName],filter1);
                    BuildBar(id, nonSortedChart, options, 2);
                } else {
                    $('#hfid').val(d[xVarName]);
                        var nonSortedChart = chartData.sort(function (a, b) {
                        return parseFloat(b[options[0].yaxis]) - parseFloat(a[options[0].yaxis]);
                    });
                    TransformChartData(nonSortedChart, options, 1, d[xVarName]);
                    BuildBar(id, nonSortedChart, options, 1);
                }
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
            .attr("x", x.rangeBand() / 2 + margin.left - 10)
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
//         console.log("BuildBar function level4 : " +level);
        if (level == 1) {
           
            chart.select(".x.axis")
                .selectAll("text")
                .attr("transform", " translate(-20,10) rotate(-35)");
        } else if (level == 2) {
            chart.select(".x.axis")
                .selectAll("text")
                .attr("transform", " translate(-20,10) rotate(-35)");
        } else {
            chart.select(".x.axis")
                .selectAll("text")
            .attr("transform", " translate(-20,10) rotate(-90)");
        }
//        console.log("BuildBar function end");
    }

    function TransformChartData(chartData, opts, level, filter, filter1) {
        console.log("TransformChartData function");
        var result = [];
        var resultColors = [];
        var counter = 0;
        var hasMatch;
        var xVarName;
        var yVarName = opts[0].yaxis;
        console.log("TransformChartData function level : " +level);
        console.log("chartData : ");
        console.log(chartData);
        console.log("opts : ");
        console.log(opts);
        console.log("filter : " +filter);
        if (level == 2) {
            console.log("TransformChartData function level2");
            xVarName = opts[0].xaxisl2;
            for (var i in chartData) {
                hasMatch = false;
                console.log("result 2 level : ");
                console.log(result);
                console.log("result length : "+result.length);
                              
                console.log("chartData[i][opts[0].xaxisl1] : "+chartData[i][opts[0].xaxisl1]);
                for (var index = 0; index < result.length; ++index) {
                    var data = result[index];
                     console.log("data[xVarName] : "+data[xVarName]);
                     console.log("chartData[i][xVarName] : "+chartData[i][xVarName]);
                    if ((data[xVarName] == chartData[i][xVarName]) && (chartData[i][opts[0].xaxisl1]) == filter && (chartData[i][opts[0].xaxis]) == filter1) {
                        console.log("result[index][yVarName] : "+result[index][yVarName]);
                        console.log("chartData[i][yVarName] : "+chartData[i][yVarName]);
                        result[index][yVarName] = result[index][yVarName] + chartData[i][yVarName];
                        console.log(" Y axis result : "+result[index][yVarName]);
                        hasMatch = true;
                        break;
                    }
                }
                if ((hasMatch == false) && ((chartData[i][opts[0].xaxisl1]) == filter) && ((chartData[i][opts[0].xaxis]) == filter1)) {
                     console.log("in filter");
                    if (result.length < 9) {
                        ditem = {}
                        ditem[xVarName] = chartData[i][xVarName];
                        ditem[yVarName] = chartData[i][yVarName];
                        ditem["caption"] = chartData[i][xVarName];
                        ditem["title"] = chartData[i][xVarName];
                        ditem["op"] = 1.0 - parseFloat("0." + (result.length));
                        
                        result.push(ditem);
                        resultColors[counter] = opts[0].color[0][chartData[i][opts[0].xaxis]];
                        counter += 1;
                    }
                }
            }
        } else if (level == 1) {
            console.log("TransformChartData function level1");
            xVarName = opts[0].xaxisl1;
            for (var i in chartData) {
                hasMatch = false; 
                console.log("result 1 level : ");
                console.log(result);
                console.log("result length : "+result.length);
                               
                console.log("chartData[i][opts[0].xaxisl] : "+chartData[i][opts[0].xaxis]);
                for (var index = 0; index < result.length; ++index) {                    
                    var data = result[index];
                    console.log("data[xVarName] : "+data[xVarName]);
                    console.log("chartData[i][xVarName] : "+chartData[i][xVarName]);
                    if ((data[xVarName] == chartData[i][xVarName]) && (chartData[i][opts[0].xaxis]) == filter) {
                        console.log("result[index][yVarName] : "+result[index][yVarName]);
                        console.log("chartData[i][yVarName] : "+chartData[i][yVarName]);
                        result[index][yVarName] = result[index][yVarName] + chartData[i][yVarName];
                        console.log(" Y axis result : "+result[index][yVarName]);
                        hasMatch = true;
                        break;
                    }
                }
                if ((hasMatch == false) && ((chartData[i][opts[0].xaxis]) == filter)) {
                     console.log("in filter");
                     console.log(chartData[i][xVarName]);
                    if (result.length < 9) {
                        ditem = {}
                        ditem[xVarName] = chartData[i][xVarName];
                        ditem[yVarName] = chartData[i][yVarName];
                        ditem["caption"] = chartData[i][xVarName];
                        ditem["title"] = chartData[i][xVarName];
                        ditem["op"] = 1.0 - parseFloat("0." + (result.length));
                        result.push(ditem);
                        resultColors[counter] = opts[0].color[0][chartData[i][opts[0].xaxis]];
                        counter += 1;
                    }
                }
            }
        } else {
            console.log("TransformChartData function level0");
            xVarName = opts[0].xaxis;
            for (var i in chartData) {
                hasMatch = false;  
                console.log("result 0 level : ");
                 console.log(result);
                console.log("result length : "+result.length);
                               
                for (var index = 0; index < result.length; ++index) {
                    var data = result[index];
                    console.log("data[xVarName] : "+data[xVarName]);
                    console.log("chartData[i][xVarName] : "+chartData[i][xVarName]);
                    if (data[xVarName] == chartData[i][xVarName]) {
                        console.log("result[index][yVarName] : "+result[index][yVarName]);
                        console.log("chartData[i][yVarName] : "+chartData[i][yVarName]);
                        result[index][yVarName] = result[index][yVarName] + chartData[i][yVarName];
                        console.log(" Y axis result : "+result[index][yVarName]);
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
        }
        console.log("Result : ");
        console.log(result);
        runningData = result;
        runningColors = resultColors;
        console.log("TransformChartData function end");
        return;
    }

</script>
<script type="text/javascript">
    var chartData = [
<?php foreach ($data as $data1) {
    ?>
        {
            District: "<?php echo $data1['distname']; ?>",
            Office: "<?php echo $data1['document_type']; ?>",
            Model: "<?php echo $data1['model']; ?>",
            Total: <?php echo $data1['Total']; ?>,
        },
         <?php } ?>
    ];
    chartOptions = [{
        "captions": [{
            <?php foreach ($data as $data1) { ?>

            "<?php echo $data1['distname']; ?>": "<?php echo $data1['distname']; ?>",
            <?php } ?>
            }],
        "color": [{
            <?php foreach ($data as $data1) { ?>

            "<?php echo $data1['distname']; ?>": "#FFA500",
            <?php } ?>

                    }],
        "xaxis": "District",
        "xaxisl1": "Office",
        "xaxisl2": "Model",
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
