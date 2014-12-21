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
        </style>
    </head>
    <body>
        <canvas id="canvas" width="600" height="600">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d');
            var cW = canvas.width, cH = canvas.height,
                angle_max = Math.PI * 2;
            function drawText(){
                context.save();
                context.fillStyle = 'cornflowerblue';
                context.fillText('HTML5', 20, 250);

                context.strokeStyle = 'yellow';
                context.strokeText('HTML5', 20, 250);

                context.restore();
            }

            function setClippingRegion(radius){
                context.beginPath();
                context.arc(cW/2, cH/2, radius, 0, angle_max, false);
                context.clip();
            }

            function fillCanvas(color){
                context.fillStyle = color;
                context.fillRect(0,0,cW, cH);
            }

            function endAnimation(loop) {
                clearInterval(loop);
                setTimeout(function(e){
                    context.clearRect(0,0,cW,cH);
                    drawText();
                }, 1000);
            }
            function drawAnimationFrame(radius){
                setClippingRegion(radius);
                fillCanvas('lightgray');
                drawText();
            }

            function animate(){
                var radius = cW/2, loop;
                loop = setInterval(function(){
                    radius -= cW/100;
                    fillCanvas('charcoal');
                    if(radius > 0){
                        context.save();
                        drawAnimationFrame(radius);
                        context.restore();
                    }else{
                        endAnimation(loop);
                    }
                }, 16);

            }

            canvas.onmousedown = function(e){
                animate();
            }

            context.lineWidth = 0.5;
            context.font = "128pt Comic-sans";
            drawText();
        </script>
    </body>
</html>