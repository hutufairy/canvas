<!DOCTYPE html>
<html>
    <head>
        <title>2-8-arcto</title>
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
        <canvas id="canvas" width="600" height="400">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d');
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

            function roundedRect(cornerX, cornerY, width, height, cornerRadius){
                if(width>0) context.moveTo(cornerX+cornerRadius, cornerY);
                else context.moveTo(cornerX-cornerRadius, cornerY);

                context.arcTo(cornerX + width, cornerY, cornerX+width, cornerY+height, cornerRadius);
                context.arcTo(cornerX + width, cornerY+height, cornerX, cornerY+height, cornerRadius);
                context.arcTo(cornerX, cornerY+height, cornerX, cornerY, cornerRadius);
                if(width>0) context.arcTo(cornerX, cornerY, cornerX+cornerRadius, cornerY, cornerRadius);
                else context.arcTo(cornerX, cornerY, cornerX-cornerRadius, cornerY, cornerRadius);
            }

            function drawRoundedRect(strokeStyle, fillStyle, cornerX, cornerY, width, height, cornerRadius){
                context.beginPath();
                roundedRect(cornerX, cornerY, width, height, cornerRadius);
                context.strokeStyle = strokeStyle;
                context.fillStyle = fillStyle;
                context.stroke();
                context.fill();
            }
            drawGrid(context, '#efefef', 10, 10);
            drawRoundedRect('blue', 'yellow', 50, 40, 100, 100, 10);
            drawRoundedRect('purple', 'green', 275, 40, -100, 100, 20);
        </script>
    </body>
</html>