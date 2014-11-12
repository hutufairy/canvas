<!DOCTYPE html>
<html>
    <head>
        <title>2-2-gradient</title>
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
        <canvas id="canvas" width="600" height="800">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                cW = canvas.width, cH = canvas.height;
                
            function setLinearGradient(context, x, y, w, h){
                var gradient = context.createLinearGradient(x, y, w, h);
                
                gradient.addColorStop(0, 'blue');
                gradient.addColorStop(0.25, 'white');
                gradient.addColorStop(0.5, 'purple');
                gradient.addColorStop(0.75, 'red');
                gradient.addColorStop(1, 'yellow');
                return gradient;
            }
            
            context.fillStyle = setLinearGradient(context, 50, 50, 275, 50);
            context.fillRect(50, 50, 225, 125);

            context.fillStyle = setLinearGradient(context, 325, 50, 325, 175);
            context.fillRect(325, 50, 225, 125);
            
            context.fillStyle = setLinearGradient(context, 50, 225, 50, 300);
            context.fillRect(50, 225, 225, 125);

            context.fillStyle = setLinearGradient(context, 325, 225, 550, 350);
            context.fillRect(325, 225, 225, 125);
            var gradient = context.createRadialGradient(cW/2, cH, 10, cW/2, 400, 100);
            gradient.addColorStop(0, 'blue');
            gradient.addColorStop(0.25, 'white');
            gradient.addColorStop(0.5, 'purple');
            gradient.addColorStop(0.75, 'red');
            gradient.addColorStop(1, 'yellow');
            context.fillStyle = gradient;
            context.rect(0, 400, cW, 400);//fillRect : 等价于rect(), fill()
            context.fill();

        </script>
    </body>
</html>