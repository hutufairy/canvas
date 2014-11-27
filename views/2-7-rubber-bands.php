<!DOCTYPE html>
<html>
    <head>
        <title>2-6-axis</title>
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
                drawingSurfaceImageData,
                mousedown = {},
                rubberbandRect = {},
                dragging = false,
                guidewires = guidewireCheckbox.checked;

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

            function windowToCanvas(x,y){
                var bbox = canvas.getBoundingClientRect();
                return {
                    x: x - bbox.left * (cW/bbox.width),
                    y: y - bbox.top * (cH/bbox.height),
                }
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
                context.rect(rubberbandRect.left, rubberbandRect.top, rubberbandRect.width, rubberbandRect.height);
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
            context.strokeStyle = strokeStyleSelect.value;
            drawGrid(context, '#efefef', 10, 10);
        </script>
    </body>
</html>