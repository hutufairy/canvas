<!DOCTYPE html>
<html>
    <head>
        <title>2-11-bezier</title>
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

            .floatingControls{
                position: absolute;
                left: 150px;
                top: 100px;
                width: 300px;
                padding: 20px;
                border: 1px solid rgba(0,0,0,0.3);
                background: rgba(0,0,0,0.1);
                color: blue;
                font: 14px Arial;
                box-shadow: rgba(0,0,0,0.2) 6px 6px 8px;
                display: none;
            }

            .floatingControls p{
                margin: 0 0 20px;
            }

        </style>
    </head>
    <body>
        <canvas id="canvas" width="600" height="600">Canvas not support</canvas>
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
        <div class="floatingControls" id="instructions">
            <p>Drag the curve end- and control points to change the shape of the curve.</p>
            <p>When you are done dragging end- and control points, click outside of the points to finalize the curve.</p>

            <input id="instructionsOkayButton" type="button" value="好哒" autofocus />
            <input id="instructionsNoMoreButton" type="button" value="不再提示"></input>
        </div>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),

                cW = canvas.width,
                cH = canvas.height,

                eraseAllButton = document.getElementById('eraseAllButton'),
                strokeStyleSelect = document.getElementById('strokeStyleSelect'),
                guidewireCheckbox = document.getElementById('guidewireCheckbox'),
                instructions = document.getElementById('instructions'),
                instructionsOkayButton = document.getElementById('instructionsOkayButton'),
                instructionsNoMoreButton = document.getElementById('instructionsNoMoreButton'),

                guidewires = guidewireCheckbox.checked,

                showInstructions = true,

                axis_margin = 40,
                horizontal_tick_spacing = 10,
                vertical_tick_spacing = 10,
                tick_size = 10,

                axis_origin = {x: axis_margin, y: cH - axis_margin},
                axis_top = axis_margin,
                axis_right = cW - axis_margin,
                axis_width = axis_right - axis_origin.x,
                axis_height = axis_origin.y - axis_top,

                num_vertical_ticks = axis_height / vertical_tick_spacing,
                num_horizontal_ticks = axis_width / horizontal_tick_spacing,

                grid_stroke_style = '#efefef',
                grid_spacing = 10,

                control_point_radius = 5,
                control_point_stroke_style = 'blue',
                control_point_fill_style = 'rgba(255, 255, 0, 0.5)',

                end_point_stroke_style = 'navy',
                end_point_fill_style = 'rgba(0, 255, 0, 0.5)',

                guidewire_stroke_style = 'rgba(0, 0, 230, 0.4)',

                drawingSurfaceImageData,

                mousedown = {},
                rubberbandRect = {},

                dragging = false,
                draggingPoint = false,

                endPoints = [{}, {}],
                controlPoints = [{}, {}],

                editing = false;


            var PI = Math.PI, angle_max = 2 * PI;

            // draw grid
            function drawGrid(context, color, stepx, stepy){
                context.save();
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
                context.restore();
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

            function drawBezierCurve(){
                context.beginPath();
                context.moveTo(endPoints[0].x, endPoints[0].y);
                context.bezierCurveTo(controlPoints[0].x, controlPoints[0].y, 
                                      controlPoints[1].x, controlPoints[1].y,
                                      endPoints[1].x, endPoints[1].y);
                context.stroke();
            }

            function updateEndAndControlPoints(){
                endPoints[0].x = rubberbandRect.left;
                endPoints[0].y = rubberbandRect.top;
                endPoints[1].x = rubberbandRect.left + rubberbandRect.width;
                endPoints[1].y = rubberbandRect.top + rubberbandRect.height;

                controlPoints[0].x = rubberbandRect.left;
                controlPoints[0].y = rubberbandRect.top + rubberbandRect.height;
                controlPoints[1].x = rubberbandRect.left + rubberbandRect.width;
                controlPoints[1].y = rubberbandRect.top;
            }

            function drawRubberbandShape(loc){
                updateEndAndControlPoints(loc);
                drawBezierCurve();
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
                context.strokeStyle = guidewire_stroke_style;
                context.lineWidth = 0.5;
                drawVerticalLine(x);
                drawHorizontalLine(y);
                context.restore();
            }

            function drawPoint(point, radius){
                context.beginPath();
                context.arc(point.x, point.y, radius, 0, angle_max, false);
                context.stroke();
                context.fill();
            }

            function drawPoints(points, radius, strokeStyle, fillStyle){
                context.save();
                context.strokeStyle = strokeStyle;
                context.fillStyle = fillStyle;

                points.forEach(function(point){
                    drawPoint(point, radius);    
                });
                context.restore();
            }

            function drawControlAndEndPoints(){
                drawPoints(controlPoints, control_point_radius, control_point_stroke_style, control_point_fill_style);
                drawPoints(endPoints, control_point_radius, end_point_stroke_style, end_point_fill_style);

            }

            function cursorInPoint(points, loc){
                var pt = false;
                points.forEach(function(point){
                    context.beginPath();
                    context.arc(point.x, point.y, control_point_radius, 0, angle_max, false);
                    context.closePath();

                    if(context.isPointInPath(loc.x, loc.y)){
                        pt = point;
                    }
                });

                return pt;
            }

            function cursorInEndPoint(loc){
                return cursorInPoint(endPoints, loc);
            }

            function cursorInControlPoint(loc){
                return cursorInPoint(controlPoints, loc);
            }

            function updateDraggingPoint(loc){
                draggingPoint.x = loc.x;
                draggingPoint.y = loc.y;
            }

            canvas.onmousedown = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                if(!editing){
                    saveDawingSurface();
                    mousedown.x = loc.x;
                    mousedown.y = loc.y;
                    updateRubberbandRectangle(loc);
                    dragging = true;
                }else{
                    draggingPoint = cursorInControlPoint(loc);
                    if(!draggingPoint){
                        draggingPoint = cursorInEndPoint(loc);
                    }
                }
                return false;
            }

            canvas.onmousemove = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                if(dragging || draggingPoint){
                    e.preventDefault()
                    restoreDrawingSurface();
                    if(guidewires){
                        drawGuidewires(loc.x, loc.y);
                    }
                    if(dragging){
                        updateRubberband(loc);
                        drawControlAndEndPoints();
                    }else if(draggingPoint){
                        updateDraggingPoint(loc);
                        drawControlAndEndPoints();
                        drawBezierCurve();
                    }
                }
            }

            canvas.onmouseup = function(e){
                var loc = windowToCanvas(e.clientX, e.clientY);
                restoreDrawingSurface();
                if(!editing){
                    updateRubberband(loc);
                    drawControlAndEndPoints();
                    dragging = false;
                    editing = true;
                    if(showInstructions){
                        instructions.style.display = 'inline';
                    }
                }else{
                    if(draggingPoint) drawControlAndEndPoints();
                    else editing = false;

                    drawBezierCurve();
                    draggingPoint = undefined;
                }
            }

            eraseAllButton.onclick = function(e){
                init();
                saveDawingSurface();
                dragging = false;
                editing = false;
                draggingPoint = undefined;
            }

            strokeStyleSelect.onchange = function(e){
                context.strokeStyle = strokeStyleSelect.value;
            }

            guidewireCheckbox.onchange = function(e){
                guidewires = guidewireCheckbox.checked;
            }

            instructionsOkayButton.onclick = function(e) {
                instructions.style.display = 'none';
            }

            instructionsNoMoreButton.onclick = function(e) {
                instructions.style.display = 'none';
                showInstructions = false;
            }

            function init(){
                context.strokeStyle = strokeStyleSelect.value;
                context.clearRect(0, 0, cW, cH);
                drawGrid(context, grid_stroke_style, grid_spacing, grid_spacing);
            }
    
            init();
            </script>
    </body>
</html>