<!DOCTYPE html>
<html>
    <head>
        <title>2-4-path</title>
        <style>
            body{background: #ddd;}
            #canvas{
                background: #fff;
                margin: 20px;
                padding: 20px;
                border: inset thin #aaa;
            }
        </style>
    </head>
    <body>
        <canvas id="canvas" width="1024" height="768">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var cW = canvas.width, cH = canvas.height;

            // draw grid
            function drawGrid(context, color, stepx, stepy){
                context.strokeStyle = color;
                context.lineWidth = 1

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
            }
            draw();
            drawGrid(context, '#efefef', 10, 10);

            context.font = "48pt Helvetica";
            context.strokeStyle = '#516d81';
            context.fillStyle = "#ff8989";
            context.lineWidth = 2;

            context.strokeText('Stroke', 60, 110);
            context.fillText('Fill', 440, 110);
            context.strokeText('Stroke & Fill', 650, 110);
            context.fillText('Stroke & Fill', 650, 110);

            // rectangles
            //
            context.lineWidth = 5;
            // context.strokeRect(80, 150, 150, 100);
            context.beginPath();
            context.rect(80, 150, 150, 100);
            context.stroke();

            context.beginPath();
            context.rect(400, 150, 150, 100);
            context.fill();

            context.beginPath();
            context.rect(750, 150, 150, 100);
            context.stroke();
            context.fill();

            //open arcs
            //
            context.beginPath();
            context.arc(150, 370, 60, 0, Math.PI*3/2);
            context.stroke();

            context.beginPath();
            context.arc(475, 370, 60, 0, Math.PI*3/2);
            context.fill();

            context.beginPath();
            context.arc(820, 370, 60, 0, Math.PI*3/2);
            context.fill();
            context.stroke();

            // close arcs
            // 
            context.beginPath();
            context.arc(150, 550, 60, 0, Math.PI*3/2);
            context.closePath();
            context.stroke();

            context.beginPath();
            context.arc(475, 550, 60, 0, Math.PI*3/2);
            context.closePath();
            context.fill();

            context.beginPath();
            context.arc(820, 550, 60, 0, Math.PI*3/2);
            context.closePath();
            context.fill();
            context.stroke();

            function drawTwoArcs(){
                context.beginPath();
                context.arc(cW/2, 680, 60, 0, Math.PI*2, false);
                context.arc(cW/2, 680, 40, 0, Math.PI*2, true);

                context.fill();
                context.shadowColor = undefined;
                context.shadowOffsetX = 0;
                context.shadowOffsetY = 0;
                context.stroke();
            }

            function draw(){
                context.save();
                context.fillStyle = "rgba(100, 140, 230, 0.5)";
                context.strokeStyle = context.fillStyle;
                context.shadowColor = 'rgba(0,0,0,0.8)';
                context.shadowOffsetX = 12;
                context.shadowOffsetY = 12;
                context.shadowBlur = 15;

                drawTwoArcs();
                context.restore();
            }


        </script>
    </body>
</html>