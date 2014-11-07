<!DOCTYPE html>
<html>
    <head>
        <title>1-2-sprite</title>
        <style>
            body{background: #ddd;}
            #canvas{
                background: #fff;
                margin: 20px;
                border: inset thin rgba(100,150,230,0.5);
                cursor: pointer;
                position: absolute;
                left: 0;
                top: 20px;
            }
            #readout{
                margin-top: 10px;
                margin-left: 15px;
                color: blue;
            }
        </style>
    </head>
    <body>
        <div id="readout"></div>
        <canvas id="canvas" width="400" height="400"></canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                readout = document.getElementById('readout'),
                context = canvas.getContext('2d'),
                spriteSheet = new Image();

            var cW = canvas.width, cH = canvas.height;

            var bbox = canvas.getBoundingClientRect();

            function windowToCanvas(canvas, x, y){
                return {
                    x: (cW / bbox.width) * (x - bbox.left),
                    y: (cH / bbox.height) * (y - bbox.top)
                }
            }
            
            function drawBackground(){
                var vertical_line_spacing = 12, i = cH;
                context.clearRect(0, 0, cW, cH);
                context.strokeStyle = 'lightgray';
                context.lineWidth = 0.5;

                while(i > vertical_line_spacing * 4){
                    context.beginPath();
                    context.moveTo(0, i);
                    context.lineTo(cW, i);
                    context.stroke();
                    i -= vertical_line_spacing;
                }
            }

            function drawSpritSheet(){
                context.drawImage(spriteSheet, 0, 0);
            }

            function drawGuideLines(x, y){
                context.strokeStyle = "rgba(0,0,230,0.8)";
                context.lineWidth = 0.5;
                drawVerticalLine(x);
                drawHorizontalLine(y);
            }

            function drawVerticalLine(x){
                context.beginPath();
                context.moveTo(x+0.5, 0);
                context.lineTo(x+0.5, cH);
                context.stroke();
            }

            function drawHorizontalLine(y){
                context.beginPath();
                context.moveTo(0, y+0.5);
                context.lineTo(cW, y+0.5);
                context.stroke();
            }

            function updateReadout(x,y){
                readout.innerText = '('+x.toFixed(0) + ', ' + y.toFixed(0) + ')';
            }

            canvas.onmousemove = function(e){
                var loc = windowToCanvas(canvas, e.clientX, e.clientY);
                drawBackground();
                drawSpritSheet();
                drawGuideLines(loc.x, loc.y);
                updateReadout(loc.x, loc.y);
            }
            spriteSheet.src = "../imgs/1-2-sprite.png";

            spriteSheet.onload = function(e){
                drawSpritSheet();
            }
            drawBackground();
        </script>
    </body>
</html>