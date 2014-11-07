<!DOCTYPE html>
<html>
    <head>
        <title>box flex</title>
        <style>
            html,body{background: #eee; height: 100%; margin: 0; padding: 0;}
            body{
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: box;
                box-orient: vertical;
                -webkit-box-orient: vertical;
                width: 100%;
                background-color: #fff;
            }
            .box{width: 100%;}
            #box1{
                background-color: #ff8989;
                height: 30%;
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: box;
                box-orient: horizontal;
                -webkit-box-orient: horizontal;
            }

            #box2{
                background-color: #1bbc9b;
                box-flex: 1;
                -moz-box-flex: 1;
                -webkit-box-flex: 1;
            }

            #box3{
                background-color: #516d81;
                box-flex: 1;
                -moz-box-flex: 1;
                -webkit-box-flex: 1;
            }

            #box4{
                background-color: #ff8989;
                box-flex: 1;
                -moz-box-flex: 1;
                -webkit-box-flex: 1;
            }
        </style>
    </head>
    <body>
        <div class="box" id="box1">
            <div id="box3"></div>
            <div id="box4"></div>
        </div>
        <div class="box" id="box2"></div>
    </body>
</html>