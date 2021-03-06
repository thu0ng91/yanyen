<?php
$backend = dirname(dirname(__FILE__));
$frontend = dirname($backend);
Yii::setPathOfAlias('backend',$backend);

$frontendArray = require_once($frontend.'/config/main.php');

$backendArray=array(
	'name'=>'网站后台管理系统',
	'basePath'=>$frontend,
    'viewPath'=>$frontend.'/../admin/views',
	'controllerPath'=>$frontend.'/../admin/controllers',
    'runtimePath'=>$frontend.'/../admin/runtime',
	'import'=>array(	
		'application.models.*',
		'application.components.*',
	    'backend.models.*',
		'backend.components.*',
	),
    'components' => array(
        'user' => array(
            'class' => 'AdminWebUser',
            'allowAutoLogin' => true,
            'stateKeyPrefix' => 'admin', //session名前缀
            'loginUrl' => array('site/login'),
        ),
    ),
	//'params'=>CMap::mergeArray(require($frontend.'/config/params.php'),require($backend.'/config/params.php')),
);
if(isset($frontendArray['components']['user'])) unset($frontendArray['components']['user']);
return CMap::mergeArray($frontendArray,$backendArray); 