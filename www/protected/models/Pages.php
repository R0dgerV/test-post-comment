<?php

/**
 * This is the model class for table "Pages".
 *
 * The followings are the available columns in table 'Pages':
 * @property string $id
 * @property string $title
 * @property string $text
 * @property string $authorId
 * @property string $created
 * @property string $modified
 * @property string $flags
 *
 * The followings are the available model relations:
 * @property Authors $author
 */
Yii::import('application.models.base.BasePages');

class Pages extends BasePages
{
	public function behaviors()
	{
		return array(
			'autoModifiedBehavior'=>array(
				'class'=>'TSAutoModifiedBehavior',
				'Created'=>'created',
				'Modified'=>'modified',
				'Unixtime'=>false
			),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
