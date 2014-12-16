<!DOCTYPE html>
<html>
    <head>
        <title>2-7-rubber-bands</title>
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
        <div id="controls">
            choose sides:
            <select id="sidesSelect">
                <option value="3" selected>3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="8">8</option>
            </select>
            stroke color: 
            <select id="strokeStyleSelect">
                <option value="red">red</option>
                <option value="green">green</option>
                <option value="blue">blue</option>
                <option value="orange">orange</option>
                <option value="cornflowerblue" selected>cornflowerblue</option>
                <option value="goldenrod">goldenrod</option>
                <option value="navy">navy</option>
                <option value="purple">purple</option>
            </select>
            Guidewires:
            <input id="guidewireCheckbox" type="checkbox" checked />
            <input id="eraseAllButton" type="button" value="Erase all" />
        </div>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                eraseAllButton = document.getElementById('eraseAllButton'),
                strokeStyleSelect = document.getElementById('strokeStyleSelect'),
                guidewireCheckbox = document.getElementById('guidewireCheckbox'),
                sidesSelect = document.getElementById('sidesSelect'),
                drawingSurfaceImageData,
                mousedown = {},
                rubberbandRect = {},
                dragging = false,
                guidewires = guidewireCheckbox.checked,
                sides = sidesSelect.value;

            var cW = canvas.width, cH = canvas.height,
                angle_max = Math.PI * 2;
            var Point = function(x, y){
                this.x = x;
                this.y = y;
            }

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

            function windowToCanvas(x,y){
                var bbox = canvas.getBoundingClientRect();
                return {
                    x: x - bbox.left * (cW/bbox.width),
                    y: y - bbox.top * (cH/bbox.height),
                }
            }

            function getPolygonPoints(oX, oY, radius, sides, startAngle){
                var points = [],
                    angle = startAngle || 0;

                for(var i = 0; i < sides; i++){
                    points.push(new Point(oX + radius * Math.cos(angle),
                                          oY + radius * Math.sin(angle)));
                    angle += angle_max/sides;
                }
                return points;
            }

            function createPolygonPath(oX, oY, radius, sides, startAngle){
                var points = getPolygonPoints(oX, oY, radius, sides, startAngle);

                context.beginPath();
                context.moveTo(points[0].x, points[0].y);

                for(var i = 1; i < sides; i++){
                    context.lineTo(points[i].x, points[i].y);
                }
                context.closePath();
            }

            function saveDawingSurface(){
                drawingSurfaceImageData = context.getImageData(0, 0, cW, cH);
            }

            function restoreDrawingSurface(){
                context.putImageData(drawingSurfaceImageData, 0, 0);
            }

            function updateRubberbandRectangle(loc){//更新选中框
                rubberbandRect.width = Math.abs(loc.x - mousedown.x);
                rubberbandRect.height = Math.abs(loc.y - mousedown.y);
                rubberbandRect.left = Math.min(mousedown.x, loc.x);
                rubberbandRect.top = Math.min(mousedown.y, loc.y);
            }

            function drawRubberbandShape(loc){//从起点画线至终点
                context.beginPath();
                context.moveTo(mousedown.x, mousedown.y);
                context.lineTo(loc.x, loc.y);
                // context.rect(rubberbandRect.left, rubberbandRect.top, rubberbandRect.width, rubberbandRect.height);
                context.stroke();
                var radius = Math.sqrt(Math.pow(rubberbandRect.width, 2) + Math.pow(rubberbandRect.height, 2));
                var angle = Math.acos((loc.x - mousedown.x)/radius);
                if(mousedown.y > loc.y){
                    angle = angle_max - angle;
                }
                createPolygonPath(mousedown.x, mousedown.y, radius, sides, angle);
                context.stroke();
            }

            function updateRubberband(loc){
                updateRubberbandRectangle(loc);
                drawRubberbandShape(loc);
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

            function drawGuidewires(x, y){
                context.save();
                context.strokeStyle = 'rgba(0,0,230,0.4)';
                context.lineWidth = 0.5;
                drawVerticalLine(x);
                drawHorizontalLine(y);
                context.restore();
            }

            canvas.onmousedown = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                saveDawingSurface();
                mousedown.x = loc.x;
                mousedown.y = loc.y;
                dragging = true;
                return false;
            }

            canvas.onmousemove = function(e){
                if(dragging){
                    e.preventDefault();
                    var loc = windowToCanvas(e.clientX, e.clientY);
                    restoreDrawingSurface();
                    updateRubberband(loc);
                    if(guidewires){
                        drawGuidewires(loc.x, loc.y);
                    }
                }
            }

            canvas.onmouseup = function(e){
                if(dragging){
                    var loc = windowToCanvas(e.clientX, e.clientY);
                    restoreDrawingSurface();
                    updateRubberband(loc);
                    dragging = false;
                }
            }

            eraseAllButton.onclick = function(e){
                context.clearRect(0, 0, cW, cH);
                drawGrid(context, '#efefef', 10, 10);
                saveDawingSurface();
            }

            strokeStyleSelect.onchange = function(e){
                context.strokeStyle = strokeStyleSelect.value;
            }

            guidewireCheckbox.onchange = function(e){
                guidewires = guidewireCheckbox.checked;
            }
            sidesSelect.onchange = function(e){
                sides = sidesSelect.value;
            }
            context.strokeStyle = strokeStyleSelect.value;
            drawGrid(context, '#efefef', 10, 10);
        </script>
    </body>
</html>