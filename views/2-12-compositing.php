<!DOCTYPE html>
<html>
    <head>
        <title>2-12-compositing</title>
        <style>
            body{background: #ddd;}
            #canvas{
                background: #fff;
                cursor: pointer;
                margin: 10px 0 0 10px;
                box-shadow: 4px 4px 8px rgba(0,0,0,0.5);
            }
            /*#controls{
                position: absolute;
                left: 25px;
                top: 25px;
            }*/

        </style>
    </head>
    <body>
        <select id="compositingSelect">
            <option value="source-atop">source-atop</option>
            <option value="source-in">source-in</option>
            <option value="source-out">source-out</option>
            <option value="source-over">source-over</option>
            <option value="destination-atop">destination-atop</option>
            <option value="destination-in">destination-in</option>
            <option value="destination-out">destination-out</option>
            <option value="destination-over">destination-over</option>
            <option value="lighter">lighter</option>
            <option value="copy">copy</option>
            <option value="xor">xor</option>
        </select>
        <canvas id="canvas" width="600" height="600">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                selectElement = document.getElementById('compositingSelect'),
                angle_max = Math.PI * 2,
                cW = canvas.width,
                cH = canvas.height;

            function drawText(){
                context.save();
                context.fillStyle = 'cornflowerblue';
                context.fillText('HTML5', 20, 250);

                context.strokeStyle = 'yellow';
                context.stroke();

                context.restore();
            }

            function windowToCanvas(x,y){
                var bbox = canvas.getBoundingClientRect();
                return {
                    x: x - bbox.left * (cW/bbox.width),
                    y: y - bbox.top * (cH/bbox.height),
                }
            }

            canvas.onmousemove = function(e) {
                var loc = windowToCanvas(e.clientX, e.clientY);
                context.clearRect(0, 0, cW, cH);

                drawText();

                context.save();
                context.globalCompositeOperation = selectElement.value;
                context.beginPath();
                context.arc(loc.x, loc.y, 100, 0, angle_max, false);
                context.fillStyle = 'orange';
                context.stroke();
                context.fill();
                context.restore();
            }

            selectElement.selectedIndex = 3;
            context.lineWidth = 0.5;
            context.font = '128pt Comic-sans';
            drawText();

        </script>
    </body>
</html>