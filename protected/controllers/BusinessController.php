<?php
class BusinessController extends CController{

	public function beforeAction(){
		if(!Yii::app()->user->id){
			CV::showmsg('请先登录',Yii::app()->createUrl('site/login'));
			Yii::app()->end();
		}
		return true;
	}
	public function actionCenter(){
		$your = Authorizer::model()->findAllByAttributes(array('uid'=>Yii::app()->user->id));
		
		$this->render('index',array('your'=>$your));
	}
	public function actionAdd(){
		$version = Version::model()->findAll();
		if(Yii::app()->request->isPostRequest){
			$url = Yii::app()->request->getParam('url',null);
			if(null == $url){
				CV::showmsg('域名不能为空',Yii::app()->createUrl('business/add'));
			}
			if (!preg_match('/^([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',$url)) {
			    CV::showmsg('域名格式错误',Yii::app()->createUrl('business/add'));
			}
			$result = Authorizer::model()->findByAttributes(array('url'=>$url));
			if(null != $result){
				CV::showmsg('该域名已经授权',Yii::app()->createUrl('business/add'));
			}
			
			$model = new Authorizer();
			$model->username = Yii::app()->user->name;
			$model->uid = Yii::app()->user->id;
			$model->url = $url;
			$model->version = addslashes($_POST['version']);
			$versionsys = Version::model()->findByAttributes(array('version_number'=>$model->version));
			$nums = Authorizer::model()->countByAttributes(array('uid'=>Yii::app()->user->id,'type'=>2));
			$userinfo = User2::model()->findByAttributes(array('username'=>Yii::app()->user->name),array('select'=>'groupid'));
			$usergroup = Group::model()->findByPk($userinfo->groupid);

			if($versionsys->price != 0 && $nums >= $usergroup->version_nums){
				CV::showmsg('域名授权已经达到上限，请联系管理员',Yii::app()->createUrl('business/add'));
			}
			if($versionsys->price == 0){
				$model->type = 1;
			}else{
				$model->type = 2;
			}

			$model->dateline = time();
			$model->sqm = $this->create_sqm($url,$model->version);
			if($model->save()){
				CV::showmsg('增加新域名授权成功',Yii::app()->createUrl('business/center'));
			}
			CV::showmsg('域名授权未成功，请联系官方',Yii::app()->createUrl('business/center'));
		}
		$this->render('add',array('version'=>$version));
	}
	public function actionDownload(){
		echo 'download';
	}
	public function actionComment(){
		echo 'comment';
	}
	public function create_sqm($domain,$version) {
		$sep = "|";
		
		$pubKey = $domain . $sep . $version;
		$privKey = 'yun yue novel systme';
		$des = new STD3Des($pubKey, $privKey);
		
		$v = array(
		'domain' => $domain,
		'version' => $version,
		'username' => Yii::app()->user->name,
		);
		
		$v = serialize($v);
		$hash = $des->encrypt($v);

	    return $hash;
	}
}