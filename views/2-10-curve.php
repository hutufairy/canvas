<!DOCTYPE html>
<html>
    <head>
        <title>2-10-curve</title>
        <style>
            body{background: #ddd;}
            #canvas{
                background: #fff;
                cursor: pointer;
                margin: 10px 0 0 10px;
                box-shadow: 4px 4px 8px rgba(0,0,0,0.5);
            }
            #controls{
                position: absolute;
                left: 25px;
                top: 25px;
            }
        </style>
    </head>
    <body>
        <canvas id="canvas" width="600" height="600">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d');
            var cW = canvas.width, cH = canvas.height;
            var PI = Math.PI, angle_max = 2 * PI,
                arrow_margin = 30, point_radiu = 7,
                points = [{
                        x: cW - arrow_margin,
                        y: cH - arrow_margin
                    },{
                        x: cW - arrow_margin*2,
                        y: cH - arrow_margin
                    },{
                        x: point_radiu,
                        y: cH/2
                    },{
                        x: arrow_margin,
                        y: cH/2 - arrow_margin
                    },{
                        x: cW - arrow_margin,
                        y: arrow_margin
                    },{
                        x: cW - arrow_margin,
                        y: arrow_margin*2
                    }],
                points_start = [{
                    x: points[0].x, 
                    y: points[0].y - arrow_margin
                },{
                    x: arrow_margin,
                    y: cH/2+arrow_margin
                },{
                    x: cW - 2*arrow_margin,
                    y: arrow_margin
                }];

            var endPoints = [{x: 130, y: 70}, {x: 430, y: 270}],
                controlPoints = [{x: 130, y: 250}, {x: 450, y: 70}];

            function draw(){
                drawGrid(context, '#efefef', 10, 10);
                drawCurve();
                drawArrow();
                drawBezierPoints();
                drawBezierCurve();
            }

            // draw grid
            function drawGrid(context, color, stepx, stepy){
                context.save();
                context.shadowColor = undefined;
                context.shadowOffsetX = 0;
                context.shadowOffsetY = 0;
                context.fillStyle = '#ffffff';
                context.strokeStyle = color;
                context.shadowBlur = 0;
                context.lineWidth = 0.5;
                context.fillRect(0, 0, cW, cH);

                for(var i = stepx + 0.5; i < cW; i+= stepx){
                    context.beginPath();
                    context.moveTo(i, 0);
                    context.lineTo(i, cH);
                    context.stroke();
                }
                for(var i = stepy + 0.5; i < cH; i += stepy){
                    context.beginPath();
                    context.moveTo(0, i);
                    context.lineTo(cW, i);
                    context.stroke();
                }
                context.restore();
            }

            function drawCurve(){
                context.save();
                context.shadowColor = 'rgba(50,50,50,0.5)';
                context.shadowOffsetX = 2;
                context.shadowOffsetY = 2;
                context.shadowBlur = 4;
                context.lineWidth = 20;
                context.lineCap = 'round';
                context.fillStyle = 'cornflowerblue';
                context.strokeStyle = '#ff8989';

                context.beginPath();
                context.moveTo(120.5, 130);
                context.quadraticCurveTo(150.8, 130, 160.6, 150.5);
                context.quadraticCurveTo(190, 250, 210.5, 160.5);
                context.quadraticCurveTo(240, 100.5, 290, 70.5);
                context.stroke();
                context.restore();
            }

            function drawArrow(){
                context.save();

                context.strokeStyle = '#fff';
                context.fillStyle = 'cornflowerblue';
                context.lineWidth = 1;
                context.beginPath();
                context.moveTo(points[5].x, points[5].y);
                context.lineTo(points_start[0].x, points_start[0].y);
                context.quadraticCurveTo(points[0].x, points[0].y, points[1].x, points[1].y);

                context.lineTo(points_start[1].x, points_start[1].y);
                context.quadraticCurveTo(points[2].x, points[2].y, points[3].x, points[3].y);

                context.lineTo(points_start[2].x, points_start[2].y);
                context.quadraticCurveTo(points[4].x, points[4].y, points[5].x, points[5].y)
                context.fill();
                context.stroke();
                context.restore();
            }

            function drawPoint(x, y, strokeStyle, fillStyle){
                context.beginPath();
                context.fillStyle = fillStyle;
                context.strokeStyle = strokeStyle;
                context.lineWidth = 0.5;
                context.arc(x, y, point_radiu, 0, angle_max, false);
                context.fill();
                context.stroke();
            }

            function drawBezierPoints(){
                var strokeStyle, fillStyle;
                for(var i = 0; i < points.length; i++){
                    fillStyle = i%2 ? 'blue' : 'white';
                    strokeStyle = i%2 ? 'white' : 'blue';
                    drawPoint(points[i].x, points[i].y, strokeStyle, fillStyle);
                }
                context.font = "14px Helvetica";
                for(var i = 0; i < points_start.length; i++){
                    fillStyle = '#516d81';
                    drawPoint(points_start[i].x, points_start[i].y, strokeStyle, fillStyle);
                    context.fillText('start', points_start[i].x, points_start[i].y+20)
                }
            }

            function drawBezierCurve(){
                context.save();

                context.strokeStyle = '#666';
                context.beginPath();
                context.moveTo(endPoints[0].x, endPoints[0].y);
                context.bezierCurveTo(controlPoints[0].x, controlPoints[0].y, 
                                      controlPoints[1].x, controlPoints[1].y,
                                      endPoints[1].x, endPoints[1].y);
                context.stroke();

                endPoints.forEach(function(point){
                    drawPoint(point.x, point.y, '#1bbc9b', '#fff');
                });
                controlPoints.forEach(function(point){
                    drawPoint(point.x, point.y, 'yellow', '#1bbc9b');
                })
                context.restore();
            }
            draw();
        </script>
    </body>
</html>