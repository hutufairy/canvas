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
                position: relative;
            }
            .dropmenu{
                border-radius: 4px;
                border: 1px solid rgba(0,0,0,0.2);
                background: #fff;
                padding: 4px 0;
                min-width: 160px;
                position: absolute;
                left: 0;
                top: 100%;
                opacity: 0;
                margin-top: -5px;
                transition: opacity 0.3s ease-in-out, margin-top 0.3s ease-in-out;
            }
            #radio:hover .dropmenu{opacity: 1; margin-top: 0;}
            .dropmenu .before{
                border: 7px solid transparent;
                border-top: 0;
                border-bottom-color: rgba(0,0,0,0.2);
                position: absolute;
                top: -7px;
                left: 9px;
            }
            .dropmenu .after{
                border: 6px solid transparent;
                border-top-width: 0;
                border-bottom-color: #fff;
                top: -6px;
                left: 10px;
                position: absolute;
            }
            .dropmenu .body{
                min-height: 60px;
            }
        </style>
    </head>
    <body>
        <div id="radio">
            <label><input id="repeatRadio" type="radio" name="patternRadio" checked value="repeat">repeat</label>
            <label><input id="repeatXRadio" type="radio" name="patternRadio" value="repeat-x">repeat-x</label>
            <label><input id="repeatYRadio" type="radio" name="patternRadio" value="repeat-y">repeat-y</label>
            <label><input id="noRepeatRadio" type="radio" name="patternRadio" value="no-repeat">no-repeat</label>
            <div class="dropmenu">
                <div class="before"></div>
                <div class="body">图片重复模式</div>
                <div class="after"></div>
            </div>
        </div>
        <canvas id="canvas" width="450" height="450">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                radios = document.getElementsByTagName('input'),
                image = new Image();
            var cW = canvas.width, cH = canvas.height;

            function fillCanvasWithPatter(type){
                var pattern = context.createPattern(image, type);

                context.clearRect(0,0,cW,cH);
                context.fillStyle=pattern;
                context.fillRect(0,0,cW,cH);
            }
            for(var i in radios){
                radios[i].onclick = function(e){
                    var v = this.value;
                    fillCanvasWithPatter(v);
                }
            }

            image.src="../imgs/2-3-redball.png";
            image.onload=function(e){
                fillCanvasWithPatter('repeat')
                drawEraser();
            }

            function drawEraser(){
                context.save();

                context.lineWidth = 1;
                context.shadowColor = 'blue';
                context.shadowOffsetX = -5;
                context.shadowOffsetY = -5;
                context.shadowBlur = 20;
                context.strokeStyle = 'rgba(0,0,255,0.8)';

                context.beginPath();
                context.arc(cW/2, cH/2, 60, 0, Math.PI*2, false);
                context.clip();
                context.stroke();

                context.restore();
            }
        </script>
    </body>
</html>