<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

    public function actionDeleteCommet()
    {
        header('Content-Type: application/json');
        echo CJSON::encode(Posts::deleteComments($_POST['id']));

    }

	public function actionGetComments($id)
	{
        header('Content-Type: application/json');
        echo CJSON::encode(Posts::findAllCildrenAsArray($id));
	}

    public function actionAddChildrenComment()
    {
        header('Content-Type: application/json');
        if (isset($_POST['pk']) && $_POST['pk'] != '') {
            $post = $this->loadPost($_POST['pk']);
        }
        echo CJSON::encode(Posts::addChildrenComment($post, $_POST['value']));
    }

    public function actionCreateNewPostComment()
    {
        header('Content-Type: application/json');
        if (isset($_POST['pk']) && $_POST['pk'] != '') {
            $post = $this->loadPost($_POST['pk']);
        } else {
            $post = new Posts();
        }
        echo CJSON::encode(Posts::createNewPostComment($post, $_POST['value']));
    }

    public function actionPostComment()
    {
        $post = $this->loadPost($_POST['pk']);
        $post->text = $_POST['value'];
        if ($post->saveNode()) {
            echo CJSON::encode([
                'success'=>true
            ]);
        } else {
            throw new CHttpException(404, 'This comment not save.');
        }
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$page = Pages::model()->findByPk(1);
		$comments = Posts::model()->templates()->roots()->findAll();
        $childrens = Posts::getCountChildrens($comments);
		$this->render('index', [
			'page'=>$page,
			'comments'=>$comments,
            'childrens'=>$childrens,
		]);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    public function loadPost($id)
    {
        $model=Posts::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}