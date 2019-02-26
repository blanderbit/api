
<!DOCTYPE html>
<html>
<head>
    <title>Chart.js Rounded Bar Charts Demo</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    {{--<script src="../Chart.roundedBarCharts.js"></script>--}}
</head>


<body>
<div id="main" data-id-unicum="{{$id_unicum}}"
     data-one-unicum="{{$one}}"
     data-two-unicum="{{$two}}"
     data-three-unicum="{{$three}}"
     data-four-unicum="{{$four}}"
     data-five-unicum="{{$five}}"
     data-six-unicum="{{$six}}"
>
    <div style="width: 100vw; height: 400px; ">
        <style>
            #down {
                text-decoration: none;
                outline: none;
                display: inline-block;
                color: white;
                padding: 20px 30px;
                margin: 10px 20px;
                border-radius: 10px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                letter-spacing: 2px;
                background-image: linear-gradient(to right, #9EEFE1 0%, #4830F0 51%, #9EEFE1 100%);
                background-size: 200% auto;
                box-shadow: 0 0 20px rgba(0,0,0,.1);
                transition: .5s;
            }
            #down {background-position: right center;}
        </style>
        <div class="spin" id="button_spin" style="width: 100%; height: 100%; background: white; position: absolute"></div>
        <a href="http://274e4203.ngrok.io/api/pdf/22" id="down">Button</a>
    </div>
    <div class="container" style="width: 800px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="one" style="width: 800px; margin-bottom:  40px"></canvas>
    </div>
    <div class="container" style="width: 800px; margin-bottom:  40px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="two" style="margin-bottom: 40px"></canvas>
    </div>
    <div class="container" style="width: 800px; margin-bottom:  40px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="three" style="margin-bottom: 40px"></canvas>
    </div>
    <div class="container" style="width: 800px; margin-bottom:  40px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="four"></canvas>
    </div>
    <div class="container" style="width: 800px; margin-bottom:  40px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="five" ></canvas>
    </div>
    <div class="container" style="width: 800px; margin-bottom:  40px">
        <div class="spin" style="width: 100vw; height: 100vh; background: white; position: absolute"></div>
        <canvas id="six" ></canvas>
    </div>
</div>

{{--<button id="convert">213123</button>--}}
</body>
<script>
    /*
*   Rounded Rectangle Extension for Bar Charts and Horizontal Bar Charts
*   Tested with Charts.js 2.7.0
*/

    var promise = [];
        let glob_height = [];

        Chart.elements.Rectangle.prototype.draw = function() {
// debugger;
            var ctx = this._chart.ctx;
            // console.log(this);
            var vm = this._view;
            var left, right, top, bottom, signX, signY, borderSkipped, radius;
            var borderWidth = this._model.datasetLabel == 'small' ? 1 : vm.borderWidth;
            // ctx.gridLines ="#D8E1F0"
            // If radius is less than 0 or is large enough to cause drawing errors a max
            //      radius is imposed. If cornerRadius is not defined set it to 0.
            var cornerRadius = this._chart.config.options.cornerRadius;
            if(cornerRadius < 0){ cornerRadius = 0; }
            if(typeof cornerRadius == 'undefined'){ cornerRadius = 0; }
            if (!vm.horizontal) {
                // bar
                left = vm.x - vm.width / 2;
                right = vm.x + vm.width / 2;
                top = vm.y;
                bottom = vm.base;
                signX = 1;
                signY = bottom > top? 1: -1;
                borderSkipped = vm.borderSkipped || 'bottom';
            } else {
                // horizontal bar
                left = vm.base;
                right = vm.x;
                top = vm.y - vm.height / 2;
                bottom = vm.y + vm.height / 2;
                signX = right > left? 1: -1;
                signY = 1;
                borderSkipped = vm.borderSkipped || 'left';
            }

            // Canvas doesn't allow us to stroke inside the width so we can
            // adjust the sizes to fit if we're setting a stroke on the line
            if (borderWidth) {
                // borderWidth shold be less than bar width and bar height.
                var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                borderWidth = borderWidth > barSize? barSize: borderWidth;
                var halfStroke = borderWidth / 2;
                // Adjust borderWidth when bar top position is near vm.base(zero).
                var borderLeft = left + (borderSkipped !== 'left'? halfStroke * signX: 0);
                var borderRight = right + (borderSkipped !== 'right'? -halfStroke * signX: 0);
                var borderTop = top + (borderSkipped !== 'top'? halfStroke * signY: 0);
                var borderBottom = bottom + (borderSkipped !== 'bottom'? -halfStroke * signY: 0);
                // not become a vertical line?
                if (borderLeft !== borderRight) {
                    top = borderTop;
                    bottom = borderBottom;
                }
                // not become a horizontal line?
                if (borderTop !== borderBottom) {
                    left = borderLeft;
                    right = borderRight;
                }
            }

            ctx.beginPath();
            ctx.fillStyle = vm.backgroundColor;
            ctx.strokeStyle = vm.borderColor;
            ctx.borderWidth = 2;
            ctx.borderColor = vm.backgroundColor;
            ctx.lineWidth = borderWidth;

            // Corner points, from bottom-left to bottom-right clockwise
            // | 1 2 |
            // | 0 3 |
            var corners = [
                [left, bottom],
                [left, top],
                [right, top],
                [right, bottom]
            ];

            // Find first (starting) corner with fallback to 'bottom'
            var borders = ['bottom', 'left', 'top', 'right'];
            var startCorner = borders.indexOf(borderSkipped, 0);
            if (startCorner === -1) {
                startCorner = 0;
            }

            function cornerAt(index) {
                return corners[(startCorner + index) % 4];
            }

            // Draw rectangle from 'startCorner'
            var corner = cornerAt(0);
            ctx.moveTo(corner[0], corner[1]);

            for (var i = 1; i < 4; i++) {
                corner = cornerAt(i);
                nextCornerId = i+1;
                if(nextCornerId == 4){
                    nextCornerId = 0
                }

                nextCorner = cornerAt(nextCornerId);

                width = 28;//corners[2][0] - corners[1][0] , corners[2][0] - corners[1][0] - 10
                height = corners[0][1] - corners[1][1];
                // console.log(height)
                // if(this._model.datasetLabel == 'small') height = glob_height[this._index];
                if(i == 1 && this._model.datasetLabel != 'small') glob_height.push(corners[0][1] - corners[1][1]);
                x = this._model.datasetLabel == 'small' ? corners[1][0] + 80 :corners[1][0] + 37;//corners[1][0]
                y = this._model.datasetLabel == 'small' ? corners[1][1] - glob_height[this._index] - 4:corners[1][1];
                var radius = this._model.datasetLabel == 'small' ? 15 : 40;
                // Fix radius being too large
                if(this._model.datasetLabel != 'small'){
                    if(radius > Math.abs(height)/2){
                        radius = Math.floor(Math.abs(height)/2
                        );
                    }
                    if(radius > Math.abs(width)/2){
                        radius = Math.floor(Math.abs(width)/2);
                    }
                }
                if(this._model.datasetLabel == 'small'){
                    ctx.moveTo(x + radius , y);
                    ctx.lineTo(x + width - radius, y);
                    ctx.quadraticCurveTo(x + width, y, x + width, y + radius );
                    ctx.lineTo(x + width , y + height - radius );
                    ctx.quadraticCurveTo(x + width , y + height, x + width - radius  , y + height);
                    ctx.lineTo(x  + radius, y + height); //ctx.lineTo(x + radius , y + height);
                    ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                    ctx.lineTo(x, y + radius);
                    ctx.quadraticCurveTo(x, y, x + radius, y);
                } else {

                    if(height < 15){
                        ctx.moveTo(x + radius, y);
                        ctx.lineTo(x + width - radius, y);
                        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                        ctx.lineTo(x + width + 1, y + height - radius );
                        ctx.quadraticCurveTo(x + width , y + height, x + width - radius , y + height);
                        ctx.lineTo(x  , y + height); //ctx.lineTo(x + radius , y + height);
                        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                        ctx.lineTo(x, y + radius);
                        ctx.quadraticCurveTo(x, y, x + radius, y);
                    } else if (height < 35) {
                        ctx.moveTo(x + radius, y);
                        ctx.lineTo(x + width - radius, y);
                        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                        ctx.lineTo(x + width + 1, y + height - radius - 10);
                        ctx.quadraticCurveTo(x + width , y + height, x + width - radius - 10, y + height);
                        ctx.lineTo(x  , y + height); //ctx.lineTo(x + radius , y + height);
                        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                        ctx.lineTo(x, y + radius);
                        ctx.quadraticCurveTo(x, y, x + radius, y);
                    }

                    else {
                        ctx.moveTo(x + radius, y);
                        ctx.lineTo(x + width - radius, y);
                        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                        ctx.lineTo(x + width + 1, y + height - radius - 30);
                        ctx.quadraticCurveTo(x + width , y + height, x + width - radius - 10, y + height);
                        ctx.lineTo(x  , y + height); //ctx.lineTo(x + radius , y + height);
                        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                        ctx.lineTo(x, y + radius);
                        ctx.quadraticCurveTo(x, y, x + radius, y);
                    }

                }

                // }
                ctx.shadowColor = this._model.datasetLabel == 'small' ? 'rgba(0, 0, 0, 0.25)' :  'rgba(0, 0, 0, 0.35)' ;
                ctx.shadowBlur = 6;
                ctx.shadowOffsetX =1;
                ctx.shadowOffsetY = 2;
            }

            ctx.fill();
            if (borderWidth) {
                ctx.stroke();
            }
        };

        var size = [document.documentElement.clientWidth,document.documentElement.clientHeight];
        window.onresize = function(){
            document.body.style.zoom=document.documentElement.clientWidth/size[0];
        }

        var backgroundColor = 'white';
        Chart.plugins.register({
            beforeDraw: function(c) {
                var ctx = c.chart.ctx;
                ctx.fillStyle = backgroundColor;
                ctx.fillRect(0, 0, c.chart.width, c.chart.height);
            }
        });
        function data_options(str){
            return  {
                labels: ["R", "I", "A", "S", "E", "C", ''],
                fillColor: 'red',
                fontColor: 'red',
                datasets: [
                    {
                        label: "small",
                        backgroundColor: 'white',
                        borderColor: [ '#F59A9A', '#4876D0', '#77DCC1', '#85B1F5', '#FDC572', '#F9892E'],
                        data: [8,8,8,8,8,8]
                    }, {
                        fillColor: ['#F59A9A', '#4876D0', '#77DCC1', '#85B1F5', '#FDC572', '#F9892E', 'red'],
                        strokeColor: ['#F59A9A', '#4876D0', '#77DCC1', '#85B1F5', '#FDC572', '#F9892E', 'red'],
                        backgroundColor: [
                            '#F59A9A', '#4876D0', '#77DCC1', '#85B1F5', '#FDC572', '#F9892E', 'transparent'
                        ],
                        data: str.split(',').concat(120).map(function(name) {
                            return Number(name);
                        }),
                    }
                ]
            };
        }
        var count = 1;
        var options = {
            cornerRadius: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        min:0,
                        max:110,
                        stepSize:50,
                        fontColor: 'black',
                        paddingLeft: 30
                    },
                    afterBuildTicks: function(humdaysChart) {
                        humdaysChart.ticks = [];

                        humdaysChart.paddingLeft = 20;
                        humdaysChart.left = 20;
                        humdaysChart.margins.left = 20;
                        humdaysChart.ticks.push(0);
                        humdaysChart.ticks.push(20);
                        humdaysChart.ticks.push(40);
                        humdaysChart.ticks.push(60);
                        humdaysChart.ticks.push(80);
                        humdaysChart.ticks.push(100);
                    },
                    gridLines: {
                        color: "#D8E1F0",
                    }
                }],
                xAxes:[{
                    ticks: {
                        autoSkip: false,
                        beginAtZero:true,
                        fillColor:"red",
                        fontColor: 'white'
                    },
                    gridLines: {
                        color: "#D8E1F0",
                    }
                }]
            },
            gridLines: {
                display: false ,
                color: "#D8E1F0"
            },
            events: false,
            tooltips: {
                enabled: false
            },
            hover: {
                animationDuration: 0
            },
            legend: {
                display: false,
            },
            animation: {
                duration: 1,
                onComplete: function () {
                    var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                    var constants = null;
                    var controller = this.chart.controller;
                    var chart = controller.chart;
                    var xAxis = controller.scales['x-axis-0'];
                    var numTicks = xAxis.ticks.length;
                    var xOffsetStart = xAxis.width / numTicks;
                    var halfBarWidth = (xAxis.width / (numTicks * 2));
                    xAxis.paddingLeft = xAxis.paddingLeft + 30;
                    xAxis.ticks.forEach(function(value, index) {
                        var xOffset = (xOffsetStart * index) + halfBarWidth + 87;
                        var yOffset = chart.height -5;
                        ctx.fillStyle = 'black';
                        ctx.fontSize = 9;
                        ctx.fillText(value, xOffset, yOffset);
                    });
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.reverse().forEach(function (bar, index) {
                            var data = dataset.data[index];
                            if(!constants) constants = bar._chart.config.data.datasets[1].data.reverse().splice(1);
                            if(bar._model.datasetLabel == "small") {
                                ctx.beginPath();
                                ctx.fillStyle = 'black';
                                ctx.strokeStyle = 'red';
                                ctx.fontSize = 10;
                                ctx.lineWidth = 20;
                                ctx.fill();
                                ctx.stroke();
                                if (constants[index] < 10) {
                                    ctx.fillText(constants[index], bar._model.x + 72, bar._model.y + 14 - glob_height[bar._index]);
                                } else if(constants[index] < 100){
                                    ctx.fillText(constants[index], bar._model.x + 69, bar._model.y + 14 - glob_height[bar._index]);
                                }else {
                                    ctx.fillText(constants[index], bar._model.x + 65, bar._model.y + 14 - glob_height[bar._index]);
                                }
                            }

                        });
                    });
                    glob_height = [];
                    var obj = {};
                    obj[`count`] = count;
                    obj[`image`] = ctx.canvas.toDataURL("image/jpeg", 1.0);
                    if(count == 6) {
                        count = 0;
                        return;
                    } else {
                        promise.push($.ajax( `${window.location.origin}/api/post/${location.pathname.split('/')[3]}`,{
                            type: 'Post',
                            dataType: "json",
                            data: obj
                        }));
                        count++;
                    }
                }
            }
        };

        // function onLoad(){
        //     document.querySelector('body').style.overflow = 'hidden';
            let id = document.querySelector('#main').getAttribute('data-id-unicum');
            let one = document.querySelector('#main').getAttribute('data-one-unicum');
            let two = document.querySelector('#main').getAttribute('data-two-unicum');
            let three = document.querySelector('#main').getAttribute('data-three-unicum');
            let four = document.querySelector('#main').getAttribute('data-four-unicum');
            let five = document.querySelector('#main').getAttribute('data-five-unicum');
            let six = document.querySelector('#main').getAttribute('data-six-unicum');

            let chart1= new Chart(document.getElementById("one"), {
                type: 'bar',
                data: data_options(one),
                options: options
            });
            let chart2= new Chart(document.getElementById("two"), {
                type: 'bar',
                data: data_options(two),
                options: options
            });
            let chart3=new Chart(document.getElementById("three"), {
                type: 'bar',
                data: data_options(three),
                options: options
            });
            let chart4= new Chart(document.getElementById("four"), {
                type: 'bar',
                data: data_options(four),
                options: options
            });
            let chart5= new Chart(document.getElementById("five"), {
                type: 'bar',
                data: data_options(five),
                options: options
            });
        // }
        Chart.defaults.multicolorLine = Chart.defaults.line;
        Chart.controllers.multicolorLine = Chart.controllers.line.extend({
            draw: function(ease) {
                var ctx = this.chart.ctx;
                // debugger
                var
                    startIndex = 0,
                    meta = this.getMeta(),
                    points = meta.data || [],
                    colors = this.getDataset().colors,
                    data = this.getDataset().data,
                    area = this.chart.chartArea,
                    originalDatasets = meta.dataset._children
                        .filter(function(data) {
                            return !isNaN(data._view.y);
                        });
                function _setColor(newColor, meta) {
                    meta.dataset._view.borderColor = newColor;
                }

                if (!colors) {
                    Chart.controllers.line.prototype.draw.call(this, ease);
                    return;
                }

                for (var i = 2; i <= colors.length; i++) {
                    if (colors[i-1] !== colors[i]) {
                        _setColor(colors[i-1], meta);
                        meta.dataset._children = originalDatasets.slice(startIndex, i);
                        meta.dataset.draw();
                        startIndex = i - 1;
                    }
                }

                meta.dataset._children = originalDatasets.slice(startIndex);
                meta.dataset.draw();
                meta.dataset._children = originalDatasets;

                points.forEach(function(point, index) {
                    point._view.borderColor = colors[index];
                    // ctx.fillText(222, 222, 222);
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.35)';
                    ctx.shadowBlur = 8;
                    ctx.shadowOffsetX = 1;
                    ctx.shadowOffsetY = 1;
                    point.draw(area);
                });
            }
        });
        var config = {
            type: 'multicolorLine',
            data: {
                labels: ['', 'R', 'I', 'A', 'S', 'E', 'C', ''],
                datasets: [{
                    label: "Stock A",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "blue",
                    borderColor: "red", // The main line color
                    borderCapStyle: 'square',
                    borderDash: [], // try [5, 15] for instance
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "black",
                    pointBackgroundColor: "white",
                    pointBorderWidth: 3,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: "yellow",
                    pointHoverBorderColor: "brown",
                    pointHoverBorderWidth: 0,
                    pointRadius: 18,
                    pointHitRadius: 18,
                    // notice the gap in the data and the spanGaps: true
                    data: [,].concat(six.split(',')).concat([,]),
                    colors: ['', '#F59A9A', '#4876D0', '#77DCC1', '#85B1F5', '#FDC572', '#F9892E'],
                    spanGaps: false,
                }]
            },
            options: {
                // responsive: true,
                legend: {
                    position: 'bottom',
                    display: false
                },
                hover: {
                    // mode: 'index'
                    display: false
                },
                showTooltips: false,
                events: false,
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            // labelString: 'Month'
                        },
                        ticks: {
                            fontColor: 'black',
                        },
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            // labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero:true,
                            min:- 20,
                            max:110,
                            stepSize:50,
                            fontColor: 'black',
                            paddingLeft: 30,
                        },
                        afterBuildTicks: function(humdaysChart) {
                            humdaysChart.ticks = [];

                            humdaysChart.paddingLeft = 20;
                            humdaysChart.left = 20;
                            humdaysChart.margins.left = 20;
                            // humdaysChart.ticks.push(- 5);
                            humdaysChart.ticks.push(0);
                            humdaysChart.ticks.push(20);
                            humdaysChart.ticks.push(40);
                            humdaysChart.ticks.push(60);
                            humdaysChart.ticks.push(80);
                            humdaysChart.ticks.push(100);
                        },
                    }]
                },
                animation: {
                    onComplete: function(animation) {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            // console.log(meta);
                            var data = meta.controller._data.reverse();
                            meta.data.reverse().forEach(function (bar, index) {
                                ctx.beginPath();
                                ctx.fillStyle = 'black';
                                ctx.mode= 'index';
                                ctx.fill();
                                ctx.stroke();
                                if (data[index] < 10) {
                                    ctx.fillText(data[index], bar._model.x - 2, bar._model.y + 4);
                                } else if(data[index] == 100){
                                    ctx.fillText(data[index], bar._model.x - 10, bar._model.y + 4);
                                } else {
                                    ctx.fillText(data[index], bar._model.x - 6, bar._model.y + 4);
                                }
                            });
                        });
                        var obj = {};
                        obj[`count`] = 6;
                        obj[`image`] = ctx.canvas.toDataURL("image/jpeg", 1.0);
                        promise.push($.ajax(`${window.location.origin}/api/post/${location.pathname.split('/')[3]}`,{
                            type: 'Post',
                            dataType: "json",
                            data: obj
                        }));
                        Promise.all(promise).then(v=> {
                            document.querySelector('#down').setAttribute('href',
                                `${window.location.origin}/api/pdf/${location.pathname.split('/')[3]}`);
                            document.querySelector('#button_spin').style.display = 'none'
                        });
                    }
                },
                title: {
                    display: false,
                    // text: 'Chart.js Line Chart - Different point sizes'
                }
            }
        };

        var ctx = document.getElementById('six').getContext('2d');
        new Chart(ctx, config);

</script>
</html>


