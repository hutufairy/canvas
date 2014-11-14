<!DOCTYPE html>
<html>
    <head>
        <title>2-6-axis</title>
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
        <canvas id="canvas" width="600" height="400">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var cW = canvas.width, cH = canvas.height;
            var axis_margin = 40,
                axis_color = '#516d81',
                axis_lineWidth = 1;
            var ticks_spacing = 10,
                ticks_width = 10,
                ticks_lineWidth = 0.5,
                ticks_color = 'navy';
            var oX = axis_margin, oY = cH - axis_margin,
                tX = cW - axis_margin, tY = axis_margin;

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

            function drawAxes(){
                context.save();
                context.strokeStyle = axis_color;
                context.lineWidth = axis_lineWidth;

                drawAxis();
                drawAxis(true);

                context.lineWidth = ticks_lineWidth;
                context.strokeStyle = ticks_color;

                drawAxisTicks();
                drawAxisTicks(true);
                context.restore();

            }

            function drawAxis(vertical){
                context.beginPath();
                context.moveTo(oX, oY);
                if(vertical){
                    context.lineTo(oX, tY);
                }else{
                    context.lineTo(tX, oY);
                }
                context.stroke();
            }

            function drawAxisTicks(vertical){
                var sum = ((vertical ? cH : cW) - 2*axis_margin) / ticks_spacing;
                for(var i = 1; i < sum; i++){
                    var delta = ticks_width;
                    if(i%5 !== 0) delta *= 0.5;
                    context.beginPath();
                    var n = i*ticks_spacing;
                    if(vertical){
                        context.moveTo( oX - 0.5 * delta, oY - n);
                        context.lineTo( oX + 0.5 * delta, oY - n)
                    }else{
                        context.moveTo( oX + n, oY - delta*0.5 );
                        context.lineTo( oX + n, oY + delta*0.5);
                    }
                    context.stroke();
                }
            }

            drawGrid(context, '#efefef', 10, 10);
            drawAxes();
        </script>
    </body>
</html>