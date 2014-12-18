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

            edit:
            <input id="editCheckbox" type="checkbox" />
            <input id="eraseAllButton" type="button" value="Erase all" />
        </div>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                eraseAllButton = document.getElementById('eraseAllButton'),
                strokeStyleSelect = document.getElementById('strokeStyleSelect'),
                guidewireCheckbox = document.getElementById('guidewireCheckbox'),
                editCheckbox = document.getElementById('editCheckbox'),

                guidewires = guidewireCheckbox.checked,
                sidesSelect = document.getElementById('sidesSelect'),
                strokeStyle,

                sides = sidesSelect.value,

                drawingSurfaceImageData,
                mousedown = {},
                rubberbandRect = {},

                dragging = false,
                draggingOffsetX,
                draggingOffsetY,

                editing = false,
                polygons = [];// 多边形对象集合

            var cW = canvas.width, cH = canvas.height,
                angle_max = Math.PI * 2;
            var Point = function(x, y){
                this.x = x;
                this.y = y;
            }

            var Polygon = function(ox, oy, radius, sides, startAngle, strokeStyle){
                this.x = ox;
                this.y = oy;
                this.radius = radius;
                this.sides = sides;
                this.startAngle = startAngle;
                this.strokeStyle = strokeStyle;
            }

            Polygon.prototype = {
                getPoints: function(){
                    var points = [],
                        angle = this.startAngle || 0;
                    for(var i = 0; i < this.sides; i++){
                        points.push(new Point(this.x + this.radius * Math.cos(angle),
                                              this.y + this.radius * Math.sin(angle)));
                        angle += angle_max/this.sides;
                    }
                    return points;
                },
                createPath: function(context){
                    var points = this.getPoints();
                    context.beginPath();
                    context.moveTo(points[0].x, points[0].y);

                    for(var i = 1; i < this.sides; i++){
                        context.lineTo(points[i].x, points[i].y);
                    }
                    context.closePath();
                },
                stroke: function (context) {
                    context.save();
                    this.createPath(context);
                    context.strokeStyle = this.strokeStyle;
                    context.stroke();
                    context.restore();
                },
                move: function(x, y){
                    this.x = x;
                    this.y = y;
                }
            }

            function drawPalygons(context){
                polygons.forEach(function(polygon){
                    polygon.stroke(context);
                })
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
                context.closePath();

                var radius = Math.sqrt(Math.pow(rubberbandRect.width, 2) + Math.pow(rubberbandRect.height, 2));
                var angle = Math.acos((loc.x - mousedown.x)/radius);
                if(mousedown.y > loc.y){
                    angle = angle_max - angle;
                }
                var polygon = new Polygon(mousedown.x, mousedown.y, radius, sides, angle, strokeStyle);
                polygon.stroke(context);
                if(!dragging){
                    polygons.push(polygon);
                }
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

            function startDragging(loc){
                saveDawingSurface();
                mousedown.x = loc.x;
                mousedown.y = loc.y;
            }
            function startEditing(){
                canvas.style.cursor = 'pointer';
                editing = true;
            }

            function stopEditing(){
                canvas.style.cursor = 'crosshair';
                editing = false;
            }

            function init(){
                context.clearRect(0, 0, cW, cH);
                drawGrid(context, '#efefef', 10, 10);
            }

            canvas.onmousedown = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                if(editing){
                    polygons.forEach( function (polygon) {
                        polygon.createPath(context);
                        if (context.isPointInPath(loc.x, loc.y)) {
                            startDragging(loc);
                            dragging = polygon;
                            draggingOffsetX = loc.x - polygon.x;
                            draggingOffsetY = loc.y - polygon.y;
                            return false;
                        }
                    });
                }else{
                    startDragging(loc);
                    dragging = true; 
                }
                return false;
            }

            canvas.onmousemove = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                e.preventDefault()
                if(dragging && editing){
                    dragging.x = loc.x - draggingOffsetX;
                    dragging.y = loc.y - draggingOffsetY;
                    init();
                    drawPalygons(context);
                }else{
                    if(dragging){
                        restoreDrawingSurface();
                        updateRubberband(loc);
                        if(guidewires){
                            drawGuidewires(loc.x, loc.y);
                        }    
                    }
                }
            }

            canvas.onmouseup = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                dragging = false;
                if(!editing){
                    restoreDrawingSurface();
                    updateRubberband(loc);
                }
            }

            eraseAllButton.onclick = function(e){
                init();
                polygons = [];
                saveDawingSurface();
            }

            strokeStyleSelect.onchange = function(e){
                strokeStyle = strokeStyleSelect.value;
            }

            guidewireCheckbox.onchange = function(e){
                guidewires = guidewireCheckbox.checked;
            }
            sidesSelect.onchange = function(e){
                sides = sidesSelect.value;
            }
            editCheckbox.onchange = function(e) {
                if(editCheckbox.checked){
                    startEditing();
                }else{
                    stopEditing();
                }
            }

            context.strokeStyle = strokeStyleSelect.value;
            init();
        </script>
    </body>
</html>