<!DOCTYPE html>
<html>
    <head>
        <title>1-1-clock</title>
        <style>
            body{background: #ddd;}
            #snapshotImage{
                background: #fff;
                margin: 20px;
                padding: 20px;
                border: inset thin #aaa;
            }
            #canvas{display: none;}
        </style>
    </head>
    <body>
        <img src="" alt="clock" id="snapshotImage" />
        <canvas id="canvas" width="400" height="400">Canvas not support</canvas>
        <script>
            var canvas = document.getElementById('canvas'),
                snapshotImage = document.getElementById('snapshotImage');
            var context = canvas.getContext('2d');

            var cW = canvas.width, cH = canvas.height,
                margin = 30,
                font_height = 15,
                numeral_spacing = 20,//表圈与数字之间的间距
                radius = cW/2 - margin,//钟表半径
                hand_truncation = cW/25,
                hour_hand_truncation = cW/10,
                pi = Math.PI,
                hand_radius = radius + numeral_spacing;

            var time_id = null;
            context.font = font_height + 'px Arial';


            function drawCircle(){//画出表盘轮廓
                context.beginPath();
                context.arc(cW/2, cH/2,radius,0, pi*2, true);//圆心坐标x,y,半径,起始角度,终止角度,是否逆时针
                context.stroke();
            }

            function drawNumerals(){//画上表盘周围的时间数字
                var angle = 0, numeralWidth = 0;
                for(var i = 1; i < 13; i++){
                    angle = (i-3) * pi/6;
                    numeralWidth = context.measureText(i).width;
                    context.fillText(i, cW/2 + Math.cos(angle)*hand_radius - numeralWidth/2, cH/2 + Math.sin(angle) * hand_radius + font_height/3 );
                }
            }

            function drawCenter(){//画出表盘中心的圆点
                context.beginPath();
                context.arc(cW/2, cH/2, 5, 0, pi*2, true);
                context.fill();
            }

            function drawHand(loc, isHour, ratio){
                var angle = (loc * pi / 30) - pi/2,
                    handRadius = radius - ( hand_truncation + ( isHour ? hour_hand_truncation : 0 ) ) * ratio,//指针长度
                    oX = cW/2, oY = cH/2,
                    dX = oX + Math.cos(angle) * handRadius,
                    dY = oY + Math.sin(angle) * handRadius;
                context.moveTo(oX, oY);
                context.lineTo(dX, dY);
                context.stroke();
            }

            function drawHands(){
                var date = new Date(),
                    hour = date.getHours();
                hour = hour > 12 ? hour - 12 : hour;

                drawHand(hour * 5, true, 1);
                drawHand(date.getMinutes(), false, 1);
                drawHand(date.getSeconds(), false, 0.5);
            }

            function drawClock(){
                context.clearRect(0, 0, cW, cH);
                context.save();

                context.fillStyle = 'rgba(255, 255, 255, 0.8)';
                context.fillRect(0, 0, cW, cH);
                drawCircle();
                drawHands();

                context.restore();
                drawCenter(); //恢复原来的fillstyle 再作画
                drawNumerals();//恢复原来的fillstyle 再作画
                updateClockImage();
                time_id = setTimeout(drawClock, 1000);
            }

            function updateClockImage(){
                snapshotImage.src = canvas.toDataURL();
            }

            drawClock();

        </script>
    </body>
</html>