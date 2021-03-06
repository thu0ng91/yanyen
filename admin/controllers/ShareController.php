<?php

class ShareController extends Controller
{
	public function beforeAction(){
		if(!Yii::app()->user->id){
			CV::showmsg('请先登录', Yii::app()->createUrl('site/login'));
		}
		return true;
	}
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $menu = array(
        array('label'=>'用户分享列表', 'url'=>array('index')),      
	    array('label'=>'上传TXT小说', 'url'=>array('update')),
        array('label'=>'小说分类', 'url'=>array('novelSortIndex')),      
	    array('label'=>'添加分类', 'url'=>array('novelSortUpdate')),
    );
	public function actionNovelSortIndex(){
		$model=new NovelSort('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NovelSort']))
			$model->attributes=$_GET['NovelSort'];

		$this->render('novelSortIndex',array(
			'model'=>$model,
		));
	}
	public function actionNovelSortUpdate(){
		$id = intval(Yii::app()->request->getParam('id',null));
		$model = NovelSort::model()->findByPk($id);
		if($model === null) {
			$model = new NovelSort();
		}
		if(isset($_POST['NovelSort'])){
			$model->attributes=$_POST['NovelSort'];
			if($model->save())
				$this->redirect(array('novelSortView','id'=>$model->id));
		}
		$this->render('novelsortupdate',array(
			'model'=>$model,
		));
	}
	public function actionNovelSortView(){
		$id = intval(Yii::app()->request->getParam('id',null));
		$model = NovelSort::model()->findByPk($id);
		$this->render('novelsortview',array(
			'model'=>$model,
		));
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$id = intval(Yii::app()->request->getParam('id',null));
		if(null == $id){
			$model= new Share();
		}else{
			$model=$this->loadModel($id);
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Share']))
		{
			if(isset($_FILES['Share']['name']['cover']) && !empty($_FILES['Share']['name']['cover'])){
				$file = CUploadedFile::getInstance($model,'cover');
				$filename = $file->getName();//获取文件名
				$filesize = $file->getSize();//获取文件大小
				$filetype = $file->getType();//获取文件类型
				$filename1 = iconv("utf-8", "gb2312", $filename);//这里是处理中文的问题，非中文不需要
				$uploadPath = "./upload/novel/cover/".CV::getFilePath(Yii::app()->user->id);
				$uploadfile = $uploadPath.$filename1;
				CV::dmkdir($uploadPath);
				$file->saveAs($uploadfile,true);//上传操作
				$nowtime = time();
				rename($uploadfile,$uploadPath.$nowtime.'.jpg');
				$_POST['Share']['cover'] = $nowtime;
			}else{
				unset($_POST['Share']['cover']);
			}
			if(isset($_FILES['Share']['name']['attachment']) && !empty($_FILES['Share']['name']['attachment'])){
				$file = CUploadedFile::getInstance($model,'attachment');
				$filename = $file->getName();//获取文件名
				$filesize = $file->getSize();//获取文件大小
				$filetype = $file->getType();//获取文件类型
				$filename1 = iconv("utf-8", "gb2312", $filename);//这里是处理中文的问题，非中文不需要
				$uploadPath = "./upload/novel/txt/".CV::getFilePath(Yii::app()->user->id);
				$uploadfile = $uploadPath.$filename1;
				CV::dmkdir($uploadPath);
				$file->saveAs($uploadfile,true);//上传操作
				$nowtime = time();
				rename($uploadfile,$uploadPath.$nowtime.'.txt');
				$_POST['Share']['attachment'] = $nowtime;
			}else{
				unset($_POST['Share']['attachment']);
			}
			$model->attributes=$_POST['Share'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		$sortResult = NovelSort::model()->findAll();

		$sort = array();
		foreach ($sortResult as $value){
			$sort[$value->id] = $value->name;
		}
		$this->render('update',array(
			'model'=>$model,'sort'=>$sort,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Share('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Share']))
			$model->attributes=$_GET['Share'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Share the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Share::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Share $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='share-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
