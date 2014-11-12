<!DOCTYPE html>
<html>
    <head>
        <title>2-3-pattern</title>
        <style>
            body{background: #ddd;}
            #canvas{
                background: #eee;
                border: inset thin cornflowblue;
            }
            #radio{
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div id="radio">
            <label><input id="repeatRadio" type="radio" name="patternRadio" checked>repeat</label>
            <label><input id="repeatXRadio" type="radio" name="patternRadio">repeat-x</label>
            <label><input id="repeatYRadio" type="radio" name="patternRadio">repeat-y</label>
            <label><input id="noRepeatRadio" type="radio" name="patternRadio">no-repeat</label>
        </div>
        <canvas id="canvas" width="450" height="275">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d');
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