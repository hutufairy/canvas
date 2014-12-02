<!DOCTYPE html>
<html>
    <head>
        <title>2-9-dial</title>
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
            var centroid_radius = 10,
                centroid_stroke_style = 'rgba(0,0,0,0.5)',
                centroid_fill_style = 'rgba(80,190,240,0.6)',

                ring_inner_radius = 35,
                ring_outer_radius = 55,
                ring_inner_stroke_style = 'rgba(0,0,0,0.1)';

                annotations_fill_style = 'rgba(0,0,230,0.9)',
                annotations_text_size = 12,

                tick_width = 10,
                tick_long_stroke_style = 'rgba(100,140,230,0.9)',
                tick_short_stroke_style = 'rgba(100, 140, 230, 0.7)',

                tracking_dial_stroke_style = 'rgba(100, 140, 230, 0.5)',

                guidewire_stroke_style = 'goldenrod',
                guidewire_fill_style = 'rgba(250, 250, 250, 0.6)',

                circle = {
                    x: cW/2,
                    y: cH/2,
                    radius: 150
                };
            var PI = Math.PI, angle_max = 2 * PI;

            function draw(){
                context.shadowColor = 'rgba(0,0,0,0.4)';
                context.shadowOffsetX = 2;
                context.shadowOffsetY = 2;
                context.shadowBlur = 4;
                context.textAlign = 'center';
                context.textBaseline = 'middle';
                drawGrid(context, '#efefef', 10, 10);
                drawDial();
            }

            function drawDial(){
                var loc = {x: circle.x, y: circle.y};
                drawCentroid();
                drawCentroidGuidewire(loc);
                drawRing();
                drawTickInnerCircle();
                drawTicks();
                drawAnnotations();
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

            function drawCentroid(){
                context.beginPath();
                context.save();
                context.strokeStyle = centroid_stroke_style;
                context.fillStyle = centroid_fill_style;
                context.arc(circle.x, circle.y, centroid_radius, 0, angle_max, false);
                context.stroke();
                context.fill();
                context.restore();
            }

            function drawCentroidGuidewire(loc){
                var angle = -1 * PI / 4,
                    radius, endpt;

                radius = circle.radius + ring_outer_radius;
                
                var dir = loc.x >= circle.x ? 1 : -1;
                endpt = {
                    x: circle.x + dir * radius * Math.cos(angle),
                    y: circle.y + dir * radius * Math.sin(angle)
                }

                context.save();

                context.strokeStyle = guidewire_stroke_style;
                context.fillStyle = guidewire_fill_style;
                context.beginPath();
                context.moveTo(circle.x, circle.y);
                context.lineTo(endpt.x, endpt.y);
                context.stroke();

                context.beginPath();
                context.strokeStyle = tick_long_stroke_style;
                context.arc(endpt.x, endpt.y, 5, 0, angle_max, false);
                context.fill();
                context.stroke();

                context.restore();

            }

            function drawRing(){
                drawRingOuterCircle();

                context.strokeStyle = ring_inner_stroke_style;
                context.arc(circle.x, circle.y, circle.radius + ring_inner_radius, 0, angle_max, false);
                context.fillStyle = 'rgba(100, 140, 230, 0.1)';
                context.fill();
                context.stroke();
            }

            function drawRingOuterCircle(){
                context.shadowColor = 'rgba(0,0,0,0.7)';
                context.shadowOffsetX = 3;
                context.shadowOffsetY = 3;
                context.shadowBlur = 4;
                context.strokeStyle = tracking_dial_stroke_style;
                context.beginPath();
                context.arc(circle.x, circle.y, circle.radius + ring_outer_radius, 0, angle_max, true);
                context.stroke();
            }

            function drawTickInnerCircle(){
                context.save();
                context.beginPath();
                context.strokeStyle = ring_inner_stroke_style;
                context.arc(circle.x, circle.y, circle.radius+ring_inner_radius-tick_width, 0, angle_max, false);
                context.stroke();
                context.restore();
            }

            function drawTick(angle, radius, cnt){
                var tickWidth = ( cnt % 2 === 0 ) ? tick_width : ( tick_width/2 );
                context.beginPath();
                context.moveTo( circle.x + Math.cos(angle) * (radius - tickWidth), 
                                circle.y + Math.sin(angle) * (radius - tickWidth) );
                context.lineTo( circle.x + Math.cos(angle) * radius, 
                                circle.y + Math.sin(angle) * radius );
                context.strokeStyle = tick_short_stroke_style;
                context.stroke();
            }

            function drawTicks () {
                var radius = circle.radius + ring_inner_radius;
                var angle_delta = PI/64, tickWidth;
                context.save();
                for(var angle = 0, cnt = 0; angle < angle_max; angle += angle_delta, cnt++){
                    drawTick(angle, radius, cnt);
                }
                context.restore();
            }

            function drawAnnotations(){
                var radius = circle.radius + ring_inner_radius;
                context.save();
                context.fillStyle = annotations_fill_style;
                context.font = annotations_text_size + 'px Helvetica';
                for( var angle = 0; angle < angle_max; angle += PI/8){
                    context.beginPath();
                    context.fillText((angle*180/PI).toFixed(0), 
                        circle.x + Math.cos(angle) * (radius - tick_width/2),
                        circle.y - Math.sin(angle) * (radius - tick_width/2))
                }
                context.restore();
            }
            draw();
        </script>
    </body>
</html>