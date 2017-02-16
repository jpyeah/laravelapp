<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>川基车载</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <?php /* <link href="<?php echo e(elixir('css/app.css')); ?>" rel="stylesheet"> */ ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
         wx.config(<?php $wechat = app('wechat'); $js = $wechat->js;echo $js->config(array('onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ', 'onMenuShareWeibo'), false) ?>);
         wx.ready(function(){
         
          wx.onMenuShareAppMessage({

			    title: '我推荐', // 分享标题

			    desc: '川基', // 分享描述
                
			    link: 'http://test.bibicars.com/home/share/<?php echo e($Id); ?>', // 分享链接

			    imgUrl: 'http://test.bibicars.com/1.pic.png', // 分享图标

			    type: 'link', // 分享类型,music、video或link，不填默认为link

			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空

			    success: function () { 
                    
			        // 用户确认分享后执行的回调函数

			    },
			    cancel: function () { 
                   
			        // 用户取消分享后执行的回调函数

			    }
         });


       });

    </script>
    <style>
           .bg{background:url() no-repeat center fixed;background-size:contain}
           .col-center-block {  
                float: none;  
                display: block; 
                margin-left: auto;  
                margin-right: auto;  
            }
            .circle{
                margin-top:40px;
            }
            body{
               background-color:#ff6600;
            }
    </style>
</head>
<body id="app-layout">
   <div class="container"  >
        <div class="row circle text-center">
            <figure > 
              <img class="img-circle " style="width: 55px;height: 55px;"src="<?php echo e($signature); ?>" alt="头像"/> 
             </figure> 
            <h4  class="text-center" style="color:#000;"><?php echo e($name); ?>推荐二维码</h4>
        </div>    
        <div class="row col-center-block">
        <img src="<?php echo e($url); ?>" class="img-responsive center-block" alt="">
        </div>

    </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <?php /* <script src="<?php echo e(elixir('js/app.js')); ?>"></script> */ ?>
</body>
</html>