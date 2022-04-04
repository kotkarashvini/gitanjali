<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
//echo $this->Html->script('d3.tip.v0.6.3');
//echo $this->Html->script('d3.v3.min');
?>

<style>

/*    body {
        font: 10px sans-serif;
    }*/

/*    svg {
  margin-left: 50px;
  margin-right: 50px;
  display: block;
}*/
    .axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }
    .axis text{
    font: Times;
    font-size: 12px;
    font-weight: bold;
}

    .bar {
        fill: orange;
    }

    .bar:hover {
        fill: orangered ;
    }

    .x.axis path {
        display: none;
    }

    .d3-tip {
        line-height: 1;
        font-weight: bold;
        padding: 12px;
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        border-radius: 2px;
    }

    /* Creates a small triangle extender for the tooltip */
    .d3-tip:after {
        box-sizing: border-box;
        display: inline;
        font-size: 10px;
        width: 100%;
        line-height: 1;
        color: rgba(0, 0, 0, 0.8);
        content: "\25BC";
        position: absolute;
        text-align: center;
    }

    /* Style northward tooltips differently */
    .d3-tip.n:after {
        margin: -1px 0 0 0;
        top: 100%;
        left: 0;
    }
</style>

<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
<script>
    $(document).ready(function () {
        
        var margin = {top: 40, right: 20, bottom: 30, left: 50},
        width = 300 - margin.left - margin.right,
                height = 300 - margin.top - margin.bottom;

        var formatPercent = d3.format("0");

        var x = d3.scale.ordinal()
                .rangeRoundBands([0, width], .1);

        var y = d3.scale.linear()
                .range([height, 0]);

        var xAxis = d3.svg.axis()
                .scale(x)
                .orient("bottom");

        var yAxis = d3.svg.axis()
                .scale(y)
                .orient("left")
                .tickFormat(formatPercent);

        var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .html(function (d) {
                    return "<strong>Office : </strong> <span style='color:red'>" + d.office_name_en + "</span><br>\n\
        <strong>Income : </strong> <span style='color:red'>"+ d.income + "</span>";
                })

        var svg = d3.select("#divbar").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        svg.call(tip);

//        d3.tsv("../app/webroot/files/data.csv", type, function (error, data) {
            d3.json("getgraph", function (index, data) {
                 var dataarr = [];
            $.each(data, function (index1, d) {
                dataarr[index1] = d;
                d.income = +d.income;
                return d.income;

            });
            x.domain(data.map(function (d) {
                return d.office_name_en;
            }));
            y.domain([0, d3.max(data, function (d) {
                    return d.income;
                })]);

            svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

            svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis)
                    .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Income");

            svg.selectAll(".bar")
                    .data(data)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", function (d) {
                        return x(d.office_name_en);
                    })
                    .attr("width", x.rangeBand())
                    .attr("y", function (d) {
                        return y(d.income);
                    })
                    .attr("height", function (d) {
                        return height - y(d.income);
                    })
                    .on('mouseover', tip.show)
                    .on('mouseout', tip.hide)

        });

      });</script>


<?php echo $this->Form->create('graph1', array('id' => 'graph1', 'autocomplete' => 'off')); ?>

<!--<iframe src="http://localhost/NGDRS/Masters/circle"></iframe>-->
<br>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">

                <center><h3 class="box-title headbolder"><?php echo __('lblbarchart'); ?></h3></center>

            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!--<div class="col-sm-1">&nbsp;</div>-->
                    <div class="col-sm-6">
                        <div id="divbar"></div>
                    </div>

                </div>
            </div>
        </div>




    </div>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
