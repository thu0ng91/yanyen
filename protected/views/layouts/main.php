<!DOCTYPE html>
<html>
  <head>
    <!--[if gte IE 9]><html class="ie9"><![endif]-->
    <!--[if IE 8]><html class="ie8"><![endif]-->
    <!--[if IE 7]><html class="ie7"><![endif]-->
    <!--[if lt IE 7]><html class="ie6"><![endif]-->
    <meta charset="UTF-8">
    <meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible">
    <meta name="baidu-site-verification" content="gWdJDRbShF" />
    <meta name="keywords" content="云阅小说系统,小说系统,php小说,免费小说系统">
    <meta name="description" content="云阅网倾力打造，我们是小说系统专家，具有多年开发经验，按照站长实际需求快速更新迭代">

    <link href="/favicon.ico" rel="shortcut icon" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <!--[if lt IE 7]><script>try{ document.execCommand("BackgroundImageCache", false, true); } catch(e){};</script><![endif]-->
        <link href="<?php echo Yii::app()->baseUrl;?>/css/yunyue/reset.css" rel="stylesheet" />
        <link href="<?php echo Yii::app()->baseUrl;?>/css/yunyue/style.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/yacms/css/index.css" />
  	<script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?16f765ff9cec649f99eb18c2f4a8dfaa";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
  	
  </head>
  <body>
    <div class="container">
      <div id="header" class="clearfix">
        <div id="top" class="wp clearfix">
          <h1 id="logo"><a href="/"><?php echo Yii::app()->params['sitename']?></a></h1>
          <?php
		$nav = Headnav::model()->findAllByAttributes(array('status'=>1),array('order'=>'sequence ASC','limit'=>6));


		foreach($nav as $value){
			$menu[$value->id] = $value;
		}
	?>
          <ul id="nav">
				<li class="<?php if(empty($_GET['r'])) echo 'current';?>"><a href="/index.php">首页</a></li>
				<?php
				 foreach ($menu as $key=>$value){
						$url = Yii::app()->params['site_url'].$value->url;
					?>
					<li <?php if($this->action->id != 'index' && false != strstr($url,$this->action->id)) echo 'class="current"';?>><a href="<?php echo $url;?>" <?php if($value->target == 2):?>target="_blank"<?php endif;?>><?php echo $value->name;?></a></li>
				<?php	} ?>
          </ul>
        </div>
      <!-- /header -->

	<?php echo $content; ?>
	<?php $friendLinks = Friendlink::model()->findAllByAttributes(array('status'=>1),array('order'=>'sequence desc'));?>
<div id="footer" class="clearfix">
        <div id="links">
          <div class="wp">
            <h3>合作伙伴</h3>
            <div class="linklist">
            <ul class="clearfix">
<li>
<a href="/"><span class="img"><img border="0" alt="云阅小说" src="/images/logo2.png" width="155" height="50"></span><span class="label">云阅小说</span></a>
</li>
</ul>
            </div>
          </div>
          <div class="ripple"></div>
        </div>
	
        <div id="stuff">
          <div class="wp"><span id="copyright">ICP备案号：沪ICP备14032879号-1</span></div>
        </div>
      </div>
      <!-- /footer -->
    </div>
    <!--[if lt IE 7]><script src="<?php echo Yii::app()->baseUrl;?>/css/yunyue/iepngfix_tilebg.js"></script><![endif]-->
    <script src="<?php echo Yii::app()->baseUrl;?>/css/yunyue/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl;?>/css/yunyue/plugins.js"></script>
    <script src="<?php echo Yii::app()->baseUrl;?>/css/yunyue/script.js"></script>
  </body>
</html>
