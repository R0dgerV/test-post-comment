<?php

/**
 * This is the model class for table "Authors".
 *
 * The followings are the available columns in table 'Authors':
 * @property string $id
 * @property string $email
 * @property string $name
 * @property string $created
 * @property string $modified
 * @property string $flags
 *
 * The followings are the available model relations:
 * @property Pages[] $pages
 */
Yii::import('application.models.base.BaseAuthors');

class Authors extends BaseAuthors
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
