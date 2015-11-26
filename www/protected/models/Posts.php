<?php


/**
 * This is the model class for table "Posts".
 *
 * The followings are the available columns in table 'Posts':
 * @property string $id
 * @property string $text
 * @property string $authorId
 * @property string $pageId
 * @property string $lft
 * @property string $rgt
 * @property string $root
 * @property integer $level
 * @property string $created
 * @property string $modified
 * @property string $flags
 *
 * The followings are the available model relations:
 * @property Authors $author
 * @property Pages $page
 */

Yii::import('application.models.base.BasePosts');

class Posts extends BasePosts
{
	public function behaviors()
	{
		return [
			'autoModifiedBehavior'=>[
				'class'=>'TSAutoModifiedBehavior',
				'Created'=>'created',
				'Modified'=>'modified',
				'Unixtime'=>false
			],
			'nestedSetBehavior'=>[
				'class'=>'NestedSetBehavior',
				'rootAttribute'=>'root',
				'leftAttribute'=>'lft',
				'rightAttribute'=>'rgt',
				'levelAttribute'=>'level',
				'hasManyRoots'=>true,
			],
		];
	}

    public static function addChildrenComment($model, $message) {
        $comment = new Posts();
        $comment->authorId = 1;
        $comment->pageId = 1;
        $comment->text = $message;
        if ($comment->appendTo($model)) {
            return [
                'success'=>true,
                'post'=>['id'=>$comment->id, 'text'=>$comment->text, 'modified'=>date("Y-m-d H:i:s"), 'children'=>0, 'author'=>$comment->author->name],
            ];
        } else {
            throw new CHttpException(404, 'This comment not save.');
        }
    }

    public static function createNewPostComment($model, $message) {
        if ($model->isNewRecord) {
            $model->authorId = 1;
            $model->pageId = 1;
        }
        $model->text = $message;
        if ($model->saveNode()) {
            return [
                'success'=>true,
                'post'=>['id'=>$model->id, 'text'=>$model->text, 'modified'=>date("Y-m-d H:i:s"), 'children'=>0, 'author'=>$model->author->name],
            ];
        } else {
            throw new CHttpException(404, 'This comment not save.');
        }
    }

    public static function deleteComments($id)
    {
        $comment = Posts::model()->findByPk($id);
        if ($comment->deleteNode()) {
            return ['success'=>true];
        } else {
            return ['success'=>false];
        }
    }

	public static function findAllCildrenAsArray($id)
	{
		$comment = Posts::model()->findByPk($id);
		$model = array_reduce($comment->children()->findAll(), function($array, $item) {
			$array[] = $item->toJson();
			return $array;
		}, []);
		return $model;
	}

    public static function getCountChildrens($models)
    {
        $model = array_reduce($models, function($array, $item) {
            $array[] = count($item->children()->findAll());
            return $array;
        }, []);
        return $model;
    }

	public function toJson()
	{
		$json = $this->getAttributes(explode(', ', 'id, text, modified'));
		$json['author']= $this->author->name;
		$json['children']=count($this->children()->findAll());
		return $json;
	}

	public function scopes()
	{
		return [
			'templates'=>[
				'condition'=>'pageId = 1 AND authorId = 1',
			],
		];
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
