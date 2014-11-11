<!DOCTYPE html>
<html>
    <head>
        <title>2-1-rect</title>
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
        <canvas id="canvas" width="600" height="400"></canvas>
        <script>
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var cW = canvas.width, cH = canvas.height;

            context.lineJoin = 'round';
            context.lineWidth = 30;
            context.font = "Arial 24px";
            context.fillText('Click anywhere to ease', 225, 200);

            context.strokeStyle = "goldenrod";
            context.strokeRect(75, 100, 200, 200);//矩形描边
            context.fillStyle = "rgba(0,0,255,0.5)";
            context.fillRect(325, 100, 200, 200);//矩形填充

            context.canvas.onmousedown = function(){
                context.clearRect(0, 0, cW, cH);
            }
        </script>
    </body>
</html>