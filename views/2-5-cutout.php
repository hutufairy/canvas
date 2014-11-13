<!DOCTYPE html>
<html>
    <head>
        <title>2-5-cutout</title>
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

            function rect(x,y,w,h,dir){
                context.moveTo(x,y);
                if(dir){//counterclockwise
                    context.lineTo(x, y+h);
                    context.lineTo(x+w, y+h);
                    context.lineTo(x+w, y);
                }else{//clockwise
                    context.lineTo(x+w, y);
                    context.lineTo(x+w, y+h);
                    context.lineTo(x, y+h);
                }
                context.closePath();
            }

            function addTrianglePath(){
                context.moveTo(400, 200);
                context.lineTo(250, 115);
                context.lineTo(200, 200);
                context.closePath();
            }

            function addCirclePath(){
                context.arc(300, 300, 40, 0, Math.PI*2, true);
            }

            function addOuterRectanglePath(){
                context.rect(110,25, 370, 335);
            }
            function addRectanglePath(){
                rect(310, 55, 70, 35, true);
            }

            function strokeCutoutShapes(){
                context.save();
                context.strokeStyle = 'rgba(0,0,0,0.7)';
                context.beginPath();
                addOuterRectanglePath();
                context.stroke();

                context.beginPath();
                addCirclePath();
                addRectanglePath();
                addTrianglePath();

                context.stroke();
                context.restore();
            }

            function drawCutouts(){
                context.beginPath();
                addOuterRectanglePath();
                addRectanglePath();
                addTrianglePath();
                addCirclePath();
                context.fill();
            }

            function draw(){
                context.clearRect(0,0,cW,cH);
                drawGrid(context, '#efefef', 10, 10);
                context.save();
                context.shadowColor = 'rgba(200,200,200,0.5)';
                context.shadowOffsetX = 12;
                context.shadowOffsetY = 12;
                context.shadowBlur = 15;
                drawCutouts();
                strokeCutoutShapes();
                context.restore();
            }

            context.fillStyle = "goldenrod";
            draw();
        </script>
    </body>
</html>